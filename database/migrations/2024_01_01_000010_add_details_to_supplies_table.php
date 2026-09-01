<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('supplies', function (Blueprint $table) {
            if (!Schema::hasColumn('supplies', 'manufacturer')) {
                $table->string('manufacturer')->nullable()->after('min_stock');
            }
            if (!Schema::hasColumn('supplies', 'storage_location')) {
                $table->string('storage_location')->nullable()->after('manufacturer');
            }
            if (!Schema::hasColumn('supplies', 'image_path')) {
                $table->string('image_path')->nullable()->after('storage_location');
            }
        });
    }

    public function down()
    {
        Schema::table('supplies', function (Blueprint $table) {
            $table->dropColumn(['manufacturer', 'storage_location', 'image_path']);
        });
    }
};
