<?php

namespace App\Models\Utility;

use App\Enums\ImportStatus;
use App\Enums\ImportType;
use App\Models\Concerns\CreatedBy;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    use HasFactory, HasUuids, CreatedBy;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'uuid';

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'import_type' => ImportType::class,
        'status' => ImportStatus::class,
        'process_start_at' => 'immutable_datetime',
        'finished_at' => 'immutable_datetime',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'datetime',
    ];

    public const STORAGE_PATH = 'imports/';

    public function details()
    {
        return $this->hasMany(ImportDetail::class);
    }

    public function getImportFileStoragePath($absolute = false):string
    {
        if (!$absolute) {
            return static::STORAGE_PATH.$this->getImportFilename();
        }

        return storage_path('app/'.static::STORAGE_PATH.$this->getImportFilename());
    }

    public function getImportFilename():string
    {
        return "{$this->uuid}{$this->file_extension}";
    }

    public function start()
    {
        /** @var \App\Enums\ImportType $importType */
        $importType = $this->import_type;
        $importClass = $importType->importClass();

        /** @var \App\Imports\BaseImport $import */
        $import = new $importClass($this);

        return $import->handle();
    }

    public function markPending()
    {
        $this->status = ImportStatus::PENDING;
        $this->save();
    }

    public function markOnProcess()
    {
        $this->status = ImportStatus::ON_PROCESS;
        $this->process_start_at = CarbonImmutable::now();
        $this->save();
    }

    public function markComplete()
    {
        $this->status = ImportStatus::COMPLETE;
        $this->finished_at = CarbonImmutable::now();
        $this->save();
    }

    public function markFailed()
    {
        $this->status = ImportStatus::FAILED;
        $this->finished_at = CarbonImmutable::now();
        $this->save();
    }
}
