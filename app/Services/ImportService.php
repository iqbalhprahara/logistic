<?php

namespace App\Services;

use App\Dto\Import\CreateImportDto;
use App\Enums\ImportStatus;
use App\Jobs\ImportJob;
use App\Models\Utility\Import;
use Illuminate\Support\Facades\Storage;

final class ImportService
{
    public function __construct()
    {
    }

    public function create(CreateImportDto $createImportDto): Import
    {
        $import = new Import($createImportDto->only('importType', 'filename', 'fileExtension')->toArray());
        $import->status = ImportStatus::PENDING;
        $import->created_by = optional(auth()->user())->uuid;
        $import->markPending();

        Storage::putFileAs(Import::STORAGE_PATH, $createImportDto->importFile, $import->getImportFilename());

        ImportJob::dispatch($import)->afterCommit();

        return $import;
    }
}
