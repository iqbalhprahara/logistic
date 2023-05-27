@push('after-scripts')
    <script>
        function permissionSelectDeselectAll() {
            let inputs = document.querySelectorAll('#role-permission-list input');

            if (inputs.length === 0) {
                return;
            }

            let state = inputs[0].checked;
            inputs.forEach(el => {
                el.checked = !state;
            });
        }
    </script>
@endpush
<div class="row">
    <x-core-system.card>
        <x-slot name="body">
            @livewire('core-system.administrative.access.role.table')
        </x-slot>
    </x-core-system.card>
</div>
