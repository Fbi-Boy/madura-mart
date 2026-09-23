<nav class="w-56 h-[calc(100vh-2rem)] shrink-0 self-start rounded-[20px]
                  bg-[#202024] dark:bg-[#FCFCFB]
                  text-white dark:text-[#171719]
                  flex flex-col overflow-hidden
                  shadow-[0_8px_25px_rgba(0,0,0,0.10)]
                  dark:shadow-[0_8px_25px_rgba(0,0,0,0.06)]
                  transition-colors duration-200">

    {{-- LOGO --}}
    <div class="px-4 pt-5 pb-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">

            <div class="w-9 h-9 rounded-xl
                        bg-white dark:bg-[#171719]
                        flex items-center justify-center
                        font-black text-base
                        text-[#202024] dark:text-white">
                M
            </div>

            <p class="text-[16px] font-bold tracking-tight">
                <span class="text-white dark:text-[#171719]">WAR</span><span class="text-[#B9F23D]">-MART</span>
            </p>

        </a>
    </div>

    {{-- NAVIGATION --}}
    <div class="flex-1 px-3 overflow-y-auto">

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

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 4h18v16H3z"></path>
                    <path d="M7 8h10M7 12h6M7 16h8"></path>
                </svg>
                Penjualan
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 5h16v14H4z"></path>
                    <path d="M8 9h8M8 13h5"></path>
                </svg>
                Pembelian
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="9" r="3"></circle>
                    <path d="M3 20a6 6 0 0 1 12 0"></path>
                    <path d="M16 11h5M18.5 8.5v5"></path>
                </svg>
                Pesanan
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16v16H4z"></path>
                    <path d="M8 8h8M8 12h8M8 16h5"></path>
                </svg>
                Produk
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 7h18"></path>
                    <path d="M5 7v13h14V7"></path>
                    <path d="M8 4h8l1 3H7z"></path>
                </svg>
                Distributor
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="3"></circle>
                    <path d="M5 21a7 7 0 0 1 14 0"></path>
                </svg>
                Client
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                    <path d="M7 9h10M7 13h6"></path>
                </svg>
                Kurir
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 19V5"></path>
                    <path d="M4 19h16"></path>
                    <path d="m7 15 3-4 3 2 4-6"></path>
                </svg>
                Laporan Penjualan
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16v16H4z"></path>
                    <path d="M8 8h8M8 12h8M8 16h5"></path>
                </svg>
                Laporan Pembelian
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 20V9"></path>
                    <path d="M10 20V5"></path>
                    <path d="M15 20v-7"></path>
                    <path d="M20 20V3"></path>
                </svg>
                Stok
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="3"></circle>
                    <path d="M5 21a7 7 0 0 1 14 0"></path>
                </svg>
                Pengguna
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-1.8 1.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6V20h-2.5v-.1a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1-1.8-1.8.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.6-1H6v-2.5h.1a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9l-.1-.1 1.8-1.8.1.1a1.7 1.7 0 0 0 1.9.3 1.7 1.7 0 0 0 1-1.6V5h2.5v.1a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1 1.8 1.8-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.1v2.5h-.1a1.7 1.7 0 0 0-1.6 1z"></path>
                </svg>
                Pengaturan
            </a>

        @elseif(auth()->user()->role === 'kasir')

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 4h18v16H3z"></path>
                    <path d="M7 8h10M7 12h10M7 16h6"></path>
                </svg>
                Transaksi Baru
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 4h14v16H5z"></path>
                    <path d="M8 8h8M8 12h8M8 16h5"></path>
                </svg>
                Riwayat Transaksi
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 5h16v14H4z"></path>
                    <path d="m8 9 8 6M16 9l-8 6"></path>
                </svg>
                Retur
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="8"></circle>
                    <path d="M12 8v4l3 2"></path>
                </svg>
                Buka Shift
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="8"></circle>
                    <path d="M8 12h8"></path>
                </svg>
                Tutup Shift
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm
                               text-white/75 dark:text-black/70 hover:text-white dark:hover:text-black transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="8"></circle>
                    <path d="M8 12h8M12 8v8"></path>
                </svg>
                Riwayat Shift
            </a>

        @endif

    </div>

    {{-- PROFILE CARD + LOGOUT --}}
    <div class="px-3 pb-3 pt-2">

        <div class="rounded-[18px] p-3
                    bg-[#B9F23D] dark:bg-[#B9F23D]
                    text-[#171719]">

            <div class="flex items-center gap-3">

                <div class="w-10 h-10 shrink-0 rounded-full
                            bg-white dark:bg-[#202024]
                            text-[#171719] dark:text-white
                            flex items-center justify-center
                            text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <p class="text-sm font-semibold truncate">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-[10px] opacity-60 truncate">
                        {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                    </p>
                </div>

            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">

                @csrf

                <button type="submit"
                        class="w-full flex items-center justify-center gap-2
                               rounded-xl py-2
                               bg-black/10 hover:bg-black/15
                               text-sm font-medium transition">

                    <svg class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10 17l5-5-5-5"></path>
                        <path d="M15 12H3"></path>
                        <path d="M15 4h5v16h-5"></path>
                    </svg>

                    Logout

                </button>

            </form>

        </div>

    </div>

</nav>