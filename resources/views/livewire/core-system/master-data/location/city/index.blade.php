@push('after-scripts')
<script>
    Livewire.hook('component.initialized', (component) => {
    if (
        component.fingerprint.name == 'core-system.master-data.location.city.edit'
    ) {
        var id = component.serverMemo.data.cityId
        $('#modal-edit-city-'+id).on('shown.bs.modal', function () {
            var wire = window.livewire.find(component.fingerprint.id);
            wire.emitSelf('initializeFormData');
        })
    }
});
</script>
@endpush
<div class="row">
    <x-core-system.card>
        <x-slot name="body">
            @livewire('core-system.master-data.location.city.table')
        </x-slot>
    </x-core-system.card>
</div>
