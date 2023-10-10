<div class="mb-3 position-relative">
    <label class="col-form-label">Province<span class="text-danger">*</span></label>
    <select
        id="province-selection"
        class="form-control @error('city.province_id') is-invalid @enderror"
        placeholder="Select Province" wire:model.blur="city.province_id" style="width:100%" required>
        @if ($provinceOptions)
        <option value="">Select Province</option>
        @foreach($provinceOptions as $value => $text)
        <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
        @else
            <option value="" disabled selected>Loading data ...</option>
        @endif
    </select>
    @error('city.province_id')
    <div class="invalid-tooltip">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 position-relative">
    <label class="col-form-label">City Type<span class="text-danger">*</span></label>
    <select
        id="province-selection"
        class="form-control @error('city.type') is-invalid @enderror"
        placeholders="Select City Type" wire:model.blur="city.type" style="width:100%" required>
        @if ($cityTypeOptions)
        <option value="">Select City Type</option>
        @foreach($cityTypeOptions as $value => $text)
        <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
        @else
            <option value="" disabled selected>Loading data ...</option>
        @endif
    </select>
    @error('city.type')
    <div class="invalid-tooltip">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 position-relative">
    <label class="col-form-label">Three Letter Code (TLC)<span class="text-danger">*</span></label>
    <input type="text"
        class="form-control @error('city.code') is-invalid @enderror"
        wire:model.blur="city.code"
        placeholder="Code" minlength="3" maxlength="3" required>
    @error('city.code')
    <div class="invalid-tooltip">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 position-relative">
    <label class="col-form-label">Name<span class="text-danger">*</span></label>
    <input type="text"
        class="form-control @error('city.name') is-invalid @enderror"
        wire:model.blur="city.name"
        placeholder="Name" required>
    @error('city.name')
    <div class="invalid-tooltip">{{ $message }}</div>
    @enderror
</div>
