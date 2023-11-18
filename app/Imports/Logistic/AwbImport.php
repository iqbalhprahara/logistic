<?php

namespace App\Imports\Logistic;

use App\Dto\Awb\CreateAwbDto;
use App\Enums\ImportDetailStatus;
use App\Imports\BaseImport;
use App\Models\Utility\ImportDetail;
use App\Services\AwbService;
use Illuminate\Support\Facades\DB;

final class AwbImport extends BaseImport
{
    public function onRow(ImportDetail $importDetail)
    {
        try {
            DB::transaction(function () use ($importDetail) {
                /** @var AwbService */
                $service = app(AwbService::class);
                $awb = $service->create(
                    CreateAwbDto::fromImport($importDetail)
                );

                $importDetail->status = ImportDetailStatus::SUCCESS;
                $importDetail->importable()->associate($awb);
                $importDetail->save();

                $this->import->processed++;
                $this->import->success++;
                $this->import->save();
            });
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::transaction(function () use ($importDetail, $e) {
                $importDetail->refresh(); // refresh data to prevent query exception when failed

                $this->import->processed++;
                $this->import->failed++;
                $this->import->save();

                $importDetail->status = ImportDetailStatus::FAILED;
                $importDetail->message = "Validation fail for row {$importDetail->row} - {$e->validator->errors()->first()}";
                $importDetail->save();
            });
        } catch (\Throwable $t) {
            DB::transaction(function () use ($importDetail, $t) {
                $importDetail->refresh(); // refresh data to prevent query exception when failed

                $this->import->processed++;
                $this->import->failed++;
                $this->import->save();

                $importDetail->status = ImportDetailStatus::FAILED;
                $importDetail->message = "Unexpected error occurs when saving import data for row {$importDetail->row}";
                $importDetail->exception = $t;
                $importDetail->save();
            });
        }
    }
}
