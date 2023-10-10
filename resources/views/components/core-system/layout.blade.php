<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="url-home" content="{{ route('app.home') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>{{ $metaTitle }}</title>

    @stack('before-styles')
    <!-- Bootstrap Css -->
    @vite('resources/vendor/skote/css/bootstrap.min.css')
    <!-- Icons Css -->
    @vite('resources/vendor/skote/css/icons.min.css')
    <!-- Toastr.js -->
    @vite('resources/vendor/skote/libs/toastr/toastr.min.css')
    <!-- App Css-->
    @vite('resources/vendor/skote/css/app.min.css')
    @livewireStyles()
    @stack('vendor-styles')
    {{ $styles ?? null }}
    @stack('after-styles')
</head>

<body data-sidebar="dark">
    {{-- <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner-chase">
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
            </div>
        </div>
    </div> --}}
    <!-- Begin page -->
    <div id="layout-wrapper">
        @livewire('core-system.topbar')
        <x-core-system.sidebar />

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    @if(isset($title))
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">{{ $title }}</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- end page title -->
                    {{-- Content Here --}}
                    {{ $slot }}
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <x-core-system.footer />
        </div>
        @stack('modal')
    </div>
    {{-- Vendor Scripts --}}
    @vite([
        'resources/vendor/skote/libs/jquery/jquery.min.js',
        'resources/vendor/skote/libs/bootstrap/bootstrap.min.js',
        'resources/vendor/skote/libs/metismenu/metismenu.min.js',
        'resources/vendor/skote/libs/simplebar/simplebar.min.js',
        'resources/vendor/skote/libs/node-waves/node-waves.min.js',
        'resources/vendor/skote/libs/toastr/toastr.min.js'
    ])
    @livewireScriptConfig
    @stack('vendor-scripts')
    <script>
        $(document).ready(function() {
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

            if (location.hash) {
                $('a[href="' + location.hash + '"]').tab('show');
                $(location.hash).parents('.tab-pane:not(.active)').each(function() {
                    //each pane should have an id - find it's anchor target and call show
                    $('a[href="#' + this.id + '"]').tab('show');
                });
            }

            toastr.options = {
                "closeButton" : true,
                "progressBar" : true
            }
        });
    </script>

    {{-- Application Script --}}
    @stack('before-scripts')
    @stack('after-scripts')
    @vite('resources/vendor/skote/js/app.js')
</body>
</html>
