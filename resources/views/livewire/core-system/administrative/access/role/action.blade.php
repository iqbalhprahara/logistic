@if (!in_array($role->name, ['Super Admin', 'Client']))
    @can('administrative:access:role:assign-permissions')
        <a href="" data-bs-toggle="modal" class="btn btn btn-outline-success btn-icon"
            data-bs-target="#modal-assign-role-{{ $role->id }}">
            <i class="fas fas fa-user-check"></i>
        </a>
    @endcan

    @can('administrative:access:role:update')
        <a href="" data-bs-toggle="modal" class="btn btn-outline-primary btn-icon"
            data-bs-target="#modal-edit-role-{{ $role->id }}">
            <i class="fas fa-pen"></i>
        </a>
    @endcan
    @can('administrative:access:role:delete')
        <a href="" data-bs-toggle="modal" class="btn btn-outline-danger"
            data-bs-target="#modal-delete-role-{{ $role->id }}">
            <i class="fas fa-trash"></i>
        </a>
    @endcan

    @can('administrative:access:role:assign-permissions')
        @livewire('core-system.administrative.access.role.assign-permissions', ['role' => $role, key('role-assign-'.$role->id)])
    @endcan

    @can('administrative:access:role:update')
        @livewire('core-system.administrative.access.role.edit', ['role' => $role, key('role-edit-'.$role->id)])
    @endcan
    @can('administrative:access:role:delete')
        @livewire('core-system.administrative.access.role.delete', ['role' => $role, key('role-delete-'.$role->id)])
    @endcan
@endif
