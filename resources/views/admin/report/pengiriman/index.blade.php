<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Report</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Laporan Pengiriman</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Ringkasan order dan status pengiriman berdasarkan periode</p>
        </div>
        <button type="button" onclick="window.print()"
            class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 9V3h12v6"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v7H6z"/>
            </svg>
            Cetak
        </button>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-4">
        <div class="flex flex-wrap items-end gap-3">
            <div class="min-w-[150px]">
                <label class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Dari</label>
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}"
                    class="mt-1.5 w-full h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>
            <div class="min-w-[150px]">
                <label class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Sampai</label>
                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}"
                    class="mt-1.5 w-full h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>
            <div>
                <button type="submit"
                    class="h-10 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>
                    </svg>
                    Filter
                </button>
            </div>
        </div>
    </form>

    {{-- SUMMARY --}}
    @php
        $kpis = [
            ['label' => 'Total Pengiriman',  'value' => number_format($totalShipments, 0, ',', '.'),      'hint' => 'semua order',         'icon' => 'truck',  'color' => 'blue'],
            ['label' => 'Sudah Ditugaskan',  'value' => number_format($assignedShipments, 0, ',', '.'),   'hint' => 'ada kurir',            'icon' => 'user',   'color' => 'purple'],
            ['label' => 'Selesai',           'value' => number_format($statusSummary['delivered'], 0, ',', '.'), 'hint' => 'terkirim',       'icon' => 'check',  'color' => 'lime'],
            ['label' => 'Nilai Order Aktif', 'value' => 'Rp ' . number_format($shippingValue, 0, ',', '.'),'hint' => 'order berjalan',       'icon' => 'money',  'color' => 'amber'],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @foreach ($kpis as $kpi)
            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">{{ $kpi['label'] }}</p>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center
                        {{ $kpi['color'] === 'blue' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                        {{ $kpi['color'] === 'purple' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400' : '' }}
                        {{ $kpi['color'] === 'lime' ? 'bg-lime-100 dark:bg-lime-900/30 text-lime-600 dark:text-lime-400' : '' }}
                        {{ $kpi['color'] === 'amber' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : '' }}">
                        @if($kpi['icon'] === 'truck')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="1" y="3" width="15" height="13"/>
                                <path d="M16 8h4l3 3v5h-7V8z"/>
                                <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                            </svg>
                        @elseif($kpi['icon'] === 'user')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        @elseif($kpi['icon'] === 'check')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        @elseif($kpi['icon'] === 'money')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        @endif
                    </div>
                </div>
                <p class="text-lg font-bold text-[#171719] dark:text-white truncate">{{ $kpi['value'] }}</p>
                <p class="mt-1 text-[10px] text-black/45 dark:text-white/45">{{ $kpi['hint'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- STATUS BREAKDOWN --}}
    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
        <h2 class="text-sm font-bold text-[#171719] dark:text-white mb-4">Distribusi Status Pengiriman</h2>
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            @foreach (['pending' => ['Menunggu', 'amber'], 'processing' => ['Diproses', 'purple'], 'shipped' => ['Dikirim', 'blue'], 'delivered' => ['Selesai', 'lime'], 'cancelled' => ['Dibatalkan', 'red']] as $key => [$label, $color])
                <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-3">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-2 h-2 rounded-full
                            {{ $color === 'amber' ? 'bg-amber-500' : '' }}
                            {{ $color === 'purple' ? 'bg-purple-500' : '' }}
                            {{ $color === 'blue' ? 'bg-blue-500' : '' }}
                            {{ $color === 'lime' ? 'bg-lime-500' : '' }}
                            {{ $color === 'red' ? 'bg-red-500' : '' }}"></div>
                        <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">{{ $label }}</p>
                    </div>
                    <p class="text-xl font-bold text-[#171719] dark:text-white">{{ number_format($statusSummary[$key] ?? 0, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- TABLE --}}
    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">
        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
            <h2 class="text-sm font-bold text-[#171719] dark:text-white">Daftar Pengiriman</h2>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">{{ $totalShipments }} order ditemukan</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Order</th>
                        <th class="px-5 py-3.5 font-medium">Customer</th>
                        <th class="px-5 py-3.5 font-medium">Kurir</th>
                        <th class="px-5 py-3.5 font-medium">Tanggal</th>
                        <th class="px-5 py-3.5 text-center font-medium">Status</th>
                        <th class="px-5 py-3.5 text-right font-medium">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse($orders ?? [] as $order)
                        @php
                            $statusColor = match($order->status ?? '') {
                                'delivered' => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                'shipped'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'processing'=> 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                default     => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <td class="px-5 py-4">
                                <p class="text-xs font-semibold text-[#171719] dark:text-white font-mono">{{ $order->order_number }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70">{{ $order->customer?->name ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-4">
                                @if($order->courier?->name)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-lime-400 flex items-center justify-center text-[10px] font-bold text-black shrink-0">
                                            {{ strtoupper(substr($order->courier->name, 0, 1)) }}
                                        </div>
                                        <span class="text-xs text-black/70 dark:text-white/70">{{ $order->courier->name }}</span>
                                    </div>
                                @else
                                    <span class="text-[11px] italic text-black/40 dark:text-white/40">Belum ditugaskan</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70 font-mono">{{ $order->order_date?->format('d/m/Y H:i') }}</p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <p class="text-xs font-bold text-[#171719] dark:text-white">
                                    Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                                </p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Tidak ada order pada periode ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($orders) && $orders instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $orders->hasPages())
            <div class="border-t border-black/5 dark:border-white/10 px-5 py-4">
                {{ $orders->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    @media print { nav, aside, button, form { display: none !important; } body { background: white !important; } }
</style>
</x-app-layout>
