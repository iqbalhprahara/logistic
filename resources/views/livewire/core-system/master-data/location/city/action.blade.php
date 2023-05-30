@can('master-data:location:city:update')
    <a href="" data-bs-toggle="modal" class="btn btn-outline-primary btn-icon"
        data-bs-target="#modal-edit-city-{{ $city->id }}">
        <i class="fas fa-pen"></i>
    </a>
@endcan
@if ($city->deleted_at === null)
@can('master-data:location:city:delete')
    <a href="" data-bs-toggle="modal" class="btn btn-outline-danger"
        data-bs-target="#modal-delete-city-{{ $city->id }}">
        <i class="fas fa-trash"></i>
    </a>
@endcan
@endif

@if ($city->deleted_at !== null)
@can('master-data:location:city:restore')
    <a href="" data-bs-toggle="modal" class="btn btn-success"
        data-bs-target="#modal-restore-city-{{ $city->id }}">
        Restore
    </a>
@endcan
@endif

@can('master-data:location:city:update')
    @livewire('core-system.master-data.location.city.edit', ['city' => $city, 'provinceList' => $provinceList, key('city-edit-'.$city->id)])
@endcan
@if ($city->deleted_at === null)
@can('master-data:location:city:delete')
    @livewire('core-system.master-data.location.city.delete', ['city' => $city, key('city-delete-'.$city->id)])
@endcan
@endif
@if ($city->deleted_at !== null)
@can('master-data:location:city:restore')
    @livewire('core-system.master-data.location.city.restore', ['city' => $city, key('city-restore-'.$city->id)])
@endcan
@endif
