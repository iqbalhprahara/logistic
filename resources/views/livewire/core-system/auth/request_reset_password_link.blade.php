<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-primary bg-soft">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary"> Reset Password</h5>
                                    <p>Send reset password email</p>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{ asset('/vendor/skote/images/profile-img.png') }}" alt=""
                                    class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div>
                            <a href="javascript:void(0)">
                                <div class="avatar-md profile-user-wid mb-4">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="{{ asset('/vendor/skote/images/logo.svg') }}" alt=""
                                            class="rounded-circle" height="34">
                                    </span>
                                </div>
                            </a>
                        </div>

                        <div class="p-2">
                            <form class="form-horizontal" wire:submit.prevent="sendResetLinkEmail">
                                <div class="mb-3">
                                    <label for="useremail" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Enter email"
                                        wire:model.lazy="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <button class="btn btn-primary w-md waves-effect waves-light"
                                        type="submit">Reset</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
                <div class="mt-5 text-center">
                    <p>Remember It ? <a href="{{ route('login') }}" class="fw-medium text-primary"> Sign In here</a>
                    </p>
                    <p>© {{ date('Y') }} {{ config('app.name') }}</p>
                </div>

            </div>
        </div>
    </div>
</div>
