<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('first_aid_kits', function (Blueprint $table) {
            $table->id();
            $table->string('kit_code')->unique();
            $table->string('name');
            $table->enum('status', ['available', 'borrowed', 'maintenance'])->default('available');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('kit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('first_aid_kit_id')->constrained()->onDelete('cascade');
            $table->foreignId('supply_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kit_items');
        Schema::dropIfExists('first_aid_kits');
    }
};
