<?php

namespace App\Filament\AppPanel\Resources\Logistic;

use App\Enums\ImportStatus;
use App\Enums\ImportType;
use App\Filament\AppPanel\Resources\Logistic\ImportAwbLogResource\Pages;
use App\Filament\AppPanel\Resources\Logistic\ImportAwbLogResource\RelationManagers;
use App\Models\Utility\Import;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ImportAwbLogResource extends Resource
{
    protected static ?string $model = Import::class;

    protected static ?string $modelLabel = 'Import AWB Log';

    protected static ?string $pluralModelLabel = 'Import AWB Log';

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('filename')->icon('heroicon-o-document')->columnSpan(2),
                Infolists\Components\TextEntry::make('status')->badge()->color(fn (Import $record): string => $record->status->color())->columnSpan(2),
                Infolists\Components\TextEntry::make('message')
                    ->columnSpanFull()
                    ->hidden(fn (Import $record): string => $record->status === ImportStatus::COMPLETE),
                Infolists\Components\TextEntry::make('created_at')->dateTime()->columnSpan(2),
                Infolists\Components\TextEntry::make('createdBy.name')->columnSpan(2),
                Infolists\Components\TextEntry::make('process_start_at')->dateTime()->placeholder('-')->columnSpan(2),
                Infolists\Components\TextEntry::make('finished_at')->dateTime()->placeholder('-')->columnSpan(2),
                Infolists\Components\TextEntry::make('rows')->numeric(thousandsSeparator: '.')->badge()->placeholder('-'),
                Infolists\Components\TextEntry::make('processed')->numeric(thousandsSeparator: '.')->badge()->color('info')->placeholder('-'),
                Infolists\Components\TextEntry::make('success')->numeric(thousandsSeparator: '.')->badge()->color('success')->placeholder('-'),
                Infolists\Components\TextEntry::make('failed')->numeric(thousandsSeparator: '.')->badge()->color('danger')->placeholder('-'),
            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('uuid')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('filename')->icon('heroicon-o-document')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (Import $record): string => $record->status->color())
                    ->description(fn (Import $record) => $record->message)
                    ->extraCellAttributes(['class' => 'break-all'], true)
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->description(fn (Import $record) => $record->createdBy->name),
                Tables\Columns\TextColumn::make('process_start_at')->dateTime()->placeholder('-'),
                Tables\Columns\TextColumn::make('finished_at')->dateTime()->placeholder('-'),
                Tables\Columns\TextColumn::make('rows')->numeric(thousandsSeparator: '.')->badge()->placeholder('-')->sortable(),
                Tables\Columns\TextColumn::make('processed')->numeric(thousandsSeparator: '.')->badge()->color('info')->placeholder('-')->sortable(),
                Tables\Columns\TextColumn::make('success')->numeric(thousandsSeparator: '.')->badge()->color('success')->placeholder('-')->sortable(),
                Tables\Columns\TextColumn::make('failed')->numeric(thousandsSeparator: '.')->badge()->color('danger')->placeholder('-')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->searchable()
                    ->options(ImportStatus::asOptions()),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('import_type', ImportType::AWB)
            ->when(auth()->user()->isClient(), fn (Builder $query) => $query->where('created_by', auth()->user()->uuid))
            ->with([
                'createdBy:uuid,name',
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DetailsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListImportAwbLogs::route('/'),
            'view' => Pages\ViewImportAwbLog::route('/{record}'),
        ];
    }
}
