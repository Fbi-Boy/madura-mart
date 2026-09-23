<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'WAR-MART') }}</title>

    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen
             bg-[#FCFCFB] dark:bg-[#111113]
             text-[#171719] dark:text-white
             transition-colors duration-200">

    <div class="min-h-screen flex gap-4 p-4 sm:gap-5 sm:p-5 lg:gap-6 lg:p-6">

        {{-- SIDEBAR --}}
        @include('layouts.navigation')

        {{-- MAIN CONTENT CARD --}}
        <div class="flex-1 min-w-0 min-h-[calc(100vh-2rem)] sm:min-h-[calc(100vh-2.5rem)] lg:min-h-[calc(100vh-3rem)]
                    flex flex-col overflow-hidden rounded-[20px]
                    bg-[#FCFCFB] dark:bg-[#111113]
                    shadow-[0_10px_35px_rgba(0,0,0,0.06)]
                    dark:shadow-[0_10px_35px_rgba(0,0,0,0.16)]
                    transition-colors duration-200">

            {{-- TOPBAR --}}
            <header class="h-16 shrink-0 flex items-center justify-between px-5 sm:px-6
                           bg-[#FCFCFB] dark:bg-[#111113]
                           border-b border-black/5 dark:border-white/5">

                <div>

                    <h1 class="text-lg font-semibold text-[#171719] dark:text-white">
                        WAR-MART
                    </h1>

                    <p class="hidden sm:block text-[11px] text-black/40 dark:text-white/40 mt-0.5">
                        Management System
                    </p>

                </div>

                <div class="flex items-center gap-3 sm:gap-4">

                    <button id="themeToggle"
                            type="button"
                            class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl
                                   border border-black/10 dark:border-white/10
                                   bg-white dark:bg-white/5
                                   text-black dark:text-white
                                   hover:bg-black/5 dark:hover:bg-white/10 transition"
                            aria-label="Gunakan Dark Mode">
                        ☾
                    </button>

                    <div class="text-right">

                        <p class="text-sm font-semibold text-black/75 dark:text-white/85">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-[11px] text-black/40 dark:text-white/40">
                            {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                        </p>

                    </div>

                </div>

            </header>

            @isset($header)

                <div class="shrink-0 px-5 sm:px-6 py-4
                            bg-[#FCFCFB] dark:bg-[#111113]
                            border-b border-black/5 dark:border-white/5">

                    {{ $header }}

                </div>

            @endisset

            <main class="flex-1 min-h-0 overflow-auto
                         bg-[#FCFCFB] dark:bg-[#111113]">

                {{ $slot }}

            </main>

        </div>

    </div>

</body>
</html>