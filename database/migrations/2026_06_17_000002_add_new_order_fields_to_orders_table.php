<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('patient_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
                $table->string('order_number', 30)->unique();
                $table->string('service_type', 30)->default('Walk-in');
                $table->string('zone', 80)->nullable();
                $table->decimal('zone_fee', 10, 2)->default(0);
                $table->unsignedInteger('pieces')->default(1);
                $table->json('add_ons')->nullable();
                $table->decimal('add_ons_total', 10, 2)->default(0);
                $table->decimal('subtotal', 10, 2)->default(0);
                $table->decimal('total', 10, 2)->default(0);
                $table->decimal('total_amount', 10, 2)->default(0);
                $table->decimal('paid_amount', 10, 2)->default(0);
                $table->string('payment_status', 30)->default('unpaid');
                $table->string('payment_method', 40)->nullable();
                $table->string('status', 40)->default('Pending');
                $table->text('notes')->nullable();
                $table->string('clothing_photo_path')->nullable();
                $table->unsignedInteger('loyalty_stamps_awarded')->default(0);
                $table->timestamps();
            });

            return;
        }

        $this->makeStatusString();

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'order_number')) {
                $table->string('order_number', 30)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('orders', 'customer_id')) {
                $table->foreignId('customer_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'plan_id')) {
                $table->foreignId('plan_id')->nullable()->after('patient_id')->constrained()->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'service_type')) {
                $table->string('service_type', 30)->default('Walk-in')->after('plan_id');
            }
            if (!Schema::hasColumn('orders', 'zone')) {
                $table->string('zone', 80)->nullable()->after('service_type');
            }
            if (!Schema::hasColumn('orders', 'zone_fee')) {
                $table->decimal('zone_fee', 10, 2)->default(0)->after('zone');
            }
            if (!Schema::hasColumn('orders', 'pieces')) {
                $table->unsignedInteger('pieces')->default(1)->after('zone_fee');
            }
            if (!Schema::hasColumn('orders', 'add_ons')) {
                $table->json('add_ons')->nullable()->after('pieces');
            }
            if (!Schema::hasColumn('orders', 'add_ons_total')) {
                $table->decimal('add_ons_total', 10, 2)->default(0)->after('add_ons');
            }
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('add_ons_total');
            }
            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 10, 2)->default(0)->after('total');
            }
            if (!Schema::hasColumn('orders', 'paid_amount')) {
                $table->decimal('paid_amount', 10, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status', 30)->default('unpaid')->after('paid_amount');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method', 40)->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
            if (!Schema::hasColumn('orders', 'clothing_photo_path')) {
                $table->string('clothing_photo_path')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('orders', 'loyalty_stamps_awarded')) {
                $table->unsignedInteger('loyalty_stamps_awarded')->default(0)->after('clothing_photo_path');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        Schema::table('orders', function (Blueprint $table) {
            $columns = [
                'order_number',
                'customer_id',
                'plan_id',
                'service_type',
                'zone',
                'zone_fee',
                'pieces',
                'add_ons',
                'add_ons_total',
                'subtotal',
                'total_amount',
                'paid_amount',
                'payment_status',
                'payment_method',
                'notes',
                'clothing_photo_path',
                'loyalty_stamps_awarded',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    private function makeStatusString(): void
    {
        try {
            DB::statement("UPDATE orders SET status = 'Pending' WHERE status = 'pending'");
            DB::statement("UPDATE orders SET status = 'Delivered' WHERE status = 'completed'");
            DB::statement("UPDATE orders SET status = 'Cancelled' WHERE status = 'cancelled'");
            DB::statement("ALTER TABLE orders MODIFY status VARCHAR(40) NOT NULL DEFAULT 'Pending'");
        } catch (\Throwable $e) {
            report($e);
        }
    }
};
