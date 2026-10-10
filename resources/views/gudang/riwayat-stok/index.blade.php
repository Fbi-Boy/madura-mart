<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Gudang</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Riwayat Stok</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                Ledger pergerakan stok dari penerimaan, penjualan, retur, dan stock opname
            </p>
        </div>

        <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
            {{ $movements->total() }} catatan
        </span>
    </div>

    {{-- ================= FILTER ================= --}}
    <form method="GET" class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-4">
        <div class="flex flex-wrap items-center gap-3">

            {{-- SEARCH --}}
            <div class="relative flex-1 min-w-[220px]">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari produk / SKU..."
                    class="w-full h-10 pl-10 pr-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>

            {{-- TYPE --}}
            <select name="type"
                class="h-10 px-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
                <option value="">Semua Aktivitas</option>
                @foreach (['purchase_receipt' => 'Penerimaan', 'sale' => 'Penjualan', 'return' => 'Retur', 'adjustment' => 'Penyesuaian'] as $key => $label)
                    <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                @endforeach
            </select>

            {{-- DATE --}}
            <input type="date" name="date" value="{{ request('date') }}"
                class="h-10 px-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">

            {{-- SUBMIT --}}
            <button type="submit"
                class="h-10 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>
                </svg>
                Filter
            </button>

            {{-- RESET --}}
            @if(request()->anyFilled(['search', 'type', 'date']))
                <a href="{{ route('gudang.riwayat-stok.index') }}"
                   class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- ================= SUMMARY CARDS ================= --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        {{-- MASUK --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Total Masuk</p>
                <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 19V5"/><path d="m5 12 7-7 7 7"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-lime-600 dark:text-lime-400">
                +{{ number_format($summary['inbound'] ?? 0, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">unit</p>
        </div>

        {{-- KELUAR --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Total Keluar</p>
                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-red-600 dark:text-red-400">
                −{{ number_format($summary['outbound'] ?? 0, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">unit</p>
        </div>

        {{-- PENYESUAIAN --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Penyesuaian</p>
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-amber-600 dark:text-amber-400">
                {{ number_format($summary['adjustment'] ?? 0, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">unit</p>
        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Aktivitas Stok</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                    {{ $movements->total() }} catatan pergerakan
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Waktu</th>
                        <th class="px-5 py-3.5 font-medium">Produk</th>
                        <th class="px-5 py-3.5 font-medium">Aktivitas</th>
                        <th class="px-5 py-3.5 text-center font-medium">Qty</th>
                        <th class="px-5 py-3.5 font-medium">Petugas</th>
                        <th class="px-5 py-3.5 font-medium">Referensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse ($movements as $movement)
                        @php
                            $typeLabel = match($movement->type) {
                                'purchase_receipt' => 'Penerimaan',
                                'sale'             => 'Penjualan',
                                'return'           => 'Retur',
                                'adjustment'       => 'Penyesuaian',
                                default            => ucfirst($movement->type),
                            };
                            $typeColor = match($movement->type) {
                                'purchase_receipt' => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                'sale'             => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'return'           => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                'adjustment'       => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                default            => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
                            };
                            $isInbound = $movement->quantity >= 0;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">

                            {{-- WAKTU --}}
                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70 font-mono">
                                    {{ $movement->occurred_at->format('d/m/Y') }}
                                </p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 font-mono">
                                    {{ $movement->occurred_at->format('H:i') }}
                                </p>
                            </td>

                            {{-- PRODUK --}}
                            <td class="px-5 py-4">
                                <p class="text-xs font-semibold text-[#171719] dark:text-white truncate max-w-[200px]">
                                    {{ $movement->product?->name ?? '-' }}
                                </p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 font-mono mt-0.5">
                                    {{ $movement->product?->sku ?? '-' }}
                                </p>
                            </td>

                            {{-- AKTIVITAS --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $typeColor }}">
                                    {{ $typeLabel }}
                                </span>
                            </td>

                            {{-- QTY --}}
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg font-bold text-xs
                                    {{ $isInbound
                                        ? 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400'
                                        : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                    @if($isInbound)
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M12 19V5"/><path d="m5 12 7-7 7 7"/>
                                        </svg>
                                        +{{ number_format($movement->quantity, 0, ',', '.') }}
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>
                                        </svg>
                                        {{ number_format($movement->quantity, 0, ',', '.') }}
                                    @endif
                                </span>
                            </td>

                            {{-- PETUGAS --}}
                            <td class="px-5 py-4">
                                @if($movement->user)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-lime-400 flex items-center justify-center text-[10px] font-bold text-black shrink-0">
                                            {{ strtoupper(substr($movement->user->name, 0, 1)) }}
                                        </div>
                                        <span class="text-xs text-black/70 dark:text-white/70 truncate">
                                            {{ $movement->user->name }}
                                        </span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center shrink-0">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10"/>
                                                <path d="M12 16v-4"/><path d="M12 8h.01"/>
                                            </svg>
                                        </div>
                                        <span class="text-xs text-black/45 dark:text-white/45">Sistem</span>
                                    </div>
                                @endif
                            </td>

                            {{-- REFERENSI --}}
                            <td class="px-5 py-4">
                                @if($movement->reference_type)
                                    <p class="text-xs text-black/70 dark:text-white/70">
                                        {{ ucfirst(str_replace('_', ' ', $movement->reference_type)) }}
                                    </p>
                                    <p class="text-[10px] text-black/45 dark:text-white/45 font-mono mt-0.5">
                                        #{{ $movement->reference_id }}
                                    </p>
                                @else
                                    <span class="text-xs text-black/40 dark:text-white/40">—</span>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                                </svg>
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada riwayat pergerakan stok</p>
                                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Riwayat akan muncul setelah ada transaksi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($movements->hasPages())
            <div class="border-t border-black/5 dark:border-white/10 px-5 py-4">
                {{ $movements->links() }}
            </div>
        @endif
    </section>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
