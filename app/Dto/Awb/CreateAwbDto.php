<?php

namespace App\Dto\Awb;

use App\Attributes\Validation\RequiredWhenAuthIsNotClient;
use App\Enums\AwbSource;
use App\Models\Auth\User;
use App\Models\Logistic\Awb;
use App\Models\MasterData\City;
use App\Models\MasterData\Client;
use App\Models\MasterData\Packing;
use App\Models\MasterData\Province;
use App\Models\MasterData\Service;
use App\Models\MasterData\ShipmentType;
use App\Models\MasterData\Subdistrict;
use App\Models\MasterData\Transportation;
use App\Models\MasterData\Zipcode;
use App\Models\Utility\ImportDetail;
use App\Rules\CityExists;
use App\Rules\SubdistrictExists;
use Illuminate\Support\Facades\Auth;
use Ramsey;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Digits;
use Spatie\LaravelData\Attributes\Validation\DigitsBetween;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Uuid;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapInputName(SnakeCaseMapper::class), MapOutputName(SnakeCaseMapper::class)]
class CreateAwbDto extends Data
{
    public function __construct(
        #[WithoutValidation]
        public AwbSource|Optional $source,

        #[Nullable, Max(Awb::REF_NO_MAX_LENGTH)]
        public ?string $refNo,

        #[RequiredWhenAuthIsNotClient, Uuid, Exists(Client::class, 'uuid', withoutTrashed: true)]
        public string|Optional $clientUuid,

        #[Required, Exists(Transportation::class, 'id')]
        public string $transportationId,

        #[Required, Exists(ShipmentType::class, 'id')]
        public string $shipmentTypeId,

        #[Required, Exists(Service::class, 'id')]
        public string $serviceId,

        #[Nullable, Exists(Packing::class, 'id')]
        public ?string $packingId,

        #[Required, BooleanType]
        public bool $isCod,

        #[Required, BooleanType]
        public bool $isInsurance,

        #[Required, Max(Awb::ADDRESS_MAX_LENGTH), MapInputName('origin_address_line1'), MapOutputName('origin_address_line1')]
        public string $originAddressLine1,

        #[Nullable, Max(Awb::ADDRESS_MAX_LENGTH), MapInputName('origin_address_line2'), MapOutputName('origin_address_line2')]
        public ?string $originAddressLine2,

        #[Required, Exists(Province::class, 'id', withoutTrashed: true)]
        public int $originProvinceId,

        #[Required, Rule(new CityExists('origin_province_id'))]
        public int $originCityId,

        #[Required, Rule(new SubdistrictExists('origin_city_id'))]
        public int $originSubdistrictId,

        #[Required, Digits(Awb::ZIPCODE_DIGIT)]
        public int $originZipcode,

        #[Required, Max(Awb::CONTACT_NAME_MAX_LENGTH)]
        public string $originContactName,

        #[Required, DigitsBetween(Awb::PHONE_MIN_DIGIT, Awb::PHONE_MAX_DIGIT)]
        public string $originContactPhone,

        #[Nullable, DigitsBetween(Awb::PHONE_MIN_DIGIT, Awb::PHONE_MAX_DIGIT)]
        public ?string $originContactAltPhone,

        #[Required, Max(Awb::ADDRESS_MAX_LENGTH), MapInputName('destination_address_line1'), MapOutputName('destination_address_line1')]
        public string $destinationAddressLine1,

        #[Nullable, Max(Awb::ADDRESS_MAX_LENGTH), MapInputName('destination_address_line2'), MapOutputName('destination_address_line2')]
        public ?string $destinationAddressLine2,

        #[Required, Exists(Province::class, 'id', withoutTrashed: true)]
        public int $destinationProvinceId,

        #[Required, Rule(new CityExists('destination_province_id'))]
        public int $destinationCityId,

        #[Required, Rule(new SubdistrictExists('destination_city_id'))]
        public int $destinationSubdistrictId,

        #[Required, Digits(Awb::ZIPCODE_DIGIT)]
        public int $destinationZipcode,

        #[Required, Max(Awb::CONTACT_NAME_MAX_LENGTH)]
        public string $destinationContactName,

        #[Required, DigitsBetween(Awb::PHONE_MIN_DIGIT, Awb::PHONE_MAX_DIGIT)]
        public string $destinationContactPhone,

        #[Nullable, DigitsBetween(Awb::PHONE_MIN_DIGIT, Awb::PHONE_MAX_DIGIT)]
        public ?string $destinationContactAltPhone,

        #[Required, Min(Awb::MIN_KOLI), Max(Awb::MAX_KOLI)]
        public int $packageKoli,

        #[Required, Rule('decimal:0,2'), Min(Awb::MIN_WEIGHT), Max(Awb::MAX_WEIGHT)]
        public float $packageWeight,

        #[Required, Rule('decimal:0,2'), Min(Awb::MIN_DIMENSION), Max(Awb::MAX_DIMENSION)]
        public float $packageLength,

        #[Required, Rule('decimal:0,2'), Min(Awb::MIN_DIMENSION), Max(Awb::MAX_DIMENSION)]
        public float $packageWidth,

        #[Required, Rule('decimal:0,2'), Min(Awb::MIN_DIMENSION), Max(Awb::MAX_DIMENSION)]
        public float $packageHeight,

        #[Nullable, Max(Awb::PACKAGE_DESC_MAX_LENGTH)]
        public ?string $packageDesc = null,

        #[Nullable, IntegerType, Max(Awb::MAX_PACKAGE_VALUE)]
        public ?float $packageValue = null,

        #[Nullable, Max(Awb::PACKAGE_INSTRUCTION_MAX_LENGTH)]
        public ?string $packageInstruction = null,

        #[Nullable, Uuid, Exists(User::class, 'uuid', withoutTrashed: true)]
        public string|null|Optional $createdBy = null,
    ) {
        if ($clientUuid instanceof Optional && Auth::check() && Auth::user()->isClient()) {
            $this->clientUuid = Auth::user()->client()->value('uuid');
        }
    }

