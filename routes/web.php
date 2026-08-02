<?php

use App\Http\Controllers\Administrator\AdminDashboardController;
use App\Http\Controllers\Cashier\ReceiptController;
use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Secretary\SecretaryDashboardController;
use App\Livewire\Admin\CategoryComponent;
use App\Livewire\Admin\ClinicalTaskCenterComponent;
use App\Livewire\Admin\OfflineHealthDashboardComponent;
use App\Livewire\Admin\ProductsComponent;
use App\Livewire\CartComponent;
use App\Livewire\Cashier\CashierDashboardComponent;
use App\Livewire\Cashier\CashierPatientClearanceComponent;
use App\Livewire\Cashier\SalesRecordsComponent;
use App\Livewire\Cashier\SellerDeskComponent;
use App\Livewire\Doctor\AllrecordsComponent;
use App\Livewire\Doctor\PatientAwaitingComponent;
use App\Livewire\Doctor\PatientRecordsComponent;
use App\Livewire\Doctor\ShowConsultationComponent;
use App\Livewire\Doctor\UpdateConsultationComponent;
use App\Livewire\Doctor\UsersComponent;
use App\Livewire\POSComponent;
use App\Livewire\OutstandingBalancesComponent;
use App\Livewire\RefundLogsComponent;
use App\Livewire\ReportsComponent;
use App\Livewire\Secretary\AppointmentsComponent;
use App\Livewire\Secretary\PatientsComponent;
use App\Livewire\Secretary\SpectaclesComponent;
use App\Livewire\Admin\UserRoleManagerComponent;
use App\Livewire\Admin\RolePermissionManagerComponent;
use App\Livewire\Admin\PasswordResetApprovalsComponent;
use App\Livewire\Admin\DiscountApprovalsComponent;
use App\Livewire\Admin\RefundApprovalsComponent;
use App\Livewire\Admin\ClearanceRevokeApprovalsComponent;
use App\Livewire\Admin\AllApprovalsComponent;
use App\Livewire\Admin\AuditTrailViewerComponent;
use App\Livewire\Admin\LicenseComponent;
use App\Livewire\Admin\DailyCashSummaryComponent;
use App\Livewire\Admin\InventoryAlertsComponent;
use App\Livewire\Admin\LoginHistoryComponent;
use App\Livewire\Admin\StockMovementComponent;
use App\Models\Spectacles;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Doctor\PatientMedicalRecordController;
use App\Http\Controllers\DiscountApprovalNoticeController;
use App\Http\Controllers\Doctor\ReferralController;
use App\Http\Controllers\Doctor\ClearanceNoticeController;
use App\Http\Controllers\IncomeStatementExportController;
use App\Livewire\Doctor\ReferralComponent;
use App\Livewire\StaffMessagingComponent;
use App\Livewire\Admin\AdminSettingsComponent;
use App\Livewire\Admin\BackupManagerComponent;
use App\Livewire\Admin\ReportDeliveryComponent;
use App\Livewire\Admin\MailSettingsComponent;
use App\Livewire\Admin\SmsLogsComponent;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->hasRole('Super Admin') || $user->hasRole('Manager')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('Doctor')) {
        return redirect()->route('doctor.dashboard');
    } elseif ($user->hasRole('Secretary')) {
        return redirect()->route('secretary.dashboard');
    } elseif ($user->hasRole('Cashier')) {
        return redirect()->route('cashier.dashboard');
    }
    return redirect()->route('login');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', 'App\Livewire\UserProfileComponent')->name('user.profile');

    Route::get('/notifications/unread-count', function () {
        return response()->json([
            'count' => \App\Models\AppNotification::forUser(auth()->id())->unread()->count(),
        ]);
    })->name('notifications.unread-count');

    Route::get('/messages', StaffMessagingComponent::class)->name('staff.messages');

    Route::get('/messages/unread-count', function () {
        return response()->json([
            'count' => \App\Models\StaffMessage::where('recipient_id', auth()->id())->whereNull('read_at')->count(),
        ]);
    })->name('messages.unread-count');
});




//secretary

// Added the pipe | between Manager and Super Admin
Route::middleware(['auth', 'role:Secretary|Manager|Super Admin'])->group(function () {
   Route::get('secretary/dashboard', SecretaryDashboardController::class)->name('secretary.dashboard');

    Route::get('secretary/patients', PatientsComponent::class)->name('secretary.patients');
    Route::get('secretary/appointments', AppointmentsComponent::class)->name('secretary.appointments')->middleware('feature:appointments');
    Route::get('secretary/spectacles', SpectaclesComponent::class)->name('secretary.spectacles');
Route::get('secretary/patient-clearance', CashierPatientClearanceComponent::class)->name('secretary.patient-clearance');


});




