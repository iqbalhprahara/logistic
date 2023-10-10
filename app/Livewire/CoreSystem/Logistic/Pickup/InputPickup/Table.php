<?php

namespace App\Livewire\CoreSystem\Logistic\Pickup\InputPickup;

use App\Livewire\BaseTableComponent;
use Core\Logistic\Models\Awb;
use Core\MasterData\Models\AwbStatus;
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
            'client' => $row->client,
            'pickup_info' => '<span class="fw-bold">Pengirim : </span><br>'.$row->origin_contact_name.'<br>'.'<span class="fw-bold">Alamat Pickup : </span><br>'.$row->origin_address_line1.'<br><span class="badge bg-primary">'.$row->origin_code.'</span>',
            'awbs.created_at' => $row->created_at,
            'user_input.name' => $row->user_input_name,
            'destination_info' => '<span class="fw-bold">Penerima : </span><br>'.$row->destination_contact_name.'<br>'.'<span class="fw-bold">Alamat : </span><br>'.$row->destination_address_line1.'<br><span class="badge bg-primary">'.$row->destination_code.'</span>',
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

    public function advanceSearch(): array
    {
        return [
            'status' => [
                'name' => 'Pilih Status',
                'type' => 'dropDown',
                'options' => AwbStatus::pluck('name', 'id'),
                'column' => 'awbs.awb_status_id',
            ],
        ];
    }

    protected function columnDefinition(): array
    {
        /** type => hidden used for search and sorting only */
        return [
            [
                'header' => 'No. AWB / No. Referensi',
                'column' => 'awb_ref_no',
                'type' => 'raw',
                'searchable' => false,
                'sortable' => false,
            ],
            [
                'header' => 'No. Awb',
                'column' => 'awbs.awb_no',
                'type' => 'hidden',
            ],
            [
                'header' => 'No. Referensi',
                'column' => 'awbs.ref_no',
                'type' => 'hidden',
            ],
            [
                'header' => 'Client',
                'column' => 'client',
                'searchable' => false,
                'sortable' => false,
            ],
            [
                'header' => 'Nama Client',
                'column' => 'user_client.name',
                'type' => 'hidden',
            ],
            [
                'header' => 'Nama Perusahaan',
                'column' => 'company_client.name',
                'type' => 'hidden',
            ],
            [
                'header' => 'Status',
                'column' => 'status.name',
                'type' => 'hidden',
            ],
            [
                'header' => 'Informasi Pickup',
                'column' => 'pickup_info',
                'searchable' => false,
                'sortable' => false,
                'type' => 'raw',
                'width' => 300,
            ],
            [
                'header' => 'Nama Pengirim',
                'column' => 'origin_contact_name',
                'type' => 'hidden',
            ],
            [
                'header' => 'Alamat Pengirim',
                'column' => 'origin_address_line1',
                'type' => 'hidden',
            ],
            [
                'header' => 'Origin',
                'type' => 'hidden',
                'column' => 'origin.code',
            ],
            [
                'header' => 'Informasi Penerima',
                'column' => 'destination_info',
                'searchable' => false,
                'sortable' => false,
                'type' => 'raw',
                'width' => 300,
            ],
            [
                'header' => 'Nama Penerima',
                'column' => 'destination_contact_name',
                'type' => 'hidden',
            ],
            [
                'header' => 'Alamat Penerima',
                'column' => 'destination_address_line1',
                'type' => 'hidden',
            ],
            [
                'header' => 'Destination',
                'type' => 'hidden',
                'column' => 'destination.code',
            ],
            [
                'header' => 'Tanggal Input',
                'column' => 'awbs.created_at',
            ],
            [
                'header' => 'User Input',
                'column' => 'user_input.name',
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
            DB::raw('concat(user_client.name, \' - \', company_client.name) as client'),
            DB::raw('case when ref_no IS NULL then awb_no else concat(awb_no, \'/\', ref_no) end as awb_ref_no'),
            'origin_address_line1',
            'origin_contact_name',
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
            ->leftJoin('clients', 'awbs.client_uuid', '=', 'clients.uuid')
            ->leftJoin('users as user_client', 'clients.user_uuid', '=', 'user_client.uuid')
            ->leftJoin('companies as company_client', 'clients.company_uuid', '=', 'company_client.uuid')
            ->leftJoin('awb_statuses as status', 'awbs.awb_status_id', '=', 'status.id')
            ->leftJoin('cities as origin', 'awbs.origin_city_id', '=', 'origin.id')
            ->leftJoin('cities as destination', 'awbs.destination_city_id', '=', 'destination.id')
            ->leftJoin('users as user_input', 'awbs.created_by', '=', 'user_input.uuid');
    }
}
