@if ($subdistrict->deleted_at === null)
@can('master-data:location:subdistrict:update')
    <a href="" data-bs-toggle="modal" class="btn btn-outline-primary btn-icon"
        data-bs-target="#modal-edit-subdistrict-{{ $subdistrict->id }}">
        <i class="fas fa-pen"></i>
    </a>
@endcan
@can('master-data:location:subdistrict:delete')
    <a href="" data-bs-toggle="modal" class="btn btn-outline-danger"
        data-bs-target="#modal-delete-subdistrict-{{ $subdistrict->id }}">
        <i class="fas fa-trash"></i>
    </a>
@endcan
@endif

@if ($subdistrict->deleted_at !== null)
@can('master-data:location:subdistrict:restore')
    <a href="" data-bs-toggle="modal" class="btn btn-success"
        data-bs-target="#modal-restore-subdistrict-{{ $subdistrict->id }}">
        Restore
    </a>
@endcan
@endif


@if ($subdistrict->deleted_at === null)
@can('master-data:location:subdistrict:update')
    @livewire('core-system.master-data.location.subdistrict.edit', ['id' => $subdistrict->id, key('subdistrict-edit-'.$subdistrict->id)])
@endcan
@can('master-data:location:subdistrict:delete')
    @livewire('core-system.master-data.location.subdistrict.delete', ['id' => $subdistrict->id, key('subdistrict-delete-'.$subdistrict->id)])
@endcan
@endif
@if ($subdistrict->deleted_at !== null)
@can('master-data:location:subdistrict:restore')
    @livewire('core-system.master-data.location.subdistrict.restore', ['id' => $subdistrict->id, key('subdistrict-restore-'.$subdistrict->id)])
@endcan
@endif
