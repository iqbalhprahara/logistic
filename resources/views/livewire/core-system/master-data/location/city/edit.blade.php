<form class="form-horizontal" wire:submit.prevent="update">
    <script>
        document.addEventListener('livewire:load', function () {
            $('#modal-edit-city-{{ $city->id }}').on('shown.bs.modal' , function () {
                initSelectProvinceEditCity('{{ $city->id }}');
            });

            @this.on('initSelectProvinceEditCity', (id) => {
                initSelectProvinceEditCity(id)
            });

            $(document).on('change.select2', '#province-selection-{{$city->id}}', function (e) {
                @this.set('city.province_id', e.target.value);
            })
        });
    </script>
    <x-core-system.modal id="modal-edit-city-{{ $city->id }}" wire:ignore.self>
        <x-slot name="title">Edit City</x-slot>
        <x-slot name="body">
            <div class="mb-3 position-relative">
                <label class="col-form-label">Province<span class="text-danger">*</span></label>
                <div id="province-selection-container-{{ $city->id }}">
                <select
                id="province-selection-{{$city->id}}"
                class="form-control @error('city.province_id') is-invalid @enderror"
                placeholder="Select Province" style="width:100%" required>
                    <option value=""></option>
                    @foreach ($provinceList as $value => $text)
                    <option value="{{ $value }}" @selected($city->province_id === $value)>{{ $text }}</option>
                    @endforeach
                </select>
                </div>
                @error('city.province_id')
                <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label class="col-form-label">Code<span class="text-danger">*</span></label>
                <input type="text"
                    class="form-control @error('city.code') is-invalid @enderror"
                    wire:model.lazy="city.code"
                    placeholder="Code" minlength="3" maxlength="3" required>
                @error('city.code')
                <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label class="col-form-label">Name<span class="text-danger">*</span></label>
                <input type="text"
                    class="form-control @error('city.name') is-invalid @enderror"
                    wire:model.lazy="city.name"
                    placeholder="Name" required>
                @error('city.name')
                <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Close</x-core-system.button>
            <x-core-system.button>Update</x-core-system.button>
        </x-slot>
    </x-core-system.modal>
</form>
