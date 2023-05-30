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
        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->unsignedBigInteger('province_id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
        });

        Schema::create('subdistricts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subdistricts');
        Schema::dropIfExists('cities');
        Schema::dropIfExists('provinces');
    }
};
