<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Monitoring</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Produk</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Kelola katalog, harga, stok, dan status produk</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
                {{ $productCount ?? 0 }} produk
            </span>
            <a href="{{ url('/admin/products/create') }}"
               class="h-10 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14"/><path d="M5 12h14"/>
                </svg>
                Tambah Produk
            </a>
        </div>
    </div>

    {{-- ================= SUMMARY CARDS ================= --}}
    @php
        $productCollection = isset($products)
            ? collect($products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $products->items() : $products)
            : collect();

        $productCount = isset($products) && $products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
            ? $products->total()
            : $productCollection->count();

        $activeProducts = $productCollection->where('is_active', 1)->count();
        $inactiveProducts = $productCollection->where('is_active', 0)->count();
        $lowStock = $productCollection->filter(fn ($product) => ($product->stock ?? 0) <= 5)->count();

        $kpis = [
            ['label' => 'Total Produk',  'value' => number_format($productCount, 0, ',', '.'),    'hint' => 'semua produk',      'icon' => 'box',      'color' => 'blue'],
            ['label' => 'Produk Aktif',  'value' => number_format($activeProducts, 0, ',', '.'),  'hint' => 'siap dijual',       'icon' => 'check',    'color' => 'lime'],
            ['label' => 'Stok Rendah',   'value' => number_format($lowStock, 0, ',', '.'),        'hint' => '≤ 5 unit',          'icon' => 'alert',    'color' => 'amber'],
            ['label' => 'Nonaktif',      'value' => number_format($inactiveProducts, 0, ',', '.'),'hint' => 'tidak dijual',      'icon' => 'x',        'color' => 'red'],
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
                        @if($kpi['icon'] === 'box')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                <path d="m3.27 6.96 8.73 5.05 8.73-5.05"/><path d="M12 22.08V12"/>
                            </svg>
                        @elseif($kpi['icon'] === 'check')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        @elseif($kpi['icon'] === 'alert')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 9v4"/><path d="M12 17h.01"/>
                                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            </svg>
                        @elseif($kpi['icon'] === 'x')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                            </svg>
                        @endif
                    </div>
                </div>
                <p class="text-xl font-bold text-[#171719] dark:text-white">{{ $kpi['value'] }}</p>
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
                    placeholder="Cari SKU atau nama produk..."
                    class="w-full h-10 pl-10 pr-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>

            {{-- CATEGORY --}}
            <select name="category_id"
                class="h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white min-w-[180px]">
                <option value="">Semua Kategori</option>
                @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
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

                @if(request()->anyFilled(['search', 'category_id']))
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
                <h2 class="text-sm font-bold text-[#171719] dark:text-white">Daftar Produk</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">{{ $productCount }} produk ditemukan</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Produk</th>
                        <th class="px-5 py-3.5 font-medium">Kategori</th>
                        <th class="px-5 py-3.5 text-right font-medium">Harga</th>
                        <th class="px-5 py-3.5 text-center font-medium">Stok</th>
                        <th class="px-5 py-3.5 text-center font-medium">Status</th>
                        <th class="px-5 py-3.5 text-center font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse ($products ?? [] as $product)
                        @php
                            $stock = $product->stock ?? 0;
                            $lowStockColor = $stock <= 5
                                ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                                : 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400';

                            $statusColor = $product->is_active
                                ? 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400'
                                : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';

                            $statusLabel = $product->is_active ? 'Aktif' : 'Nonaktif';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">

                            {{-- PRODUK --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-lime-100 to-lime-50 dark:from-lime-900/30 dark:to-lime-900/10 flex items-center justify-center shrink-0">
                                        <span class="text-lg font-black text-lime-600 dark:text-lime-400">
                                            {{ strtoupper(substr($product->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-[#171719] dark:text-white truncate max-w-[220px]">
                                            {{ $product->name }}
                                        </p>
                                        <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 font-mono">
                                            {{ $product->sku }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- KATEGORI --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2 py-1 rounded-md bg-gray-100 dark:bg-white/10 text-[10px] font-medium text-black/60 dark:text-white/60">
                                    {{ optional($product->category)->name ?? 'Umum' }}
                                </span>
                            </td>

                            {{-- HARGA --}}
                            <td class="px-5 py-4 text-right">
                                <p class="text-xs font-bold text-lime-600 dark:text-lime-400">
                                    Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}
                                </p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5">
                                    / {{ $product->unit ?? 'pcs' }}
                                </p>
                            </td>

                            {{-- STOK --}}
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold {{ $lowStockColor }}">
                                    {{ $stock }} {{ $product->unit ?? 'pcs' }}
                                </span>
                            </td>

                            {{-- STATUS --}}
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ url('/admin/products/'.$product->id.'/edit') }}"
                                       class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 hover:bg-blue-200 dark:hover:bg-blue-900/50 flex items-center justify-center transition"
                                       title="Edit">
                                        <svg class="w-3.5 h-3.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="m4 20 4-1 10-10-3-3L5 16l-1 4Z"/>
                                            <path d="m13 7 3 3"/>
                                        </svg>
                                    </a>

                                    <a href="{{ url('/admin/products/'.$product->id) }}"
                                       class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 flex items-center justify-center transition"
                                       title="Detail">
                                        <svg class="w-3.5 h-3.5 text-[#171719] dark:text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada produk</p>
                                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Yuk tambahkan produk pertama Anda</p>
                                <a href="{{ url('/admin/products/create') }}"
                                   class="inline-flex items-center gap-1.5 mt-4 h-9 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-medium transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M12 5v14"/><path d="M5 12h14"/>
                                    </svg>
                                    Tambah Produk
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (isset($products) && $products instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $products->hasPages())
            <div class="border-t border-black/5 dark:border-white/10 px-5 py-4">
                {{ $products->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
