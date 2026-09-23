<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Madura Mart') }}</title>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#5B3FA6] text-gray-800 dark:bg-[#30205C] dark:text-gray-100 transition-colors duration-200">

    <div class="min-h-screen flex">

        {{-- SIDEBAR --}}
        @include('layouts.navigation')

        {{-- MAIN WORKSPACE --}}
        <div class="flex-1 min-w-0 bg-[#5B3FA6] dark:bg-[#30205C] p-4 sm:p-5 lg:p-6">

            {{-- PAGE CONTAINER --}}
            <div class="min-h-[calc(100vh-2rem)] sm:min-h-[calc(100vh-2.5rem)] lg:min-h-[calc(100vh-3rem)]
                        flex flex-col overflow-hidden rounded-[24px]
                        bg-white dark:bg-gray-900 shadow-[0_10px_35px_rgba(30,20,70,0.12)]
                        transition-colors duration-200">

                {{-- TOPBAR --}}
                <header class="h-16 shrink-0 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800
                               flex items-center justify-between px-5 sm:px-6 transition-colors duration-200">

                    <div>
                        <h1 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Madura Mart
                        </h1>
                        <p class="hidden sm:block text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">
                            Management System
                        </p>
                    </div>

                    <div class="flex items-center gap-3 sm:gap-4">
                        <button id="themeToggle" type="button"
                                class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl
                                       border border-gray-200 bg-white text-gray-600 hover:bg-gray-100
                                       dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition"
                                aria-label="Gunakan Dark Mode">
                            ☾
                        </button>

                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-[11px] text-gray-400 dark:text-gray-500">
                                {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                            </p>
                        </div>
                    </div>
                </header>

                @isset($header)
                    <div class="shrink-0 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 px-5 sm:px-6 py-4">
                        {{ $header }}
                    </div>
                @endisset

                <main class="flex-1 min-h-0 bg-white dark:bg-gray-900 overflow-auto">
                    {{ $slot }}
                </main>

            </div>
        </div>
    </div>
</body>
</html>