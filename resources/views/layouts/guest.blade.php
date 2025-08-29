<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Business Manager') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
              <!-- Favicons -->
<link rel="icon" href="/favicon_io/favicon.ico" type="image/x-icon">
<link rel="apple-touch-icon" sizes="180x180" href="/favicon_io/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="192x192" href="/favicon_io/android-chrome-192x192.png">
<link rel="icon" type="image/png" sizes="512x512" href="/favicon_io/android-chrome-512x512.png">

<!-- Manifest -->
<link rel="manifest" href="/favicon_io/site.webmanifest">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
        <script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js')
            .then(reg => console.log('SW registered', reg))
            .catch(err => console.error('SW registration failed', err));
    });
}

</script>
@if(session('api_token'))
<script>
    localStorage.setItem('api_token', @json(session('api_token')));
</script>
@endif

    </body>
</html>
