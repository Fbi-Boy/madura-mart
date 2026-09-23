<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Selamat datang di Madura Mart
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Welcome --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Selamat Datang, {{ auth()->user()->name }}
                </h1>

                <p class="text-gray-500 mt-2">
                    Anda login sebagai
                    <span class="font-semibold text-gray-700">
                        {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                    </span>
                </p>
            </div>

            {{-- Statistik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Total Produk</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2">0</h3>
                    <p class="text-xs text-gray-400 mt-1">
                        Produk terdaftar
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2">0</h3>
                    <p class="text-xs text-gray-400 mt-1">
                        Transaksi penjualan
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Pesanan</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2">0</h3>
                    <p class="text-xs text-gray-400 mt-1">
                        Pesanan aktif
                    </p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <p class="text-sm text-gray-500">Stok Menipis</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-2">0</h3>
                    <p class="text-xs text-gray-400 mt-1">
                        Perlu diperiksa
                    </p>
                </div>

            </div>

            {{-- Aktivitas --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Aktivitas Terbaru
                            </h3>
                            <p class="text-sm text-gray-500">
                                Aktivitas sistem terbaru akan muncul di sini.
                            </p>
                        </div>
                    </div>

                    <div class="py-12 text-center">
                        <p class="text-gray-400">
                            Belum ada aktivitas.
                        </p>
                    </div>

                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Informasi Akun
                    </h3>

                    <div class="mt-5 space-y-4">

                        <div>
                            <p class="text-xs text-gray-400 uppercase">
                                Nama
                            </p>
                            <p class="text-sm font-medium text-gray-700 mt-1">
                                {{ auth()->user()->name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 uppercase">
                                Email
                            </p>
                            <p class="text-sm font-medium text-gray-700 mt-1">
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 uppercase">
                                Role
                            </p>
                            <p class="text-sm font-medium text-gray-700 mt-1">
                                {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</x-app-layout>
