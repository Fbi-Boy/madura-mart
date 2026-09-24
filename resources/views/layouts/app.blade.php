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

<body class="h-screen overflow-hidden bg-[#F8F8FC] dark:bg-[#111113] text-[#171719] dark:text-white transition-colors duration-200">

    <div class="h-screen flex gap-2 p-4 sm:gap-2.5 sm:p-5 lg:gap-3 lg:p-6 overflow-hidden">

        @include('layouts.navigation')

        <div class="flex-1 min-w-0 h-[calc(100vh-2rem)] flex flex-col overflow-hidden">

            {{-- =====================================================
                GLOBAL HEADER
            ====================================================== --}}
            <header class="h-[70px] shrink-0 flex items-center justify-between gap-4 px-1 sm:px-2">

                {{-- LEFT --}}
                <div class="min-w-0 flex items-center gap-4">

                    <div class="hidden lg:block shrink-0 leading-none">

                        <p class="text-[7px] font-semibold uppercase tracking-[0.035em] text-black/40 dark:text-white/40">
                            Selamat Datang Kembali
                        </p>

                        <p class="mt-1 text-[14px] font-bold text-[#171719] dark:text-white">
                            Admin Stores
                        </p>

                    </div>


                    {{-- SEARCH --}}
                    <div class="hidden md:flex items-center w-[250px] xl:w-[310px] h-[38px]
                                rounded-full bg-[#EFF0F7] dark:bg-white/[0.06]
                                px-3.5 gap-2">

                        <svg
                            class="w-[15px] h-[15px] shrink-0 text-black/35 dark:text-white/35"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-4-4"></path>
                        </svg>

                        <input
                            type="text"
                            placeholder="Cari menu, SKU, invoice..."
                            class="w-full bg-transparent border-0 outline-none
                                   text-[10px] text-[#171719] dark:text-white
                                   placeholder:text-black/35 dark:placeholder:text-white/35
                                   focus:ring-0"
                        >

                    </div>

                </div>


                {{-- RIGHT --}}
                <div class="flex items-center gap-2 shrink-0">

                    {{-- SETTINGS --}}
                    <button
                        type="button"
                        class="w-[38px] h-[38px] flex items-center justify-center
                               rounded-full bg-[#EFF0F7] dark:bg-white/[0.06]
                               text-black/55 dark:text-white/55
                               hover:bg-black/[0.06] dark:hover:bg-white/[0.1] transition"
                        aria-label="Pengaturan"
                    >
                        <svg
                            class="w-[16px] h-[16px]"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M4 7h10"></path>
                            <path d="M18 7h2"></path>
                            <circle cx="16" cy="7" r="2"></circle>
                            <path d="M4 12h2"></path>
                            <path d="M10 12h10"></path>
                            <circle cx="8" cy="12" r="2"></circle>
                            <path d="M4 17h10"></path>
                            <path d="M18 17h2"></path>
                            <circle cx="16" cy="17" r="2"></circle>
                        </svg>
                    </button>


                    {{-- THEME --}}
                    <button
                        id="themeToggle"
                        type="button"
                        class="w-[38px] h-[38px] flex items-center justify-center
                               rounded-full bg-[#EFF0F7] dark:bg-white/[0.06]
                               text-black/55 dark:text-white/55
                               hover:bg-black/[0.06] dark:hover:bg-white/[0.1] transition"
                        aria-label="Gunakan Dark Mode"
                    >
                        <svg
                            class="w-[16px] h-[16px]"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.9"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="12" cy="12" r="4"></circle>
                            <path d="M12 2v2"></path>
                            <path d="M12 20v2"></path>
                            <path d="m4.93 4.93 1.42 1.42"></path>
                            <path d="m17.65 17.65 1.42 1.42"></path>
                            <path d="M2 12h2"></path>
                            <path d="M20 12h2"></path>
                            <path d="m4.93 19.07-1.42-1.42"></path>
                            <path d="m17.65 6.35 1.42-1.42"></path>
                        </svg>
                    </button>


                    {{-- CABANG --}}
                    <button
                        type="button"
                        class="hidden sm:flex h-[38px] items-center gap-2 rounded-full
                               bg-[#EFF0F7] dark:bg-white/[0.06]
                               px-3.5 text-[9px] font-medium
                               text-[#171719] dark:text-white"
                    >

                        <svg
                            class="w-[13px] h-[13px] text-[#52623C]"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M3 21h18"></path>
                            <path d="M5 21V7l7-4 7 4v14"></path>
                            <path d="M9 21v-6h6v6"></path>
                            <path d="M9 9h.01"></path>
                            <path d="M15 9h.01"></path>
                        </svg>

                        Cabang Utama - Jakarta Selatan

                    </button>


                    {{-- NOTIFICATION --}}
                    <button
                        type="button"
                        class="relative w-[38px] h-[38px] flex items-center justify-center
                               rounded-full bg-[#EFF0F7] dark:bg-white/[0.06]
                               text-black/55 dark:text-white/55"
                        aria-label="Notifikasi"
                    >

                        <svg
                            class="w-[16px] h-[16px]"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>

                        <span class="absolute top-[7px] right-[7px] w-[5px] h-[5px] rounded-full bg-[#A8F23A]"></span>

                    </button>


                    {{-- USER --}}
                    <div class="flex items-center gap-2.5 pl-1">

                        <div class="hidden sm:block text-right leading-none">

                            <p class="text-[12px] font-semibold text-[#171719] dark:text-white">
                                {{ auth()->user()->name }}
                            </p>

                        </div>

                        <div
                            class="w-[36px] h-[36px] rounded-full bg-[#D9C5A7]
                                   flex items-center justify-center
                                   text-[12px] font-bold text-[#3B3025]"
                        >
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                    </div>

                </div>

            </header>


            {{-- CONTENT SAJA YANG SCROLL --}}
            <main
                class="flex-1 min-h-0 overflow-y-auto overflow-x-hidden px-1 sm:px-2 pt-1 pb-6
                       [&::-webkit-scrollbar]:w-1.5
                       [&::-webkit-scrollbar-track]:bg-transparent
                       [&::-webkit-scrollbar-thumb]:rounded-full
                       [&::-webkit-scrollbar-thumb]:bg-black/15
                       dark:[&::-webkit-scrollbar-thumb]:bg-white/15"
            >
                {{ $slot }}
            </main>

        </div>

    </div>

</body>
</html>