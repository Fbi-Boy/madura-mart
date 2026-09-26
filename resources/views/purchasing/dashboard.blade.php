<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Dashboard Purchasing</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Procurement Operations · Pantau pembelian dan penerimaan barang.</p>
            </div>
            <div class="rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs font-medium text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                {{ now()->translatedFormat('d M Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @php
                    $kpis = [
                        ['label' => 'Supplier & Monitoring Aktif', 'value' => number_format($activeSuppliers, 0, ',', '.'), 'hint' => 'supplier yang tersedia', 'icon' => 'S'],
                        ['label' => 'Pembelian Hari Ini', 'value' => 'Rp '.number_format($todayPurchases, 0, ',', '.'), 'hint' => $todayTransactions.' transaksi', 'icon' => 'Rp'],
                        ['label' => 'Draft Menunggu', 'value' => number_format($draftPurchases, 0, ',', '.'), 'hint' => 'perlu ditindaklanjuti', 'icon' => 'D'],
                        ['label' => 'Diterima Hari Ini', 'value' => number_format($receivedToday, 0, ',', '.'), 'hint' => 'transaksi berstatus received', 'icon' => 'R'],
                    ];
                @endphp

                @foreach ($kpis as $kpi)
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $kpi['label'] }}</p>
                                <p class="mt-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $kpi['value'] }}</p>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $kpi['hint'] }}</p>
                            </div>
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#A8F23A]/20 text-sm font-bold text-gray-900 dark:text-[#A8F23A]">{{ $kpi['icon'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="grid gap-6 xl:grid-cols-[1.65fr_1fr]">
                <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Pembelian Terbaru</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Aktivitas procurement terbaru.</p>
                        </div>
                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ $recentPurchases->count() }} data</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[680px] text-left text-sm">
                            <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                                <tr>
                                    <th class="px-5 py-3">Invoice</th><th class="px-5 py-3">Supplier</th><th class="px-5 py-3">Tanggal</th><th class="px-5 py-3">Item</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($recentPurchases as $purchase)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                        <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">{{ $purchase->invoice }}</td>
                                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $purchase->supplier?->name ?? '-' }}</td>
                                        <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $purchase->purchase_date?->format('d/m/Y') }}</td>
                                        <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $purchase->items_count }}</td>
                                        <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-medium @if ($purchase->status === 'received') bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300 @elseif ($purchase->status === 'cancelled') bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300 @else bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-300 @endif">{{ ucfirst($purchase->status) }}</span></td>
                                        <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada transaksi pembelian.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="space-y-6">
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Status Pembelian</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ringkasan seluruh transaksi.</p>
                        <div class="mt-5 space-y-3">
                            @foreach (['draft' => 'Draft', 'received' => 'Received', 'cancelled' => 'Cancelled'] as $key => $label)
                                <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-700/50">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $label }}</span>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($statusSummary[$key], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Supplier Utama</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Berdasarkan nilai pembelian tercatat.</p>
                        <div class="mt-5 space-y-4">
                            @forelse ($supplierPurchases as $item)
                                <div>
                                    <div class="flex items-center justify-between gap-4 text-sm">
                                        <span class="truncate font-medium text-gray-700 dark:text-gray-200">{{ $item->supplier?->name ?? 'Supplier tidak tersedia' }}</span>
                                        <span class="shrink-0 text-gray-500 dark:text-gray-400">{{ $item->transaction_count }} transaksi</span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Rp {{ number_format((float) $item->total_value, 0, ',', '.') }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-400">Belum ada data supplier.</p>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Nilai Procurement</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pisahkan nilai barang yang sudah diterima dan draft yang masih tertunda.</p>
                        </div>
                        <span class="rounded-full bg-[#A8F23A]/20 px-2.5 py-1 text-[10px] font-bold text-gray-800 dark:text-[#A8F23A]">LIVE</span>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-xl bg-[#A8F23A]/10 p-4">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-[#4d6800] dark:text-[#A8F23A]">Received hari ini</p>
                            <p class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">Rp {{ number_format($receivedValueToday, 0, ',', '.') }}</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $receivedToday }} transaksi</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-700/50">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-gray-500 dark:text-gray-400">Nilai draft</p>
                            <p class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">Rp {{ number_format($draftPurchaseValue, 0, ',', '.') }}</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $draftPurchases }} transaksi menunggu proses</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Alur Procurement</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Prioritas kerja berdasarkan status transaksi saat ini.</p>

                    <div class="mt-5 space-y-3">
                        @foreach ([
                            ['label' => 'Draft', 'value' => $statusSummary['draft'], 'tone' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-300'],
                            ['label' => 'Received', 'value' => $statusSummary['received'], 'tone' => 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300'],
                            ['label' => 'Cancelled', 'value' => $statusSummary['cancelled'], 'tone' => 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300'],
                        ] as $step)
                            <div class="flex items-center justify-between rounded-xl border border-gray-100 px-4 py-3 dark:border-gray-700">
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $step['label'] }}</span>
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $step['tone'] }}">{{ number_format($step['value'], 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">Tren Procurement</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nilai pembelian berstatus received dalam 6 bulan terakhir.</p>
                        </div>
                        <span class="text-xs font-medium text-gray-400 dark:text-gray-500">6 bulan</span>
                    </div>
                    <div class="mt-6 flex h-40 items-end gap-3">
                        @php($maxTrend = max((float) $procurementTrend->max('value'), 1))
                        @foreach ($procurementTrend as $point)
                            @php($height = max(8, (int) (($point['value'] / $maxTrend) * 100)))
                            <div class="flex min-w-0 flex-1 flex-col items-center justify-end gap-2">
                                <span class="text-[10px] font-medium text-gray-400 dark:text-gray-500">Rp {{ number_format($point['value'] / 1000, 0, ',', '.') }}k</span>
                                <div class="w-full rounded-t-lg bg-[#A8F23A]/25 dark:bg-[#A8F23A]/15" style="height: {{ $height }}%"></div>
                                <span class="text-[10px] font-semibold text-gray-500 dark:text-gray-400">{{ $point['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Akses Cepat</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Jalur kerja utama purchasing.</p>
                    </div>
                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <a href="{{ route('admin.monitoring.pembelian') }}" class="rounded-xl border border-gray-200 p-4 transition hover:border-[#A8F23A] hover:bg-[#A8F23A]/10 dark:border-gray-700">
                            <p class="font-semibold text-gray-900 dark:text-white">Monitoring Pembelian</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pantau status dan nilai pembelian.</p>
                        </a>
                        <a href="{{ route('admin.monitoring.pembelian') }}" class="rounded-xl border border-gray-200 p-4 transition hover:border-[#A8F23A] hover:bg-[#A8F23A]/10 dark:border-gray-700">
                            <p class="font-semibold text-gray-900 dark:text-white">Riwayat Pembelian</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Cari dan pantau transaksi pembelian.</p>
                        </a>
                        <a href="{{ route('admin.monitoring.pembelian') }}" class="rounded-xl border border-gray-200 p-4 transition hover:border-[#A8F23A] hover:bg-[#A8F23A]/10 dark:border-gray-700">
                            <p class="font-semibold text-gray-900 dark:text-white">Monitoring</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Lihat status dan nilai pembelian.</p>
                        </a>
                        <a href="{{ route('admin.monitoring.pembelian') }}" class="rounded-xl border border-gray-200 p-4 transition hover:border-[#A8F23A] hover:bg-[#A8F23A]/10 dark:border-gray-700">
                            <p class="font-semibold text-gray-900 dark:text-white">Supplier</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Lihat directory supplier aktif.</p>
                        </a>
                    </div>
                </section>
            </div>

            <div class="rounded-2xl border border-[#A8F23A]/40 bg-[#A8F23A]/10 p-5 dark:bg-[#A8F23A]/5">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Fokus Operasional</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Prioritaskan {{ $draftPurchases }} transaksi draft yang masih menunggu proses.</p>
                    </div>
                    <a href="{{ route('purchasing.purchases.index') }}" class="inline-flex w-fit rounded-full bg-[#A8F23A] px-3 py-1.5 text-xs font-semibold text-gray-900 transition hover:opacity-80">Buka Purchase Order</a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
