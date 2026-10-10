<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Gudang</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Stock Opname</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Bandingkan stok sistem dengan stok fisik dan sesuaikan selisihnya</p>
        </div>

        <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
            {{ now()->translatedFormat('d F Y') }}
        </span>
    </div>

    {{-- ================= ALERT SUCCESS ================= --}}
    @if (session('success'))
        <div class="rounded-2xl border border-lime-200 bg-lime-50 dark:border-lime-500/20 dark:bg-lime-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-lime-600 dark:text-lime-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
            <p class="text-sm font-medium text-lime-800 dark:text-lime-300">{{ session('success') }}</p>
        </div>
    @endif

    {{-- ================= ALERT ERROR ================= --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 dark:border-red-500/20 dark:bg-red-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-red-800 dark:text-red-300">Periksa input stock opname</p>
                <ul class="mt-1 list-disc space-y-0.5 pl-5 text-xs text-red-700 dark:text-red-200">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- ================= SUMMARY CARDS ================= --}}
    @php
        $totalProducts = $products->count();
        $lowStock = $products->where('stock', '<=', 10)->count();
        $outOfStock = $products->where('stock', 0)->count();
        $totalUnits = $products->sum('stock');
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Produk Diperiksa</p>
                <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ number_format($totalProducts, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Produk aktif</p>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Total Unit</p>
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="7" width="20" height="14" rx="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ number_format($totalUnits, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Unit di sistem</p>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Stok Menipis</p>
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 9v4"/><path d="M12 17h.01"/>
                        <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ number_format($lowStock, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">≤ 10 unit tersisa</p>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Stok Habis</p>
                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ number_format($outOfStock, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Perlu segera diisi</p>
        </div>
    </div>

    {{-- ================= FORM STOCK OPNAME ================= --}}
    <form method="POST" action="{{ route('gudang.stock-opname.store') }}" class="space-y-5">
        @csrf

        <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">

            {{-- SECTION HEADER --}}
            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Pengecekan Stok Fisik</h2>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                        Masukkan jumlah fisik. Stok sistem jadi referensi sebelum penyesuaian.
                    </p>
                </div>

                {{-- SEARCH --}}
                <div class="relative hidden sm:block min-w-[220px]">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                    </svg>
                    <input type="text" id="searchOpname"
                        placeholder="Cari produk / SKU..."
                        class="w-full h-10 pl-10 pr-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
                </div>
            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left">
                    <thead class="border-b border-black/5 dark:border-white/10">
                        <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                            <th class="px-5 py-3.5 font-medium">Produk</th>
                            <th class="px-5 py-3.5 font-medium text-center">Stok Sistem</th>
                            <th class="px-5 py-3.5 font-medium text-center">Stok Fisik</th>
                            <th class="px-5 py-3.5 font-medium text-right">Selisih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/10">
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition opname-row"
                                data-search="{{ strtolower($product->name . ' ' . $product->sku) }}"
                                x-data="{ actual: {{ $product->stock }} }">

                                {{-- PRODUK --}}
                                <td class="px-5 py-4">
                                    <p class="text-xs font-semibold text-[#171719] dark:text-white truncate">{{ $product->name }}</p>
                                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45 font-mono">{{ $product->sku }} · {{ $product->unit }}</p>
                                </td>

                                {{-- STOK SISTEM --}}
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex px-3 py-1 rounded-lg bg-gray-100 dark:bg-white/10 text-xs font-bold text-[#171719] dark:text-white">
                                        {{ $product->stock }}
                                    </span>
                                </td>

                                {{-- STOK FISIK (INPUT) --}}
                                <td class="px-5 py-4 text-center">
                                    <input type="number" min="0"
                                        name="actual_stock[{{ $product->id }}]"
                                        x-model.number="actual"
                                        required
                                        class="no-spinner w-24 h-10 px-3 text-center rounded-xl border-2 border-black/10 dark:border-white/10 bg-transparent text-sm font-bold text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 dark:focus:border-lime-400 transition">
                                </td>

                                {{-- SELISIH --}}
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <template x-if="actual - {{ $product->stock }} === 0">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-white/10 text-[11px] font-bold text-black/50 dark:text-white/50">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path d="M5 12h14"/>
                                                </svg>
                                                0
                                            </span>
                                        </template>
                                        <template x-if="actual - {{ $product->stock }} > 0">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-lime-100 dark:bg-lime-900/30 text-[11px] font-bold text-lime-700 dark:text-lime-400">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path d="M12 19V5"/><path d="m5 12 7-7 7 7"/>
                                                </svg>
                                                +<span x-text="actual - {{ $product->stock }}"></span>
                                            </span>
                                        </template>
                                        <template x-if="actual - {{ $product->stock }} < 0">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-red-100 dark:bg-red-900/30 text-[11px] font-bold text-red-600 dark:text-red-400">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path d="M12 5v14"/><path d="m19 12-7 7-7-7"/>
                                                </svg>
                                                <span x-text="actual - {{ $product->stock }}"></span>
                                            </span>
                                        </template>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-16 text-center">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada produk aktif</p>
                                    <p class="mt-1 text-xs text-black/40 dark:text-white/40">Tambahkan produk dulu di menu Master Produk.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- NOTES + SUBMIT --}}
            @if($products->count() > 0)
            <div class="p-5 border-t border-black/5 dark:border-white/10">
                <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                    Catatan
                </label>
                <textarea name="notes" rows="3"
                    placeholder="Contoh: pengecekan rak A selesai, rak B sedang proses..."
                    class="mt-2 w-full rounded-xl border border-black/10 dark:border-white/10 bg-transparent px-4 py-3 text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 resize-none">{{ old('notes') }}</textarea>

                <div class="mt-4 flex items-center justify-between gap-3 flex-wrap">
                    <div class="flex items-center gap-2 text-[11px] text-black/45 dark:text-white/45">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                        </svg>
                        Stok sistem akan otomatis disesuaikan setelah disimpan.
                    </div>

                    <button type="submit"
                        class="h-11 px-6 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                        Simpan Stock Opname
                    </button>
                </div>
            </div>
            @endif
        </section>
    </form>

    {{-- ================= RIWAYAT STOCK OPNAME ================= --}}
    <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Riwayat Stock Opname</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">8 pemeriksaan terakhir</p>
            </div>
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-2.5 py-1 rounded-lg font-medium">
                {{ $recentOpnames->count() }} riwayat
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[620px] text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Tanggal</th>
                        <th class="px-5 py-3.5 font-medium">Petugas</th>
                        <th class="px-5 py-3.5 font-medium text-center">Item</th>
                        <th class="px-5 py-3.5 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse ($recentOpnames as $opname)
                        @php
                            $statusColor = match(strtolower($opname->status)) {
                                'completed', 'selesai' => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'cancelled', 'batal' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70 font-mono">
                                    {{ $opname->created_at?->format('d/m/Y') }}
                                </p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 font-mono">
                                    {{ $opname->created_at?->format('H:i') }}
                                </p>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-lime-400 flex items-center justify-center text-[10px] font-bold text-black shrink-0">
                                        {{ strtoupper(substr($opname->user?->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="text-xs text-black/70 dark:text-white/70">
                                        {{ $opname->user?->name ?? '-' }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-white/10 text-[11px] font-bold text-[#171719] dark:text-white">
                                    {{ $opname->items_count }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                    {{ $opname->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M9 11l3 3L22 4"/>
                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                                </svg>
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada riwayat stock opname</p>
                                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Riwayat akan muncul setelah Anda menyimpan pengecekan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('searchOpname');
        const rows = document.querySelectorAll('.opname-row');

        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                const q = e.target.value.toLowerCase().trim();
                rows.forEach(row => {
                    const match = !q || row.dataset.search.includes(q);
                    row.style.display = match ? '' : 'none';
                });
            });
        }
    });
</script>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .no-spinner { -moz-appearance: textfield; appearance: textfield; }
</style>
</x-app-layout>
