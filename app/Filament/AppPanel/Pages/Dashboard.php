<?php

namespace App\Filament\AppPanel\Pages;

use App\Filament\AppPanel\Resources;
use Filament\Widgets;

class Dashboard extends \Filament\Pages\Dashboard
{
    /**
     * @return int | string | array<string, int | string | null>
     */
    public function getColumns(): int|string|array
    {
        return 4;
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            Widgets\AccountWidget::class,
            Resources\Logistic\InputPickupResource\Widgets\AwbOverview::class,
            Resources\Logistic\InputPickupResource\Widgets\LatestAwb::class,
            Resources\Logistic\InputPickupResource\Widgets\LatestVoidAwb::class,
        ];
    }
}
