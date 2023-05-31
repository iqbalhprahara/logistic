<div class="row">
    @push('after-scripts')
    <script>
        Livewire.hook('component.initialized', (component) => {
            if (
                component.fingerprint.name == 'core-system.master-data.location.province.edit'
            ) {
                var id = component.serverMemo.data.provinceId
                $('#modal-edit-province-'+id).on('shown.bs.modal', function () {
                    var wire = window.livewire.find(component.fingerprint.id);
                    wire.emitSelf('initializeFormData');
                })
            }
        });
    </script>
    @endpush

    <x-core-system.card>
        <x-slot name="body">
            @livewire('core-system.master-data.location.province.table')
        </x-slot>
    </x-core-system.card>
</div>
