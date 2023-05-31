<div>
    <script>
        document.addEventListener('livewire:load', function () {
            $('#modal-create-subdistrict').on('shown.bs.modal' , function () {
                @this.emitSelf('initializeFormData');
            });
        });
    </script>

    <x-core-system.button type="button" data-bs-toggle="modal" data-bs-target="#modal-create-subdistrict">
        &plus; Add New
    </x-core-system.button>

    <form class="form-horizontal" wire:submit.prevent="store">
        <x-core-system.modal id="modal-create-subdistrict" wire:ignore.self>
            <x-slot name="title">Add New Subdistrict</x-slot>
            <x-slot name="body">
                @include('livewire.core-system.master-data.location.subdistrict.part.form')
            </x-slot>
            <x-slot name="footer">
                <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Close</x-core-system.button>
                <x-core-system.button>Submit</x-core-system.button>
            </x-slot>
        </x-core-system.modal>
    </form>
</div>
