@can('master-data:company:update')
    <a href="" data-bs-toggle="modal" class="btn btn-outline-primary btn-icon"
        data-bs-target="#modal-edit-company-{{ $company->uuid }}">
        <i class="fas fa-pen"></i>
    </a>
@endcan
@if ($company->deleted_at === null)
@can('master-data:company:delete')
    <a href="" data-bs-toggle="modal" class="btn btn-outline-danger"
        data-bs-target="#modal-delete-company-{{ $company->uuid }}">
        <i class="fas fa-trash"></i>
    </a>
@endcan
@endif

@if ($company->deleted_at !== null)
@can('master-data:company:restore')
    <a href="" data-bs-toggle="modal" class="btn btn-success"
        data-bs-target="#modal-restore-company-{{ $company->uuid }}">
        Restore
    </a>
@endcan
@endif

@can('master-data:company:update')
    @livewire('core-system.master-data.company.edit', ['company' => $company, key('company-edit-'.$company->uuid)])
@endcan
@if ($company->deleted_at === null)
@can('master-data:company:delete')
    @livewire('core-system.master-data.company.delete', ['company' => $company, key('company-delete-'.$company->uuid)])
@endcan
@endif
@if ($company->deleted_at !== null)
@can('master-data:company:restore')
    @livewire('core-system.master-data.company.restore', ['company' => $company, key('company-restore-'.$company->uuid)])
@endcan
@endif
