<?php

namespace App\Console\Commands;

use App\Mail\FinancialReportMail;
use App\Models\Sales;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendFinancialReportCommand extends Command
{
    // --force   : bypass the report_enabled check (used by Send Test button)
    // --today   : cover today's data instead of yesterday's (used by Send Test button)
    protected $signature   = 'report:send-financial {--period=} {--force} {--today}';
    protected $description = 'Email the financial summary report to configured recipients.';

    public function handle(): int
    {
        $settings = Setting::getSettings();

        if (!$this->option('force') && !$settings->report_enabled) {
            $this->info('Report delivery is disabled. Pass --force to send anyway.');
            return 0;
        }

        $recipients = array_filter((array) ($settings->report_recipients ?? []));
        if (empty($recipients)) {
            $this->warn('No recipients configured. Add them in Admin › Report Delivery.');
            return 0;
        }

        $period  = $this->option('period') ?: $settings->report_frequency;
        $isTest  = (bool) $this->option('force');
        $useToday = (bool) $this->option('today');

        [$start, $end, $subject, $periodLabel] = $this->resolvePeriod($period, $isTest, $useToday);

        // Daily: use whereDate to avoid timezone drift between app and DB.
        // Weekly: use whereBetween across the full date range.
        $sales = $period === 'daily'
            ? Sales::whereDate('created_at', $start->toDateString())->get()
            : Sales::whereBetween('created_at', [
                $start->toDateTimeString(),
                $end->toDateTimeString(),
              ])->get();

        $nonRefunded = $sales->where('is_refunded', false);

        // Refunds processed within the same window (sale may be from an earlier date)
        $refundsInPeriod = $period === 'daily'
            ? Sales::whereDate('refunded_at', $start->toDateString())->where('is_refunded', true)->get()
            : Sales::whereBetween('refunded_at', [
                $start->toDateTimeString(),
                $end->toDateTimeString(),
              ])->where('is_refunded', true)->get();

        // total_amount = what was billed (revenue). amount_paid = cash tendered by patient
        // (can exceed total_amount when customer gives cash and receives change back).
        $grossRevenue = $nonRefunded->sum('total_amount');
        $refundTotal  = $refundsInPeriod->sum('total_amount');

        $report = [
            'subject'      => $subject,
            'period_label' => $periodLabel,
            'start'        => $start,
            'end'          => $end,
            'clinic'       => $settings,

            'total_transactions' => $sales->count(),
            'gross_revenue'      => $grossRevenue,
            'net_revenue'        => $grossRevenue - $refundTotal,

            'paid_count'    => $nonRefunded->where('payment_status', 'paid')->count(),
            'partial_count' => $nonRefunded->where('payment_status', 'partial')->count(),
            'pending_count' => $nonRefunded->where('payment_status', 'pending')->count(),

            'total_discounts' => $nonRefunded->sum('discount_amount'),

            'refund_count' => $refundsInPeriod->count(),
            'refund_total' => $refundTotal,

            'outstanding' => $nonRefunded->sum(fn ($s) => max(0, $s->total_amount - $s->amount_paid)),
        ];

        $sent = 0;
        foreach ($recipients as $email) {
            try {
                Mail::to(trim($email))->send(new FinancialReportMail($report));
                $this->line("  Sent → {$email}");
                $sent++;
            } catch (\Throwable $e) {
                $this->error("  Failed → {$email}: " . $e->getMessage());
            }
        }

        $this->info("Done. Sent to {$sent} of " . count($recipients) . " recipient(s).");
        return 0;
    }

    private function resolvePeriod(string $period, bool $isTest, bool $useToday): array
    {
        if ($period === 'weekly') {
            if ($useToday) {
                // "Send Current Report": cover this week so far (Mon 00:00 → now)
                $start = Carbon::now()->startOfWeek();
                $end   = Carbon::now();
            } else {
                $start = Carbon::now()->subWeek()->startOfWeek();
                $end   = Carbon::now()->subWeek()->endOfWeek()->endOfDay();
            }
            $periodLabel = $useToday ? 'This Week (so far)' : 'Weekly';
            $subject     = 'Weekly Financial Report — ' . $start->format('d M') . ' to ' . $end->format('d M Y');
        } else {
            if ($useToday) {
                // "Send Current Report": cover today so far (00:00 → now)
                $start = Carbon::today()->startOfDay();
                $end   = Carbon::now();
            } else {
                $start = Carbon::yesterday()->startOfDay();
                $end   = Carbon::yesterday()->endOfDay();
            }
            $periodLabel = $useToday ? 'Today (so far)' : 'Daily';
            $subject     = 'Daily Financial Report — ' . $start->format('d M Y');
        }

        return [$start, $end, $subject, $periodLabel];
    }
}
