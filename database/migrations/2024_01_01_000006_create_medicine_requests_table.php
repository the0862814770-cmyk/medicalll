<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('medicine_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('symptoms');
            $table->string('status')->default('pending');
            $table->text('staff_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('medicine_request_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('supply_id')->constrained()->onDelete('cascade');
            $table->integer('quantity_requested');
            $table->integer('quantity_approved')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medicine_request_items');
        Schema::dropIfExists('medicine_requests');
    }
};
