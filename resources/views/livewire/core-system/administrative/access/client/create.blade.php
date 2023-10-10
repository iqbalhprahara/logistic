<div>
    <script>
        document.addEventListener('livewire:init', function () {
            window.initSelectCompany = () => {
                $('#company-selection').select2({
                    placeholder: 'Select Company',
                    allowClear: false,
                    dropdownParent: $("#modal-create-user"),
                });
            }

            initSelectCompany();

            @this.on('initSelectCompany', initSelectCompany);

            $(document).on('change.select2', '#company-selection', function (e) {
                @this.set('company', e.target.value);
            })
        });
    </script>
    <x-core-system.button type="button" data-bs-toggle="modal" data-bs-target="#modal-create-user">
        &plus; Add New
    </x-core-system.button>

    <form class="form-horizontal" wire:submit="store">
        <x-core-system.modal id="modal-create-user" wire:ignore.self>
            <x-slot name="title">Add New User</x-slot>
            <x-slot name="body">
                <div class="mb-3 position-relative">
                    <label class="col-form-label">Name<span class="text-danger">*</span></label>
                    <input type="text"
                        class="form-control @error('user.name') is-invalid @enderror"
                        wire:model.blur="user.name"
                        placeholder="Name" required>
                    @error('user.name')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <label class="col-form-label">Email<span class="text-danger">*</span></label>
                    <input type="email"
                        class="form-control @error('user.email') is-invalid @enderror"
                        wire:model.blur="user.email"
                        placeholder="Email" required>
                    @error('user.email')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <label class="col-form-label">Company<span class="text-danger">*</span></label>
                    <select
                        id="company-selection"
                        class="form-control @error('company') is-invalid @enderror"
                        placeholder="Select Company" style="width:100%" required>
                        <option value=""></option>
                        @foreach ($companyList as $value => $text)
                        <option value="{{ $value }}" @selected($company === $value)>{{ $text }}</option>
                        @endforeach
                    </select>
                    @error('company')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <label class="col-form-label">Create Password</label>
                    <input type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        wire:model.blur="password"
                        placeholder="Enter New Password" required>
                    @error('password')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <label class="col-form-label">Confirm Password</label>
                    <input type="password"
                        class="form-control @error('passwordConfirmation') is-invalid @enderror"
                        wire:model.blur="passwordConfirmation"
                        placeholder="Confirm Password" required>
                    @error('passwordConfirmation')
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
