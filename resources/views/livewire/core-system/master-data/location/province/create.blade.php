<div>
    <x-core-system.button type="button" data-bs-toggle="modal" data-bs-target="#modal-create-province">
        &plus; Add New
    </x-core-system.button>

    <form class="form-horizontal" wire:submit.prevent="store">
        <x-core-system.modal id="modal-create-province" wire:ignore.self>
            <x-slot name="title">Add New Province</x-slot>
            <x-slot name="body">
                <div class="mb-3 position-relative">
                    <label class="col-form-label">Name<span class="text-danger">*</span></label>
                    <input type="text"
                        class="form-control @error('province.name') is-invalid @enderror"
                        wire:model.lazy="province.name"
                        placeholder="Name" required>
                    @error('province.name')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Close</x-core-system.button>
                <x-core-system.button>Submit</x-core-system.button>
            </x-slot>
        </x-core-system.modal>
    </form>
</div>
