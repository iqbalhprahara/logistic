<x-core-system.modal id="modal-status-history-awb-{{ $uuid }}" wire:ignore.self>
    <x-slot name="title">Status #{{ $awbNo }}</x-slot>
    <x-slot name="body">
        <ul class="verti-timeline list-unstyled">
            @if ($awbStatusHistories)
                @foreach ($awbStatusHistories as $index => $status)
                <li class="event-list @if($index === 0) active @endif">
                    <div class="event-timeline-dot">
                        <i class="bx bx-right-arrow-circle @if($index === 0) bx-fade-right @endif"></i>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <h5 class="font-size-14">{{ $status->created_at->format('d F Y H:i:s') }} <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                        </div>
                        <div class="flex-grow-1">
                            <div>
                                <h5>{{ $status->awbStatus->name }}</h5>
                                @if (!empty($status->note))
                                <p class="text-muted">{{ $status->note }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            @else
            <li class="event-list active">
                <div class="flex-shrink-0 me-3">
                <div class="event-timeline-dot">
                    <i class="bx bx-right-arrow-circle bx-fade-right"></i>
                </div>
                </div>
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <div>
                            <h5>Loading data...</h5>
                        </div>
                    </div>
                </div>
            </li>
            @endif
        </ul>
    </x-slot>
    <x-slot name="footer">
        <x-core-system.button type="button" color="secondary" data-bs-dismiss="modal">Close</x-core-system.button>
    </x-slot>
</x-core-system.modal>