    public static function fromImport(ImportDetail $importDetail)
    {
        // load parent if not loaded
        $importDetail->loadMissing([
            'parent',
            'parent.createdBy',
        ]);

        $dataOptionalCheck = optional($importDetail->data);
        $originCity = Subdistrict::whereId($dataOptionalCheck['kecamatan_pengirim' ?? 0])->value('city_id');
        $originProvince = City::whereId($originCity ?? 0)->value('province_id');
        $destinationCity = Subdistrict::whereId($dataOptionalCheck['kecamatan_penerima'] ?? 0)->value('city_id');
        $destinationProvince = City::whereId($destinationCity ?? 0)->value('province_id');

        $clientUuid = $dataOptionalCheck['client_uuid'] ?? null;
        if ($importDetail->parent->createdBy->isClient()) {
            $importDetail->parent->createdBy->loadMissing('client');

            $clientUuid = $importDetail->parent->createdBy->client->uuid;
        }

        return static::validateAndCreate([
            'source' => AwbSource::IMPORT,
            'ref_no' => $dataOptionalCheck['ref_no'] ?: null,
            'client_uuid' => $clientUuid,
            'transportation_id' => $dataOptionalCheck['transportasi'],
            'shipment_type_id' => $dataOptionalCheck['jenis_kiriman'],
            'service_id' => $dataOptionalCheck['jenis_layanan'],
            'packing_id' => $dataOptionalCheck['packing'] ?: null,
            'is_cod' => static::castYesOrNoToBooleanFromImport($dataOptionalCheck, 'cod'),
            'is_insurance' => static::castYesOrNoToBooleanFromImport($dataOptionalCheck, 'asuransi'),
            'origin_address_line1' => $dataOptionalCheck['alamat_pengirim'],
            'origin_address_line2' => $dataOptionalCheck['alamat_pengirim_2'] ?: null,
            'origin_province_id' => $originProvince,
            'origin_city_id' => $originCity,
            'origin_subdistrict_id' => $dataOptionalCheck['kecamatan_pengirim'],
            'origin_zipcode' => $dataOptionalCheck['kode_pos_pengirim'],
            'origin_contact_name' => $dataOptionalCheck['nama_pengirim'],
            'origin_contact_phone' => $dataOptionalCheck['telepon_pengirim'],
            'origin_contact_alt_phone' => $dataOptionalCheck['telepon_pengirim_alternatif'] ?: null,
            'destination_address_line1' => $dataOptionalCheck['alamat_penerima'],
            'destination_address_line2' => $dataOptionalCheck['alamat_penerima_2'] ?: null,
            'destination_province_id' => $destinationProvince,
            'destination_city_id' => $destinationCity,
            'destination_subdistrict_id' => $dataOptionalCheck['kecamatan_penerima'],
            'destination_zipcode' => $dataOptionalCheck['kode_pos_penerima'],
            'destination_contact_name' => $dataOptionalCheck['nama_penerima'],
            'destination_contact_phone' => $dataOptionalCheck['telepon_penerima'],
            'destination_contact_alt_phone' => $dataOptionalCheck['telepon_penerima_alternatif'] ?: null,
            'package_koli' => $dataOptionalCheck['jumlah_koli'],
            'package_weight' => $dataOptionalCheck['berat_paket'],
            'package_length' => $dataOptionalCheck['panjang_volumetric_paket'],
            'package_width' => $dataOptionalCheck['lebar_volumetric_paket'],
            'package_height' => $dataOptionalCheck['tinggi_volumetric_paket'],
            'package_desc' => $dataOptionalCheck['deskripsi_barang'] ?: null,
            'package_value' => $dataOptionalCheck['nilai_barang'] ?: null,
            'package_instruction' => $dataOptionalCheck['instruksi_khusus'] ?: null,
            'created_by' => $importDetail->parent->createdBy->uuid,
        ]);
    }

