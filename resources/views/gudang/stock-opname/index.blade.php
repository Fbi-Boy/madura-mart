<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Stock Opname</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Bandingkan stok sistem dengan stok fisik dan sesuaikan selisihnya.</p>
            </div>
            <span class="w-fit rounded-full bg-[#A8F23A]/20 px-3 py-1.5 text-xs font-semibold text-gray-800 dark:text-[#A8F23A]">Gudang</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800 dark:border-green-900/50 dark:bg-green-900/20 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-200">
                    <p class="font-semibold">Periksa input stock opname.</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('gudang.stock-opname.store') }}" class="space-y-6">
                @csrf

                <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Pengecekan Stok Fisik</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Masukkan jumlah fisik. Stok sistem akan menjadi nilai referensi sebelum penyesuaian.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[720px] text-left text-sm">
                            <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                                <tr>
                                    <th class="px-5 py-3">SKU</th>
                                    <th class="px-5 py-3">Produk</th>
                                    <th class="px-5 py-3">Stok Sistem</th>
                                    <th class="px-5 py-3">Stok Fisik</th>
                                    <th class="px-5 py-3">Selisih</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($products as $product)
                                    <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/40" x-data="{ actual: {{ $product->stock }} }">
                                        <td class="px-5 py-4 font-mono text-xs text-gray-500 dark:text-gray-400">{{ $product->sku }}</td>
                                        <td class="px-5 py-4">
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $product->unit }}</p>
                                        </td>
                                        <td class="px-5 py-4 font-semibold text-gray-700 dark:text-gray-200">{{ $product->stock }}</td>
                                        <td class="px-5 py-4">
                                            <input
                                                type="number"
                                                min="0"
                                                name="actual_stock[{{ $product->id }}]"
                                                x-model.number="actual"
                                                class="w-32 rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-[#A8F23A] focus:ring-[#A8F23A] dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                                required
                                            >
                                        </td>
                                        <td class="px-5 py-4">
                                            <span class="font-semibold" :class="actual - {{ $product->stock }} === 0 ? 'text-gray-400' : (actual - {{ $product->stock }} > 0 ? 'text-green-600' : 'text-red-600')" x-text="actual - {{ $product->stock }}"></span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada produk aktif untuk diperiksa.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t border-gray-100 p-5 dark:border-gray-700">
                        <label for="notes" class="text-sm font-medium text-gray-700 dark:text-gray-200">Catatan</label>
                        <textarea id="notes" name="notes" rows="3" class="mt-2 w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-[#A8F23A] focus:ring-[#A8F23A] dark:border-gray-600 dark:bg-gray-900 dark:text-white" placeholder="Contoh: pengecekan rak A selesai.">{{ old('notes') }}</textarea>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="rounded-xl bg-[#A8F23A] px-5 py-2.5 text-sm font-semibold text-gray-900 shadow-sm transition hover:brightness-95">
                                Simpan Stock Opname
                            </button>
                        </div>
                    </div>
                </section>
            </form>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Riwayat Stock Opname</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Delapan pemeriksaan terakhir.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[620px] text-left text-sm">
                        <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3">Petugas</th>
                                <th class="px-5 py-3">Item</th>
                                <th class="px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($recentOpnames as $opname)
                                <tr>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $opname->created_at?->format('d/m/Y H:i') }}</td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $opname->user?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $opname->items_count }}</td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-300">{{ ucfirst($opname->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400">Belum ada riwayat stock opname.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
