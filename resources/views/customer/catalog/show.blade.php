<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Detail Produk</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->category?->name ?? 'Produk Madura Mart' }}</p>
            </div>
            <a href="{{ route('customer.catalog.index') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="grid overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800 lg:grid-cols-2">
                <div class="flex min-h-[360px] items-center justify-center bg-gray-100 dark:bg-gray-900">
                    <span class="text-8xl font-bold text-gray-300 dark:text-gray-700">{{ strtoupper(substr($product->name, 0, 1)) }}</span>
                </div>
                <div class="p-6 sm:p-8">
                    <span class="rounded-full bg-[#A8F23A]/20 px-3 py-1 text-xs font-semibold text-gray-800 dark:text-[#A8F23A]">{{ $product->category?->name ?? 'Tanpa kategori' }}</span>
                    <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $product->name }}</h1>
                    <p class="mt-2 text-xs text-gray-400">SKU: {{ $product->sku }}</p>
                    <p class="mt-6 text-3xl font-bold text-gray-900 dark:text-white">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                    <div class="mt-5 rounded-2xl bg-gray-50 p-4 dark:bg-gray-700/50">
                        <p class="text-xs uppercase tracking-wider text-gray-400">Ketersediaan</p>
                        <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $product->stock }} {{ $product->unit }} tersedia</p>
                    </div>
                    <form method="POST" action="{{ route('customer.cart.add', $product) }}" class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-end">
                        @csrf
                        <div>
                            <label for="quantity" class="block text-xs font-medium text-gray-500 dark:text-gray-400">Jumlah</label>
                            <input id="quantity" name="quantity" type="number" min="1" max="{{ $product->stock }}" value="1" class="mt-1 w-24 rounded-xl border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                        </div>
                        <button class="rounded-xl bg-[#A8F23A] px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:brightness-95">Tambah ke Keranjang</button>
                    </form>

                    @if ($product->description)
                        <div class="mt-6">
                            <h3 class="font-semibold text-gray-900 dark:text-white">Deskripsi</h3>
                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-600 dark:text-gray-300">{{ $product->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
