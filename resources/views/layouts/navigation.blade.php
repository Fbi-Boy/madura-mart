<nav class="w-64 min-h-screen shrink-0 bg-white dark:bg-gray-950 border-r border-gray-100 dark:border-gray-800 flex flex-col transition-colors duration-200">

    {{-- BRAND --}}
    <div class="h-20 px-6 flex items-center">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#6C4AB6] flex items-center justify-center text-white font-bold text-lg shadow-sm">
                M
            </div>
            <div>
                <p class="text-[15px] font-bold text-gray-900 dark:text-white leading-none">
                    Madura Mart
                </p>
                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">
                    Management System
                </p>
            </div>
        </a>
    </div>

    {{-- USER --}}
    <div class="px-5 pb-5">
        <div class="rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 p-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-[#E9DFFF] text-[#6C4AB6] flex items-center justify-center font-semibold text-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div class="min-w-0">
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100 truncate">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-0.5">
                    {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                </p>
            </div>
        </div>
    </div>

    {{-- MENU --}}
    <div class="flex-1 px-4 overflow-y-auto">

        @if(auth()->user()->role === 'admin')

            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.12em] text-gray-400">
                Menu Utama
            </p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm font-medium text-[#6C4AB6] bg-[#F2ECFF] dark:bg-[#39296A] dark:text-[#D8C8FF]">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10.5 12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6"/>
                </svg>
                Dashboard
            </a>

            <p class="px-3 mt-7 mb-2 text-[10px] font-bold uppercase tracking-[0.12em] text-gray-400">
                Monitoring
            </p>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19V9m5 10V5m5 14v-7m5 7V3"/></svg>
                Penjualan
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16M4 12h16M4 17h10"/></svg>
                Persediaan
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 20v-1a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v1m5-8a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm8-1a3 3 0 1 0 0-6m4 13v-1a3 3 0 0 0-2-2.83"/></svg>
                Aktivitas User
            </a>

            <p class="px-3 mt-7 mb-2 text-[10px] font-bold uppercase tracking-[0.12em] text-gray-400">
                Laporan
            </p>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Laporan Penjualan</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Laporan Stok</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Laporan Pembelian</a>

            <p class="px-3 mt-7 mb-2 text-[10px] font-bold uppercase tracking-[0.12em] text-gray-400">
                Pengaturan
            </p>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Pengguna</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Pengaturan Sistem</a>

        @elseif(auth()->user()->role === 'kasir')

            <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.12em] text-gray-400">
                Menu Utama
            </p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm font-medium text-[#6C4AB6] bg-[#F2ECFF] dark:bg-[#39296A] dark:text-[#D8C8FF]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10.5 12 3l9 7.5M5 9.5V21h14V9.5M9 21v-6h6v6"/></svg>
                Dashboard
            </a>

            <p class="px-3 mt-7 mb-2 text-[10px] font-bold uppercase tracking-[0.12em] text-gray-400">
                Penjualan
            </p>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Transaksi Baru</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Riwayat Transaksi</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Retur</a>

            <p class="px-3 mt-7 mb-2 text-[10px] font-bold uppercase tracking-[0.12em] text-gray-400">
                Shift
            </p>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Buka Shift</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Tutup Shift</a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Riwayat Shift</a>

        @endif
    </div>

    {{-- LOGOUT --}}
    <div class="p-4 mt-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-gray-500 hover:text-red-600 hover:bg-red-50 dark:text-gray-400 dark:hover:bg-red-950/30 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17l5-5-5-5m5 5H9m3-7V4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h5a2 2 0 0 0 2-2v-1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>

</nav>