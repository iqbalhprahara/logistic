<div class="mb-3 position-relative">
    <label class="col-form-label">Province<span class="text-danger">*</span></label>
    <select
        class="form-control @error('provinceId') is-invalid @enderror"
        placeholder="Select Province" wire:model.lazy="provinceId" style="width:100%" required>
        @if ($provinceOptions)
        <option value="">Select Province</option>
        @foreach($provinceOptions as $value => $text)
        <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
        @else
            <option value="" disabled selected>Loading data ...</option>
        @endif
    </select>
    @error('provinceId')
    <div class="invalid-tooltip">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 position-relative">
    <label class="col-form-label">City<span class="text-danger">*</span></label>
    <select
        class="form-control @error('subdistrict.city_id') is-invalid @enderror"
        placeholder="Select City" wire:model.lazy="subdistrict.city_id" style="width:100%" required>
        @if ($cityOptions)
        <option value="">Select City</option>
        @foreach($cityOptions as $value => $text)
        <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
        @else
            <option value="" disabled selected>Loading data ...</option>
        @endif
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
