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

<body class="h-screen overflow-hidden bg-[#FCFCFB] dark:bg-[#111113] text-[#171719] dark:text-white transition-colors duration-200">

    <div class="h-screen flex gap-2 p-4 sm:gap-2.5 sm:p-5 lg:gap-3 lg:p-6 overflow-hidden">

        @include('layouts.navigation')

        <div class="flex-1 min-w-0 h-[calc(100vh-2rem)] flex flex-col overflow-hidden">

            <header class="h-[72px] shrink-0 flex items-start justify-between px-2 sm:px-3 pt-1">

                <h1 class="text-xl sm:text-[24px] font-semibold tracking-[-0.03em] leading-tight text-[#171719] dark:text-white"
                    style="font-family: 'Poppins', sans-serif;">
                    Selamat datang, {{ auth()->user()->name }} 👋
                </h1>

                <div class="flex items-center gap-2.5 sm:gap-3">

                    {{-- SETTINGS --}}
                    <button type="button"
                            class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl
                                   border border-black/10 dark:border-white/10
                                   bg-white/70 dark:bg-white/5
                                   text-black dark:text-white
                                   hover:bg-black/5 dark:hover:bg-white/10 transition"
                            aria-label="Pengaturan">
                        <svg class="w-[19px] h-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"></path>
                            <path d="M19.4 15a1.7 1.7 0 0 0 .34 1.88l.06.06-1.75 1.75-.06-.06a1.7 1.7 0 0 0-1.88-.34 1.7 1.7 0 0 0-1.03 1.56V20h-2.47v-.15a1.7 1.7 0 0 0-1.03-1.56 1.7 1.7 0 0 0-1.88.34l-.06.06-1.75-1.75.06-.06A1.7 1.7 0 0 0 8.28 15a1.7 1.7 0 0 0-1.56-1.03H6.5v-2.47h.22A1.7 1.7 0 0 0 8.28 10a1.7 1.7 0 0 0-.34-1.88l-.06-.06 1.75-1.75.06.06a1.7 1.7 0 0 0 1.88.34 1.7 1.7 0 0 0 1.03-1.56V5h2.47v.15a1.7 1.7 0 0 0 1.03 1.56 1.7 1.7 0 0 0 1.88-.34l.06-.06 1.75 1.75-.06.06A1.7 1.7 0 0 0 19.4 10a1.7 1.7 0 0 0 1.56 1.03h.15v2.47h-.15A1.7 1.7 0 0 0 19.4 15Z"></path>
                        </svg>
                    </button>

                    {{-- THEME --}}
                    <button id="themeToggle"
                            type="button"
                            class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl
                                   border border-black/10 dark:border-white/10
                                   bg-white/70 dark:bg-white/5
                                   text-black dark:text-white
                                   hover:bg-black/5 dark:hover:bg-white/10 transition"
                            aria-label="Gunakan Dark Mode">
                        <svg class="w-[19px] h-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.5 15.2A8.5 8.5 0 0 1 8.8 3.5 8.5 8.5 0 1 0 20.5 15.2Z"></path>
                        </svg>
                    </button>

                    {{-- ACCOUNT --}}
                    <div class="hidden sm:flex items-center gap-2.5 pl-1.5">

                        <div class="text-right">
                            <p class="text-sm font-normal text-black/75 dark:text-white/85">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-[11px] text-black/40 dark:text-white/40">
                                {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                            </p>
                        </div>

                        <div class="w-9 h-9 rounded-full bg-[#A8F23A] text-[#171719]
                                    flex items-center justify-center text-[13px] font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                    </div>

                </div>

            </header>

            <main class="flex-1 min-h-0 overflow-hidden px-2 sm:px-3 pt-0">
                {{ $slot }}
            </main>

        </div>

    </div>

</body>
</html>