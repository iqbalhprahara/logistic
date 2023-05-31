<x-core-system.modal id="modal-delete-province-{{ $province->id }}" class="text-start" wire:ignore.self>
<x-slot name="title">Delete Province</x-slot>
<x-slot name="body">
    Are you sure you want to delete this province?
</x-slot>
<x-slot name="footer">
    <form wire:submit.prevent="destroy">
        <x-core-system.button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No
        </x-core-system.button>
        <x-core-system.button type="submit" class="text-white" color="danger">Yes</x-core-system.button>
    </form>
</x-slot>
</x-core-system.modal>