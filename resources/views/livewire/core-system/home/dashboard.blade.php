@include('livewire.core-system.logistic.pickup.input-pickup.part.action-js')
<div wire:poll.60000ms>
    <x-slot name="title">Dashboard</x-slot>
    <div class="row">
        <div class="col-xl-8">
            <div class="card overflow-hidden">
                <div class="bg-primary bg-soft">
                    <div class="row">
                        <div class="col-7">
                            <div class="text-primary p-3">
                                <h5 class="text-primary">Welcome Back !</h5>
                            </div>
                        </div>
                        <div class="col-5 align-self-end">
                            <img src="{{ asset('/vendor/skote/images/profile-img.png') }}" alt="" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="avatar-md profile-user-wid mb-4">
                                <img src="{{ asset('/vendor/skote/images/logo-light.svg') }}" alt="" class="img-thumbnail rounded-circle">
                            </div>
                            <h5 class="font-size-15 text-truncate">{{ Str::ucfirst(Auth::user()->name) }}</h5>
                            @if(Auth::user()->isClient())
                            <p class="text-muted mb-0 text-truncate">{{ Auth::user()->company_name }}</p>
                            @endif
                        </div>

                        <div class="col-sm-8">
                            <div class="pt-4">

                                <div class="row">
                                    <div class="col-6">
                                        <h5 class="font-size-15">{{ number_format($totalAwb, 0,  '.', ',') }}</h5>
                                        <p class="text-muted mb-0">Total AWB</p>
                                    </div>
                                    <div class="col-6">
                                        <h5 class="font-size-15">{{ number_format($currentMonthAwb, 0,  '.', ',') }}</h5>
                                        <p class="text-muted mb-0">AWB Bulan Ini</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium">On Going AWB</p>
                                    <h4 class="mb-0">{{ number_format($pendingAwb, 0,  '.', ',') }}</h4>
                                </div>

                                <div class="flex-shrink-0 align-self-center ">
                                    <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                        <span class="avatar-title rounded-circle bg-primary">
                                            <i class="bx bx-time font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium">Complete AWB</p>
                                    <h4 class="mb-0">{{ number_format($completeAwb, 0,  '.', ',') }}</h4>
                                </div>

                                <div class="flex-shrink-0 align-self-center">
                                    <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                        <span class="avatar-title rounded-circle bg-primary">
                                            <i class="bx bx-check-double font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
    <!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4 d-flex justify-content-between align-items-center">
                        Latest AWB

                        @can('logistic:pickup:input-pickup')
                        <a href="{{ route('app.logistic.pickup.input-pickup') }}" class="btn btn-sm btn-primary">
                        View All
                        </a>
                        @endcan
                    </h4>

                    @livewire('core-system.home.latest-awb-table')
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->
</div>
