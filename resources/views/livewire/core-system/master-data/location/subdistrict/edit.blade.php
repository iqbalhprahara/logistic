<form class="form-horizontal" wire:submit.prevent="update">
    <script>
        document.addEventListener('livewire:load', function () {
            $('#modal-edit-subdistrict-{{ $subdistrict->id }}').on('shown.bs.modal' , function () {
                initSelectProvinceEditSubdistrict({{ $subdistrict->id }});
                initSelectCityEditSubdistrict({{ $subdistrict->id }});
            });

            @this.on('initSelectProvinceEditSubdistrict', (id) => {
                initSelectProvinceEditSubdistrict(id)
            });

            @this.on('initSelectCityEditsubdistrict', (id) => {
                initSelectCityEditSubdistrict(id);
            });

            $(document).on('change.select2', '#province-selection-{{$subdistrict->id}}', function (e) {
                @this.set('province', e.target.value);
            });

            $(document).on('change.select2', '#city-selection-{{$subdistrict->id}}', function (e) {
                @this.set('subdistrict.city_id', e.target.value);
            });
        });
    </script>
    <x-core-system.modal id="modal-edit-subdistrict-{{ $subdistrict->id }}" wire:ignore.self>
        <x-slot name="title">Edit Subdistrict</x-slot>
        <x-slot name="body">
            <div class="mb-3 position-relative">
                <label class="col-form-label">Province</label>
                <div id="province-selection-container-{{ $subdistrict->id }}">
                <select
                    id="province-selection-{{$subdistrict->id}}"
                    class="form-control @error('province') is-invalid @enderror"
                    placeholder="Select Province" style="width:100%">
                    <option value=""></option>
                    @foreach ($provinceList as $provinceOption)
                    <option value="{{ $provinceOption->id }}" @selected($province == $provinceOption->id)>{{ $provinceOption->name }}</option>
                    @endforeach
                </select>
                </div>
                @error('province')
                <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label class="col-form-label">City<span class="text-danger">*</span></label>
                <div id="city-selection-container-{{ $subdistrict->id }}">
                <select
                    id="city-selection-{{ $subdistrict->id }}"
                    class="form-control @error('subdistrict.city_id') is-invalid @enderror"
                    placeholder="Select City" style="width:100%" required>
                    <option value=""></option>
                    @foreach ($cityList as $cityOption)
                    <option value="{{ $cityOption->id }}" @selected($subdistrict->city_id == $cityOption->id)>{{ $cityOption->name }}</option>
                    @endforeach
                </select>
                </div>
                @error('subdistrict.city_id')
                <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label class="col-form-label">Name<span class="text-danger">*</span></label>
                <input type="text"
                    class="form-control @error('subdistrict.name') is-invalid @enderror"
                    wire:model.lazy="subdistrict.name"
                    placeholder="Name" required>
                @error('subdistrict.name')
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
