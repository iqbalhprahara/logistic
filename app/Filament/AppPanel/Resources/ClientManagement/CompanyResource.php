<?php

namespace App\Filament\AppPanel\Resources\ClientManagement;

use App\Filament\AppPanel\Resources\ClientManagement\CompanyResources\Pages;
use App\Models\MasterData\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function canViewAny(): bool
    {
        return auth()->user()->can('client-management:company');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->required()
                    ->string()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->placeholder('BNNEX')
                    ->autofocus(),
                Forms\Components\TextInput::make('name')
                    ->string()
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Banana Express'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('uuid')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('users_count')->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->authorize('client-management:company:update'),
                Tables\Actions\RestoreAction::make()->hidden(fn (Company $record) => is_null($record->deleted_at))->authorize('client-management:company:restore'),
                Tables\Actions\DeleteAction::make()->hidden(fn (Company $record) => ! is_null($record->deleted_at))->authorize('client-management:company:delete'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()->authorize('client-management:company:restore'),
                    Tables\Actions\DeleteBulkAction::make()->authorize('client-management:company:delete'),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount(['users']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCompanies::route('/'),
        ];
    }
}
