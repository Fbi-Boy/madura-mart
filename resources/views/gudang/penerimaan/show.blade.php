<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Review Penerimaan</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Periksa detail PO dan catat barang diterima/rusak sebelum stok masuk ke gudang.</p>
            </div>
            <a href="{{ route('gudang.penerimaan.index') }}"
                class="inline-flex w-fit items-center rounded-xl border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:border-[#A8F23A] hover:bg-[#A8F23A]/10 dark:border-gray-700 dark:text-gray-200">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if($purchase->status !== 'draft' || $purchase->submitted_at === null)
                <div class="rounded-2xl border border-yellow-200 bg-yellow-50 px-5 py-4 text-sm text-yellow-800 dark:border-yellow-900/40 dark:bg-yellow-900/20 dark:text-yellow-200">
                    PO ini tidak lagi berada pada antrean penerimaan. Detail tetap dapat dilihat untuk audit operasional.
                </div>
            @endif

            <section class="grid gap-4 md:grid-cols-4">
                @foreach([
                    ['label' => 'Invoice', 'value' => $purchase->invoice],
                    ['label' => 'Supplier', 'value' => $purchase->supplier?->name ?? '-'],
                    ['label' => 'Tanggal PO', 'value' => $purchase->purchase_date?->format('d M Y') ?? '-'],
                    ['label' => 'Dibuat Oleh', 'value' => $purchase->user?->name ?? '-'],
                ] as $item)
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-xs uppercase tracking-wide text-gray-400 dark:text-gray-500">{{ $item['label'] }}</p>
                        <p class="mt-2 truncate font-semibold text-gray-900 dark:text-white">{{ $item['value'] }}</p>
                    </div>
                @endforeach
            </section>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-col gap-2 border-b border-gray-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-700">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Daftar Barang</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Bandingkan item, SKU, unit, harga, dan kuantitas sebelum konfirmasi.</p>
                    </div>
                    <span class="rounded-full bg-[#A8F23A]/20 px-3 py-1 text-xs font-semibold text-gray-900 dark:text-[#A8F23A]">
                        {{ $purchase->items->count() }} item
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left text-sm">
                        <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Produk</th>
                                <th class="px-5 py-3">SKU</th>
                                <th class="px-5 py-3">Qty PO</th>
                                <th class="px-5 py-3">Diterima</th>
                                <th class="px-5 py-3">Rusak</th>
                                <th class="px-5 py-3">Unit</th>
                                <th class="px-5 py-3 text-right">Harga Beli</th>
                                <th class="px-5 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($purchase->items as $item)
                                <tr>
                                    <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">{{ $item->product?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $item->product?->sku ?? '-' }}</td>
                                    <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4">
                                        <input form="receive-form" type="number" min="0" max="{{ $item->quantity }}" name="received[{{ $item->id }}]" value="{{ $item->received_quantity ?: $item->quantity }}" class="w-20 rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white" @disabled($purchase->status !== 'draft' || $purchase->submitted_at === null)>
                                    </td>
                                    <td class="px-5 py-4">
                                        <input form="receive-form" type="number" min="0" max="{{ $item->quantity }}" name="damaged[{{ $item->id }}]" value="{{ $item->damaged_quantity }}" class="w-20 rounded-lg border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white" @disabled($purchase->status !== 'draft' || $purchase->submitted_at === null)>
                                    </td>
                                    <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $item->product?->unit ?? '-' }}</td>
                                    <td class="px-5 py-4 text-right text-gray-600 dark:text-gray-300">Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="px-5 py-10 text-center text-gray-400">PO tidak memiliki item.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot class="border-t border-gray-100 dark:border-gray-700">
                            <tr>
                                <td colspan="7" class="px-5 py-4 text-right font-semibold text-gray-600 dark:text-gray-300">Total PO</td>
                                <td class="px-5 py-4 text-right text-lg font-bold text-gray-900 dark:text-white">Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </section>

            <section class="grid gap-4 lg:grid-cols-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Informasi Supplier</h3>
                    <div class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                        <p><span class="text-gray-400">Kontak:</span> {{ $purchase->supplier?->contact_person ?? '-' }}</p>
                        <p><span class="text-gray-400">Telepon:</span> {{ $purchase->supplier?->phone ?? '-' }}</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-[#A8F23A]/40 bg-[#A8F23A]/10 p-5 dark:bg-[#A8F23A]/5">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Konfirmasi Penerimaan</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Masukkan jumlah yang benar-benar diterima dan jumlah rusak. Barang rusak atau kurang tidak menambah stok; setelah konfirmasi PO selesai diproses.</p>
                    @if($purchase->status === 'draft' && $purchase->submitted_at !== null)
                        <form id="receive-form" method="POST" action="{{ route('gudang.penerimaan.receive', $purchase) }}" class="mt-4" onsubmit="return confirm('Konfirmasi seluruh item PO sudah diterima dan tambahkan stok?')">
                            @csrf
                            <button type="submit" class="inline-flex rounded-xl bg-[#A8F23A] px-4 py-2.5 text-sm font-semibold text-gray-900 transition hover:opacity-80">
                                Terima & Tambah Stok
                            </button>
                        </form>
                    @else
                        <p class="mt-4 text-xs font-medium text-gray-500 dark:text-gray-400">Aksi penerimaan tidak tersedia untuk status ini.</p>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
