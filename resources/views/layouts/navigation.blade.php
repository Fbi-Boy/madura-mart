<nav class="w-64 h-[calc(100vh-2rem)] shrink-0 self-start rounded-[20px]
                  bg-[#202024] dark:bg-[#FCFCFB]
                  text-white dark:text-[#171719]
                  flex flex-col overflow-hidden
                  shadow-[0_8px_25px_rgba(0,0,0,0.10)]
                  dark:shadow-[0_8px_25px_rgba(0,0,0,0.06)]
                  transition-colors duration-200">

    {{-- LOGO --}}
    <div class="px-5 pt-6 pb-5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">

            <div class="w-10 h-10 rounded-xl
                        bg-white dark:bg-[#171719]
                        flex items-center justify-center
                        font-black text-lg
                        text-[#202024] dark:text-white">
                M
            </div>

            <p class="text-[17px] font-bold tracking-tight">
                <span class="text-white dark:text-[#171719]">WAR</span><span class="text-[#B9F23D]">-MART</span>
            </p>

        </a>
    </div>

    {{-- NAVIGATION --}}
    <div class="flex-1 px-4 overflow-y-auto">

        {{-- MAIN --}}
        <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-[0.14em]
                  text-white/35 dark:text-black/35">
            Main
        </p>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl
                  text-sm font-medium transition
                  bg-[#B9F23D] text-[#171719]">

            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 10.5 12 3l9 7.5"></path>
                <path d="M5 9.5V21h14V9.5"></path>
                <path d="M9 21v-6h6v6"></path>
            </svg>

            Dashboard

        </a>

        @if(auth()->user()->role === 'admin')

            {{-- MANAGEMENT --}}
            <p class="px-3 mt-5 mb-2 text-[10px] font-semibold uppercase tracking-[0.14em]
                      text-white/35 dark:text-black/35">
                Management
            </p>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm
                      text-white/75 dark:text-black/70
                      hover:text-white dark:hover:text-black transition">

                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2h12v20H6z"></path>
                    <path d="M9 6h6M9 10h6M9 14h3"></path>
                </svg>

                Transaksi

            </a>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm
                      text-white/75 dark:text-black/70
                      hover:text-white dark:hover:text-black transition">

                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16v16H4z"></path>
                    <path d="M8 8h8M8 12h8M8 16h5"></path>
                </svg>

                Master Data

            </a>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm
                      text-white/75 dark:text-black/70
                      hover:text-white dark:hover:text-black transition">

                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19V5"></path>
                    <path d="M4 19h16"></path>
                    <path d="m7 15 3-4 3 2 4-6"></path>
                </svg>

                Laporan

            </a>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm
                      text-white/75 dark:text-black/70
                      hover:text-white dark:hover:text-black transition">

                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="3"></circle>
                    <path d="M5 21a7 7 0 0 1 14 0"></path>
                </svg>

                Manajemen

            </a>

        @elseif(auth()->user()->role === 'kasir')

            {{-- SALES --}}
            <p class="px-3 mt-5 mb-2 text-[10px] font-semibold uppercase tracking-[0.14em]
                      text-white/35 dark:text-black/35">
                Sales
            </p>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm
                      text-white/75 dark:text-black/70
                      hover:text-white dark:hover:text-black transition">

                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 4h18v16H3z"></path>
                    <path d="M7 8h10M7 12h10M7 16h6"></path>
                </svg>

                Penjualan

            </a>

            <a href="#"
               class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm
                      text-white/75 dark:text-black/70
                      hover:text-white dark:hover:text-black transition">

                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                    <path d="M8 8h8M8 12h8M8 16h5"></path>
                </svg>

                Shift

            </a>

        @endif

    </div>

    {{-- PROFILE + LOGOUT --}}
    <div class="px-5 pt-4 pb-5">

        <div class="flex justify-center mb-4">

            <div class="w-11 h-11 rounded-full
                        bg-white dark:bg-[#171719]
                        text-[#202024] dark:text-white
                        flex items-center justify-center
                        text-sm font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

        </div>

        <form method="POST" action="{{ route('logout') }}">

            @csrf

            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl
                           text-sm font-medium
                           text-white/70 dark:text-black/65
                           hover:text-white dark:hover:text-black
                           hover:bg-white/10 dark:hover:bg-black/5
                           transition">

                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10 17l5-5-5-5"></path>
                    <path d="M15 12H3"></path>
                    <path d="M15 4h5v16h-5"></path>
                </svg>

                Logout

            </button>

        </form>

    </div>

</nav>