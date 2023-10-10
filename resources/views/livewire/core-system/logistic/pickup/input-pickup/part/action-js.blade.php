@push('after-scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('component.initialized', (component) => {
            if (
                component.fingerprint.name == 'core-system.logistic.pickup.input-pickup.edit'
            ) {
                var id = component.serverMemo.data.uuid
                $('#modal-edit-awb-'+id).on('shown.bs.modal', function () {
                    var wire = window.livewire.find(component.fingerprint.id);
                    wire.emitSelf('initializeFormData');
                })
            }

            if (
                component.fingerprint.name == 'core-system.logistic.pickup.input-pickup.view'
            ) {
                var id = component.serverMemo.data.uuid
                $('#modal-view-awb-'+id).on('shown.bs.modal', function () {
                    var wire = window.livewire.find(component.fingerprint.id);
                    wire.emitSelf('initializeFormData');
                })
            }

            if (
                component.fingerprint.name == 'core-system.logistic.pickup.input-pickup.input-status'
            ) {
                var id = component.serverMemo.data.uuid
                $('#modal-input-status-awb-'+id).on('shown.bs.modal', function () {
                    var wire = window.livewire.find(component.fingerprint.id);
                    wire.emitSelf('initializeData');
                })
            }

            if (
                component.fingerprint.name == 'core-system.logistic.pickup.input-pickup.status-history'
            ) {
                var id = component.serverMemo.data.uuid
                $('#modal-status-history-awb-'+id).on('shown.bs.modal', function () {
                    var wire = window.livewire.find(component.fingerprint.id);
                    wire.emitSelf('initializeData');
                })
            }
        });
    });
</script>
@endpush
