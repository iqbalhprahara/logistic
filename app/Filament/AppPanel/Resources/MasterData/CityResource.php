<?php

namespace App\Filament\AppPanel\Resources\MasterData;

use App\Enums\CityType;
use App\Filament\AppPanel\Resources\MasterData\CityResource\Pages;
use App\Models\MasterData\City;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function canViewAny(): bool
    {
        return auth()->user()->can('master-data:city');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::formSchema());
    }

    public static function formSchema(): array
    {
        return [
            Forms\Components\Select::make('province_id')
                ->relationship(name: 'province', titleAttribute: 'name')
                ->label('Province')
                ->required()
                ->native(false)
                ->searchable()
                ->preload()
                ->dehydrateStateUsing(fn ($state): int => intval($state))
                ->columnSpanFull(),
            Forms\Components\Select::make('type')
                ->required()
                ->native(false)
                ->searchable()
                ->options(CityType::asOptions())
                ->in(CityType::cases())
                ->dehydrateStateUsing(fn ($state): CityType => CityType::from($state))
                ->columnSpanFull(),
            Forms\Components\TextInput::make('code')
                ->label('Three Leter Code (TLC)')
                ->mask(RawJs::make(<<<'JS'
                    $input.toUpperCase()
                JS))
                ->formatStateUsing(fn (string $state = null): string => strtoupper($state))
                ->dehydrateStateUsing(fn (string $state = null): string => strtoupper($state))
                ->required()
                ->length(3)
                ->columnSpanFull(),
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('province')
            ->groups([
                Tables\Grouping\Group::make('province')
                    ->getTitleFromRecordUsing(fn (City $record): string => $record->province->name)
                    ->getDescriptionFromRecordUsing(fn (City $record): string => "City in {$record->province->name} with province id  {$record->province_id}.")
                    ->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderBy(DB::raw('(select name from provinces where provinces.id = cities.province_id)'), $direction)),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')->label('Three Letter Code (TLC)')->searchable()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('province')
                    ->multiple()
                    ->relationship('province', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('type')
                    ->options(CityType::asOptions())
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(fn (City $record) => ! is_null($record->deleted_at))->authorize('master-data:city:update')->modalWidth('md'),
                Tables\Actions\DeleteAction::make()->hidden(fn (City $record) => ! is_null($record->deleted_at))->authorize('master-data:city:delete'),
                Tables\Actions\RestoreAction::make()->hidden(fn (City $record) => is_null($record->deleted_at))->authorize('master-data:city:restore'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->authorize('master-data:city:delete'),
                    Tables\Actions\RestoreBulkAction::make()->authorize('master-data:city:restore'),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('province');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCities::route('/'),
        ];
    }
}
