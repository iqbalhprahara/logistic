<div>
    <form class="form-horizontal" wire:submit.prevent="update">
        <x-core-system.modal id="modal-edit-awb-{{ $uuid }}" wire:ignore.self size="fullscreen" headerClass="bg-primary" closeButton=false>
            <x-slot name="title">Edit AWB</x-slot>
            <x-slot name="body">
                @include('livewire.core-system.logistic.pickup.input-pickup.part.form')
            </x-slot>
            <x-slot name="footer">
                <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Cancel</x-core-system.button>
                <x-core-system.button>Update</x-core-system.button>
            </x-slot>
        </x-core-system.modal>
    </form>
</div>
