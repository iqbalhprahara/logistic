<?php

namespace App\Imports;

use App\Models\Utility\Import;
use App\Models\Utility\ImportDetail;
use Illuminate\Support\LazyCollection;
use Spatie\SimpleExcel\SimpleExcelReader;

abstract class BaseImport
{
    protected SimpleExcelReader $reader;

    public function __construct(protected Import $import)
    {
        $import->load('createdBy');
        $this->import->rows = 0;
        $this->import->processed = 0;
        $this->import->success = 0;
        $this->import->failed = 0;
        $this->import->save();
    }

    private function readRows(): LazyCollection
    {
        return SimpleExcelReader::create($this->import->getImportFileStoragePath(absolute: true))
            ->headersToSnakeCase()
            ->getRows();
    }

    private function getTotalRowCount(): int
    {
        return $this->readRows()->count();
    }

    private function saveTotalRow()
    {
        $this->import->rows = $this->getTotalRowCount();
        $this->import->save();
    }

    public function handle()
    {
        $this->saveTotalRow();

        foreach ($this->readRows() as $index => $row) {
            $detail = $this->import->details()->create(['row' => $index + 1, 'data' => $row]);
            $detail->setRelation('parent', $this->import);
            $this->onRow($detail);
        }
    }

    abstract public function onRow(ImportDetail $importDetail);
}
