<?php

use App\Models\Logistic\Awb;
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
        Schema::create('awb_status_histories', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('awb_uuid');
            $table->unsignedBigInteger('awb_status_id');
            $table->text('note')->nullable();
            $table->uuid('created_by');
            $table->timestamps();

            $table->foreign('awb_uuid')->references('uuid')->on('awbs');
            $table->foreign('awb_status_id')->references('id')->on('awb_statuses');
            $table->foreign('created_by')->references('uuid')->on('users');
        });

        foreach (Awb::cursor() as $awb) {
            $awb->awbStatusHistories()->create([
                'awb_status_id' => 1,
                'created_by' => $awb->created_by,
                'created_at' => $awb->created_at,
                'updated_at' => $awb->created_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awb_status_histories');
    }
};
