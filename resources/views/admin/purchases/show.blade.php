<x-app-layout>
<div class="mx-auto max-w-6xl space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-black/40">Detail Procurement</p>
            <h1 class="mt-1 text-2xl font-bold">{{ $purchase->invoice }}</h1>
            <p class="mt-1 text-sm text-black/50">Rincian purchase order, supplier, item, dan status penerimaan.</p>
        </div>
        <a href="{{ route(auth()->user()->role === 'purchasing' ? 'purchasing.purchases.index' : 'admin.purchases.index') }}" class="rounded-xl border border-black/10 px-4 py-2.5 text-sm font-semibold">← Kembali</a>
    </div>

    <div class="grid gap-4 md:grid-cols-4">
        <div class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm"><p class="text-xs text-black/40">Supplier</p><p class="mt-2 font-semibold">{{ $purchase->supplier?->name ?? '-' }}</p></div>
        <div class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm"><p class="text-xs text-black/40">Tanggal</p><p class="mt-2 font-semibold">{{ $purchase->purchase_date?->format('d/m/Y') ?? '-' }}</p></div>
        <div class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm"><p class="text-xs text-black/40">Dibuat Oleh</p><p class="mt-2 font-semibold">{{ $purchase->user?->name ?? '-' }}</p></div>
        <div class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm"><p class="text-xs text-black/40">Status</p><p class="mt-2 inline-flex rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold">{{ $purchase->submitted_at ? 'Submitted' : ucfirst($purchase->status) }}</p></div>
    </div>

    <section class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm">
        <div class="border-b border-black/5 px-5 py-4">
            <h2 class="font-semibold">Item Pembelian</h2>
            <p class="mt-1 text-xs text-black/40">{{ $purchase->items->count() }} item produk.</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-black/5 bg-black/[0.02]">
                    <tr><th class="px-5 py-3">Produk</th><th class="px-5 py-3">SKU</th><th class="px-5 py-3 text-right">Qty</th><th class="px-5 py-3 text-right">Harga</th><th class="px-5 py-3 text-right">Subtotal</th></tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @foreach($purchase->items as $item)
                        <tr>
                            <td class="px-5 py-4 font-medium">{{ $item->product?->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-black/50">{{ $item->product?->sku ?? '-' }}</td>
                            <td class="px-5 py-4 text-right">{{ number_format($item->quantity, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right">Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right font-semibold">Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t border-black/5">
                    <tr><td colspan="4" class="px-5 py-4 text-right font-semibold">Total</td><td class="px-5 py-4 text-right text-lg font-bold">Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}</td></tr>
                </tfoot>
            </table>
        </div>
    </section>

    <div class="grid gap-4 md:grid-cols-2">
        <section class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm">
            <h2 class="font-semibold">Timeline Procurement</h2>
            <div class="mt-4 space-y-3 text-sm">
                <div class="flex justify-between gap-4"><span class="text-black/50">Purchase dibuat</span><span class="font-medium">{{ $purchase->created_at?->format('d/m/Y H:i') }}</span></div>
                <div class="flex justify-between gap-4"><span class="text-black/50">Submitted</span><span class="font-medium">{{ $purchase->submitted_at?->format('d/m/Y H:i') ?? 'Belum dikirim' }}</span></div>
                <div class="flex justify-between gap-4"><span class="text-black/50">Status</span><span class="font-medium">{{ ucfirst($purchase->status) }}</span></div>
            </div>
        </section>
        <section class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm">
            <h2 class="font-semibold">Catatan</h2>
            <p class="mt-3 whitespace-pre-line text-sm leading-6 text-black/60">{{ $purchase->notes ?: 'Tidak ada catatan.' }}</p>
        </section>
    </div>
</div>
</x-app-layout>
