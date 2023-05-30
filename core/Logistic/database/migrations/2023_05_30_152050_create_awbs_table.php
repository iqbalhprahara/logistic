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
        Schema::create('awbs', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('awb_no')->unique();
            $table->string('ref_no')->nullable();
            $table->uuid('client_uuid');
            $table->string('transportation_id', 15);
            $table->string('shipment_type_id', 15);
            $table->string('service_id', 15);
            $table->string('packaging_id', 15)->nullable();
            $table->text('note')->nullable();
            $table->boolean('is_cod');
            $table->boolean('is_insurance');
            $table->text('origin_address_line1');
            $table->text('origin_address_line2')->nullable();
            $table->unsignedBigInteger('origin_province_id');
            $table->unsignedBigInteger('origin_city_id');
            $table->unsignedBigInteger('origin_subdistrict_id');
            $table->string('origin_zipcode', 10);
            $table->string('origin_contact_name');
            $table->string('origin_contact_phone');
            $table->string('origin_contact_alt_phone');
            $table->text('destination_address_line1');
            $table->text('destination_address_line2')->nullable();
            $table->unsignedBigInteger('destination_province_id');
            $table->unsignedBigInteger('destination_city_id');
            $table->unsignedBigInteger('destination_subdistrict_id');
            $table->string('destination_zipcode', 10);
            $table->string('destination_contact_name');
            $table->string('destination_contact_phone');
            $table->string('destination_contact_alt_phone');
            $table->uuid('created_by');
            $table->uuid('updated_by')->nullable();
            $table->uuid('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('ref_no');
            $table->foreign('client_uuid')->references('uuid')->on('clients');
            $table->foreign('transportation_id')->references('id')->on('transportations');
            $table->foreign('shipment_type_id')->references('id')->on('shipment_types');
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('packaging_id')->references('id')->on('packagings');
            $table->foreign('origin_province_id')->references('id')->on('provinces');
            $table->foreign('origin_city_id')->references('id')->on('cities');
            $table->foreign('origin_subdistrict_id')->references('id')->on('subdistricts');
            $table->foreign('destination_province_id')->references('id')->on('provinces');
            $table->foreign('destination_city_id')->references('id')->on('cities');
            $table->foreign('destination_subdistrict_id')->references('id')->on('subdistricts');
            $table->foreign('created_by')->references('uuid')->on('users');
            $table->foreign('updated_by')->references('uuid')->on('users');
            $table->foreign('deleted_by')->references('uuid')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('awbs');
    }
};
