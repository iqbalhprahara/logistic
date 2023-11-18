<?php

namespace App\Services;

use App\Dto\Awb\CreateAwbDto;
use App\Dto\Awb\InputAwbStatusDto;
use App\Dto\Awb\UpdateAwbDto;
use App\Dto\Import\CreateImportDto;
use App\Enums\ImportType;
use App\Exports\Logistic\AwbImportTemplate;
use App\Models\Logistic\Awb;
use App\Models\Logistic\AwbStatusHistory;
use App\Models\Utility\Import;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

final class AwbService
{
    protected ImportService $importService;

    protected SequenceService $sequenceService;

    protected \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard $auth;

    public function __construct()
    {
        $this->importService = app(ImportService::class);
        $this->sequenceService = app(SequenceService::class);
        $this->auth = auth();
    }

    public function create(CreateAwbDto $createAwbDto): Awb
    {
        $awb = new Awb($createAwbDto->all());
        $awb->awb_no = $this->sequenceService->generate('awb', now());
        $awb->awb_status_id = Awb::defaultStatus();

        if (! $awb->created_by) {
            $awb->created_by = optional($this->auth->user())->uuid;
        }

        $awb->save();

        $awb->awbStatusHistories()->create([
            'awb_status_id' => $awb->awb_status_id,
            'created_by' => $awb->created_by,
            'created_at' => $awb->created_at,
        ]);

        return $awb;
    }

    public function update(string $uuid, UpdateAwbDto $updateAwbDto): Awb
    {
        $awb = Awb::findOrFail($uuid);
        $awb->fill($updateAwbDto->all());
        $awb->updated_by = optional($this->auth->user())->uuid;
        $awb->save();

        return $awb;
    }

    public function inputStatus(InputAwbStatusDto $inputAwbStatusDto): AwbStatusHistory
    {
        /** @var Awb $awb */
        $awb = Awb::find($inputAwbStatusDto->awbUuid);
        $awb->awb_status_id = $inputAwbStatusDto->awbStatusId;
        $awb->save();

        $statusHistory = $awb->awbStatusHistories()->create(
            array_merge(
                $inputAwbStatusDto->all(),
                ['created_by' => optional($this->auth->user())->uuid],
            ),
        );

        return $statusHistory;
    }

    /**
     * @param \App\Models\Logistic\Awb
     */
    public function downloadAwb(Awb $awb): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $logo = base64_encode(File::get(resource_path('images/logo/color.svg')));

        /** @var \Barryvdh\DomPDF\PDF $pdf */
        $pdf = Pdf::loadView('exports.logistic.awb-pdf', compact('awb', 'logo'))
            ->setPaper('A6', 'landscape');

        return response()->streamDownload(fn () => print($pdf->output()), $awb->awb_no.'.pdf');
    }

    public function import(UploadedFile $importFile): Import
    {
        return $this->importService->create(CreateImportDto::from(['import_type' => ImportType::AWB, 'import_file' => $importFile]));
    }

    /**
     * Export then download awb import template
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|string
     */
    public function downloadImportTemplate($asClient = false)
    {
        return (new AwbImportTemplate(asClient: $asClient))->download();
    }
}
