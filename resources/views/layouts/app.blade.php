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
                        <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19 15.5a2 2 0 0 0 .4 2.2l-1.7 1.7a2 2 0 0 0-2.2-.4 2 2 0 0 0-1.2 1.8h-2.4a2 2 0 0 0-1.2-1.8 2 2 0 0 0-2.2.4l-1.7-1.7a2 2 0 0 0 .4-2.2 2 2 0 0 0 .4-2.2A2 2 0 0 0 5 12a2 2 0 0 0-1.8-1.2V8.5a2 2 0 0 0 1.8-1.2 2 2 0 0 0-.4-2.2l1.7-1.7a2 2 0 0 0 2.2.4A2 2 0 0 0 9.5 2h2.4a2 2 0 0 0 1.2 1.8 2 2 0 0 0 2.2-.4L17 5.1a2 2 0 0 0-.4 2.2A2 2 0 0 0 18.5 8.5V12a2 2 0 0 0 1.8 1.2v2.3a2 2 0 0 0-1.3 0z"></path>
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
                        <svg class="w-[18px] h-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="12" cy="12" r="4"></circle>
                            <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"></path>
                        </svg>
                    </button>

                    {{-- ACCOUNT --}}
                    <div class="hidden sm:flex items-center gap-2.5 pl-1.5">

                        <div class="w-9 h-9 rounded-full bg-[#A8F23A] text-[#171719]
                                    flex items-center justify-center text-[13px] font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <div class="text-right">
                            <p class="text-sm font-normal text-black/75 dark:text-white/85">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-[11px] text-black/40 dark:text-white/40">
                                {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                            </p>
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