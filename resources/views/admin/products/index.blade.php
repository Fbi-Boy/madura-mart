<x-app-layout>
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-black/40 dark:text-white/40">Master Data</p>
                <h1 class="mt-1 text-2xl font-bold">Produk</h1>
                <p class="mt-1 text-sm text-black/50 dark:text-white/50">Kelola katalog produk Madura Mart.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 dark:bg-white dark:text-[#171719]">Tambah Produk</a>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm dark:border-white/10 dark:bg-white/[0.04]">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-black/5 bg-black/[0.02] dark:border-white/10 dark:bg-white/[0.03]">
                        <tr>
                            <th class="px-5 py-3 font-semibold">SKU</th>
                            <th class="px-5 py-3 font-semibold">Produk</th>
                            <th class="px-5 py-3 font-semibold">Kategori</th>
                            <th class="px-5 py-3 font-semibold">Harga</th>
                            <th class="px-5 py-3 font-semibold">Stok</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/10">
                        @forelse ($products as $product)
                            <tr>
                                <td class="px-5 py-4 font-medium">{{ $product->sku }}</td>
                                <td class="px-5 py-4">{{ $product->name }}</td>
                                <td class="px-5 py-4">{{ $product->category->name }}</td>
                                <td class="px-5 py-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-5 py-4">{{ $product->stock }} {{ $product->unit }}</td>
                                <td class="px-5 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="rounded-lg border border-black/10 px-3 py-1.5 text-xs font-semibold dark:border-white/10">Edit</a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-5 py-10 text-center text-sm text-black/45">Belum ada produk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($products->hasPages())
                <div class="border-t border-black/5 px-5 py-4 dark:border-white/10">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
