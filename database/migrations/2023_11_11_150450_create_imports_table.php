<?php

use App\Enums\ImportDetailStatus;
use App\Enums\ImportStatus;
use App\Enums\ImportType;
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
        Schema::create('imports', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->enum('import_type', array_column(ImportType::cases(), 'value'));
            $table->string('filename');
            $table->string('file_extension');
            $table->unsignedInteger('rows')->nullable();
            $table->unsignedInteger('processed')->nullable();
            $table->unsignedInteger('success')->nullable();
            $table->unsignedInteger('failed')->nullable();
            $table->enum('status', array_column(ImportStatus::cases(), 'value'))->default(ImportStatus::PENDING->value);
            $table->longText('message')->nullable();
            $table->longText('exception')->nullable();
            $table->dateTime('process_start_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->uuid('created_by');
            $table->timestamps();

            $table->foreign('created_by')->references('uuid')->on('users');
            $table->index('status');
            $table->index('finished_at');
            $table->index('created_at');
            $table->index('updated_at');
        });

        Schema::create('import_details', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->uuid('import_uuid');
            $table->unsignedInteger('row');
            $table->jsonb('data')->nullable();
            $table->enum('status', array_column(ImportDetailStatus::cases(), 'value'))->default(ImportDetailStatus::ON_PROCESS->value);
            $table->text('message')->nullable();
            $table->longText('exception')->nullable();
            $table->nullableUuidMorphs('importable');
            $table->timestamps();

            $table->foreign('import_uuid', 'import_details_parent_fk')->references('uuid')->on('imports');
            $table->index('row');
            $table->index('data', null, 'gin');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_details');
        Schema::dropIfExists('imports');
    }
};
