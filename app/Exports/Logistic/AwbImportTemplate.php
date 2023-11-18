<?php

namespace App\Exports\Logistic;

use App\Dto\Awb\CreateAwbDto;
use App\Exports\BaseExport;
use App\Models\MasterData\Packing;
use App\Models\MasterData\Service;
use App\Models\MasterData\ShipmentType;
use App\Models\MasterData\Transportation;
use Illuminate\Support\Facades\DB;

final class AwbImportTemplate extends BaseExport
{
    public function __construct(protected bool $asClient = false)
    {
        parent::__construct();
    }

    /**
     * Export then download awb import template
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|string
     */
    public function download(string $filename = null)
    {
        return parent::download($filename ?? 'awb_import_template.xlsx');
    }

    public function handle()
    {
        $this->handleAwbSheet();

        if (! $this->asClient) {
            $this->handleClientSheet();
        }

        $this->handleTransportationSheet()
            ->handleShipmentTypeSheet()
            ->handleServiceSheet()
            ->handlePackingSheet()
            ->handleLocationSheet();
    }

    public function handleAwbSheet(): self
    {
        $clientColumn = fn (int $columnIndex) => $this->asClient ? $columnIndex - 1 : $columnIndex;

        $this->writer->nameCurrentSheet('awb');

        if (! $this->asClient) {
            $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(35, 1);
        }

        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(22, $clientColumn(2));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(13, $clientColumn(3));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(14, $clientColumn(4));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(14, $clientColumn(5));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(13, $clientColumn(6));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(4, $clientColumn(7));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(9, $clientColumn(8));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(30, $clientColumn(9));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(30, $clientColumn(10));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(20, $clientColumn(11));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(20, $clientColumn(12));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(16, $clientColumn(13));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(17, $clientColumn(14));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(28, $clientColumn(15));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(30, $clientColumn(16));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(30, $clientColumn(17));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(20, $clientColumn(18));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(20, $clientColumn(19));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(16, $clientColumn(20));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(17, $clientColumn(21));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(28, $clientColumn(22));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(11, $clientColumn(23));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(11, $clientColumn(24));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(25, $clientColumn(25));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(25, $clientColumn(26));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(25, $clientColumn(27));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(20, $clientColumn(28));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(20, $clientColumn(29));
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(20, $clientColumn(30));

        $this->addRow(CreateAwbDto::defaultImportData($this->asClient));

        return $this;
    }

    public function handleClientSheet(): self
    {
        $this->writer->addNewSheetAndMakeItCurrent('ref. client');
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(35, 1);
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(25, 2);
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(25, 3);

        $clientQuery = DB::table('clients as c')
            ->join('users as u', 'c.user_uuid', 'u.uuid')
            ->join('companies as cp', 'c.company_uuid', 'cp.uuid')
            ->whereNull('u.deleted_at')
            ->whereNull('c.deleted_at')
            ->whereNull('cp.deleted_at')
            ->select([
                'c.uuid',
                'u.name',
                'cp.name as company',
            ]);

        foreach ($clientQuery->cursor() as $client) {
            $this->addRow(collect($client)->toArray());
        }

        return $this;
    }

    public function handleTransportationSheet(): self
    {
        $this->writer->addNewSheetAndMakeItCurrent('ref. transporasi');
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(15, 1);
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(15, 2);

        foreach (Transportation::cursor() as $transportation) {
            $this->addRow([
                'id' => $transportation->id,
                'deskripsi' => $transportation->name,
            ]);
        }

        return $this;
    }

    public function handleShipmentTypeSheet(): self
    {
        $this->writer->addNewSheetAndMakeItCurrent('ref. jenis kiriman');
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(10, 1);
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(10, 2);

        foreach (ShipmentType::cursor() as $shipmenType) {
            $this->addRow([
                'id' => $shipmenType->id,
                'deskripsi' => $shipmenType->name,
            ]);
        }

        return $this;
    }

    public function handleServiceSheet(): self
    {
        $this->writer->addNewSheetAndMakeItCurrent('ref. jenis layanan');
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(10, 1);

        foreach (Service::cursor() as $service) {
            $this->addRow([
                'id' => $service->id,
            ]);
        }

        return $this;
    }

    public function handlePackingSheet(): self
    {
        $this->writer->addNewSheetAndMakeItCurrent('ref. packing');
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(15, 1);
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(15, 2);

        foreach (Packing::cursor() as $packing) {
            $this->addrow([
                'id' => $packing->id,
                'deskripsi' => $packing->name,
            ]);
        }

        return $this;
    }

    public function handleLocationSheet(): self
    {
        $this->writer->addNewSheetAndMakeItCurrent('ref. lokasi');
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(20, 1);
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(20, 2);
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(20, 3);
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(10, 4);
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(25, 5);
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(13, 6);
        $this->writer->getWriter()->getCurrentSheet()->setColumnWidth(13, 7);

        $locationQuery = DB::table('zipcodes as z')
            ->join('subdistricts as s', 'z.subdistrict_id', 's.id')
            ->join('cities as c', 's.city_id', 'c.id')
            ->join('provinces as p', 'c.province_id', 'p.id')
            ->select([
                'p.name as provinsi',
                'c.name as kota',
                'c.type as tipe_kota',
                'c.code as kode_kota',
                's.name as kecamatan',
                's.id as id_kecamatan',
                'z.zipcode as kodepos',
            ]);

        foreach ($locationQuery->cursor() as $location) {
            $this->addRow(collect($location)->toArray());
        }

        return $this;
    }
}
