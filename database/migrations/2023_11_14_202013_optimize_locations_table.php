<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->index('name', 'provinces_name_idx');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->index('name', 'cities_name_idx');
            $table->index('type', 'cities_type_idx');
        });

        Schema::table('subdistricts', function (Blueprint $table) {
            $table->index('name', 'subdistricts_name_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provinces', function (Blueprint $table) {
            $table->dropIndex('provinces_name_idx');
        });

        Schema::table('cities', function (Blueprint $table) {
            $table->dropIndex('cities_name_idx');
            $table->dropIndex('cities_type_idx');
        });

        Schema::table('subdistricts', function (Blueprint $table) {
            $table->dropIndex('subdistricts_name_idx');
        });
    }
};
