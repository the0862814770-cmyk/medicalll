<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('supply_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supply_id')->constrained()->onDelete('cascade');
            $table->foreignId('supply_lot_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['receive', 'dispense', 'return', 'adjust']);
            $table->integer('quantity');
            $table->text('notes')->nullable();
            $table->string('reference')->nullable(); // เลขที่อ้างอิง
            $table->foreignId('performed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supply_transactions');
    }
};
