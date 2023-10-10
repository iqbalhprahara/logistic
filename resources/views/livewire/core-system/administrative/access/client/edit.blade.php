<form class="form-horizontal" wire:submit="update">
    <script>
        document.addEventListener('livewire:init', function () {
            $('#modal-edit-user-{{ $user->uuid }}').on('shown.bs.modal' , function () {
                initSelectCompanyEditUser('{{ $user->uuid }}');
            });

            @this.on('initSelectCompanyEditUser', (id) => {
                initSelectCompanyEditUser(id)
            });

            $(document).on('change.select2', '#company-selection-{{$user->uuid}}', function (e) {
                @this.set('company', e.target.value);
            })
        });
    </script>
    <x-core-system.modal id="modal-edit-user-{{ $user->uuid }}" wire:ignore.self>
        <x-slot name="title">Edit User</x-slot>
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
                <div id="company-selection-container-{{ $user->uuid }}">
                <select
                id="company-selection-{{$user->uuid}}"
                class="form-control @error('company') is-invalid @enderror"
                placeholder="Select Company" style="width:100%" required>
                    <option value=""></option>
                    @foreach ($companyList as $value => $text)
                    <option value="{{ $value }}" @selected($company === $value)>{{ $text }}</option>
                    @endforeach
                </select>
                </div>
                @error('company')
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
