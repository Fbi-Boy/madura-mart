
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Madura Mart') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 text-gray-800">

    <div class="min-h-screen flex">

        {{-- SIDEBAR --}}
        @include('layouts.navigation')

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- TOPBAR --}}
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6">

                <div>
                    <h1 class="text-lg font-semibold text-gray-800">
                        Madura Mart
                    </h1>
                </div>

                <div class="flex items-center gap-4">

                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-700">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-gray-500">
                            {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                        </p>
                    </div>

                </div>

            </header>

            {{-- PAGE HEADER --}}
            @isset($header)
                <div class="bg-white border-b border-gray-200 px-6 py-4">
                    {{ $header }}
                </div>
            @endisset

            {{-- PAGE CONTENT --}}
            <main class="flex-1">
                {{ $slot }}
            </main>

        </div>

    </div>

</body>

</html>

