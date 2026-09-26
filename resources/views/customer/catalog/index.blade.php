<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Katalog Produk</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Temukan produk aktif yang tersedia di Madura Mart.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex w-fit items-center rounded-xl border border-gray-200 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">Dashboard</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('customer.catalog.index') }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-[1fr_180px_140px_140px_180px_auto]">
                    <input name="q" value="{{ $search }}" type="search" placeholder="Cari nama, SKU, atau deskripsi..." class="w-full rounded-xl border-gray-300 text-sm focus:border-[#A8F23A] focus:ring-[#A8F23A] dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <select name="category" class="rounded-xl border-gray-300 text-sm focus:border-[#A8F23A] focus:ring-[#A8F23A] dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="">Semua kategori</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->slug }}" @selected($category === $item->slug)>{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <input name="min_price" value="{{ $minPrice !== null ? $minPrice : '' }}" type="number" min="0" step="1000" placeholder="Harga min" class="w-full rounded-xl border-gray-300 text-sm focus:border-[#A8F23A] focus:ring-[#A8F23A] dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <input name="max_price" value="{{ $maxPrice !== null ? $maxPrice : '' }}" type="number" min="0" step="1000" placeholder="Harga max" class="w-full rounded-xl border-gray-300 text-sm focus:border-[#A8F23A] focus:ring-[#A8F23A] dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <select name="sort" class="rounded-xl border-gray-300 text-sm focus:border-[#A8F23A] focus:ring-[#A8F23A] dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        <option value="newest" @selected($sort === 'newest')>Terbaru</option>
                        <option value="price_asc" @selected($sort === 'price_asc')>Harga terendah</option>
                        <option value="price_desc" @selected($sort === 'price_desc')>Harga tertinggi</option>
                        <option value="name_asc" @selected($sort === 'name_asc')>Nama A–Z</option>
                    </select>
                    <button class="rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-gray-700 dark:bg-[#A8F23A] dark:text-gray-900 dark:hover:bg-[#b8ff63]">Cari</button>
                </div>
            </form>

            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Produk Tersedia</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $products->total() }} produk ditemukan</p>
                </div>
                @if ($search || $category || $sort !== 'newest' || $minPrice !== null || $maxPrice !== null)
                    <a href="{{ route('customer.catalog.index') }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Reset filter</a>
                @endif
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($products as $product)
                    <a href="{{ route('customer.catalog.show', $product->slug) }}" class="group overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex h-36 items-center justify-center bg-gray-100 dark:bg-gray-900">
                            <span class="text-4xl font-bold text-gray-300 dark:text-gray-700">{{ strtoupper(substr($product->name, 0, 1)) }}</span>
                        </div>
                        <div class="p-4">
                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $product->category?->name ?? 'Tanpa kategori' }}</p>
                            <h4 class="mt-1 line-clamp-2 font-semibold text-gray-900 group-hover:text-gray-700 dark:text-white dark:group-hover:text-[#A8F23A]">{{ $product->name }}</h4>
                            <p class="mt-3 text-lg font-bold text-gray-900 dark:text-white">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Stok {{ $product->stock }} {{ $product->unit }}</p>
                        </div>
                    </a>
                @empty
                    <div class="sm:col-span-2 lg:col-span-3 xl:col-span-4 rounded-2xl border border-dashed border-gray-300 p-10 text-center dark:border-gray-700">
                        <p class="font-semibold text-gray-900 dark:text-white">Produk tidak ditemukan</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Coba ubah kata kunci atau kategori.</p>
                    </div>
                @endforelse
            </div>

            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
