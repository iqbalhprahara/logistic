<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />
        <title>{{ $metaTitle }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
        @stack('before-styles')
        <!-- Bootstrap Css -->
        <link href="{{ asset('vendor/skote/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('vendor/skote/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- Toastr.js -->
        <link href="{{ asset('vendor/skote/libs/toastr/toastr.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css"/>
        <!-- App Css-->
        <link href="{{ mix('vendor/skote/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
        @livewireStyles
        {{ $styles ?? null }}
        @stack('after-styles')
    </head>

    <body class="{{ $bodyClass }}">
        {{-- Content Here --}}
        {{ $slot }}

        <!-- JAVASCRIPT -->
        <script src="{{ asset('vendor/skote/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{ asset('vendor/skote/libs/bootstrap/bootstrap.min.js')}}"></script>
        <script src="{{ asset('vendor/skote/libs/metismenu/metismenu.min.js')}}"></script>
        <script src="{{ asset('vendor/skote/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{ asset('vendor/skote/libs/node-waves/node-waves.min.js')}}"></script>
        <script src="{{ asset('vendor/skote/libs/toastr/toastr.min.js')}}"></script>
        @stack('before-scripts')
        <!-- App js -->
        <script src="{{ mix('vendor/skote/js/app.js')}}"></script>
        @livewireScripts

        <script>
            $(document).ready(function () {
                Livewire.on('close-modal', modalId => $(modalId).modal('hide'));
                Livewire.on('message', message => toastr.success(message));
                Livewire.on('error', message => toastr.error(message));
                Livewire.on('info', message => toastr.info(message));
                Livewire.on('warning', message => toastr.warning(message));

                Livewire.onError((statusCode, response) => {
                    if (statusCode !== 419) {
                        toastr.error(statusCode+' - '+response.statusText)
                        console.log(response.statusText);
                    }
                });
                Livewire.onPageExpired((response, message) => {
                    toastr.warning('Page has been expired and will be reloaded after this popup closed', null, {
                        onHidden: function () {
                            window.location.reload();
                        }
                    })
                });

                if (@js(Session::has('message'))) {
                    toastr.success(`{!! session()->pull('message') !!}`);
                }

                if (@js(Session::has('error'))) {
                    toastr.error(`{!! session()->pull('error') !!}`);
                }

                if (@js(Session::has('info'))) {
                    toastr.info(`{!! session()->pull('info') !!}`);
                }

                if (@js(Session::has('warning'))) {
                    toastr.warning(`{!! session()->pull('warning') !!}`);
                }

                toastr.options = {
                    "closeButton" : true,
                    "progressBar" : true
                }
            });
        </script>
        @stack('after-scripts')
    </body>
</html>
