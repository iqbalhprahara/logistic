<div class="btn-group">
    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Action <i class="mdi mdi-chevron-down"></i></button>
    <div class="dropdown-menu dropdown-menu-end">
        <a href="" data-bs-toggle="modal" class="dropdown-item"
        data-bs-target="#modal-view-awb-{{ $awb->uuid }}">
            Detail AWB
        </a>

        @if ($awb->deleted_at === null)
        @can('logistic:pickup:input-pickup:update')
            <a href="" data-bs-toggle="modal" class="dropdown-item"
                data-bs-target="#modal-edit-awb-{{ $awb->uuid }}">
                Edit AWB
            </a>
        @endcan
        @can('logistic:pickup:input-pickup:delete')
            <a href="" data-bs-toggle="modal" class="dropdown-item"
                data-bs-target="#modal-delete-awb-{{ $awb->uuid }}">
                Delete AWB
            </a>
        @endcan
        @endif

        @if ($awb->deleted_at !== null)
        @can('logistic:pickup:input-pickup:restore')
            <a href="" data-bs-toggle="modal" class="dropdown-item"
                data-bs-target="#modal-restore-awb-{{ $awb->uuid }}">
                Restore AWB
            </a>
        @endcan
        @endif
    </div>
</div>


@livewire('core-system.logistic.pickup.input-pickup.view', ['uuid' => $awb->uuid, key('awb-edit-'.$awb->uuid)])
@if ($awb->deleted_at === null)
@can('logistic:pickup:input-pickup:update')
    @livewire('core-system.logistic.pickup.input-pickup.edit', ['uuid' => $awb->uuid, key('awb-edit-'.$awb->uuid)])
@endcan
 @can('logistic:pickup:input-pickup:delete')
    @livewire('core-system.logistic.pickup.input-pickup.delete', ['uuid' => $awb->uuid, key('awb-delete-'.$awb->uuid)])
@endcan
@endif
@if ($awb->deleted_at !== null)
@can('logistic:pickup:input-pickup:restore')
    @livewire('core-system.logistic.pickup.input-pickup.restore', ['uuid' => $awb->uuid, key('awb-restore-'.$awb->uuid)])
@endcan
@endif
