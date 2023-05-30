<div>
    <x-core-system.button type="button" data-bs-toggle="modal" data-bs-target="#modal-create-awb">
        &plus; Request Pickup
    </x-core-system.button>

    <form class="form-horizontal" wire:submit.prevent="store">
        <x-core-system.modal id="modal-create-awb" class="" wire:ignore.self size="fullscreen" headerClass="bg-primary" closeButton=false>
            <x-slot name="title">Request Pickup</x-slot>
            <x-slot name="body">
                @include('livewire.core-system.logistic.pickup.input-pickup.part.form')
            </x-slot>
            <x-slot name="footer">
                <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Cancel</x-core-system.button>
                <x-core-system.button>Request Pickup</x-core-system.button>
            </x-slot>
        </x-core-system.modal>
    </form>
</div>
