<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- Scripts -->
    @routes
    <script src="{{ mix('js/app.js') }}" defer></script>
    <head>
<body class="antialiased">
<div class="min-h-screen 900 bg-center bg-cover bg-no-repeat sm:items-center py-4 sm:pt-0"
     style="background-image: url('/storage/images/beer_bg.jpg');">
    @if (Route::has('login'))
        <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            @auth
                <x-reversed-link-button href="{{ url('/dashboard') }}">Dashboard</x-reversed-link-button>
            @else
                <x-reversed-link-button href="{{ route('login') }}">Log in</x-reversed-link-button>

                @if (Route::has('register'))
                    <x-reversed-link-button href="{{ route('register') }}">Register</x-reversed-link-button>
                @endif
            @endauth
        </div>
    @endif
</div>

@env ('local')
    <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
@endenv
</body>
</html>
