<?php

namespace App\Dto\Awb;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class), MapOutputName(SnakeCaseMapper::class)]
class UpdateAwbDto extends Data
{
    public function __construct(
        public ?string $refNo,
        public string $transportationId,
        public string $shipmentTypeId,
        public string $serviceId,
        public ?string $packingId,
        public bool $isCod,
        public bool $isInsurance,
        public string $originAddressLine1,
        public ?string $originAddressLine2,
        public int $originProvinceId,
        public int $originCityId,
        public int $originSubdistrictId,
        public int $originZipcode,
        public string $originContactName,
        public string $originContactPhone,
        public ?string $originContactAltPhone,
        public string $destinationAddressLine1,
        public ?string $destinationAddressLine2,
        public int $destinationProvinceId,
        public int $destinationCityId,
        public int $destinationSubdistrictId,
        public int $destinationZipcode,
        public string $destinationContactName,
        public string $destinationContactPhone,
        public ?string $destinationContactAltPhone,
        public int $packageKoli,
        public float $packageWeight,
        public float $packageLength,
        public float $packageWidth,
        public float $packageHeight,
        public ?string $packageDesc = null,
        public ?float $packageValue = null,
        public ?string $packageInstruction = null,
    ) {
    }
}
