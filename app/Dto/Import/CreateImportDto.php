<?php

namespace App\Dto\Import;

use App\Enums\ImportType;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class), MapOutputName(SnakeCaseMapper::class)]
class CreateImportDto extends Data
{
    public string $filename;
    public string $fileExtension;

    public function __construct(
        public ImportType $importType,
        public UploadedFile $importFile,
    ) {
        $this->filename = $importFile->getClientOriginalName();
        $this->fileExtension = '.'.$importFile->getClientOriginalExtension();
    }
}
