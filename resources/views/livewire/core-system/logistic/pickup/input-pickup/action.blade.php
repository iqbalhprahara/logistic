@can('logistic:pickup:input-pickup:update')
    <a href="" data-bs-toggle="modal" class="btn btn-outline-primary btn-icon"
        data-bs-target="#modal-edit-company-{{ $company->uuid }}">
        <i class="fas fa-pen"></i>
    </a>
@endcan
@if ($company->deleted_at === null)
@can('logistic:pickup:input-pickup:delete')
    <a href="" data-bs-toggle="modal" class="btn btn-outline-danger"
        data-bs-target="#modal-delete-company-{{ $company->uuid }}">
        <i class="fas fa-trash"></i>
    </a>
@endcan
@endif

@if ($company->deleted_at !== null)
@can('logistic:pickup:input-pickup:restore')
    <a href="" data-bs-toggle="modal" class="btn btn-success"
        data-bs-target="#modal-restore-company-{{ $company->uuid }}">
        Restore
    </a>
@endcan
@endif

@can('logistic:pickup:input-pickup:update')
    @livewire('core-system.logistic.pickup.input-pickup.edit', ['company' => $company, key('company-edit-'.$company->uuid)])
@endcan
@if ($company->deleted_at === null)
@can('logistic:pickup:input-pickup:delete')
    @livewire('core-system.logistic.pickup.input-pickup.delete', ['company' => $company, key('company-delete-'.$company->uuid)])
@endcan
@endif
@if ($company->deleted_at !== null)
@can('logistic:pickup:input-pickup:restore')
    @livewire('core-system.logistic.pickup.input-pickup.restore', ['company' => $company, key('company-restore-'.$company->uuid)])
@endcan
@endif
