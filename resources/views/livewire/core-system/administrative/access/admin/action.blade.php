@if ($admin->email !== 'admin@banana-xpress.com')
    @can('administrative:access:admin:update')
        <a href="" data-bs-toggle="modal" class="btn btn-outline-primary btn-icon"
            data-bs-target="#modal-edit-admin-{{ $admin->uuid }}">
            <i class="fas fa-pen"></i>
        </a>
    @endcan
    @if ($admin->deleted_at === null)
    @can('administrative:access:admin:delete')
        <a href="" data-bs-toggle="modal" class="btn btn-outline-danger"
            data-bs-target="#modal-delete-admin-{{ $admin->uuid }}">
            <i class="fas fa-trash"></i>
        </a>
    @endcan
    @endif

    @if ($admin->deleted_at !== null)
    @can('administrative:access:admin:restore')
        <a href="" data-bs-toggle="modal" class="btn btn-success"
            data-bs-target="#modal-restore-admin-{{ $admin->uuid }}">
            Restore
        </a>
    @endcan
    @endif

    @can('administrative:access:admin:update')
        @livewire('core-system.administrative.access.admin.edit', ['admin' => $admin, 'roleList' => $roleList, key('admin-edit-'.$admin->uuid)])
    @endcan
    @if ($admin->deleted_at === null)
    @can('administrative:access:admin:delete')
        @livewire('core-system.administrative.access.admin.delete', ['admin' => $admin, key('admin-delete-'.$admin->uuid)])
    @endcan
    @endif
    @if ($admin->deleted_at !== null)
    @can('administrative:access:admin:restore')
        @livewire('core-system.administrative.access.admin.restore', ['admin' => $admin, key('admin-restore-'.$admin->uuid)])
    @endcan
    @endif
@endif
