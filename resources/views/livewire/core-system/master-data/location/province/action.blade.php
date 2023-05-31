@if ($province->deleted_at === null)
@can('master-data:location:province:update')
    <a href="" data-bs-toggle="modal" class="btn btn-outline-primary btn-icon"
        data-bs-target="#modal-edit-province-{{ $province->id }}">
        <i class="fas fa-pen"></i>
    </a>
@endcan
@can('master-data:location:province:delete')
    <a href="" data-bs-toggle="modal" class="btn btn-outline-danger"
        data-bs-target="#modal-delete-province-{{ $province->id }}">
        <i class="fas fa-trash"></i>
    </a>
@endcan
@endif

@if ($province->deleted_at !== null)
@can('master-data:location:province:restore')
    <a href="" data-bs-toggle="modal" class="btn btn-success"
        data-bs-target="#modal-restore-province-{{ $province->id }}">
        Restore
    </a>
@endcan
@endif

@if ($province->deleted_at === null)
@can('master-data:location:province:update')
    @livewire('core-system.master-data.location.province.edit', ['id' => $province->id, key('province-edit-'.$province->id)])
@endcan
@can('master-data:location:province:delete')
    @livewire('core-system.master-data.location.province.delete', ['id' => $province->id, key('province-delete-'.$province->id)])
@endcan
@endif
@if ($province->deleted_at !== null)
@can('master-data:location:province:restore')
    @livewire('core-system.master-data.location.province.restore', ['id' => $province->id, key('province-restore-'.$province->id)])
@endcan
@endif
