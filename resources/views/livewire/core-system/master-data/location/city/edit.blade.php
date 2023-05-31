<form class="form-horizontal" wire:submit.prevent="update">
    <x-core-system.modal id="modal-edit-city-{{ $cityId }}" wire:ignore.self>
        <x-slot name="title">Edit City</x-slot>
        <x-slot name="body">
            @include('livewire.core-system.master-data.location.city.part.form')
        </x-slot>
        <x-slot name="footer">
            <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Close</x-core-system.button>
            <x-core-system.button>Update</x-core-system.button>
        </x-slot>
    </x-core-system.modal>
</form>
