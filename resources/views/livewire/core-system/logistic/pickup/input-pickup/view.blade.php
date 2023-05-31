<div>
    <form class="form-horizontal" id="form-view-awb-{{ $uuid }}" disabled>
        <x-core-system.modal id="modal-view-awb-{{ $uuid }}" wire:ignore.self size="fullscreen" headerClass="bg-primary" closeButton=false>
            <x-slot name="title">Detail AWB</x-slot>
            <x-slot name="body">
                @include('livewire.core-system.logistic.pickup.input-pickup.part.form')
            </x-slot>
            <x-slot name="footer">
                <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Close</x-core-system.button>
            </x-slot>
        </x-core-system.modal>
    </form>
</div>
