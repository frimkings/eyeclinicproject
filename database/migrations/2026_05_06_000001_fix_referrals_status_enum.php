<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixReferralsStatusEnum extends Migration
{
    public function up()
    {
        // Temporarily widen the ENUM to allow both old and new values
        DB::statement("ALTER TABLE referrals MODIFY COLUMN status ENUM('draft','issued','pending','completed','cancelled') NOT NULL DEFAULT 'pending'");

        // Map old values to new ones
        DB::table('referrals')->where('status', 'draft')->update(['status' => 'pending']);
        DB::table('referrals')->where('status', 'issued')->update(['status' => 'completed']);

        // Lock to the final set of values
        DB::statement("ALTER TABLE referrals MODIFY COLUMN status ENUM('pending','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE referrals MODIFY COLUMN status ENUM('draft','issued','cancelled') NOT NULL DEFAULT 'draft'");
    }
}
