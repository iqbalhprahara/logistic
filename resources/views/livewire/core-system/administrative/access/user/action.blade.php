@can('administrative:access:user:update')
    <a href="" data-bs-toggle="modal" class="btn btn-outline-primary btn-icon"
        data-bs-target="#modal-edit-user-{{ $user->uuid }}">
        <i class="fas fa-pen"></i>
    </a>
@endcan
@if ($user->deleted_at === null)
@can('administrative:access:user:delete')
    <a href="" data-bs-toggle="modal" class="btn btn-outline-danger"
        data-bs-target="#modal-delete-user-{{ $user->uuid }}">
        <i class="fas fa-trash"></i>
    </a>
@endcan
@endif

@if ($user->deleted_at !== null)
@can('administrative:access:user:restore')
    <a href="" data-bs-toggle="modal" class="btn btn-success"
        data-bs-target="#modal-restore-user-{{ $user->uuid }}">
        Restore
    </a>
@endcan
@endif

@can('administrative:access:user:update')
    @livewire('core-system.administrative.access.user.edit', ['user' => $user, 'companyList' => $companyList, key('user-edit-'.$user->uuid)])
@endcan
@if ($user->deleted_at === null)
@can('administrative:access:user:delete')
    @livewire('core-system.administrative.access.user.delete', ['user' => $user, key('user-delete-'.$user->uuid)])
@endcan
@endif
@if ($user->deleted_at !== null)
@can('administrative:access:user:restore')
    @livewire('core-system.administrative.access.user.restore', ['user' => $user, key('user-restore-'.$user->uuid)])
@endcan
@endif
