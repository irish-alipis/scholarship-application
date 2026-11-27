<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ScholarPath</title>
        <link rel="shortcut icon" href="{{ asset('backend/assets/images/brand-logos/toggle-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"
             style="min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; background:#f3f4f6; padding:24px;">

            <div>
                <a href="/">
                    <img src="{{ asset('backend/assets/images/brand-logos/desktop-logo.png') }}"
                         alt="ScholarPath" style="height:48px; width:auto; display:block;">
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
                 style="width:100%; max-width:448px; margin-top:24px; padding:16px 24px; background:#fff; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.1);">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
