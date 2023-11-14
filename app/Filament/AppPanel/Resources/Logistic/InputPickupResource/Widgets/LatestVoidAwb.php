<?php

namespace App\Filament\AppPanel\Resources\Logistic\InputPickupResource\Widgets;

use App\Models\Logistic\Awb;
use Awcodes\FilamentBadgeableColumn\Components\Badge;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestVoidAwb extends BaseWidget
{
    protected int|string|array $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(Awb::onlyTrashed()->limit(5)->latest('uuid'))
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
                TextColumn::make('deleted_at')
                    ->label('Dihapus Pada')
                    ->dateTime()
                    ->icon('heroicon-o-calendar-days'),

            ])
            ->paginated(false)
            ->actions([
                Tables\Actions\RestoreAction::make()->authorize('logistic:input-pickup-restore'),
            ]);
    }
}
