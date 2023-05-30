<div>
    <script>
        document.addEventListener('livewire:load', function () {
            window.initSelectRole = () => {
                $('#role-selection').select2({
                    placeholder: 'Select Role',
                    allowClear: false,
                    dropdownParent: $("#modal-create-admin"),
                });
            }

            initSelectRole();

            @this.on('initSelectRole', initSelectRole);

            $(document).on('change.select2', '#role-selection', function (e) {
                @this.set('role', e.target.value);
            })
        });
    </script>
    <x-core-system.button type="button" data-bs-toggle="modal" data-bs-target="#modal-create-admin">
        &plus; Add New
    </x-core-system.button>

    <form class="form-horizontal" wire:submit.prevent="store">
        <x-core-system.modal id="modal-create-admin" wire:ignore.self>
            <x-slot name="title">Add New Admin</x-slot>
            <x-slot name="body">
                <div class="mb-3 position-relative">
                    <label class="col-form-label">Name<span class="text-danger">*</span></label>
                    <input type="text"
                        class="form-control @error('admin.name') is-invalid @enderror"
                        wire:model.lazy="admin.name"
                        placeholder="Name" required>
                    @error('admin.name')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <label class="col-form-label">Email<span class="text-danger">*</span></label>
                    <input type="email"
                        class="form-control @error('admin.email') is-invalid @enderror"
                        wire:model.lazy="admin.email"
                        placeholder="Email" required>
                    @error('admin.email')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <label class="col-form-label">Role<span class="text-danger">*</span></label>
                    <select
                        id="role-selection"
                        class="form-control @error('role') is-invalid @enderror"
                        placeholder="Select Role" style="width:100%" required>
                        <option value=""></option>
                        @foreach ($roleList as $value => $text)
                        <option value="{{ $value }}" @selected($role === $value)>{{ $text }}</option>
                        @endforeach
                    </select>
                    @error('role')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <label class="col-form-label">Create Password</label>
                    <input type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        wire:model.lazy="password"
                        placeholder="Enter New Password" required>
                    @error('password')
                    <div class="invalid-tooltip">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <label class="col-form-label">Confirm Password</label>
                    <input type="password"
                        class="form-control @error('passwordConfirmation') is-invalid @enderror"
                        wire:model.lazy="passwordConfirmation"
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
