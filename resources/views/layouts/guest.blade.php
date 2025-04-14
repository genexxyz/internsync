<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if($settings->default_logo)
    <link rel="icon" href="{{
    asset('storage/' . $settings->default_logo)}}" type="image/svg+xml">
    @endif
    <title>{{ $settings->system_name ?? 'InternSync' }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;

            background-image: url('/images/default_bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;

        }
    </style>
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles

   

</head>

<body class=" text-gray-900 antialiased theme-{{ $settings->default_theme ?? 'blue' }} ">
    @if(session()->has('alert'))
        <div id="alert-data" 
             data-alert="{{ json_encode(session('alert')) }}" 
             style="display: none;">
        </div>
    @endif
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="flex flex-row items-center justify-between w-auto mt-3">
            <div>
                <a href="/">
                    @if($settings->default_logo)
                    <img src="{{ asset('storage/' . $settings->default_logo) }}" alt="Logo" class="w-24 h-24">
                    @endif
                </a>

            </div>
            <div class="flex flex-col ml-5">
                <div><x-header class="text-5xl ">{{ $settings->system_name ?? 'InternSync' }}</x-header></div>
                <div>
                    <p class="text-white">{{ $settings->school_name ?? 'InternSync' }}</p>
                </div>
                </div>
                
                
        </div>
        <div class="w-full mx-6 p-6 overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const alert = document.getElementById('alert-data');
    if (alert) {
        const data = JSON.parse(alert.dataset.alert);
        Toast.fire({
            icon: data.type,
            title: data.message
        });
    }
});
    </script>
    @yield('scripts')
</body>


</html>
