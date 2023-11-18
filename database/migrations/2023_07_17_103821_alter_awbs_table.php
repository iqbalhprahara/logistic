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
        Schema::table('awbs', function (Blueprint $table) {
            $table->unsignedBigInteger('awb_status_id')->after('awb_no')->default(1);
            $table->foreign('awb_status_id')->references('id')->on('awb_statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('awbs', function (Blueprint $table) {
            $table->dropColumn('awb_status_id');
        });
    }
};
