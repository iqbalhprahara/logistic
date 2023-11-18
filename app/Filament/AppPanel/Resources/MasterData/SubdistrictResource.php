<?php

namespace App\Filament\AppPanel\Resources\MasterData;

use App\Filament\AppPanel\Resources\MasterData\SubdistrictResource\Pages;
use App\Models\MasterData\City;
use App\Models\MasterData\Province;
use App\Models\MasterData\Subdistrict;
use App\Models\MasterData\Zipcode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SubdistrictResource extends Resource
{
    protected static ?string $model = Subdistrict::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function canViewAny(): bool
    {
        return auth()->user()->can('master-data:subdistrict');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('city_id')
                    ->relationship(name: 'city', titleAttribute: 'name')
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->preload()
                    ->dehydrateStateUsing(fn ($state): int => intval($state))
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                // Forms\Components\Repeater::make('zipcodes')
                //     ->relationship()
                //     ->simple(
                //         Forms\Components\TextInput::make('zipcode')
                //             ->required()
                //             ->numeric()
                //             ->mask('99999')
                //             ->length(5)
                //     )
                //     ->addActionLabel('Add zipcode')
                //     ->minItems(1)
                //     ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('city')
            ->groups([
                Tables\Grouping\Group::make('city')
                    ->getTitleFromRecordUsing(fn (Subdistrict $record): string => "{$record->city->type} - {$record->city->name} ({$record->city->code})")
                    ->getDescriptionFromRecordUsing(fn (Subdistrict $record): string => "Subdistrict in {$record->city->type} - {$record->city->name} with city id {$record->city_id} , in {$record->city->province->name} with province id {$record->city->province->id}.")
                    ->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderBy(DB::raw('(select name from cities where cities.id = subdistricts.city_id)'), $direction)),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                // Tables\Columns\TextColumn::make('zipcodes.zipcode')->label('Zipcodes')
                //     ->icon('heroicon-o-envelope')
                //     ->listWithLineBreaks()
                //     ->searchable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('location')
                    ->form([
                        Forms\Components\Fieldset::make('Location')
                            ->schema([
                                Forms\Components\Select::make('province_id')
                                    ->label('Province')
                                    ->searchable()
                                    ->native(false)
                                    ->options(Province::pluck('name', 'id'))
                                    ->preload()
                                    ->afterStateUpdated(function (Set $set, ?int $state, ?int $old) {
                                        if ($state !== $old) {
                                            $set('city_id', null);
                                        }
                                    })
                                    ->columnSpanFull(),
                                Forms\Components\Select::make('city_id')
                                    ->label('City')
                                    ->searchable()
                                    ->native(false)
                                    ->options(function (Get $get) {
                                        $provinceId = $get('province_id');
                                        if (! $provinceId) {
                                            return [];
                                        }

                                        return City::whereProvinceId($provinceId)->select(
                                            DB::raw('concat(type, \' - \', name, \'(\', code, \')\') as name'),
                                            'id',
                                        )
                                            ->pluck('name', 'id');
                                    })
                                    ->preload()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['province_id'],
                                fn (Builder $query, $province): Builder => $query->whereHas('city', fn ($city) => $city->where('province_id', $province))
                            )
                            ->when(
                                $data['city_id'],
                                fn (Builder $query, $city): Builder => $query->where('city_id', $city)
                            );
                    })
                    ->indicateUsing(function (array $data) {
                        $indicators = [];
                        if ($data['province_id']) {
                            $province = Province::where('id', $data['province_id'])->value('name');
                            $indicators[] = Indicator::make("Province : {$province}")->removeField('province_id');
                        }

                        if ($data['city_id']) {
                            $city = City::where('id', $data['city_id'])->value('name');
                            $indicators[] = Indicator::make("City : {$city}")->removeField('city_id');
                        }

                        if (empty($indicators)) {
                            return null;
                        }

                        return $indicators;
                    }),
                // Tables\Filters\SelectFilter::make('zipcodes')
                //     ->multiple()
                //     ->getSearchResultsUsing(fn (string $search) => Zipcode::where('zipcode', 'like', '%'.$search.'%')->distinct('zipcode')->pluck('zipcode', 'zipcode'))
                //     ->query(fn (Builder $query, array $data) => $query->when($data['values'], fn (Builder $query, $zipcodes) => $query->whereHas('zipcodes', fn ($zipcode) => $zipcode->select(DB::raw(1))->whereIn('zipcode', $zipcodes))))
                //     ->searchable()
                //     ->native(false)
                //     ->indicateUsing(function (array $data): ?string {
                //         if (! $data['values']) {
                //             return null;
                //         }

                //         return 'Zipcodes : '.implode(' & ', $data['values']);
                //     }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(fn (Subdistrict $record) => ! is_null($record->deleted_at))->authorize('master-data:subdistrict:update')->modalWidth('md'),
                Tables\Actions\DeleteAction::make()->hidden(fn (Subdistrict $record) => ! is_null($record->deleted_at))->authorize('master-data:subdistrict:delete'),
                Tables\Actions\RestoreAction::make()->hidden(fn (Subdistrict $record) => is_null($record->deleted_at))->authorize('master-data:subdistrict:restore'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->authorize('master-data:subdistrict:delete'),
                    Tables\Actions\RestoreBulkAction::make()->authorize('master-data:subdistrict:restore'),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'zipcodes',
            'city',
            'city.province',
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSubdistricts::route('/'),
        ];
    }
}
