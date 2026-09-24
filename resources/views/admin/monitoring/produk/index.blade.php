<x-app-layout>
<div class="space-y-5">
    <div>
        <p class="text-sm text-black/45">Monitoring</p>
        <h2 class="mt-1 text-2xl font-semibold">Monitoring Produk</h2>
        <p class="mt-1 text-sm text-black/40">Pantau katalog, status aktif, dan kondisi stok produk.</p>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Total Produk</p><p class="mt-2 text-2xl font-semibold">{{ $totalProducts }}</p></div>
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Produk Aktif</p><p class="mt-2 text-2xl font-semibold">{{ $activeProducts }}</p></div>
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Stok Menipis</p><p class="mt-2 text-2xl font-semibold">{{ $lowStock }}</p></div>
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Stok Habis</p><p class="mt-2 text-2xl font-semibold">{{ $outOfStock }}</p></div>
    </div>

    <form method="GET" class="flex gap-3 rounded-2xl border bg-white p-5">
        <input name="search" value="{{ $search }}" placeholder="Cari SKU atau nama produk..." class="w-full rounded-xl border px-3 py-2.5">
        <button class="rounded-xl bg-[#171719] px-5 py-2.5 font-semibold text-white">Cari</button>
    </form>

    <div class="overflow-hidden rounded-2xl border bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b text-xs uppercase text-black/45">
                    <tr>
                        <th class="px-5 py-4">SKU</th>
                        <th class="px-5 py-4">Produk</th>
                        <th class="px-5 py-4">Kategori</th>
                        <th class="px-5 py-4 text-right">Harga</th>
                        <th class="px-5 py-4 text-right">Stok</th>
                        <th class="px-5 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                @forelse($products as $product)
                    <tr>
                        <td class="px-5 py-4 font-medium">{{ $product->sku }}</td>
                        <td class="px-5 py-4">{{ $product->name }}</td>
                        <td class="px-5 py-4">{{ $product->category?->name ?? '-' }}</td>
                        <td class="px-5 py-4 text-right">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-right font-semibold">{{ $product->stock }} {{ $product->unit }}</td>
                        <td class="px-5 py-4">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium
                                @if(!$product->is_active) bg-gray-100 text-gray-600
                                @elseif($product->stock === 0) bg-red-50 text-red-600
                                @elseif($product->stock <= 10) bg-yellow-50 text-yellow-700
                                @else bg-green-50 text-green-700 @endif">
                                @if(!$product->is_active) Nonaktif
                                @elseif($product->stock === 0) Stok Habis
                                @elseif($product->stock <= 10) Stok Menipis
                                @else Aktif @endif
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-black/40">Belum ada produk.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($products->hasPages())
            <div class="border-t px-5 py-4">{{ $products->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>