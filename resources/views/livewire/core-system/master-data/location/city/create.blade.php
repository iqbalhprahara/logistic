<div>
    <script>
        document.addEventListener('livewire:init', function () {
            $('#modal-create-city').on('shown.bs.modal' , function () {
                @this.emitSelf('initializeFormData');
            });
        });
    </script>
    <x-core-system.button type="button" data-bs-toggle="modal" data-bs-target="#modal-create-city">
        &plus; Add New
    </x-core-system.button>

    <form class="form-horizontal" wire:submit="store">
        <x-core-system.modal id="modal-create-city" wire:ignore.self>
            <x-slot name="title">Add New City</x-slot>
            <x-slot name="body">
                @include('livewire.core-system.master-data.location.city.part.form')
            </x-slot>
            <x-slot name="footer">
                <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Close</x-core-system.button>
                <x-core-system.button>Submit</x-core-system.button>
            </x-slot>
        </x-core-system.modal>
    </form>
</div>
