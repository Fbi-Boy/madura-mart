<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Monitoring</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Pembelian</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pantau transaksi pembelian barang dan penerimaan stok</p>
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
        $purchasesCollection = isset($purchases)
            ? collect($purchases instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $purchases->items() : $purchases)
            : collect();

        $totalPurchases     = $purchasesCollection->sum('total');
        $receivedPurchases  = $purchasesCollection->where('status', 'received')->sum('total');
        $draftPurchases     = $purchasesCollection->where('status', 'draft')->sum('total');
        $cancelledPurchases = $purchasesCollection->where('status', 'cancelled')->sum('total');

        $purchaseCount = isset($purchases)
            ? ($purchases instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
                ? $purchases->total()
                : $purchasesCollection->count())
            : 0;

        $kpis = [
            ['label' => 'Total Pembelian', 'value' => 'Rp ' . number_format($totalPurchases, 0, ',', '.'),     'hint' => 'seluruh transaksi',    'icon' => 'cart',    'color' => 'blue'],
            ['label' => 'Barang Diterima', 'value' => 'Rp ' . number_format($receivedPurchases, 0, ',', '.'),  'hint' => 'pembelian diterima',   'icon' => 'check',   'color' => 'lime'],
            ['label' => 'Draft',           'value' => 'Rp ' . number_format($draftPurchases, 0, ',', '.'),     'hint' => 'belum diterima',       'icon' => 'clock',   'color' => 'amber'],
            ['label' => 'Dibatalkan',      'value' => 'Rp ' . number_format($cancelledPurchases, 0, ',', '.'),'hint' => 'pembelian dibatalkan', 'icon' => 'x',       'color' => 'red'],
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
                        {{ $kpi['color'] === 'amber' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : '' }}
                        {{ $kpi['color'] === 'red' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : '' }}">
                        @if($kpi['icon'] === 'cart')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        @elseif($kpi['icon'] === 'check')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        @elseif($kpi['icon'] === 'clock')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
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
                    placeholder="Cari invoice atau supplier..."
                    class="w-full h-10 pl-10 pr-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>

            {{-- STATUS --}}
            <select name="status"
                class="h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white min-w-[150px]">
                <option value="">Semua Status</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="received" @selected(request('status') === 'received')>Received</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
            </select>

            {{-- DATE --}}
            <input type="date" name="purchase_date" value="{{ request('purchase_date') }}"
                class="h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">

            {{-- ACTIONS --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                    class="h-10 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>
                    </svg>
                    Filter
                </button>

                @if(request()->anyFilled(['search', 'status', 'purchase_date']))
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
                <h2 class="text-sm font-bold text-[#171719] dark:text-white">Daftar Pembelian</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">{{ $purchaseCount }} transaksi ditemukan</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Invoice</th>
                        <th class="px-5 py-3.5 font-medium">Supplier</th>
                        <th class="px-5 py-3.5 font-medium">Tanggal</th>
                        <th class="px-5 py-3.5 text-right font-medium">Total</th>
                        <th class="px-5 py-3.5 text-center font-medium">Status</th>
                        <th class="px-5 py-3.5 text-center font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse ($purchases ?? [] as $purchase)
                        @php
                            $status = $purchase->status ?? '';
                            $statusColor = match($status) {
                                'received'  => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                'draft'     => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                default     => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
                            };

                            $supplierInitial = strtoupper(substr(optional($purchase->supplier)->name ?? 'S', 0, 1));
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">

                            {{-- INVOICE --}}
                            <td class="px-5 py-4">
                                <p class="text-xs font-semibold text-[#171719] dark:text-white font-mono">{{ $purchase->invoice ?? '-' }}</p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 font-mono">#{{ $purchase->id }}</p>
                            </td>

                            {{-- SUPPLIER --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-400 flex items-center justify-center text-[10px] font-bold text-white shrink-0">
                                        {{ $supplierInitial }}
                                    </div>
                                    <span class="text-xs text-black/70 dark:text-white/70 truncate">
                                        {{ optional($purchase->supplier)->name ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            {{-- DATE --}}
                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70 font-mono">
                                    {{ optional($purchase->purchase_date)->format('d/m/Y') ?? '-' }}
                                </p>
                            </td>

                            {{-- TOTAL --}}
                            <td class="px-5 py-4 text-right">
                                <p class="text-xs font-bold text-[#171719] dark:text-white">
                                    Rp {{ number_format($purchase->total ?? 0, 0, ',', '.') }}
                                </p>
                            </td>

                            {{-- STATUS --}}
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $status ?: 'Unknown' }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-5 py-4 text-center">
                                <button type="button" onclick="showPurchaseDetail({{ $purchase->id }})"
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
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                        <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada transaksi pembelian</p>
                                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Data pembelian akan muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (isset($purchases) && $purchases instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $purchases->hasPages())
            <div class="border-t border-black/5 dark:border-white/10 px-5 py-4">
                {{ $purchases->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- ================= DETAIL MODAL ================= --}}
    <div id="purchaseDetailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur p-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl w-full max-w-md p-5 shadow-2xl"
             onclick="event.stopPropagation()">

            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                            <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-[#171719] dark:text-white">Detail Pembelian</h3>
                        <p class="text-[10px] text-black/45 dark:text-white/45">Informasi transaksi pembelian</p>
                    </div>
                </div>
                <button type="button" onclick="closePurchaseDetail()"
                    class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white flex items-center justify-center transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                </button>
            </div>

            <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-4">
                <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">ID Transaksi</p>
                <p id="purchaseDetailId" class="mt-1 text-base font-bold text-[#171719] dark:text-white font-mono">-</p>
            </div>

        </div>
    </div>

</div>

<script>
    function showPurchaseDetail(id) {
        document.getElementById('purchaseDetailId').textContent = '#' + id;
        const modal = document.getElementById('purchaseDetailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closePurchaseDetail() {
        const modal = document.getElementById('purchaseDetailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('purchaseDetailModal')?.addEventListener('click', function () {
        closePurchaseDetail();
    });
</script>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
