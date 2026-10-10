<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Gudang</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Review Penerimaan</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                Periksa detail PO dan catat barang diterima/rusak sebelum stok masuk
            </p>
        </div>

        <a href="{{ route('gudang.penerimaan.index') }}"
           class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- ================= WARNING PO SELESAI ================= --}}
    @if($purchase->status !== 'draft' || $purchase->submitted_at === null)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">PO Tidak di Antrean Penerimaan</p>
                <p class="mt-0.5 text-[11px] text-amber-700 dark:text-amber-200/80">
                    PO ini tidak lagi berada pada antrean penerimaan. Detail tetap dapat dilihat untuk audit operasional.
                </p>
            </div>
        </div>
    @endif

    {{-- ================= INFO CARDS ================= --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Invoice</p>
                <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-bold text-[#171719] dark:text-white font-mono truncate">{{ $purchase->invoice }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">No. PO</p>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Supplier</p>
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-bold text-[#171719] dark:text-white truncate">{{ $purchase->supplier?->name ?? '-' }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Penyedia barang</p>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Tanggal PO</p>
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-bold text-[#171719] dark:text-white font-mono">{{ $purchase->purchase_date?->format('d/m/Y') ?? '-' }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Tanggal order</p>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Dibuat Oleh</p>
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 2 2 7l10 5 10-5-10-5z"/>
                        <path d="m2 17 10 5 10-5"/>
                        <path d="m2 12 10 5 10-5"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm font-bold text-[#171719] dark:text-white truncate">{{ $purchase->user?->name ?? '-' }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Purchasing</p>
        </div>
    </div>

    {{-- ================= TABEL BARANG ================= --}}
    <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Daftar Barang</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                    Bandingkan item, SKU, unit, harga, dan kuantitas sebelum konfirmasi
                </p>
            </div>
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-2.5 py-1 rounded-lg font-medium">
                {{ $purchase->items->count() }} item
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Produk</th>
                        <th class="px-5 py-3.5 font-medium">SKU</th>
                        <th class="px-5 py-3.5 text-center font-medium">Qty PO</th>
                        <th class="px-5 py-3.5 text-center font-medium">Diterima</th>
                        <th class="px-5 py-3.5 text-center font-medium">Rusak</th>
                        <th class="px-5 py-3.5 font-medium">Unit</th>
                        <th class="px-5 py-3.5 text-right font-medium">Harga Beli</th>
                        <th class="px-5 py-3.5 text-right font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse ($purchase->items as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">

                            <td class="px-5 py-4">
                                <p class="text-xs font-semibold text-[#171719] dark:text-white truncate max-w-[200px]">
                                    {{ $item->product?->name ?? '-' }}
                                </p>
                            </td>

                            <td class="px-5 py-4">
                                <p class="text-[11px] text-black/45 dark:text-white/45 font-mono">
                                    {{ $item->product?->sku ?? '-' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-white/10 text-[11px] font-bold text-[#171719] dark:text-white">
                                    {{ number_format($item->quantity, 0, ',', '.') }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <input form="receive-form" type="number" min="0" max="{{ $item->quantity }}"
                                    name="received[{{ $item->id }}]"
                                    value="{{ $item->received_quantity ?: $item->quantity }}"
                                    class="no-spinner w-20 h-9 px-2 text-center rounded-lg border-2 border-black/10 dark:border-white/10 bg-transparent text-sm font-bold text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition"
                                    @disabled($purchase->status !== 'draft' || $purchase->submitted_at === null)>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <input form="receive-form" type="number" min="0" max="{{ $item->quantity }}"
                                    name="damaged[{{ $item->id }}]"
                                    value="{{ $item->damaged_quantity }}"
                                    class="no-spinner w-20 h-9 px-2 text-center rounded-lg border-2 border-black/10 dark:border-white/10 bg-transparent text-sm font-bold text-[#171719] dark:text-white focus:outline-none focus:border-red-400 transition"
                                    @disabled($purchase->status !== 'draft' || $purchase->submitted_at === null)>
                            </td>

                            <td class="px-5 py-4">
                                <p class="text-[11px] text-black/45 dark:text-white/45">
                                    {{ $item->product?->unit ?? '-' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <p class="text-xs text-black/70 dark:text-white/70">
                                    Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <p class="text-xs font-bold text-[#171719] dark:text-white">
                                    Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                                </p>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <p class="text-xs text-black/40 dark:text-white/40">PO tidak memiliki item.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                {{-- TOTAL --}}
                <tfoot class="border-t-2 border-black/5 dark:border-white/10">
                    <tr>
                        <td colspan="7" class="px-5 py-4 text-right text-xs font-bold uppercase tracking-wider text-black/45 dark:text-white/45">
                            Total PO
                        </td>
                        <td class="px-5 py-4 text-right">
                            <p class="text-base font-bold text-lime-600 dark:text-lime-400">
                                Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}
                            </p>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>

    {{-- ================= INFO SUPPLIER + KONFIRMASI ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- INFO SUPPLIER --}}
        <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Informasi Supplier</h3>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Kontak penanggung jawab</p>
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex items-center gap-3 py-2 border-b border-dashed border-black/5 dark:border-white/10">
                    <svg class="w-4 h-4 text-black/45 dark:text-white/45 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Kontak Person</p>
                        <p class="mt-0.5 text-xs font-semibold text-[#171719] dark:text-white">
                            {{ $purchase->supplier?->contact_person ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 py-2">
                    <svg class="w-4 h-4 text-black/45 dark:text-white/45 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Telepon</p>
                        <p class="mt-0.5 text-xs font-semibold text-[#171719] dark:text-white font-mono">
                            {{ $purchase->supplier?->phone ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- KONFIRMASI --}}
        <div class="rounded-2xl border-2 border-lime-400 bg-lime-50 dark:bg-lime-900/20 p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 rounded-xl bg-lime-400 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-[#171719]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Konfirmasi Penerimaan</h3>
                    <p class="mt-0.5 text-[11px] text-black/60 dark:text-white/60">Stok akan otomatis ditambahkan</p>
                </div>
            </div>

            <div class="rounded-xl bg-white/60 dark:bg-white/5 p-3 mb-4">
                <p class="text-[11px] text-black/60 dark:text-white/60">
                    Masukkan jumlah yang benar-benar diterima dan jumlah rusak. Barang rusak atau kurang tidak menambah stok. Setelah konfirmasi PO selesai diproses.
                </p>
            </div>

            @if($purchase->status === 'draft' && $purchase->submitted_at !== null)
                <form id="receive-form" method="POST"
                      action="{{ route('gudang.penerimaan.receive', $purchase) }}"
                      onsubmit="return confirm('Konfirmasi seluruh item PO sudah diterima dan tambahkan stok?')">
                    @csrf
                    <button type="submit"
                        class="w-full h-12 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center justify-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                        Terima & Tambah Stok
                    </button>
                </form>
            @else
                <div class="rounded-xl bg-gray-100 dark:bg-white/10 p-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-black/45 dark:text-white/45 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                    </svg>
                    <p class="text-[11px] font-medium text-black/60 dark:text-white/60">
                        Aksi penerimaan tidak tersedia untuk status ini.
                    </p>
                </div>
            @endif
        </div>

    </div>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .no-spinner { -moz-appearance: textfield; appearance: textfield; }
</style>
</x-app-layout>