    protected static function castYesOrNoToBooleanFromImport(\Illuminate\Support\Optional $data, string $field): ?bool
    {
        $value = $data[$field];

        if (strtolower($value) === 'yes') {
            return true;
        }

        return false;
    }

    public static function defaultImportData(bool $asClient = false): array
    {
        $defaultTransporation = Transportation::value('id');
        $defaultShipmentType = ShipmentType::value('id');
        $defaultService = Service::value('id');
        $defaultSubdistrict = Subdistrict::value('id');
        $defaultZipcode = Zipcode::whereSubdistrictId($defaultSubdistrict)->value('zipcode');

        $data = [
            'ref_no' => null,
            'transportasi' => $defaultTransporation,
            'jenis_kiriman' => $defaultShipmentType,
            'jenis_layanan' => $defaultService,
            'packing' => null,
            'cod' => 'no',
            'asuransi' => 'no',
            'alamat_pengirim' => 'Jl. Makmur No.2',
            'alamat_pengirim_2' => null,
            'kecamatan_pengirim' => $defaultSubdistrict,
            'kode_pos_pengirim' => $defaultZipcode,
            'nama_pengirim' => 'John Doe',
            'telepon_pengirim' => '681288882222',
            'telepon_pengirim_alternatif' => null,
            'alamat_penerima' => 'Jl. Sejahtera No.10',
            'alamat_penerima_2' => null,
            'kecamatan_penerima' => $defaultSubdistrict,
            'kode_pos_penerima' => $defaultZipcode,
            'nama_penerima' => 'Stephanie',
            'telepon_penerima' => '681211113333',
            'telepon_penerima_alternatif' => null,
            'jumlah_koli' => 1,
            'berat_paket' => 1,
            'panjang_volumetric_paket' => 100,
            'lebar_volumetric_paket' => 100,
            'tinggi_volumetric_paket' => 100,
            'nilai_barang' => null,
            'deskripsi_barang' => null,
            'instruksi_khusus' => null,
        ];

        if (! $asClient) {
            $data = array_merge(['client_uuid' => Client::value('uuid') ?? Ramsey\Uuid\Uuid::uuid4()], $data);
        }

        return $data;
    }
}
