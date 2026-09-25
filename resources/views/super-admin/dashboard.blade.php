<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Dashboard Super Admin</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Management & Analytics · Pantau kondisi sistem dan operasi bisnis.</p>
            </div>
            <span class="w-fit rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                {{ now()->translatedFormat('d M Y') }}
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-gray-900 p-6 text-white shadow-sm dark:bg-gray-800 sm:p-8">
                <p class="text-sm font-medium text-[#A8F23A]">System Command Center</p>
                <div class="mt-2 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold tracking-tight sm:text-3xl">Selamat datang, {{ auth()->user()->name }}</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-300">Satu ringkasan untuk melihat pengguna, katalog, transaksi, pesanan, dan area operasional yang membutuhkan perhatian.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                        <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-center text-xs font-medium text-gray-200 transition hover:bg-white/10">Users</a>
                        <a href="{{ route('admin.products.index') }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-center text-xs font-medium text-gray-200 transition hover:bg-white/10">Products</a>
                        <a href="{{ route('admin.monitoring.penjualan') }}" class="rounded-xl border border-white/10 bg-white/5 px-3 py-2 text-center text-xs font-medium text-gray-200 transition hover:bg-white/10">Sales</a>
                        <a href="{{ route('admin.report.stok') }}" class="rounded-xl bg-[#A8F23A] px-3 py-2 text-center text-xs font-semibold text-gray-900 transition hover:brightness-95">Stock</a>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @php
                    $kpis = [
                        ['label' => 'Users', 'value' => $totalUsers, 'hint' => 'seluruh akun sistem'],
                        ['label' => 'Produk Aktif', 'value' => $activeProducts, 'hint' => 'siap digunakan'],
                        ['label' => 'Customer Aktif', 'value' => $activeCustomers, 'hint' => 'customer terdaftar'],
                        ['label' => 'Kurir Aktif', 'value' => $activeCouriers, 'hint' => 'siap menangani order'],
                    ];
                @endphp

                @foreach ($kpis as $kpi)
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $kpi['label'] }}</p>
                        <p class="mt-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ number_format($kpi['value'], 0, ',', '.') }}</p>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $kpi['hint'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Omzet bulan berjalan</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-gray-400">Penjualan berstatus paid.</p>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pembelian bulan berjalan</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">Rp {{ number_format($monthlyPurchases, 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-gray-400">Pembelian berstatus received.</p>
                </div>
                <div class="rounded-2xl border border-[#A8F23A]/40 bg-[#A8F23A]/10 p-5 dark:bg-[#A8F23A]/5">
                    <p class="text-sm text-gray-600 dark:text-gray-300">Perlu perhatian</p>
                    <div class="mt-2 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $pendingOrders }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-300">pesanan berjalan</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $lowStockProducts }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-300">stok rendah</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[1fr_1.5fr]">
                <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Distribusi Role</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Komposisi akun berdasarkan role.</p>
                    <div class="mt-5 space-y-3">
                        @foreach ($roleSummary as $role => $count)
                            <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-700/50">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ ucfirst(str_replace('-', ' ', $role)) }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($count, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Pesanan Terbaru</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Monitoring lintas customer.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[620px] text-left text-sm">
                            <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                                <tr><th class="px-5 py-3">Order</th><th class="px-5 py-3">Customer</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Total</th></tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($recentOrders as $order)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                        <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</td>
                                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $order->customer?->name ?? '-' }}</td>
                                        <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ ucfirst($order->status) }}</td>
                                        <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400">Belum ada order.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            <section class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Akun Terbaru</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Aktivitas pendaftaran akun terbaru.</p>
                </div>
                <div class="grid gap-3 p-5 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse ($recentUsers as $user)
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-700/50">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                            <p class="mt-1 truncate text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            <span class="mt-3 inline-flex rounded-full bg-[#A8F23A]/20 px-2.5 py-1 text-xs font-medium text-gray-700 dark:text-[#A8F23A]">{{ ucfirst(str_replace('-', ' ', $user->role)) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">Belum ada akun.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>

</x-app-layout>
