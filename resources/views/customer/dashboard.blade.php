<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Dashboard Customer</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Personal Shopping · Pantau pesanan dan riwayat belanja Anda.</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="inline-flex w-fit items-center rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                Profil Saya
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-gray-900 p-6 text-white shadow-sm dark:bg-gray-800 sm:p-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-sm font-medium text-[#A8F23A]">Selamat datang kembali</p>
                        <h1 class="mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">{{ auth()->user()->name }}</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-300">
                            Kelola pesanan Anda dari satu tempat. Status dan total di bawah diambil langsung dari data order akun Anda.
                        </p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 px-5 py-4">
                        <p class="text-xs text-gray-400">Total pembayaran terkonfirmasi</p>
                        <p class="mt-1 text-xl font-semibold">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <section class="grid gap-3 sm:grid-cols-3">
                <a href="{{ route('customer.catalog.index') }}" class="group rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-[#A8F23A] dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-400">Belanja</p>
                    <p class="mt-1 font-semibold text-gray-900 group-hover:text-gray-700 dark:text-white dark:group-hover:text-[#A8F23A]">Jelajahi Produk →</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Cari produk dan lihat stok yang tersedia.</p>
                </a>
                <a href="{{ route('customer.cart.index') }}" class="group rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-[#A8F23A] dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-400">Keranjang</p>
                            <p class="mt-1 font-semibold text-gray-900 group-hover:text-gray-700 dark:text-white dark:group-hover:text-[#A8F23A]">Lanjut Checkout →</p>
                        </div>
                        <span class="rounded-full bg-[#A8F23A]/20 px-2.5 py-1 text-xs font-semibold text-gray-900 dark:text-[#A8F23A]">{{ number_format($cartItemCount, 0, ',', '.') }}</span>
                    </div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total item yang tersimpan di sesi keranjang.</p>
                </a>
                <a href="{{ route('customer.address.edit') }}" class="group rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-[#A8F23A] dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-400">Alamat</p>
                    <p class="mt-1 font-semibold text-gray-900 group-hover:text-gray-700 dark:text-white dark:group-hover:text-[#A8F23A]">Kelola Alamat →</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Atur nama, nomor telepon, dan alamat pengiriman.</p>
                </a>
                <a href="{{ route('customer.orders.index') }}" class="group rounded-2xl border border-gray-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-[#A8F23A] dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-400">Riwayat</p>
                    <p class="mt-1 font-semibold text-gray-900 group-hover:text-gray-700 dark:text-white dark:group-hover:text-[#A8F23A]">Lihat Semua Order →</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Buka daftar pesanan dan detail transaksi.</p>
                </a>
            </section>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @php
                    $kpis = [
                        ['label' => 'Total Pesanan', 'value' => $totalOrders, 'hint' => 'semua order akun', 'icon' => '01'],
                        ['label' => 'Pesanan Aktif', 'value' => $activeOrders, 'hint' => 'belum selesai', 'icon' => '02'],
                        ['label' => 'Pesanan Selesai', 'value' => $completedOrders, 'hint' => 'status delivered', 'icon' => '03'],
                        ['label' => 'Dibatalkan', 'value' => $cancelledOrders, 'hint' => 'status cancelled', 'icon' => '04'],
                    ];
                @endphp

                @foreach ($kpis as $kpi)
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $kpi['label'] }}</p>
                                <p class="mt-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ number_format($kpi['value'], 0, ',', '.') }}</p>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $kpi['hint'] }}</p>
                            </div>
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#A8F23A]/20 text-xs font-bold text-gray-900 dark:text-[#A8F23A]">{{ $kpi['icon'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid gap-6 xl:grid-cols-[1.7fr_1fr]">
                <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Pesanan Terbaru</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Aktivitas order terbaru akun Anda.</p>
                        </div>
                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ $recentOrders->count() }} data</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[640px] text-left text-sm">
                            <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                                <tr>
                                    <th class="px-5 py-3">Order</th>
                                    <th class="px-5 py-3">Tanggal</th>
                                    <th class="px-5 py-3">Kurir</th>
                                    <th class="px-5 py-3">Status</th>
                                    <th class="px-5 py-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($recentOrders as $order)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                        <td class="px-5 py-4">
    <a href="{{ route('customer.orders.show', $order) }}" class="font-medium text-gray-900 transition hover:text-gray-700 dark:text-white dark:hover:text-[#A8F23A]">{{ $order->order_number }}</a>
</td>
                                        <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $order->order_date?->format('d/m/Y H:i') }}</td>
                                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $order->courier?->name ?? 'Belum ditugaskan' }}</td>
                                        <td class="px-5 py-4">
                                            <span class="rounded-full px-2.5 py-1 text-xs font-medium @if ($order->status === 'delivered') bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300 @elseif ($order->status === 'cancelled') bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300 @else bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-300 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada pesanan untuk akun ini.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Status Pesanan</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Distribusi status order akun Anda.</p>
                    <div class="mt-5 space-y-3">
                        @foreach (['pending' => 'Menunggu', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'delivered' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $key => $label)
                            <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-700/50">
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $label }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($statusSummary[$key], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Status Pembayaran</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pesanan yang masih membutuhkan perhatian pembayaran.</p>
                    </div>
                    <span class="rounded-full bg-[#A8F23A]/20 px-2.5 py-1 text-xs font-semibold text-gray-900 dark:text-[#A8F23A]">LIVE</span>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <a href="{{ route('customer.orders.index') }}" class="rounded-2xl border border-gray-200 p-4 transition hover:border-[#A8F23A] hover:bg-[#A8F23A]/5 dark:border-gray-700 dark:hover:bg-[#A8F23A]/5">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-400">Belum Bayar</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($paymentAttention['unpaid'], 0, ',', '.') }}</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Belum mengirim bukti pembayaran.</p>
                    </a>
                    <a href="{{ route('customer.orders.index') }}" class="rounded-2xl border border-gray-200 p-4 transition hover:border-[#A8F23A] hover:bg-[#A8F23A]/5 dark:border-gray-700 dark:hover:bg-[#A8F23A]/5">
                        <p class="text-xs font-semibold uppercase tracking-[0.12em] text-gray-400">Menunggu Verifikasi</p>
                        <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ number_format($paymentAttention['verification'], 0, ',', '.') }}</p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Bukti pembayaran sudah dikirim.</p>
                    </a>
                </div>
            </section>

            @if (!$customer)
                <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-5 dark:border-yellow-900/40 dark:bg-yellow-900/10">
                    <h3 class="font-semibold text-yellow-900 dark:text-yellow-200">Profil customer belum terhubung</h3>
                    <p class="mt-1 text-sm text-yellow-800 dark:text-yellow-300">
                        Dashboard tetap aman dan tidak menampilkan order milik customer lain. Hubungkan email akun dengan data customer untuk melihat pesanan.
                    </p>
                </div>
            @endif
        </div>
    </div>

</x-app-layout>
