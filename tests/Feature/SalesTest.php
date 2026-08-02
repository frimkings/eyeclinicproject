<?php

namespace Tests\Feature;

use App\Livewire\Cashier\SalesRecordsComponent;
use App\Livewire\POSComponent;
use App\Models\Patient;
use App\Models\RefundLog;
use App\Models\Sales;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SalesTest extends TestCase
{
    use DatabaseTransactions;

    private User $cashier;
    private Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'Cashier', 'guard_name' => 'web']);

        $this->cashier = User::factory()->create();
        $this->cashier->assignRole('Cashier');
        $this->actingAs($this->cashier);

        $this->patient = Patient::factory()->create([
            'user_id' => $this->cashier->id,
        ]);
    }

    private function makeSale(array $overrides = []): Sales
    {
        return Sales::create(array_merge([
            'user_id'        => $this->cashier->id,
            'patient_id'     => $this->patient->id,
            'transaction_id' => 'TXN-' . strtoupper(bin2hex(random_bytes(4))),
            'total_amount'   => 200.00,
            'amount_paid'    => 200.00,
            'payment_status' => 'paid',
        ], $overrides));
    }

    // ── Sales model ──────────────────────────────────────────────────────

    public function test_remaining_balance_is_zero_when_fully_paid(): void
    {
        $sale = $this->makeSale(['total_amount' => 150.00, 'amount_paid' => 150.00]);

        $this->assertEquals(0.0, $sale->remaining_balance);
    }

    public function test_remaining_balance_reflects_partial_payment(): void
    {
        $sale = $this->makeSale(['total_amount' => 300.00, 'amount_paid' => 100.00]);

        $this->assertEquals(200.0, $sale->remaining_balance);
    }

    public function test_remaining_balance_never_goes_negative(): void
    {
        $sale = $this->makeSale(['total_amount' => 100.00, 'amount_paid' => 150.00]);

        $this->assertEquals(0.0, $sale->remaining_balance);
    }

    public function test_is_fully_paid_returns_true_for_paid_status(): void
    {
        $sale = $this->makeSale(['payment_status' => 'paid']);

        $this->assertTrue($sale->isFullyPaid());
    }

    public function test_is_fully_paid_returns_false_for_partial_status(): void
    {
        $sale = $this->makeSale(['payment_status' => 'partial', 'amount_paid' => 100.00]);

        $this->assertFalse($sale->isFullyPaid());
    }

    public function test_sale_belongs_to_patient(): void
    {
        $sale = $this->makeSale();

        $this->assertInstanceOf(Patient::class, $sale->patient);
        $this->assertEquals($this->patient->id, $sale->patient->id);
    }

    public function test_sale_belongs_to_user(): void
    {
        $sale = $this->makeSale();

        $this->assertInstanceOf(User::class, $sale->user);
        $this->assertEquals($this->cashier->id, $sale->user->id);
    }

    public function test_direct_purchase_uses_customer_name_snapshot(): void
    {
        $sale = $this->makeSale([
            'patient_id' => null,
            'customer_name' => 'Ama Boateng',
        ]);

        $this->assertEquals('Ama Boateng', $sale->customer_display_name);
    }

    public function test_unnamed_direct_purchase_is_walk_in(): void
    {
        $sale = $this->makeSale([
            'patient_id' => null,
            'customer_name' => null,
        ]);

        $this->assertEquals('Walk-in', $sale->customer_display_name);
    }

    public function test_pos_can_switch_to_direct_purchase_mode(): void
    {
        Livewire::test(POSComponent::class)
            ->call('selectDirectPurchaseMode')
            ->assertSet('purchaseMode', 'direct')
            ->set('directCustomerName', 'Direct Buyer')
            ->assertSet('directCustomerName', 'Direct Buyer');
    }

    public function test_pos_does_not_switch_selected_patient_cart_to_direct_mode(): void
    {
        Livewire::test(POSComponent::class)
            ->set('patientId', $this->patient->id)
            ->call('selectDirectPurchaseMode')
            ->assertSet('purchaseMode', 'patient')
            ->assertDispatched('notify');
    }

    public function test_walk_in_cannot_enable_part_payment(): void
    {
        Livewire::test(POSComponent::class)
            ->set('isPartPayment', true)
            ->assertSet('isPartPayment', false)
            ->assertDispatched('notify');
    }

    public function test_registered_patient_can_enable_part_payment(): void
    {
        Livewire::test(POSComponent::class)
            ->set('patientId', $this->patient->id)
            ->set('isPartPayment', true)
            ->assertSet('isPartPayment', true);
    }

    // ── SalesRecordsComponent ────────────────────────────────────────────

    public function test_cashier_can_view_sales_records_component(): void
    {
        $this->makeSale();

        Livewire::test(SalesRecordsComponent::class)
            ->assertStatus(200);
    }

    public function test_admin_can_view_sales_records_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $this->actingAs($admin)
            ->get(route('admin.sales-records'))
            ->assertOk();
    }

    public function test_search_filters_by_transaction_id(): void
    {
        $txnId = 'TXN-FINDME12345';
        $this->makeSale(['transaction_id' => $txnId]);
        $this->makeSale(); // noise

        $ids = Livewire::test(SalesRecordsComponent::class)
            ->set('searchTerm', 'FINDME12345')
            ->set('fromDate', now()->subDay()->format('Y-m-d'))
            ->set('toDate', now()->addDay()->format('Y-m-d'))
            ->viewData('sales')
            ->pluck('transaction_id');

        $this->assertContains($txnId, $ids->toArray());
    }

    public function test_search_filters_by_direct_customer_name(): void
    {
        $sale = $this->makeSale([
            'patient_id' => null,
            'customer_name' => 'Unique Direct Buyer',
        ]);

        $ids = Livewire::test(SalesRecordsComponent::class)
            ->set('searchTerm', 'Direct Buyer')
            ->set('fromDate', now()->subDay()->format('Y-m-d'))
            ->set('toDate', now()->addDay()->format('Y-m-d'))
            ->viewData('sales')
            ->pluck('id');

        $this->assertContains($sale->id, $ids->toArray());
    }

    public function test_date_range_excludes_sales_outside_window(): void
    {
        $old = $this->makeSale();
        Sales::where('id', $old->id)->update(['created_at' => now()->subMonth()]);

        $recent = $this->makeSale();

        $ids = Livewire::test(SalesRecordsComponent::class)
            ->set('fromDate', now()->format('Y-m-d'))
            ->set('toDate', now()->format('Y-m-d'))
            ->viewData('sales')
            ->pluck('id');

        $this->assertContains($recent->id, $ids->toArray());
        $this->assertNotContains($old->id, $ids->toArray());
    }

    public function test_refund_filter_shows_only_non_refunded_sales(): void
    {
        $normal   = $this->makeSale(['is_refunded' => false]);
        $refunded = $this->makeSale(['is_refunded' => true]);

        $ids = Livewire::test(SalesRecordsComponent::class)
            ->set('fromDate', now()->format('Y-m-d'))
            ->set('toDate', now()->format('Y-m-d'))
            ->set('filterRefunded', 0)
            ->viewData('sales')
            ->pluck('id');

        $this->assertContains($normal->id, $ids->toArray());
        $this->assertNotContains($refunded->id, $ids->toArray());
    }

    // ── Refund flow ──────────────────────────────────────────────────────

    public function test_refund_request_requires_reason_of_at_least_10_chars(): void
    {
        $sale = $this->makeSale();

        Livewire::test(SalesRecordsComponent::class)
            ->call('initiateRefund', $sale->id)
            ->set('initiateRefundReasonCode', 'other')
            ->set('initiateRefundType', 'refund')
            ->set('initiateRefundReason', 'Too short')
            ->call('submitRefundRequest')
            ->assertHasErrors(['initiateRefundReason']);
    }

    public function test_cashier_can_submit_refund_request(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $sale = $this->makeSale();

        Livewire::test(SalesRecordsComponent::class)
            ->call('initiateRefund', $sale->id)
            ->set('initiateRefundReasonCode', 'customer_return')
            ->set('initiateRefundType', 'refund')
            ->set('initiateRefundReason', 'Customer requested a refund for this purchase.')
            ->call('submitRefundRequest')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('refund_logs', [
            'sale_id'      => $sale->id,
            'status'       => RefundLog::STATUS_PENDING,
            'initiated_by' => $this->cashier->id,
        ]);
    }

    public function test_duplicate_refund_request_is_blocked(): void
    {
        $sale = $this->makeSale();

        RefundLog::create([
            'sale_id'      => $sale->id,
            'status'       => RefundLog::STATUS_PENDING,
            'initiated_by' => $this->cashier->id,
            'reason'       => 'Original refund request here.',
            'initiated_at' => now(),
        ]);

        Livewire::test(SalesRecordsComponent::class)
            ->call('initiateRefund', $sale->id)
            ->assertDispatched('notify');

        $this->assertEquals(1, RefundLog::where('sale_id', $sale->id)->count());
    }

    public function test_already_refunded_sale_triggers_http_exception(): void
    {
        $sale = $this->makeSale(['is_refunded' => true]);

        $this->withoutExceptionHandling();
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        Livewire::test(SalesRecordsComponent::class)
            ->call('initiateRefund', $sale->id);
    }
}
