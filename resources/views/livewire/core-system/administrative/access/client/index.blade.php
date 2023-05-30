@push('vendor-styles')
<link href="{{ asset('vendor/skote/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css"/>
@endpush
@push('vendor-scripts')
<script src="{{ asset('vendor/skote/libs/select2/select2.min.js')}}"></script>
@endpush
@push('after-scripts')
<script>
    $(document).ready(function () {
        window.initSelectCompanyEditUser = (id) => {
            $('#company-selection-'+id).select2({
                placeholder: 'Select Company',
                allowClear: false,
                dropdownParent: $("#company-selection-container-"+id),
            });
        }
    });
</script>
@endpush
<div class="row">
    <x-core-system.card>
        <x-slot name="body">
            @livewire('core-system.administrative.access.client.table', compact('companyList'))
        </x-slot>
    </x-core-system.card>
</div>
