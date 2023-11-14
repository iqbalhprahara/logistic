<?php

namespace App\Filament\AppPanel\Resources\Logistic\InputPickupResource\Widgets;

use App\Models\Logistic\Awb;
use App\Models\Logistic\AwbStatusHistory;
use App\Models\MasterData\AwbStatus;
use Carbon\CarbonPeriod;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class AwbOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 3;

    protected function getStats(): array
    {
        return [
            $this->getThisMonthStat(),
            $this->getDeliveredStat(),
            $this->getVoidStat(),
        ];
    }

    private function getThisMonthStat(): Stat
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $results = Awb::withTrashed()
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->select(
                DB::raw('extract(\'day\' from created_at) as date'),
                DB::raw('count(1) over (partition by extract(\'day\' from created_at))  as count'),
            )
            ->distinct()
            ->get();

        $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

        $chartMatrix = [];
        foreach ($period as $date) {
            $chartMatrix[] = $results->firstWhere('date', $date->day)?->count ?? 0;
        }

        return Stat::make('AWB '.$startOfMonth->format('F Y'), $results->sum('count'))
            ->color('primary')
            ->icon('heroicon-o-truck')
            ->chart($chartMatrix)
            ->chartColor('primary');
    }

    private function getDeliveredStat(): Stat
    {
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        $results = AwbStatusHistory::whereAwbStatusId(AwbStatus::DELIVERED)
            ->whereBetween(DB::raw('coalesce(status_at, created_at)'), [$startOfYear, $endOfYear])
            ->whereBetween('created_at', [$startOfYear, $endOfYear])
            ->select(
                DB::raw('extract(\'month\' from coalesce(status_at, created_at)) as month'),
                DB::raw('count(1) over (partition by extract(\'month\' from coalesce(status_at, created_at)))  as count'),
            )
            ->distinct()
            ->get();

        $chartMatrix = [];
        foreach (range(1, 12) as $month) {
            $chartMatrix[] = $results->firstWhere('month', $month)?->count ?? 0;
        }

        return Stat::make('AWB Terkirim '.$startOfYear->year, $results->sum('count'))
            ->color('success')
            ->icon('heroicon-o-document-check')
            ->chart($chartMatrix)
            ->chartColor('success');
    }

    private function getVoidStat(): Stat
    {
        $startOfYear = now()->startOfYear();
        $endOfYear = now()->endOfYear();

        $results = Awb::onlyTrashed()
            ->whereBetween('deleted_at', [$startOfYear, $endOfYear])
            ->select(
                DB::raw('extract(\'month\' from deleted_at) as month'),
                DB::raw('count(1) over (partition by extract(\'month\' from deleted_at))  as count'),
            )
            ->distinct()
            ->get();

        $chartMatrix = [];
        foreach (range(1, 12) as $month) {
            $chartMatrix[] = $results->firstWhere('month', $month)?->count ?? 0;
        }

        return Stat::make('AWB Void '.$startOfYear->year, $results->sum('count'))
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->chart($chartMatrix)
            ->chartColor('danger');
    }
}
