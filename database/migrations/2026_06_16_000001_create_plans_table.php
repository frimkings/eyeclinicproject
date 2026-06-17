<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('billing_period', 50)->default('semester');
            $table->string('description', 500)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('plans')->insert([
            [
                'name' => 'Fresher Plan',
                'price' => 320.00,
                'billing_period' => 'semester',
                'description' => 'Entry student plan.',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Scholar Plan',
                'price' => 580.00,
                'billing_period' => 'semester',
                'description' => 'Mid-tier student plan.',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Boss Plan',
                'price' => 950.00,
                'billing_period' => 'semester',
                'description' => 'Premium student plan.',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
