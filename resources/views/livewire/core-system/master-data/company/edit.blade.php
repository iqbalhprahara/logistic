<form class="form-horizontal" wire:submit="update">
    <x-core-system.modal id="modal-edit-company-{{ $company->uuid }}" wire:ignore.self>
        <x-slot name="title">Edit Company</x-slot>
        <x-slot name="body">
            <div class="mb-3 position-relative">
                <label class="col-form-label">Code<span class="text-danger">*</span></label>
                <input type="text"
                    class="form-control @error('company.code') is-invalid @enderror"
                    wire:model.blur="company.code"
                    placeholder="Code" required>
                @error('company.code')
                <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label class="col-form-label">Name<span class="text-danger">*</span></label>
                <input type="text"
                    class="form-control @error('company.name') is-invalid @enderror"
                    wire:model.blur="company.name"
                    placeholder="Name" required>
                @error('company.name')
                <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Close</x-core-system.button>
            <x-core-system.button>Update</x-core-system.button>
        </x-slot>
    </x-core-system.modal>
</form>
