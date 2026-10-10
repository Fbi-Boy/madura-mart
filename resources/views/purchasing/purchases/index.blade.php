<x-app-layout>
<div class="space-y-5 font-poppins">

    @php
        $isPurchasing = auth()->user()->role === 'purchasing';
        $createRoute = $isPurchasing ? 'purchasing.purchases.create' : 'admin.purchases.create';
        $indexRoute  = $isPurchasing ? 'purchasing.purchases.index' : 'admin.purchases.index';
        $showRoute   = $isPurchasing ? 'purchasing.purchases.show' : 'admin.purchases.show';
        $submitRoute = 'purchasing.purchases.submit';
        $cancelRoute = 'purchasing.purchases.cancel';
    @endphp

    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Transaksi</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Pembelian</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                {{ $isPurchasing
                    ? 'Kelola purchase order dan siapkan pengadaan supplier'
                    : 'Catat barang masuk dari supplier dan pembaruan stok' }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
                {{ $purchases->total() }} PO
            </span>
            <a href="{{ route($createRoute) }}"
               class="h-10 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-medium flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14"/><path d="M5 12h14"/>
                </svg>
                {{ $isPurchasing ? 'Purchase Order' : 'Pembelian Baru' }}
            </a>
        </div>
    </div>

    {{-- ALERTS --}}
    @if (session('success'))
        <div class="rounded-2xl border border-lime-200 bg-lime-50 dark:border-lime-500/20 dark:bg-lime-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-lime-600 dark:text-lime-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
            <p class="text-sm font-medium text-lime-800 dark:text-lime-300">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 dark:border-red-500/20 dark:bg-red-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
        </div>
    @endif

    {{-- FILTER --}}
    <form method="GET" class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-4">
        <div class="flex flex-wrap items-end gap-3">

            <div class="flex-1 min-w-[220px]">
                <label class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Cari</label>
                <div class="relative mt-1.5">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                    </svg>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Invoice atau nama supplier..."
                        class="w-full h-10 pl-10 pr-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
                </div>
            </div>

            <div class="min-w-[160px]">
                <label class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Status</label>
                <select name="status"
                    class="mt-1.5 w-full h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                    <option value="submitted" @selected(request('status') === 'submitted')>Submitted</option>
                    <option value="received" @selected(request('status') === 'received')>Received</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </div>

            <div class="min-w-[140px]">
                <label class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Dari</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="mt-1.5 w-full h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>

            <div class="min-w-[140px]">
                <label class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Sampai</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="mt-1.5 w-full h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                    class="h-10 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>
                    </svg>
                    Filter
                </button>

                @if(request()->anyFilled(['q', 'status', 'date_from', 'date_to']))
                    <a href="{{ route($indexRoute) }}"
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

    {{-- TABLE --}}
    <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">
        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
            <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Daftar Pembelian</h2>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Semua purchase order dari supplier</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[860px] text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Invoice</th>
                        <th class="px-5 py-3.5 font-medium">Supplier</th>
                        <th class="px-5 py-3.5 font-medium">Tanggal</th>
                        <th class="px-5 py-3.5 font-medium">Input Oleh</th>
                        <th class="px-5 py-3.5 text-right font-medium">Total</th>
                        <th class="px-5 py-3.5 text-right font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse ($purchases as $purchase)
                        @php
                            $isSubmitted = $purchase->submitted_at !== null;
                            $statusKey = $isSubmitted && $purchase->status === 'draft' ? 'submitted' : $purchase->status;
                            $statusColor = match($statusKey) {
                                'received'  => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                'submitted' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                default     => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                            };
                            $statusLabel = match($statusKey) {
                                'received'  => 'Received',
                                'cancelled' => 'Cancelled',
                                'submitted' => 'Submitted',
                                default     => 'Draft',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <td class="px-5 py-4">
                                <a href="{{ route($showRoute, $purchase) }}"
                                   class="text-xs font-semibold text-[#171719] dark:text-white font-mono hover:text-lime-600 dark:hover:text-lime-400 transition">
                                    {{ $purchase->invoice }}
                                </a>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                        <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs text-black/70 dark:text-white/70 truncate">{{ $purchase->supplier?->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70 font-mono">{{ $purchase->purchase_date?->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-lime-400 flex items-center justify-center text-[10px] font-bold text-black shrink-0">
                                        {{ strtoupper(substr($purchase->user?->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="text-xs text-black/70 dark:text-white/70 truncate">{{ $purchase->user?->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <p class="text-xs font-bold text-[#171719] dark:text-white">
                                    Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}
                                </p>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>

                                    @if($isPurchasing && $purchase->status === 'draft' && !$isSubmitted)
                                        <form method="POST" action="{{ route($submitRoute, $purchase) }}" class="inline"
                                              onsubmit="return confirm('Kirim purchase order ini ke gudang?')">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="h-8 px-3 rounded-lg bg-lime-400 hover:bg-lime-500 text-[#171719] text-[11px] font-bold transition">
                                                Kirim
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route($cancelRoute, $purchase) }}" class="inline"
                                              onsubmit="return confirm('Batalkan purchase order ini?')">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="h-8 px-3 rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 text-red-600 dark:text-red-400 text-[11px] font-bold transition">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                    <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                                </svg>
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada transaksi pembelian</p>
                                <p class="mt-1 text-xs text-black/40 dark:text-white/40">PO akan muncul di sini setelah dibuat.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($purchases->hasPages())
            <div class="border-t border-black/5 dark:border-white/10 px-5 py-4">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
