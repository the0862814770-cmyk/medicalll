<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('medicine_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('medicine_requests', 'executive_approved_by')) {
                $table->foreignId('executive_approved_by')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('medicine_requests', 'executive_approved_at')) {
                $table->timestamp('executive_approved_at')->nullable();
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE medicine_requests MODIFY status ENUM('pending', 'executive_approved', 'approved', 'rejected', 'dispensed') NOT NULL DEFAULT 'pending'");
        }

        Schema::table('kit_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('kit_requests', 'executive_approved_by')) {
                $table->foreignId('executive_approved_by')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('kit_requests', 'executive_approved_at')) {
                $table->timestamp('executive_approved_at')->nullable();
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE kit_requests MODIFY status ENUM('pending', 'executive_approved', 'approved', 'borrowed', 'return_pending', 'returned', 'rejected') NOT NULL DEFAULT 'pending'");
        }
    }

    public function down()
    {
        Schema::table('medicine_requests', function (Blueprint $table) {
            if (Schema::hasColumn('medicine_requests', 'executive_approved_by')) {
                $table->dropForeign(['executive_approved_by']);
                $table->dropColumn('executive_approved_by');
            }
            if (Schema::hasColumn('medicine_requests', 'executive_approved_at')) {
                $table->dropColumn('executive_approved_at');
            }
        });

        DB::statement("ALTER TABLE medicine_requests MODIFY status ENUM('pending', 'approved', 'rejected', 'dispensed') NOT NULL DEFAULT 'pending'");

        Schema::table('kit_requests', function (Blueprint $table) {
            if (Schema::hasColumn('kit_requests', 'executive_approved_by')) {
                $table->dropForeign(['executive_approved_by']);
                $table->dropColumn('executive_approved_by');
            }
            if (Schema::hasColumn('kit_requests', 'executive_approved_at')) {
                $table->dropColumn('executive_approved_at');
            }
        });

        DB::statement("ALTER TABLE kit_requests MODIFY status ENUM('pending', 'approved', 'borrowed', 'return_pending', 'returned', 'rejected') NOT NULL DEFAULT 'pending'");
    }
};
