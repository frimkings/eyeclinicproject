<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('order_payments')) {
            return;
        }

        Schema::table('order_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('order_payments', 'receipt_number')) {
                $table->string('receipt_number', 40)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('order_payments', 'type')) {
                $table->string('type', 20)->default('payment')->after('receipt_number');
            }
            if (!Schema::hasColumn('order_payments', 'refunded_payment_id')) {
                $table->foreignId('refunded_payment_id')->nullable()->after('type')->constrained('order_payments')->nullOnDelete();
            }
            if (!Schema::hasColumn('order_payments', 'refund_reason')) {
                $table->text('refund_reason')->nullable()->after('reference');
            }
        });

        DB::table('order_payments')->where('payment_method', 'momo')->update(['payment_method' => 'mobile_money']);
        DB::table('order_payments')->where('payment_method', 'card')->update(['payment_method' => 'credit']);

        DB::table('order_payments')
            ->whereNull('receipt_number')
            ->orderBy('id')
            ->get(['id', 'created_at'])
            ->each(function ($payment, $index) {
                $date = $payment->created_at ? date('Ymd', strtotime($payment->created_at)) : now()->format('Ymd');
                DB::table('order_payments')
                    ->where('id', $payment->id)
                    ->update(['receipt_number' => 'JW-RC-' . $date . '-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT)]);
            });
    }

    public function down(): void
    {
        if (!Schema::hasTable('order_payments')) {
            return;
        }

        Schema::table('order_payments', function (Blueprint $table) {
            if (Schema::hasColumn('order_payments', 'refund_reason')) {
                $table->dropColumn('refund_reason');
            }
            if (Schema::hasColumn('order_payments', 'refunded_payment_id')) {
                $table->dropConstrainedForeignId('refunded_payment_id');
            }
            if (Schema::hasColumn('order_payments', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('order_payments', 'receipt_number')) {
                $table->dropColumn('receipt_number');
            }
        });
    }
};
