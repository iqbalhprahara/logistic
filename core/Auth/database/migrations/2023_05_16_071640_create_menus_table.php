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
        Schema::create('menus', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('id')->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->string('icon', 100)->nullable();
            $table->enum('type', ['menu', 'divider']);
            $table->string('url')->nullable();
            $table->string('gate')->nullable();
            $table->string('guard_name');
            $table->unsignedSmallInteger('sort');
            $table->uuid('parent_uuid')->nullable();
            $table->timestamps();

            $table->index('id');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->foreign('parent_uuid')->references('uuid')->on('menus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
