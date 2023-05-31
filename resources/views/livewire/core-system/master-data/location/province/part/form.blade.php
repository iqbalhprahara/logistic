<div class="mb-3 position-relative">
    <label class="col-form-label">Name<span class="text-danger">*</span></label>
    <input type="text"
        class="form-control @error('province.name') is-invalid @enderror"
        wire:model.lazy="province.name"
        placeholder="Name" required>
    @error('province.name')
    <div class="invalid-tooltip">{{ $message }}</div>
    @enderror
</div>
