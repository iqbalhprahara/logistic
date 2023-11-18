<?php

namespace App\Filament\AppPanel\Resources\Logistic\ImportAwbLogResource\RelationManagers;

use App\Enums\ImportDetailStatus;
use App\Models\Utility\ImportDetail;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DetailsRelationManager extends RelationManager
{
    protected static string $relationship = 'details';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('row')
            ->recordTitleAttribute('uuid')
            ->columns([
                Tables\Columns\TextColumn::make('uuid')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('row')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('importable.awb_no')->label('AWB')
                    ->placeholder('awb not created.')
                    ->weight(FontWeight::SemiBold)
                    ->searchable(),
                Tables\Columns\TextColumn::make('data')->icon('heroicon-o-information-circle')->state('Click to view data')
                    ->action(
                        Tables\Actions\Action::make('data')
                            ->slideOver()
                            ->modalWidth('md')
                            ->modalHeading(fn (ImportDetail $record): string => 'Data row #'.$record->row)
                            ->modalCancelAction(false)
                            ->modalSubmitAction(false)
                            ->infolist(function (ImportDetail $record) {
                                return Infolist::make()->columns(2)
                                    ->schema(
                                        collect($record->data)->mapWithKeys(function ($value, $key) {
                                            return [
                                                $key => Infolists\Components\TextEntry::make($key)->badge()
                                                    ->state($value ?: '-')
                                                    ->columnSpan(fn () => in_array(
                                                        $key,
                                                        [
                                                            'ref_no',
                                                            'nama_pengirim',
                                                            'nama_penerima',
                                                            'client_uuid',
                                                        ],
                                                    ) ? 'full' : 1
                                                    ),
                                            ];
                                        })
                                            ->sortBy(function ($value, $key) {
                                                $keys = [
                                                    'ref_no',
                                                    'transportasi',
                                                    'jenis_kiriman',
                                                    'jenis_layanan',
                                                    'packing',
                                                    'cod',
                                                    'asuransi',
                                                    'alamat_pengirim',
                                                    'alamat_pengirim_2',
                                                    'kecamatan_pengirim',
                                                    'kode_pos_pengirim',
                                                    'nama_pengirim',
                                                    'telepon_pengirim',
                                                    'telepon_pengirim_alternatif',
                                                    'alamat_penerima',
                                                    'alamat_penerima_2',
                                                    'kecamatan_penerima',
                                                    'kode_pos_penerima',
                                                    'nama_penerima',
                                                    'telepon_penerima',
                                                    'telepon_penerima_alternatif',
                                                    'jumlah_koli',
                                                    'berat_paket',
                                                    'panjang_volumetric_paket',
                                                    'lebar_volumetric_paket',
                                                    'tinggi_volumetric_paket',
                                                    'nilai_barang',
                                                    'deskripsi_barang',
                                                    'instruksi_khusus',
                                                ];

                                                return array_search($key, $keys, true);
                                            })
                                            ->toArray()
                                    );
                            })
                    ),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (ImportDetail $record): string => $record->status->color()),
                Tables\Columns\TextColumn::make('message')
                    ->extraHeaderAttributes(['class' => 'min-w-[200px]'], true)
                    ->extraCellAttributes(['class' => 'break-all'], true)
                    ->wrap()
                    ->placeholder('no message.'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->searchable()
                    ->options(ImportDetailStatus::asOptions()),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'importable:uuid,awb_no',
        ]);
    }
}