Route::middleware(['auth', 'role:Secretary|Cashier|Manager|Super Admin'])->group(function () {
 //cashier
Route::get('cashier/dashboard', CashierDashboardComponent::class)->name('cashier.dashboard');
Route::get('cashier/seller-desk', POSComponent::class)->name('cashier.seller-desk');
Route::get('cashier/outstanding-balances', OutstandingBalancesComponent::class)->name('cashier.outstanding-balances')->middleware('feature:outstanding_balances');
Route::get('/livewire/ajax-patients', [POSComponent::class, 'getPatientsJson']);
Route::get('/cashier/sales-records', SalesRecordsComponent::class)->name('cashier.sales-records');
Route::get('/cashier/refund-logs', RefundLogsComponent::class)->name('refunds.logs');
Route::get('/cart', CartComponent::class)->name('cart');

// FIXED: Changed from /receipt/{saleId} to /cashier/receipt/{saleId}
Route::get('/cashier/receipt/{saleId}', [ReceiptController::class, 'show'])
    ->name('cashier.receipt.show');
Route::get('/cashier/receipt/{saleId}/pdf', [ReceiptController::class, 'downloadPdf'])
    ->name('cashier.receipt.pdf');
Route::get('/cashier/refund-receipt/{refund}', [\App\Http\Controllers\RefundReceiptController::class, 'show'])
    ->name('refunds.receipt');
Route::get('/cashier/refund-receipt/{refund}/pdf', [\App\Http\Controllers\RefundReceiptController::class, 'downloadPdf'])
    ->name('refunds.receipt.pdf');

Route::get('/cashier/clearance-receipt/{id}', function ($id) {
    $clearance = \App\Models\CashierPatientClearance::with(['patient', 'service', 'user', 'sale'])->findOrFail($id);
    $clinicSettings = \App\Models\Setting::getSettings();
    return view('cashier.clearance-receipt', compact('clearance', 'clinicSettings'));
})->name('cashier.clearance-receipt');
});






//end cashier


Route::get('/reports/export/pdf', [\App\Http\Controllers\ReportExportController::class, 'exportPdf'])
    ->middleware(['auth', 'role_or_permission:Super Admin|export reports', 'feature:advanced_reports'])
    ->name('reports.export.pdf');


//admin


