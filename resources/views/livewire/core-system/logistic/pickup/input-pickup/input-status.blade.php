<form class="form-horizontal" wire:submit.prevent="store">
    <x-core-system.modal id="modal-input-status-awb-{{ $uuid }}" wire:ignore.self>
        <x-slot name="title">Input Status #{{ optional($awb)->awb_no }}</x-slot>
        <x-slot name="body">
            <div class="mb-3 position-relative">
                <label class="col-form-label">Current Status</label>
                <input type="text"
                    class="form-control"
                    value="{{ $currentStatus }}"
                    disabled>
            </div>
            <div class="mb-3 position-relative">
                <label class="col-form-label">Status<span class="text-danger">*</span></label>
                <select
                    class="form-control @error('awbStatusHistory.awb_status_id') is-invalid @enderror"
                    wire:model.lazy="awbStatusHistory.awb_status_id"
                    required @if (!$statusOptions) disabled @endif>
                    @if ($statusOptions)
                    <option value="">-- Pilih Status --</option>
                    @foreach($statusOptions as $value => $text)
                    <option value="{{ $value }}">{{ $text }}</option>
                    @endforeach
                    @else
                        <option value="">Loading data ...</option>
                    @endif
                </select>

                @error('awbStatusHistory.awb_status_id')
                <div class="invalid-tooltip">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3 position-relative">
                <label class="col-form-label">Catatan</label>
                <textarea type="text"
                    class="form-control @error('awbStatusHistory.note') is-invalid @enderror"
                    wire:model.lazy="awbStatusHistory.note"
                    placeholder="Catatan"></textarea>
                @error('awbStatusHistory.note')
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
