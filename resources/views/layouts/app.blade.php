
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Madura Mart') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>

<body class="bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-100 transition-colors duration-200">

    <div class="min-h-screen flex">

        {{-- SIDEBAR --}}
        @include('layouts.navigation')

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- TOPBAR --}}
            <header class="h-16 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between px-6 transition-colors duration-200">

                <div>
                    <h1 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                        Madura Mart
                    </h1>
                </div>

                <div class="flex items-center gap-4">

                    <button
                        id="themeToggle"
                        type="button"
                        class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 hover:bg-gray-100 transition dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                        aria-label="Gunakan Dark Mode">
                        ☾
                    </button>

                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                        </p>
                    </div>

                </div>

            </header>

            {{-- PAGE HEADER --}}
            @isset($header)
                <div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 px-6 py-4">
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