Route::middleware(['auth', 'role:Super Admin|Manager'])->group(function () {
 //admin
Route::get('/admin/reports', ReportsComponent::class)->name('admin.reports');
Route::get('/admin/sales-records', SalesRecordsComponent::class)->name('admin.sales-records');
Route::get('/admin/income-statement', \App\Livewire\Admin\IncomeStatementComponent::class)->name('admin.income-statement')->middleware('feature:advanced_reports');
Route::get('/admin/income-statement/export/csv', [IncomeStatementExportController::class, 'exportCsv'])->name('admin.income-statement.export.csv')->middleware('feature:advanced_reports');
Route::get('/admin/income-statement/export/pdf', [IncomeStatementExportController::class, 'exportPdf'])->name('admin.income-statement.export.pdf')->middleware('feature:advanced_reports');
Route::get('/admin/income-statement/preview', [IncomeStatementExportController::class, 'preview'])->name('admin.income-statement.preview')->middleware('feature:advanced_reports');
Route::get('/admin/diagnoses', \App\Livewire\Admin\DiagnosisComponent::class)->name('admin.diagnoses');
Route::get('/admin/inventory-alerts', InventoryAlertsComponent::class)->name('admin.inventory-alerts')->middleware('feature:inventory');
Route::get('/admin/stock-movements', StockMovementComponent::class)->name('admin.stock-movements')->middleware('feature:inventory');
Route::get('/admin/daily-cash-summary', DailyCashSummaryComponent::class)->name('admin.daily-cash-summary');
Route::get('/admin/lens-outstanding-report', function () { // feature:spectacles_pro checked in middleware below
    $debts = \App\Models\LensOrder::with(['refraction.consultation.patient'])
        ->whereRaw('(frame_price + lens_price) > paid_amount')
        ->where('status', '!=', 'Cancelled')
        ->get();

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.outstanding-report', [
        'debts' => $debts,
        'generated_at' => now(),
        'appSettings' => \App\Models\Setting::getSettings(),
    ])->setPaper('a4', 'landscape');

    return response()->streamDownload(fn () => print($pdf->output()), 'Lens_Outstanding_Report_' . date('Y-m-d') . '.pdf');
})->name('admin.lens-outstanding-report')->middleware('feature:spectacles_pro');
Route::get('/admin/login-history', LoginHistoryComponent::class)->name('admin.login-history')->middleware('feature:audit_trail');
Route::get('/admin/audit-trail', AuditTrailViewerComponent::class)->name('admin.audit-trail')->middleware('feature:audit_trail');
Route::get('/admin/license', LicenseComponent::class)->name('admin.license')->middleware(['auth', 'role:Super Admin']);
Route::get('/admin/sms-logs', SmsLogsComponent::class)->name('admin.sms-logs')->middleware('feature:sms_campaigns');
Route::get('/admin/expenses', \App\Livewire\Admin\ExpensesComponent::class)->name('admin.expenses')->middleware('feature:expense_tracking');
Route::get('/admin/expenses/receipt/{filename}', function (string $filename) {
    // Basename strips any path traversal attempts
    $path = 'expense-receipts/' . basename($filename);
    abort_unless(\Illuminate\Support\Facades\Storage::disk('public')->exists($path), 404);
    return response()->file(\Illuminate\Support\Facades\Storage::disk('public')->path($path));
})->name('admin.expenses.receipt')->middleware(['auth']);
Route::get('admin/dashboard', AdminDashboardController::class)->name('admin.dashboard');
Route::get('admin/clinical-task-center', ClinicalTaskCenterComponent::class)->name('admin.clinical-task-center');
Route::get('admin/category', CategoryComponent::class)->name('admin.category');
Route::get('admin/product', ProductsComponent::class)->name('admin.product');
Route::get('admin/suppliers', \App\Livewire\Admin\SupplierComponent::class)->name('admin.suppliers')->middleware('feature:inventory');
Route::get('admin/quotations', \App\Livewire\Admin\QuotationComponent::class)->name('admin.quotations');
Route::get('admin/quotations/{id}/pdf', function (int $id) {
    $q = \App\Models\Quotation::with('items', 'creator')->findOrFail($id);
    $setting = \App\Models\Setting::getSettings();
    return \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.quotation', compact('q', 'setting'))
        ->setPaper('a4', 'portrait')
        ->stream("Quotation-{$q->quotation_number}.pdf");
})->name('admin.quotations.pdf');
Route::get('admin/purchase-orders', \App\Livewire\Admin\PurchaseOrderComponent::class)->name('admin.purchase-orders');
Route::get('admin/purchase-orders/{id}/pdf', function (int $id) {
    $po = \App\Models\PurchaseOrder::with('items', 'supplier', 'creator')->findOrFail($id);
    $setting = \App\Models\Setting::getSettings();
    return \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.purchase-order', compact('po', 'setting'))
        ->setPaper('a4', 'portrait')
        ->stream("PO-{$po->po_number}.pdf");
})->name('admin.purchase-orders.pdf');
Route::get('admin/patient-ledger', \App\Livewire\Admin\PatientLedgerComponent::class)->name('admin.patient-ledger');
// Route::get('users', UsersComponent::class)->name('admin.users');
Route::get('admin/settings', AdminSettingsComponent::class)->name('admin.settings');

// Insurance module
Route::get('/admin/insurance/claims', \App\Livewire\Admin\InsuranceClaimsComponent::class)->name('admin.insurance.claims');
Route::get('/admin/insurance/insurers', \App\Livewire\Admin\InsurersComponent::class)->name('admin.insurance.insurers');

// Patient Recall dashboard
Route::get('/admin/patient-recall', \App\Livewire\Admin\PatientRecallComponent::class)->name('admin.patient-recall')->middleware('feature:sms_campaigns');

});

Route::get('admin/users', UserRoleManagerComponent::class)->middleware(['auth', 'role_or_permission:Super Admin|manage users'])->name('admin.users');
Route::get('admin/roles-permissions', RolePermissionManagerComponent::class)->middleware(['auth', 'role:Super Admin'])->name('admin.roles-permissions');
Route::get('admin/password-reset-approvals', fn() => redirect()->route('admin.approvals', ['type' => 'password_reset']))->middleware(['auth', 'role:Super Admin'])->name('admin.password-reset-approvals');

Route::middleware(['auth', 'role:Super Admin'])->group(function () {
    Route::get('admin/offline-health', OfflineHealthDashboardComponent::class)->name('admin.offline-health');

    // Legacy URLs redirect to the unified settings page with the correct tab
    Route::get('admin/backups',          fn() => redirect()->route('admin.settings', ['tab' => 'backup']))->name('admin.backups');
    Route::get('admin/report-delivery',  fn() => redirect()->route('admin.settings', ['tab' => 'report']))->name('admin.report-delivery')->middleware('feature:report_delivery');
    Route::get('admin/mail-settings',    fn() => redirect()->route('admin.settings', ['tab' => 'mail']))->name('admin.mail-settings');
    Route::get('admin/backups/download/{filename}', function (string $filename) {
        $decoded = base64_decode($filename, strict: true);
        abort_if($decoded === false, 400);

        // Block traversal attempts; allow one subfolder level (e.g. AppName/2026-01-01.zip)
        abort_if(
            $decoded === '' ||
            str_contains($decoded, '..') ||
            str_starts_with($decoded, '/') ||
            str_starts_with($decoded, '\\'),
            403
        );

        $disk = Storage::disk('backups');
        abort_unless($disk->exists($decoded), 404);

        $extension = strtolower(pathinfo($decoded, PATHINFO_EXTENSION));
        $contentType = $extension === 'sql' ? 'application/sql' : 'application/zip';

        return response()->streamDownload(
            fn () => print($disk->get($decoded)),
            basename($decoded),
            ['Content-Type' => $contentType]
        );
    })->name('admin.backup.download')->middleware('feature:manual_backup');
});

