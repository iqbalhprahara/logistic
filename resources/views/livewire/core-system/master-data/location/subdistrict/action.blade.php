@can('master-data:location:subdistrict:update')
    <a href="" data-bs-toggle="modal" class="btn btn-outline-primary btn-icon"
        data-bs-target="#modal-edit-subdistrict-{{ $subdistrict->id }}">
        <i class="fas fa-pen"></i>
    </a>
@endcan
@if ($subdistrict->deleted_at === null)
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

@can('master-data:location:subdistrict:update')
    @livewire('core-system.master-data.location.subdistrict.edit', ['subdistrict' => $subdistrict, 'provinceList' => $provinceList, key('subdistrict-edit-'.$subdistrict->id)])
@endcan
@if ($subdistrict->deleted_at === null)
@can('master-data:location:subdistrict:delete')
    @livewire('core-system.master-data.location.subdistrict.delete', ['subdistrict' => $subdistrict, key('subdistrict-delete-'.$subdistrict->id)])
@endcan
@endif
@if ($subdistrict->deleted_at !== null)
@can('master-data:location:subdistrict:restore')
    @livewire('core-system.master-data.location.subdistrict.restore', ['subdistrict' => $subdistrict, key('subdistrict-restore-'.$subdistrict->id)])
@endcan
@endif
