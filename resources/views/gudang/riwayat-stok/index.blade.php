<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Riwayat Stok</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ledger pergerakan stok dari penerimaan, penjualan, retur, dan stock opname.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <form method="GET" class="grid gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:grid-cols-4 dark:border-gray-700 dark:bg-gray-800">
                <input name="search" value="{{ request('search') }}" placeholder="Cari produk / SKU" class="rounded-xl border-gray-200 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <select name="type" class="rounded-xl border-gray-200 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    <option value="">Semua aktivitas</option>
                    @foreach (['purchase_receipt' => 'Penerimaan', 'sale' => 'Penjualan', 'return' => 'Retur', 'adjustment' => 'Penyesuaian'] as $key => $label)
                        <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <input type="date" name="date" value="{{ request('date') }}" class="rounded-xl border-gray-200 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <button class="rounded-xl bg-[#A8F23A] px-4 py-2 text-sm font-semibold text-gray-900 hover:opacity-80">Filter</button>
            </form>

            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ([['Masuk', $summary['inbound'], 'text-emerald-600'], ['Keluar', $summary['outbound'], 'text-red-600'], ['Penyesuaian', $summary['adjustment'], 'text-amber-600']] as [$label, $value, $tone])
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
                        <p class="mt-2 text-2xl font-semibold {{ $tone }}">{{ number_format($value, 0, ',', '.') }} unit</p>
                    </div>
                @endforeach
            </div>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Aktivitas Stok</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $movements->total() }} catatan pergerakan.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[800px] text-left text-sm">
                        <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700">
                            <tr>
                                <th class="px-5 py-3">Waktu</th><th class="px-5 py-3">Produk</th><th class="px-5 py-3">Aktivitas</th><th class="px-5 py-3">Qty</th><th class="px-5 py-3">Petugas</th><th class="px-5 py-3">Referensi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($movements as $movement)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $movement->occurred_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-5 py-4"><p class="font-medium text-gray-900 dark:text-white">{{ $movement->product?->name ?? '-' }}</p><p class="text-xs text-gray-400">{{ $movement->product?->sku }}</p></td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ match($movement->type) { 'purchase_receipt' => 'Penerimaan', 'sale' => 'Penjualan', 'return' => 'Retur', 'adjustment' => 'Penyesuaian', default => ucfirst($movement->type) } }}</td>
                                    <td class="px-5 py-4 font-semibold {{ $movement->quantity >= 0 ? 'text-emerald-600' : 'text-red-600' }}">{{ $movement->quantity > 0 ? '+' : '' }}{{ number_format($movement->quantity, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $movement->user?->name ?? 'Sistem' }}</td>
                                    <td class="px-5 py-4 text-xs text-gray-500 dark:text-gray-400">{{ $movement->reference_type ? ucfirst(str_replace('_', ' ', $movement->reference_type)).' #'.$movement->reference_id : '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada riwayat pergerakan stok.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($movements->hasPages())
                    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700">{{ $movements->links() }}</div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
