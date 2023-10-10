<div>
    <form class="form-horizontal" wire:submit="update">
        <x-core-system.modal id="modal-edit-province-{{ $provinceId }}" wire:ignore.self>
            <x-slot name="title">Edit Province</x-slot>
            <x-slot name="body">
                @include('livewire.core-system.master-data.location.province.part.form')
            </x-slot>
            <x-slot name="footer">
                <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Close</x-core-system.button>
                <x-core-system.button>Update</x-core-system.button>
            </x-slot>
        </x-core-system.modal>
    </form>
</div>
