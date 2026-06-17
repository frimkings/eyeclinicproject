<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pickup_delivery_tasks')) {
            return;
        }

        Schema::create('pickup_delivery_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete();
            $table->string('zone_code', 20);
            $table->string('zone_name', 120);
            $table->timestamp('pickup_scheduled_at')->nullable();
            $table->timestamp('delivery_scheduled_at')->nullable();
            $table->foreignId('rider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 40)->default('Pickup Requested');
            $table->timestamp('pickup_completed_at')->nullable();
            $table->timestamp('delivery_completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['zone_code', 'status']);
            $table->index('pickup_scheduled_at');
            $table->index('delivery_scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pickup_delivery_tasks');
    }
};
