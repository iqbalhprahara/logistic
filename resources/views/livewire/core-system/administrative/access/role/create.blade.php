<div>
    <x-core-system.button type="button" data-bs-toggle="modal" data-bs-target="#modal-create-role">
        &plus; Add New
    </x-core-system.button>

    <form class="form-horizontal" wire:submit="store">
        <x-core-system.modal id="modal-create-role" wire:ignore.self>
            <x-slot name="title">Add New Role</x-slot>
            <x-slot name="body">
                <label for="name" class="form-label col-lg-3">Role Name <span class="text-danger">*</span></label>
                <input id="role-name" type="text" class="form-control @error('role.name') is-invalid @enderror" required wire:model.blur="role.name" placeholder="Role Name"/>
                @error('role.name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </x-slot>
            <x-slot name="footer">
                <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Close</x-core-system.button>
                <x-core-system.button>Submit</x-core-system.button>
            </x-slot>
        </x-core-system.modal>
    </form>
</div>
