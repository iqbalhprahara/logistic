<?php

namespace App\Filament\AppPanel\Resources\Logistic\InputPickupResource\Widgets;

use App\Filament\AppPanel\Resources\Logistic\InputPickupResource;
use App\Models\Logistic\Awb;
use App\Services\AwbService;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAwb extends BaseWidget
{
    protected int|string|array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(Awb::limit(5)->latest('uuid'))
            ->columns([
                BadgeableColumn::make('awb_no')
                    ->asPills()
                    ->label('No. AWB / No. Referensi')
                    ->description(fn (Awb $record) => $record->ref_no ?? 'Tidak ada nomor referensi')
                    ->suffixBadges([
                        Badge::make('awb_status')
                            ->label(fn (Awb $record) => $record->awbStatus->name)
                            ->color(fn (Awb $record) => $record->getStatusColor()),
                    ])
                    ->weight(FontWeight::Bold),
                TextColumn::make('created_at')
                    ->label('Diinput Pada')
                    ->dateTime()
                    ->icon('heroicon-o-calendar-days'),
            ])
            ->paginated(false)
            ->actions([
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(fn (Awb $record, AwbService $awbService) => $awbService->downloadAwb($record)),
            ])
            ->headerActions([
                Tables\Actions\Action::make('View All')
                    ->url(InputPickupResource::getNavigationUrl()),
            ]);
    }
}
