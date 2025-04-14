<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('storage/' . $settings->default_logo ) }}" type="image/svg+xml">
    <title>{{ $settings->system_name ?? 'InternSync' }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        .livewire-modal {
            z-index: 9999 !important;
        }
        body {
            font-family: 'Poppins', sans-serif;

            
        }
    </style>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

    <!-- Styles -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- Chart.js and Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>

</head>

<body class="antialiased text-antialiased theme-{{ $settings->default_theme ?? 'blue' }}">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')
        @if (session()->has('flash_message'))
            @php
                $flashMessage = session('flash_message');
            @endphp
            <x-flash-message :message="$flashMessage['message']" :type="$flashMessage['type']" :timeout="$flashMessage['timeout']" />
        @endif

        <!-- Content -->


        <!-- Livewire Scripts -->
        
        @livewire('wire-elements-modal')
        
        @livewireScripts
        <script src="https://unpkg.com/@wotz/livewire-sortablejs@1.0.0/dist/livewire-sortable.js"></script>
        <script>
            Livewire.on('reloadPage', () => {
                location.reload();
            });
        </script>
        @yield('scripts')
        
        
        
    </div>
</body>

</html>
