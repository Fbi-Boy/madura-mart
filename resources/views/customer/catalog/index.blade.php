<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Belanja</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Katalog Produk</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Temukan produk aktif yang tersedia di Madura Mart</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('customer.cart.index') }}"
               class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition relative">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
                Keranjang
            </a>
        </div>
    </div>

    {{-- ================= SEARCH + FILTER ================= --}}
    <form method="GET" action="{{ route('customer.catalog.index') }}"
          class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-4">

        {{-- SEARCH BAR (full width di atas) --}}
        <div class="relative mb-3">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
            </svg>
            <input type="search" name="q" value="{{ $search }}"
                placeholder="Cari nama, SKU, atau deskripsi produk..."
                class="w-full h-12 pl-11 pr-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
        </div>

        {{-- FILTER ROW --}}
        <div class="flex flex-wrap items-center gap-2">

            {{-- KATEGORI --}}
            <select name="category"
                class="h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $item)
                    <option value="{{ $item->slug }}" @selected($category === $item->slug)>{{ $item->name }}</option>
                @endforeach
            </select>

            {{-- HARGA MIN --}}
            <input type="number" name="min_price" value="{{ $minPrice !== null ? $minPrice : '' }}" min="0" step="1000" placeholder="Harga min"
                class="no-spinner w-32 h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">

            {{-- HARGA MAX --}}
            <input type="number" name="max_price" value="{{ $maxPrice !== null ? $maxPrice : '' }}" min="0" step="1000" placeholder="Harga max"
                class="no-spinner w-32 h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">

            {{-- SORT --}}
            <select name="sort"
                class="h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
                <option value="newest" @selected($sort === 'newest')>Terbaru</option>
                <option value="price_asc" @selected($sort === 'price_asc')>Harga Terendah</option>
                <option value="price_desc" @selected($sort === 'price_desc')>Harga Tertinggi</option>
                <option value="name_asc" @selected($sort === 'name_asc')>Nama A–Z</option>
            </select>

            {{-- SUBMIT --}}
            <button type="submit"
                class="h-10 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>
                </svg>
                Cari
            </button>

            {{-- RESET --}}
            @if ($search || $category || $sort !== 'newest' || $minPrice !== null || $maxPrice !== null)
                <a href="{{ route('customer.catalog.index') }}"
                   class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                    </svg>
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- ================= INFO BAR ================= --}}
    <div class="flex items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-[#171719] dark:text-white">Produk Tersedia</p>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                <span class="font-bold text-lime-600 dark:text-lime-400">{{ $products->total() }}</span> produk ditemukan
            </p>
        </div>
    </div>

    {{-- ================= PRODUCT GRID ================= --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse ($products as $product)
            <a href="{{ route('customer.catalog.show', $product->slug) }}"
               class="group rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden hover:border-lime-400 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">

                {{-- PRODUCT IMAGE / THUMBNAIL --}}
                <div class="relative aspect-square bg-gradient-to-br from-lime-100 to-lime-50 dark:from-lime-900/20 dark:to-lime-900/5 flex items-center justify-center overflow-hidden">
                    <span class="text-6xl font-black text-lime-600 dark:text-lime-500 opacity-80 group-hover:scale-110 transition-transform duration-300">
                        {{ strtoupper(substr($product->name, 0, 1)) }}
                    </span>

                    {{-- STOK BADGE --}}
                    @if($product->stock === 0)
                        <span class="absolute top-2 right-2 px-2 py-0.5 rounded-md bg-red-500 text-white text-[10px] font-bold uppercase">
                            Habis
                        </span>
                    @elseif($product->stock <= 10)
                        <span class="absolute top-2 right-2 px-2 py-0.5 rounded-md bg-amber-500 text-white text-[10px] font-bold uppercase">
                            Stok {{ $product->stock }}
                        </span>
                    @endif

                    {{-- KATEGORI BADGE --}}
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-white/80 dark:bg-black/40 backdrop-blur-sm text-[10px] font-medium text-black/60 dark:text-white/60">
                        {{ $product->category?->name ?? 'Umum' }}
                    </span>
                </div>

                {{-- PRODUCT INFO --}}
                <div class="p-3">
                    <h3 class="text-xs font-semibold text-[#171719] dark:text-white leading-snug line-clamp-2 min-h-[2.5em] group-hover:text-lime-600 dark:group-hover:text-lime-400 transition">
                        {{ $product->name }}
                    </h3>

                    <p class="mt-1 text-[10px] text-black/45 dark:text-white/45 font-mono truncate">
                        {{ $product->sku }}
                    </p>

                    <div class="mt-3 flex items-end justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-base font-bold text-lime-600 dark:text-lime-400 truncate">
                                Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-[10px] text-black/45 dark:text-white/45 truncate">
                                Stok: {{ $product->stock }} {{ $product->unit }}
                            </p>
                        </div>

                        <div class="shrink-0 w-8 h-8 rounded-lg bg-lime-400 group-hover:bg-lime-500 flex items-center justify-center transition">
                            <svg class="w-4 h-4 text-[#171719]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M12 5v14"/><path d="M5 12h14"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-2 lg:col-span-3 xl:col-span-4 rounded-2xl border border-dashed border-black/10 dark:border-white/10 p-16 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                </svg>
                <p class="text-base font-semibold text-[#171719] dark:text-white">Produk tidak ditemukan</p>
                <p class="mt-1 text-xs text-black/45 dark:text-white/45">Coba ubah kata kunci, kategori, atau range harga</p>
                <a href="{{ route('customer.catalog.index') }}"
                   class="inline-flex items-center gap-1.5 mt-5 h-10 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-bold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                        <path d="M21 3v5h-5"/>
                    </svg>
                    Reset Filter
                </a>
            </div>
        @endforelse
    </div>

    {{-- ================= PAGINATION ================= --}}
    @if ($products->hasPages())
        <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 px-5 py-4">
            {{ $products->links() }}
        </div>
    @endif

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .no-spinner { -moz-appearance: textfield; appearance: textfield; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
</x-app-layout>
