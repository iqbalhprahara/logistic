<?php

namespace App\Filament\AppPanel\Resources\MasterData;

use App\Filament\AppPanel\Resources\MasterData\ProvinceResource\Pages;
use App\Models\MasterData\Province;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProvinceResource extends Resource
{
    protected static ?string $model = Province::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-europe-africa';

    public static function canViewAny(): bool
    {
        return auth()->user()->can('master-data:province');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::formSchema());
    }

    public static function formSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(fn (Province $record) => ! is_null($record->deleted_at))->authorize('master-data:province:update')->modalWidth('md'),
                Tables\Actions\DeleteAction::make()->hidden(fn (Province $record) => ! is_null($record->deleted_at))->authorize('master-data:province:delete'),
                Tables\Actions\RestoreAction::make()->hidden(fn (Province $record) => is_null($record->deleted_at))->authorize('master-data:province:restore'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->authorize('master-data:province:delete'),
                    Tables\Actions\RestoreBulkAction::make()->authorize('master-data:province:restore'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProvinces::route('/'),
        ];
    }
}
