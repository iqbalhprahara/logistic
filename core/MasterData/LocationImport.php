<?php

namespace Core\MasterData;

use Core\MasterData\Models\City;
use Core\MasterData\Models\Province;
use Core\MasterData\Models\Subdistrict;
use Core\MasterData\Models\Zipcode;
use Generator;
use Spatie\SimpleExcel\SimpleExcelReader;

class LocationImport
{
    public function __construct(public readonly string $filePath, public readonly string $importFrom = 'excel')
    {
    }

    public function import()
    {
        if ($this->importFrom === 'excel') {
            return $this->importLocationFromExcel();
        }
    }

    public static function fromExcel($filePath)
    {
        return new static($filePath, 'excel');
    }

    protected function importLocationFromExcel()
    {
        foreach ($this->readExcelRows() as $row) {
            $this->insertLocationData($row);
        }
    }

    protected function readExcelRows(): Generator
    {
        /** @var \Illuminate\Support\LazyCollection */
        $rows = SimpleExcelReader::create($this->filePath)
            ->headersToSnakeCase()
            ->getRows();

        foreach ($rows as $row) {
            yield $row;
        }
    }

    protected function insertLocationData(array $data)
    {
        $province = Province::firstOrCreate([
            'name' => $data['provinsi'],
        ]);

        $city = City::firstOrCreate([
            'province_id' => $province->id,
            'code' => $data['tlc'],
            'type' => $data['jenis_kota'],
            'name' => $data['kota'],
        ]);

        $subdistrict = Subdistrict::firstOrCreate([
            'city_id' => $city->id,
            'name' => $data['kecamatan'],
        ]);

        $zipcode = Zipcode::firstOrCreate([
            'subdistrict_id' => $subdistrict->id,
            'zipcode' => $data['kode_pos'],
        ]);
    }
}
