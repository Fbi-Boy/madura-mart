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

<body class="h-screen overflow-hidden
             bg-[#FCFCFB] dark:bg-[#111113]
             text-[#171719] dark:text-white
             transition-colors duration-200">

    <div class="h-screen flex gap-4 p-4 sm:gap-5 sm:p-5 lg:gap-6 lg:p-6 overflow-hidden">

        {{-- SIDEBAR --}}
        @include('layouts.navigation')

        {{-- MAIN CONTENT --}}
        <div class="flex-1 min-w-0 h-[calc(100vh-2rem)]
                    flex flex-col overflow-hidden">

            {{-- HEADER --}}
            <header class="h-[72px] shrink-0 flex items-start justify-between px-5 sm:px-7 pt-1">

                <h1 class="text-xl sm:text-[24px] font-semibold tracking-[-0.03em]
                           leading-tight text-[#171719] dark:text-white"
                    style="font-family: 'Poppins', sans-serif;">
                    Selamat datang, {{ auth()->user()->name }} 👋
                </h1>

                <div class="flex items-center gap-3 sm:gap-4">

                    <button id="themeToggle"
                            type="button"
                            class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl
                                   border border-black/10 dark:border-white/10
                                   bg-white/70 dark:bg-white/5
                                   text-black dark:text-white
                                   hover:bg-black/5 dark:hover:bg-white/10 transition"
                            aria-label="Gunakan Dark Mode">
                        ☾
                    </button>

                    <div class="hidden sm:block text-right">
                        <p class="text-sm font-normal text-black/75 dark:text-white/85">
                            {{ auth()->user()->name }}
                        </p>

                        <p class="text-[11px] text-black/40 dark:text-white/40">
                            {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                        </p>
                    </div>

                </div>

            </header>

            {{-- PAGE CONTENT --}}
            <main class="flex-1 min-h-0 overflow-hidden px-5 sm:px-7 pt-0">
                {{ $slot }}
            </main>

        </div>

    </div>

</body>
</html>