<?php

namespace App\Http\Livewire\CoreSystem\Home;

use App\Http\Livewire\BaseComponent;
use App\View\Components\CoreSystem\Layout;
use Core\Logistic\Models\Awb;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Dashboard extends BaseComponent
{
    public int $totalAwb = 0;

    public int $currentMonthAwb = 0;

    public int $pendingAwb = 0;

    public int $completeAwb = 0;

    public function mount()
    {
        $this->reloadData();
    }

    public function reloadData()
    {
        $now = now();
        $clientUuid = null;

        if (Auth::user()->isClient()) {
            $clientUuid = Auth::user()->client()->value('uuid');
        }

        $baseQuery = Awb::when($clientUuid, function ($query) use ($clientUuid) {
            $query->client($clientUuid);
        });

        $count = (clone $baseQuery)->select([
            DB::raw('sum(1) as total'),
            DB::raw('sum(case when awb_status_id != 5 then 1 else 0 end) as pending'),
            DB::raw('sum(case when awb_status_id = 5 then 1 else 0 end) as complete'),
        ])->first();

        $this->totalAwb = intval(optional($count)->total);
        $this->pendingAwb = intval(optional($count)->pending);
        $this->completeAwb = intval(optional($count)->complete);

        $this->currentMonthAwb = (clone $baseQuery)->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();
    }

    public function render()
    {
        return view('livewire.core-system.home.dashboard')
            ->layout(Layout::class)
            ->layoutData([
                'metaTitle' => 'Dashboard',
                'title' => 'Dashboard',
            ]);
    }
}
