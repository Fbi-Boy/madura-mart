<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Dashboard Kurir</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Logistics Operations · Pantau tugas pengiriman dan status pesanan.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('kurir.pengiriman.riwayat') }}" class="rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                    Riwayat
                </a>
                <div class="rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    {{ now()->translatedFormat('d M Y') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-[#A8F23A]/40 bg-[#A8F23A]/10 p-5 dark:bg-[#A8F23A]/5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-gray-500 dark:text-gray-400">Courier Workspace</p>
                        <h1 class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $courier?->name ?? 'Tugas Pengiriman' }}</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                            {{ $courier ? 'Tugas ditampilkan berdasarkan kurir yang terhubung melalui email akun.' : 'Akun belum terhubung ke data kurir.' }}
                        </p>
                    </div>
                    <span class="inline-flex w-fit rounded-full bg-[#A8F23A] px-3 py-1.5 text-xs font-semibold text-gray-900">Logistics</span>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                @php
                    $kpis = [
                        ['label' => 'Pesanan Hari Ini', 'value' => number_format($todayOrders, 0, ',', '.'), 'hint' => 'pesanan masuk hari ini'],
                        ['label' => 'Perlu Diproses', 'value' => number_format($pendingOrders, 0, ',', '.'), 'hint' => 'pending + processing'],
                        ['label' => 'Sedang Dikirim', 'value' => number_format($shippingOrders, 0, ',', '.'), 'hint' => 'status shipped'],
                        ['label' => 'Rute Aktif', 'value' => number_format($activeDeliveryOrders, 0, ',', '.'), 'hint' => 'processing + shipped'],
                        ['label' => 'Total Selesai', 'value' => number_format($deliveredOrders, 0, ',', '.'), 'hint' => 'status delivered'],
                    ];
                @endphp
                @foreach ($kpis as $kpi)
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $kpi['label'] }}</p>
                        <p class="mt-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $kpi['value'] }}</p>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $kpi['hint'] }}</p>
                    </div>
                @endforeach
            </div>

            <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Progress Pengiriman</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Persentase tugas non-cancelled yang sudah mencapai status selesai.</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-3xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ number_format($deliveryRate, 1, ',', '.') }}%</p>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">completion rate</p>
                    </div>
                </div>
                <div class="mt-5 h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                    <div class="h-full rounded-full bg-[#A8F23A]" style="width: {{ min($deliveryRate, 100) }}%"></div>
                </div>
            </section>

            <div class="grid gap-6 xl:grid-cols-[1.55fr_1fr]">
                <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Tugas Pengiriman</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pesanan terbaru yang terkait dengan tugas kurir.</p>
                        </div>
                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ $recentOrders->count() }} data</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[720px] text-left text-sm">
                            <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                                <tr><th class="px-5 py-3">Pesanan</th><th class="px-5 py-3">Customer</th><th class="px-5 py-3">Tanggal</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Total</th><th class="px-5 py-3 text-right">Aksi</th></tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($recentOrders as $order)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                        <td class="px-5 py-4"><p class="font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</p><p class="mt-1 max-w-[260px] truncate text-xs text-gray-400 dark:text-gray-500">{{ $order->delivery_address }}</p></td>
                                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $order->customer?->name ?? '-' }}</td>
                                        <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $order->order_date?->format('d/m/Y H:i') }}</td>
                                        <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-medium @if ($order->status === 'delivered') bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300 @elseif ($order->status === 'shipped') bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300 @elseif ($order->status === 'cancelled') bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300 @else bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-300 @endif">{{ ucfirst($order->status) }}</span></td>
                                        <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td><td class="px-5 py-4 text-right">
                                            @php
                                                $nextStatus = ['pending' => 'processing', 'processing' => 'shipped', 'shipped' => 'delivered'][$order->status] ?? null;
                                                $nextLabel = ['processing' => 'Proses', 'shipped' => 'Kirim', 'delivered' => 'Selesai'][$nextStatus] ?? null;
                                            @endphp
                                            @if ($nextStatus)
                                                <form method="POST" action="{{ route('kurir.pengiriman.status', $order) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $nextStatus }}">
                                                    <button type="submit" class="rounded-lg bg-[#A8F23A] px-3 py-1.5 text-xs font-semibold text-gray-900 transition hover:brightness-95">{{ $nextLabel }}</button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada tugas pengiriman.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="rounded-2xl border border-[#A8F23A]/30 bg-white p-5 shadow-sm dark:border-[#A8F23A]/20 dark:bg-gray-800">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Prioritas Pengiriman</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pesanan berstatus shipped yang perlu dituntaskan.</p>
                        </div>
                        <span class="rounded-full bg-[#A8F23A]/20 px-2.5 py-1 text-xs font-semibold text-gray-900 dark:text-[#A8F23A]">{{ $priorityOrders->count() }}</span>
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse ($priorityOrders as $order)
                            <div class="rounded-xl bg-gray-50 p-3 dark:bg-gray-700/50">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $order->customer?->name ?? 'Customer' }}</p>
                                    </div>
                                    <span class="shrink-0 rounded-full bg-blue-50 px-2 py-1 text-[11px] font-medium text-blue-700 dark:bg-blue-900/20 dark:text-blue-300">Shipped</span>
                                </div>
                                <p class="mt-2 truncate text-xs text-gray-400 dark:text-gray-500">{{ $order->delivery_address }}</p>
                            </div>
                        @empty
                            <p class="py-4 text-sm text-gray-400">Tidak ada pengiriman prioritas.</p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Status Pengiriman</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Distribusi tugas pada akun kurir.</p>
                    <div class="mt-5 space-y-3">
                        @foreach (['pending' => 'Menunggu', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'delivered' => 'Selesai'] as $key => $label)
                            <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-700/50"><span class="text-sm text-gray-600 dark:text-gray-300">{{ $label }}</span><span class="font-semibold text-gray-900 dark:text-white">{{ number_format($statusSummary[$key], 0, ',', '.') }}</span></div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>

        <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Aktivitas Status Terbaru</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Perubahan status pada pesanan yang ditangani akun ini.</p>
                </div>
                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ $recentDeliveryUpdates->count() }} aktivitas</span>
            </div>

            <div class="mt-5 space-y-3">
                @forelse ($recentDeliveryUpdates as $activity)
                    <div class="flex gap-3 rounded-xl bg-gray-50 p-3 dark:bg-gray-700/50">
                        <div class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-[#A8F23A]"></div>
                        <div class="min-w-0">
                            <p class="text-sm text-gray-700 dark:text-gray-200">{{ $activity->description }}</p>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                                {{ $activity->created_at?->format('d/m/Y H:i') }}
                                · {{ $activity->user?->name ?? 'Sistem' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-sm text-gray-400">Belum ada perubahan status pengiriman.</p>
                @endforelse
            </div>
        </section>
    </div>

</x-app-layout>
