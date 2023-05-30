@push('vendor-styles')
<link href="{{ asset('vendor/skote/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css"/>
@endpush
@push('vendor-scripts')
<script src="{{ asset('vendor/skote/libs/select2/select2.min.js')}}"></script>
@endpush
@push('after-scripts')
<script>
    $(document).ready(function () {
        window.initSelectProvinceEditCity = (id) => {
            $('#province-selection-'+id).select2({
                placeholder: 'Select Province',
                allowClear: false,
                dropdownParent: $("#province-selection-container-"+id),
            });
        }
    });
</script>
@endpush
<div class="row">
    <x-core-system.card>
        <x-slot name="body">
            @livewire('core-system.master-data.location.city.table', compact('provinceList'))
        </x-slot>
    </x-core-system.card>
</div>
