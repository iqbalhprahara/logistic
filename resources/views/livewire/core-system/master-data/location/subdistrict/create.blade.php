<div>
    <script>
        document.addEventListener('livewire:load', function () {
            window.initSelectProvince = () => {
                $('#province-selection').select2({
                    placeholder: 'Select Province',
                    allowClear: false,
                    dropdownParent: $("#modal-create-subdistrict"),
                });
            }

            window.initSelectCity = () => {
                $('#city-selection').select2({
                    placeholder: 'Select City',
                    allowClear: false,
                    dropdownParent: $("#modal-create-subdistrict"),
                });
            }

            initSelectProvince();
            initSelectCity();

            @this.on('initSelectProvince', initSelectProvince);
            @this.on('initSelectCity', initSelectCity);

            $(document).on('change.select2', '#province-selection', function (e) {
                @this.set('province', e.target.value);
            })

            $(document).on('change.select2', '#city-selection', function (e) {
                @this.set('subdistrict.city_id', e.target.value);
            })
        });
    </script>

    <x-core-system.button type="button" data-bs-toggle="modal" data-bs-target="#modal-create-subdistrict">
        &plus; Add New
    </x-core-system.button>

    <form class="form-horizontal" wire:submit.prevent="store">
        <x-core-system.modal id="modal-create-subdistrict" wire:ignore.self>
            <x-slot name="title">Add New Subdistrict</x-slot>
            <x-slot name="body">
                <div class="mb-3 position-relative">
                    <label class="col-form-label">Province</label>
                    <select
                        id="province-selection"
                        class="form-control @error('province') is-invalid @enderror"
                        placeholder="Select Province" style="width:100%">
                        <option value=""></option>
                        @foreach ($provinceList as $provinceOption)
                        <option value="{{ $provinceOption->id }}" @selected($province == $provinceOption->id)>{{ $provinceOption->name }}</option>
                        @endforeach
                    </select>
                    @error('province')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <label class="col-form-label">City<span class="text-danger">*</span></label>
                    <select
                        id="city-selection"
                        class="form-control @error('subdistrict.city_id') is-invalid @enderror"
                        placeholder="Select City" style="width:100%" required>
                        <option value=""></option>
                        @foreach ($cityList as $cityOption)
                        <option value="{{ $cityOption->id }}" @selected($subdistrict->city_id == $cityOption->id)>{{ $cityOption->name }}</option>
                        @endforeach
                    </select>
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
                <x-core-system.button>Submit</x-core-system.button>
            </x-slot>
        </x-core-system.modal>
    </form>
</div>
