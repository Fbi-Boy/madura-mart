<x-app-layout>

    <div class="pt-0">

        {{-- PAGE TITLE --}}
        <div class="mb-5">
            <h2 class="text-[22px] font-bold tracking-[-0.02em]
                       leading-none text-[#171719] dark:text-white">
                Dashboard
            </h2>
        </div>

        {{-- STATISTIK --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-5">

            <div class="bg-white dark:bg-[#1A1A1D] rounded-2xl shadow-sm p-5">
                <p class="text-sm text-black/45 dark:text-white/45">Total Produk</p>
                <h3 class="text-2xl font-bold text-[#171719] dark:text-white mt-2">0</h3>
                <p class="text-xs text-black/35 dark:text-white/35 mt-1">Produk terdaftar</p>
            </div>

            <div class="bg-white dark:bg-[#1A1A1D] rounded-2xl shadow-sm p-5">
                <p class="text-sm text-black/45 dark:text-white/45">Transaksi Hari Ini</p>
                <h3 class="text-2xl font-bold text-[#171719] dark:text-white mt-2">0</h3>
                <p class="text-xs text-black/35 dark:text-white/35 mt-1">Transaksi penjualan</p>
            </div>

            <div class="bg-white dark:bg-[#1A1A1D] rounded-2xl shadow-sm p-5">
                <p class="text-sm text-black/45 dark:text-white/45">Pesanan</p>
                <h3 class="text-2xl font-bold text-[#171719] dark:text-white mt-2">0</h3>
                <p class="text-xs text-black/35 dark:text-white/35 mt-1">Pesanan aktif</p>
            </div>

            <div class="bg-white dark:bg-[#1A1A1D] rounded-2xl shadow-sm p-5">
                <p class="text-sm text-black/45 dark:text-white/45">Stok Menipis</p>
                <h3 class="text-2xl font-bold text-[#171719] dark:text-white mt-2">0</h3>
                <p class="text-xs text-black/35 dark:text-white/35 mt-1">Perlu diperiksa</p>
            </div>

        </div>

        {{-- AKTIVITAS --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <div class="lg:col-span-2 bg-white dark:bg-[#1A1A1D] rounded-2xl shadow-sm p-6">

                <div class="mb-5">
                    <h3 class="text-lg font-semibold text-[#171719] dark:text-white">
                        Aktivitas Terbaru
                    </h3>

                    <p class="text-sm text-black/45 dark:text-white/45">
                        Aktivitas sistem terbaru akan muncul di sini.
                    </p>
                </div>

                <div class="py-10 text-center">
                    <p class="text-sm text-black/35 dark:text-white/35">
                        Belum ada aktivitas.
                    </p>
                </div>

            </div>

            <div class="bg-white dark:bg-[#1A1A1D] rounded-2xl shadow-sm p-6">

                <h3 class="text-lg font-semibold text-[#171719] dark:text-white">
                    Informasi Akun
                </h3>

                <div class="mt-5 space-y-4">

                    <div>
                        <p class="text-xs text-black/35 dark:text-white/35 uppercase">Nama</p>
                        <p class="text-sm font-medium text-black/70 dark:text-white/70 mt-1">
                            {{ auth()->user()->name }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-black/35 dark:text-white/35 uppercase">Email</p>
                        <p class="text-sm font-medium text-black/70 dark:text-white/70 mt-1">
                            {{ auth()->user()->email }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-black/35 dark:text-white/35 uppercase">Role</p>
                        <p class="text-sm font-medium text-black/70 dark:text-white/70 mt-1">
                            {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>