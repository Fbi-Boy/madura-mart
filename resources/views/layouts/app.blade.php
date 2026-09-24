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
                GLOBAL HEADER — SAMA UNTUK SEMUA HALAMAN
            ====================================================== --}}
            <header class="h-[62px] shrink-0 flex items-center justify-between gap-5 px-1 sm:px-2">

                {{-- LEFT --}}
                <div class="min-w-0 flex items-center gap-5">

                    <div class="hidden lg:block shrink-0 leading-none">
                        <p class="text-[8px] font-semibold uppercase tracking-[0.04em] text-black/45 dark:text-white/45">
                            Selamat Datang Kembali
                        </p>

                        <p class="mt-1 text-[12px] font-bold text-[#171719] dark:text-white">
                            Store Manager
                        </p>
                    </div>

                    <div class="hidden md:flex items-center w-[300px] xl:w-[390px] h-[34px]
                                rounded-full bg-[#EFF0F7] dark:bg-white/[0.06]
                                px-3.5 gap-2">

                        <svg
                            class="w-[14px] h-[14px] shrink-0 text-black/35 dark:text-white/35"
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
                            placeholder="Cari menu, SKU produk, invoice, transaksi..."
                            class="w-full bg-transparent border-0 outline-none
                                   text-[9px] text-[#171719] dark:text-white
                                   placeholder:text-black/35 dark:placeholder:text-white/35
                                   focus:ring-0"
                        >
                    </div>

                </div>

                {{-- RIGHT --}}
                <div class="flex items-center gap-2 shrink-0">

                    <button
                        type="button"
                        class="hidden sm:flex h-[34px] items-center gap-2 rounded-full
                               bg-[#EFF0F7] dark:bg-white/[0.06]
                               px-3.5 text-[9px] font-medium
                               text-[#171719] dark:text-white"
                    >
                        <svg
                            class="w-[12px] h-[12px] text-[#52623C]"
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

                    <button
                        type="button"
                        class="relative w-[34px] h-[34px] flex items-center justify-center
                               rounded-full bg-[#EFF0F7] dark:bg-white/[0.06]
                               text-black/55 dark:text-white/55"
                        aria-label="Notifikasi"
                    >
                        <svg
                            class="w-[15px] h-[15px]"
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

                    <div class="flex items-center gap-2.5 pl-1">

                        <div class="hidden sm:block text-right leading-none">
                            <p class="text-[10px] font-semibold text-[#171719] dark:text-white">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="mt-1 text-[8px] text-black/40 dark:text-white/40">
                                {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                            </p>
                        </div>

                        <div class="w-[32px] h-[32px] rounded-full bg-[#D9C5A7]
                                    flex items-center justify-center
                                    text-[11px] font-bold text-[#3B3025]">
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