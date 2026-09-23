<nav class="w-64 min-h-screen shrink-0 bg-[#5B3FA6] dark:bg-[#30205C] text-white flex flex-col transition-colors duration-200">

    {{-- LOGO --}}
    <div class="h-20 px-6 flex items-center">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#F4C430] dark:bg-[#F4C430] flex items-center justify-center font-black text-lg text-[#5B3FA6] dark:text-white">
                M
            </div>

            <p class="text-[17px] font-bold leading-none">
                <span class="text-white">WAR</span><span class="text-[#F4C430]">-MART</span>
            </p>
        </a>
    </div>

    {{-- MENU --}}
    <div class="flex-1 px-4 overflow-y-auto">

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm font-medium text-[#F4C430]">
            <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 10.5 12 3l9 7.5"></path>
                <path d="M5 9.5V21h14V9.5"></path>
                <path d="M9 21v-6h6v6"></path>
            </svg>
            Dashboard
        </a>

        @if(auth()->user()->role === 'admin')

            {{-- TRANSAKSI --}}
            <details class="group mb-1">
                <summary class="list-none cursor-pointer flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-white/80 hover:text-[#F4C430]">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2h12v20H6z"></path>
                            <path d="M9 6h6M9 10h6M9 14h3"></path>
                        </svg>
                        Transaksi
                    </span>
                    <span class="text-xs transition-transform group-open:rotate-180">⌄</span>
                </summary>

                <div class="ml-8 mt-1 space-y-1">
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Penjualan
                    </a>
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Pembelian
                    </a>
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>Pesanan
                    </a>
                </div>
            </details>

            {{-- MASTER DATA --}}
            <details class="group mb-1">
                <summary class="list-none cursor-pointer flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-white/80 hover:text-[#F4C430]">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16v16H4z"></path>
                            <path d="M8 8h8M8 12h8M8 16h5"></path>
                        </svg>
                        Master Data
                    </span>
                    <span class="text-xs transition-transform group-open:rotate-180">⌄</span>
                </summary>

                <div class="ml-8 mt-1 space-y-1">
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Produk</a>
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Distributor</a>
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Client</a>
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Kurir</a>
                </div>
            </details>

            {{-- LAPORAN --}}
            <details class="group mb-1">
                <summary class="list-none cursor-pointer flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-white/80 hover:text-[#F4C430]">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 19V5"></path>
                            <path d="M4 19h16"></path>
                            <path d="m7 15 3-4 3 2 4-6"></path>
                        </svg>
                        Laporan
                    </span>
                    <span class="text-xs transition-transform group-open:rotate-180">⌄</span>
                </summary>

                <div class="ml-8 mt-1 space-y-1">
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Penjualan</a>
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Pembelian</a>
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Stok</a>
                </div>
            </details>

            {{-- MANAJEMEN --}}
            <details class="group mb-1">
                <summary class="list-none cursor-pointer flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-white/80 hover:text-[#F4C430]">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="3"></circle>
                            <path d="M5 21a7 7 0 0 1 14 0"></path>
                        </svg>
                        Manajemen
                    </span>
                    <span class="text-xs transition-transform group-open:rotate-180">⌄</span>
                </summary>

                <div class="ml-8 mt-1 space-y-1">
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Pengguna</a>
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Pengaturan Sistem</a>
                </div>
            </details>

        @elseif(auth()->user()->role === 'kasir')

            {{-- PENJUALAN --}}
            <details class="group mb-1">
                <summary class="list-none cursor-pointer flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-white/80 hover:text-[#F4C430]">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 4h18v16H3z"></path>
                            <path d="M7 8h10M7 12h10M7 16h6"></path>
                        </svg>
                        Penjualan
                    </span>
                    <span class="text-xs transition-transform group-open:rotate-180">⌄</span>
                </summary>

                <div class="ml-8 mt-1 space-y-1">
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Transaksi Baru</a>
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Riwayat Transaksi</a>
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Retur</a>
                </div>
            </details>

            {{-- SHIFT --}}
            <details class="group mb-1">
                <summary class="list-none cursor-pointer flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-white/80 hover:text-[#F4C430]">
                    <span class="flex items-center gap-3">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                            <path d="M8 8h8M8 12h8M8 16h5"></path>
                        </svg>
                        Shift
                    </span>
                    <span class="text-xs transition-transform group-open:rotate-180">⌄</span>
                </summary>

                <div class="ml-8 mt-1 space-y-1">
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Buka Shift</a>
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Tutup Shift</a>
                    <a href="#" class="flex items-center gap-2 px-3 py-1.5 text-xs text-white/60 hover:text-[#F4C430]"><span class="w-1.5 h-1.5 rounded-full bg-current"></span>Riwayat Shift</a>
                </div>
            </details>

        @endif
    </div>

    {{-- LOGOUT --}}
    <div class="px-4 py-4 border-t border-white/10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-white/70 hover:text-[#F4C430] transition">
                <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10 17l5-5-5-5"></path>
                    <path d="M15 12H3"></path>
                    <path d="M15 4h5v16h-5"></path>
                </svg>
                Keluar
            </button>
        </form>
    </div>

</nav>