<form class="form-horizontal" wire:submit.prevent="update">
    <script>
        document.addEventListener('livewire:load', function () {
            $('#modal-edit-admin-{{ $admin->uuid }}').on('shown.bs.modal' , function () {
                initSelectRoleEditAdmin('{{ $admin->uuid }}');
            });

            @this.on('initSelectRoleEditAdmin', (id) => {
                initSelectRoleEditAdmin(id)
            });

            $(document).on('change.select2', '#role-selection-{{$admin->uuid}}', function (e) {
                @this.set('role', e.target.value);
            })
        });
    </script>
    <x-core-system.modal id="modal-edit-admin-{{ $admin->uuid }}" wire:ignore.self>
        <x-slot name="title">Edit Role</x-slot>
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
                <div id="role-selection-container-{{ $admin->uuid }}">
                <select
                id="role-selection-{{$admin->uuid}}"
                class="form-control @error('role') is-invalid @enderror"
                placeholder="Select Role" style="width:100%" required>
                    <option value=""></option>
                    @foreach ($roleList as $value => $text)
                    <option value="{{ $value }}" @selected($role === $value)>{{ $text }}</option>
                    @endforeach
                </select>
                </div>
                @error('role')
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
