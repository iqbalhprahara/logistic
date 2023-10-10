<div>
    @push('after-scripts')
    <script>
        document.addEventListener('livewire:init', function () {
            $('#modal-create-province').on('shown.bs.modal' , function () {
                @this.emitSelf('initializeFormData');
            });
        });
    </script>
    @endpush
    <x-core-system.button type="button" data-bs-toggle="modal" data-bs-target="#modal-create-province">
        &plus; Add New
    </x-core-system.button>

    <form class="form-horizontal" wire:submit="store">
        <x-core-system.modal id="modal-create-province" wire:ignore.self>
            <x-slot name="title">Add New Province</x-slot>
            <x-slot name="body">
                @include('livewire.core-system.master-data.location.province.part.form')
            </x-slot>
            <x-slot name="footer">
                <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Close</x-core-system.button>
                <x-core-system.button>Submit</x-core-system.button>
            </x-slot>
        </x-core-system.modal>
    </form>
</div>
