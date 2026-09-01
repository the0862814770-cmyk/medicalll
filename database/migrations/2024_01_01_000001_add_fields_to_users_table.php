<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['user', 'staff', 'executive', 'admin'])->default('user')->after('email');
            $table->string('phone', 20)->nullable()->after('role');
            $table->string('student_id', 20)->nullable()->after('phone');
            $table->enum('status', ['active', 'suspended'])->default('active')->after('student_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'student_id', 'status']);
        });
    }
};
