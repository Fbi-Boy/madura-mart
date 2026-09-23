
<nav class="bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 w-64 min-h-screen flex flex-col transition-colors duration-200">

    {{-- LOGO --}}
    <div class="h-16 flex items-center px-6 border-b border-gray-200 dark:border-gray-800">
    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800 dark:text-white">
        Madura Mart
    </a>
    </div>

    {{-- USER --}}
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
            {{ auth()->user()->name }}
        </p>

        <p class="text-xs text-gray-500 mt-1">
            {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
        </p>
    </div>

    {{-- MENU --}}
    <div class="flex-1 px-4 py-5">

        {{-- ==================== --}}
        {{-- MENU ADMIN --}}
        {{-- ==================== --}}

        @if(auth()->user()->role === 'admin')

            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase">
                Menu Utama
            </p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                Dashboard
            </a>

            <p class="px-3 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase">
                Monitoring
            </p>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                Penjualan
            </a>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                Persediaan
            </a>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                Aktivitas User
            </a>

            <p class="px-3 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase">
                Laporan
            </p>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100">
                Laporan Penjualan
            </a>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100">
                Laporan Stok
            </a>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100">
                Laporan Pembelian
            </a>

            <p class="px-3 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase">
                Pengaturan
            </p>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100">
                Pengguna
            </a>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100">
                Pengaturan Sistem
            </a>


        {{-- ==================== --}}
        {{-- MENU KASIR --}}
        {{-- ==================== --}}

        @elseif(auth()->user()->role === 'kasir')

            <p class="px-3 mb-2 text-xs font-semibold text-gray-400 uppercase">
                Menu Utama
            </p>

            <a href="{{ route('dashboard') }}"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100">
                Dashboard
            </a>

            <p class="px-3 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase">
                Penjualan
            </p>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100">
                Transaksi Baru
            </a>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100">
                Riwayat Transaksi
            </a>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100">
                Retur
            </a>

            <p class="px-3 mt-6 mb-2 text-xs font-semibold text-gray-400 uppercase">
                Shift
            </p>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100">
                Buka Shift
            </a>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100">
                Tutup Shift
            </a>

            <a href="#"
               class="flex items-center px-3 py-2 mb-1 rounded-lg text-sm text-gray-700 hover:bg-gray-100">
                Riwayat Shift
            </a>

        @endif

    </div>

    {{-- LOGOUT --}}
    <div class="p-4 border-t border-gray-200 dark:border-gray-800">

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                    class="w-full text-left px-3 py-2 rounded-lg text-sm text-red-600 hover:bg-red-50">
                Logout
            </button>
        </form>

    </div>

</nav>

