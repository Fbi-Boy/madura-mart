<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Monitoring</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Penjualan</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pantau seluruh transaksi penjualan dan pembayaran</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
                {{ now()->translatedFormat('d M Y') }}
            </span>
            <button type="button" onclick="window.location.reload()"
                class="h-10 w-10 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white flex items-center justify-center transition"
                title="Refresh">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M20 11a8.1 8.1 0 1 0 1 4"/><path d="M20 4v7h-7"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- ================= SUMMARY CARDS ================= --}}
    @php
        $salesCollection = isset($sales)
            ? collect($sales instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $sales->items() : $sales)
            : collect();

        $totalSales = $salesCollection->sum('total');
        $paidSales = $salesCollection->where('status', 'paid')->sum('total');
        $cancelledSales = $salesCollection->where('status', 'cancelled')->sum('total');
        $transactionCount = isset($sales)
            ? ($sales instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
                ? $sales->total()
                : $salesCollection->count())
            : 0;

        $kpis = [
            ['label' => 'Total Transaksi', 'value' => number_format($transactionCount, 0, ',', '.'), 'hint' => 'seluruh transaksi', 'icon' => 'receipt', 'color' => 'blue'],
            ['label' => 'Nilai Penjualan', 'value' => 'Rp ' . number_format($totalSales, 0, ',', '.'), 'hint' => 'total transaksi tampil', 'icon' => 'money', 'color' => 'lime'],
            ['label' => 'Penjualan Lunas', 'value' => 'Rp ' . number_format($paidSales, 0, ',', '.'), 'hint' => 'transaksi berhasil', 'icon' => 'check', 'color' => 'emerald'],
            ['label' => 'Dibatalkan', 'value' => 'Rp ' . number_format($cancelledSales, 0, ',', '.'), 'hint' => 'transaksi dibatalkan', 'icon' => 'x', 'color' => 'red'],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @foreach ($kpis as $kpi)
            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">{{ $kpi['label'] }}</p>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center
                        {{ $kpi['color'] === 'blue' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                        {{ $kpi['color'] === 'lime' ? 'bg-lime-100 dark:bg-lime-900/30 text-lime-600 dark:text-lime-400' : '' }}
                        {{ $kpi['color'] === 'emerald' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : '' }}
                        {{ $kpi['color'] === 'red' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : '' }}">
                        @if($kpi['icon'] === 'receipt')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/>
                            </svg>
                        @elseif($kpi['icon'] === 'money')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        @elseif($kpi['icon'] === 'check')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        @elseif($kpi['icon'] === 'x')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                            </svg>
                        @endif
                    </div>
                </div>
                <p class="text-lg font-bold text-[#171719] dark:text-white truncate">{{ $kpi['value'] }}</p>
                <p class="mt-1 text-[10px] text-black/45 dark:text-white/45">{{ $kpi['hint'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- ================= FILTER ================= --}}
    <form method="GET" class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-4">
        <div class="flex flex-wrap items-center gap-3">

            {{-- SEARCH --}}
            <div class="relative flex-1 min-w-[220px]">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari invoice atau pelanggan..."
                    class="w-full h-10 pl-10 pr-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>

            {{-- STATUS --}}
            <select name="status"
                class="h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white min-w-[150px]">
                <option value="">Semua Status</option>
                <option value="paid" @selected(request('status') === 'paid')>Paid</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
            </select>

            {{-- PAYMENT --}}
            <select name="payment_method"
                class="h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white min-w-[160px]">
                <option value="">Semua Pembayaran</option>
                <option value="cash" @selected(request('payment_method') === 'cash')>Cash</option>
                <option value="transfer" @selected(request('payment_method') === 'transfer')>Transfer</option>
                <option value="qris" @selected(request('payment_method') === 'qris')>QRIS</option>
                <option value="debit" @selected(request('payment_method') === 'debit')>Debit</option>
            </select>

            {{-- ACTIONS --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                    class="h-10 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>
                    </svg>
                    Filter
                </button>

                @if(request()->anyFilled(['search', 'status', 'payment_method']))
                    <a href="{{ url()->current() }}"
                       class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                        </svg>
                        Reset
                    </a>
                @endif
            </div>
        </div>
    </form>

    {{-- ================= TABLE ================= --}}
    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-bold text-[#171719] dark:text-white">Daftar Transaksi Penjualan</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                    {{ $transactionCount }} transaksi ditemukan
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Invoice</th>
                        <th class="px-5 py-3.5 font-medium">Customer</th>
                        <th class="px-5 py-3.5 font-medium">Tanggal</th>
                        <th class="px-5 py-3.5 text-right font-medium">Total</th>
                        <th class="px-5 py-3.5 text-center font-medium">Pembayaran</th>
                        <th class="px-5 py-3.5 text-center font-medium">Status</th>
                        <th class="px-5 py-3.5 text-center font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse ($sales ?? [] as $sale)
                        @php
                            $statusColor = match($sale->status ?? '') {
                                'paid'      => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                default     => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                            };

                            $payment = $sale->payment_method ?? '';
                            $paymentColor = match($payment) {
                                'cash'     => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                'transfer' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'qris'     => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                'debit'    => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                default    => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
                            };

                            $paymentLabel = match($payment) {
                                'cash'     => 'Cash',
                                'transfer' => 'Transfer',
                                'qris'     => 'QRIS',
                                'debit'    => 'Debit',
                                default    => '-',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">

                            {{-- INVOICE --}}
                            <td class="px-5 py-4">
                                <p class="text-xs font-semibold text-[#171719] dark:text-white font-mono">{{ $sale->invoice ?? '-' }}</p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 font-mono">#{{ $sale->id }}</p>
                            </td>

                            {{-- CUSTOMER --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-lime-400 flex items-center justify-center text-[10px] font-bold text-black shrink-0">
                                        {{ strtoupper(substr(optional($sale->customer)->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="text-xs text-black/70 dark:text-white/70 truncate">
                                        {{ optional($sale->customer)->name ?? 'Umum' }}
                                    </span>
                                </div>
                            </td>

                            {{-- TANGGAL --}}
                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70 font-mono">
                                    {{ optional($sale->sale_date)->format('d/m/Y') ?? '-' }}
                                </p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 font-mono">
                                    {{ optional($sale->sale_date)->format('H:i') ?? '' }}
                                </p>
                            </td>

                            {{-- TOTAL --}}
                            <td class="px-5 py-4 text-right">
                                <p class="text-xs font-bold text-[#171719] dark:text-white">
                                    Rp {{ number_format($sale->total ?? 0, 0, ',', '.') }}
                                </p>
                            </td>

                            {{-- PAYMENT --}}
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $paymentColor }}">
                                    {{ $paymentLabel }}
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $sale->status ?? 'Unknown' }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-5 py-4 text-center">
                                <button type="button" onclick="showSaleDetail({{ $sale->id }})"
                                    class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 text-blue-600 dark:text-blue-400 text-[11px] font-bold transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    Detail
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada transaksi penjualan</p>
                                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Data transaksi akan muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (isset($sales) && $sales instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $sales->hasPages())
            <div class="border-t border-black/5 dark:border-white/10 px-5 py-4">
                {{ $sales->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- ================= DETAIL MODAL ================= --}}
    <div id="saleDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur p-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-5 shadow-2xl"
             onclick="event.stopPropagation()">

            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <path d="M14 2v6h6"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#171719] dark:text-white">Detail Penjualan</h3>
                        <p class="text-[10px] text-black/45 dark:text-white/45">Informasi transaksi</p>
                    </div>
                </div>
                <button type="button" onclick="closeSaleDetail()"
                    class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>

            <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-4">
                <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">ID Transaksi</p>
                <p id="saleDetailId" class="mt-1 text-base font-bold text-[#171719] dark:text-white font-mono">-</p>
            </div>

        </div>
    </div>

</div>

<script>
    function showSaleDetail(id) {
        document.getElementById('saleDetailId').textContent = '#' + id;
        const modal = document.getElementById('saleDetailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeSaleDetail() {
        const modal = document.getElementById('saleDetailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('saleDetailModal')?.addEventListener('click', function () {
        closeSaleDetail();
    });
</script>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
