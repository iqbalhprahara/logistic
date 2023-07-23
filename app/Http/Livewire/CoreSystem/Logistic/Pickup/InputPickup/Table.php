<?php

namespace App\Http\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use App\Http\Livewire\BaseTableComponent;
use Core\Logistic\Models\Awb;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;

class Table extends BaseTableComponent
{
    protected $gates = ['logistic:pickup:input-pickup'];

    public ?string $sortBy = 'awbs.uuid';

    protected function formatResult($row): array
    {
        return [
            'awb_ref_no' => Blade::render(<<<'blade'
                <div class="mb-5">
                    {{ $awbRefNo }}
                    <span class="badge bg-{{ $statusColor }}">{{ $status }}</span>
                </div>
                @can('logistic:pickup:input-pickup:print-awb')
                    @livewire('core-system.logistic.pickup.input-pickup.print-awb', ['uuid' => $uuid, key($uuid)])
                @endcan
            blade, ['awbRefNo' => $row->awb_ref_no, 'uuid' => $row->uuid, 'status' => $row->status, 'statusColor' => $row->getStatusColor()]),
            'origin_address_line1' => $row->origin_address_line1.'<br><span class="badge bg-primary">'.$row->origin_code.'</span>',
            'awbs.created_at' => $row->created_at,
            'user_input_name' => $row->user_input_name,
            'destination_contact_name' => $row->destination_contact_name,
            'destination_address_line1' => $row->destination_address_line1.'<br><span class="badge bg-primary">'.$row->destination_code.'</span>',
            'action' => view('livewire.core-system.logistic.pickup.input-pickup.action', [
                'awb' => $row,
            ]),
        ];
    }

    public function button(): array
    {
        return [
            Blade::render(<<<'blade'
                @can('logistic:pickup:input-pickup:create')
                    @livewire('core-system.logistic.pickup.input-pickup.create')
                @endcan
            blade),
        ];
    }

    protected function columnDefinition(): array
    {
        return [
            [
                'header' => 'No. AWB / No. Referensi',
                'column' => 'awb_ref_no',
                'type' => 'raw',
            ],
            [
                'header' => 'Status',
                'column' => 'status.name',
                'type' => 'hidden',
            ],
            [
                'header' => 'Alamat Pickup',
                'column' => 'origin_address_line1',
                'searchable' => false,
                'sortable' => false,
                'type' => 'raw',
                'width' => 300,
            ],
            [
                'header' => 'Origin',
                'type' => 'hidden',
                'column' => 'origin_code',
            ],
            [
                'header' => 'Tanggal Input',
                'column' => 'awbs.created_at',
            ],
            [
                'header' => 'User Input',
                'column' => 'user_input_name',
            ],
            [
                'header' => 'Nama Penerima',
                'column' => 'destination_contact_name',
            ],
            [
                'header' => 'Alamat Penerima',
                'column' => 'destination_address_line1',
                'searchable' => false,
                'sortable' => false,
                'type' => 'raw',
                'width' => 300,
            ],
            [
                'header' => 'Destination',
                'type' => 'hidden',
                'column' => 'destination_code',
            ],
            [
                'column' => 'action',
                'type' => 'raw',
                'searchable' => false,
                'sortable' => false,
            ],
        ];
    }

    protected function query(): Builder|QueryBuilder
    {
        return Awb::select([
            'awbs.uuid',
            DB::raw('case when ref_no IS NULL then awb_no else concat(awb_no, \'/\', ref_no) end as awb_ref_no'),
            'origin_address_line1',
            'origin.code as origin_code',
            'awbs.created_at',
            'user_input.name as user_input_name',
            'destination_contact_name',
            'destination_address_line1',
            'destination.code as destination_code',
            'awbs.deleted_at',
            'status.name as status',
            'awbs.awb_status_id',
        ])
            ->withTrashed()
            ->leftJoin('awb_statuses as status', 'awbs.awb_status_id', '=', 'status.id')
            ->leftJoin('cities as origin', 'awbs.origin_city_id', '=', 'origin.id')
            ->leftJoin('cities as destination', 'awbs.destination_city_id', '=', 'destination.id')
            ->leftJoin('users as user_input', 'awbs.created_by', '=', 'user_input.uuid');
    }
}