Route::middleware(['auth', 'role_or_permission:Manager|Super Admin|manage billing|approve clearance revoke', 'feature:approvals'])->group(function () {
    Route::get('admin/approvals', AllApprovalsComponent::class)->name('admin.approvals');
    Route::get('admin/discount-approvals', fn() => redirect()->route('admin.approvals', ['type' => 'discount']))->name('admin.discount-approvals');
    Route::get('admin/refund-approvals', fn() => redirect()->route('admin.approvals', ['type' => 'refund']))->name('admin.refund-approvals');
    Route::get('admin/clearance-revoke-approvals', fn() => redirect()->route('admin.approvals', ['type' => 'revoke']))->name('admin.clearance-revoke-approvals');
    Route::get('discount-approval-notices/pending', [DiscountApprovalNoticeController::class, 'pending'])->name('discount-approval-notices.pending');
    Route::post('discount-approval-notices/{discountRequest}/approve', [DiscountApprovalNoticeController::class, 'approve'])->name('discount-approval-notices.approve');
    Route::post('discount-approval-notices/{discountRequest}/reject', [DiscountApprovalNoticeController::class, 'reject'])->name('discount-approval-notices.reject');
});




Route::middleware(['auth', 'role:Doctor|Super Admin'])->group(function () {
 //doctor
Route::get('doctor/dashboard', DoctorDashboardController::class)->name('doctor.dashboard');
Route::get('doctor/clearance-notices/latest', [ClearanceNoticeController::class, 'latest'])->name('doctor.clearance-notices.latest');

Route::get('doctor/patient-awaiting', PatientAwaitingComponent::class)->name('doctor.patient-awaiting');
Route::get('doctor/patientrecords-{clearance}', PatientRecordsComponent::class)->name('doctor.patient-records');
Route::get('doctor/allrecords', AllrecordsComponent::class)->name('doctor.all-records');
Route::get('/doctor/patient/{patient}/clearance/{clearance}/medical-record/pdf', 
    [PatientMedicalRecordController::class, 'generatePDF'])
    ->name('doctor.medical-record.pdf');
Route::get('/doctor/patient/{patient}/clearance/{clearance}/medical-record/preview', 
    [PatientMedicalRecordController::class, 'preview'])
    ->name('doctor.medical-record.preview');

Route::get('/doctor/consultation/{consultation}/pdf', 
    [PatientMedicalRecordController::class, 'generateConsultationPDF'])
    ->name('doctor.consultation.pdf');
Route::get('/doctor/consultation/{consultation}/visit-summary/print',
    [PatientMedicalRecordController::class, 'visitSummary'])
    ->name('doctor.visit-summary.print');
Route::get('/doctor/consultation/{consultation}/prescription/print',
    [PatientMedicalRecordController::class, 'printPrescription'])
    ->name('doctor.prescription.print');
Route::get('/doctor/patient/{patient}/timeline',
    \App\Livewire\Doctor\PatientTimelineComponent::class)
    ->name('doctor.patient-timeline');
Route::get('/doctor/visit-summaries/download',
    [PatientMedicalRecordController::class, 'downloadVisitSummaries'])
    ->name('doctor.visit-summaries.download');

Route::get('doctor/referrals', ReferralComponent::class)->name('doctor.referrals')->middleware('feature:referrals');
Route::get('/doctor/referrals/{referral}/print', [ReferralController::class, 'printLetter'])->name('doctor.referral.pdf')->middleware('feature:referrals');

Route::get('/refraction/print/{consultation}', function($consultationID){
    abort_if(!auth()->user()->hasAnyRole(['Doctor', 'Super Admin', 'Manager']), 403);

    $consultation = \App\Models\Consultations::with('patient')->findOrFail($consultationID);
    $refraction   = \App\Models\Refractions::where('consultation_id', $consultationID)->first();
    $patient      = $consultation->patient;

    return view('livewire.doctor.refraction-print', compact('consultation', 'refraction', 'patient'));
})->name('refraction.print');
});
