<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kit_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('kit_requests', 'document_path')) {
                $table->string('document_path')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('kit_requests', 'activity_name')) {
                $table->string('activity_name')->nullable();
            }
            if (!Schema::hasColumn('kit_requests', 'quantity')) {
                $table->integer('quantity')->default(1);
            }
            if (!Schema::hasColumn('kit_requests', 'participants_count')) {
                $table->integer('participants_count')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('kit_requests', function (Blueprint $table) {
            $table->dropColumn(['document_path', 'activity_name', 'quantity', 'participants_count']);
        });
    }
};
