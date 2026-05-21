<?php

namespace App\Http\Livewire\Admin;

use App\Models\Appointments;
use App\Models\Consultations;
use App\Models\DiscountApprovalRequest;
use App\Models\Expense;
use App\Models\LoginLog;
use App\Models\Patient;
use App\Models\Product;
use App\Models\Sales;
use App\Models\SaleItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AdminDashboardComponent extends Component
{
    // Row 1 — Top KPIs
    public $totalPatients;
    public $todayRevenue;
    public $todayAppointments;
    public $productsInStock;

    // Row 2 — Financial
    public $monthRevenue;
    public $outstandingCount;
    public $pendingDiscounts;
    public $monthExpenses;

    // Row 3 — Revenue chart (plain PHP arrays; @json renders them in JS)
    public $revenueChartLabels;
    public $revenueChartData;

    // Row 4 — Clinic
    public $newPatientsMonth;
    public $consultationsToday;
    public $pendingPrescriptions;

    // Row 5 — Inventory
    public $lowStockCount;
    public $outOfStockCount;
    public $expiringSoonCount;
    public $expiredCount;

    // Row 6 — Tables
    public $topProducts;
    public $recentLogins;

    // Row 7 — Bottom
    public $totalActiveUsers;

    public function mount(): void
    {
        $today      = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd   = Carbon::now()->endOfMonth();

        // ── Row 1 ────────────────────────────────────────────────────────
        $this->totalPatients = Patient::count();

        // total_amount = invoice value of sales (matches Reports page "Net Revenue")
        $this->todayRevenue = Sales::whereDate('created_at', $today)
            ->where('is_refunded', false)
            ->sum('total_amount');

        $this->todayAppointments = Appointments::whereDate('scheduled_at', $today)
            ->whereNotIn('status', ['cancelled'])
            ->count();

        $this->productsInStock = Product::where('quantity', '>', 0)->count();

        // ── Row 2 ────────────────────────────────────────────────────────
        $this->monthRevenue = Sales::whereBetween('created_at', [$monthStart, $monthEnd])
            ->where('is_refunded', false)
            ->sum('total_amount');

        $this->outstandingCount = Sales::where('payment_status', 'partial')
            ->where('is_refunded', false)
            ->count();

        $this->pendingDiscounts = DiscountApprovalRequest::where(
            'status', DiscountApprovalRequest::STATUS_PENDING
        )->count();

        $this->monthExpenses = Expense::whereBetween('expense_date', [
            $monthStart->toDateString(),
            Carbon::now()->toDateString(),
        ])->sum('amount');

        // ── Chart — past 7 days (single GROUP BY query instead of 7 loops) ──
        $window = Carbon::today()->subDays(6)->startOfDay();
        $dailyRows = Sales::selectRaw('DATE(created_at) as day, SUM(total_amount) as total')
            ->where('created_at', '>=', $window)
            ->where('is_refunded', false)
            ->groupBy('day')
            ->pluck('total', 'day');

        $labels = $revenues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date       = Carbon::today()->subDays($i);
            $labels[]   = $date->format('D d');
            $revenues[] = (float) ($dailyRows[$date->toDateString()] ?? 0);
        }
        $this->revenueChartLabels = $labels;
        $this->revenueChartData   = $revenues;

        // ── Row 4 ────────────────────────────────────────────────────────
        $this->newPatientsMonth = Patient::whereBetween('created_at', [$monthStart, $monthEnd])
            ->count();

        $this->consultationsToday = Consultations::whereDate('created_at', $today)->count();

        // scopePendingDispensing references a non-existent 'products' column via scopeWithPrescriptions,
        // so query directly on the column that actually exists in the table.
        // Cached 5 min — whereDoesntHave generates a correlated subquery; expensive at scale.
        $this->pendingPrescriptions = Cache::remember('dashboard_pending_prescriptions', 300, fn () =>
            Consultations::whereNotNull('prescribed_products')
                ->whereDoesntHave('sale')
                ->count()
        );

        // ── Row 5 — inventory counts cached 5 min (stock levels don't change per-second) ──
        [
            $this->lowStockCount,
            $this->outOfStockCount,
            $this->expiringSoonCount,
            $this->expiredCount,
        ] = Cache::remember('dashboard_inventory_counts', 300, function () use ($today) {
            return [
                Product::where('quantity', '>', 0)->where('quantity', '<=', 10)->count(),
                Product::where('quantity', 0)->count(),
                Product::whereNotNull('expiry_date')
                    ->whereDate('expiry_date', '>=', $today)
                    ->whereDate('expiry_date', '<=', Carbon::today()->addDays(90))
                    ->count(),
                Product::whereNotNull('expiry_date')
                    ->whereDate('expiry_date', '<', $today)
                    ->count(),
            ];
        });

        // ── Row 6 ────────────────────────────────────────────────────────
        $this->topProducts = SaleItem::select(
                'product_id',
                DB::raw('SUM(dispensed_quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereNull('sales.deleted_at')
            ->where('sales.is_refunded', false)
            ->whereBetween('sales.created_at', [$monthStart, $monthEnd])
            ->whereNull('sale_items.deleted_at')
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->with('product:id,name')
            ->get();

        // 'role' is not a DB column — roles are Spatie pivot-based
        $this->recentLogins = LoginLog::with(['user' => fn($q) => $q->select('id', 'name')->with('roles:id,name')])
            ->latest('login_at')
            ->limit(5)
            ->get();

        // ── Row 7 ────────────────────────────────────────────────────────
        $this->totalActiveUsers = User::where('is_active', true)->count();
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard-component');
    }
}
