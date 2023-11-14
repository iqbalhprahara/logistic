<?php

namespace App\Enums;

enum CityType: string
{
    use AsOption;

    case KOTA = 'KOTA';
    case KOTA_PROVINSI = 'KOTA PROVINSI';
    case KABUPATEN = 'KAB';
}
