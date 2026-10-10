<x-app-layout>
<div class="space-y-5 font-poppins" x-data="purchaseForm()">

    @php
        $isPurchasing = auth()->user()->role === 'purchasing';
        $indexRoute = $isPurchasing ? 'purchasing.purchases.index' : 'admin.purchases.index';
        $storeRoute = $isPurchasing ? 'purchasing.purchases.store' : 'admin.purchases.store';
    @endphp

    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Transaksi</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">
                {{ $isPurchasing ? 'Purchase Order Baru' : 'Pembelian Baru' }}
            </h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                {{ $isPurchasing ? 'Buat PO sebagai draft untuk dikirim ke gudang' : 'Catat pembelian & perbarui stok' }}
            </p>
        </div>

        <a href="{{ route($indexRoute) }}"
           class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 dark:border-red-500/20 dark:bg-red-500/10 px-4 py-3">
            <p class="text-sm font-semibold text-red-800 dark:text-red-300">Periksa input pembelian</p>
            <ul class="mt-1 list-disc space-y-0.5 pl-5 text-xs text-red-700 dark:text-red-200">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route($storeRoute) }}" class="space-y-5">
        @csrf

        <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <h3 class="text-sm font-semibold text-[#171719] dark:text-white mb-4">Informasi Pembelian</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">No. Invoice</label>
                    <input type="text" name="invoice" value="{{ old('invoice', 'PO-' . now()->format('YmdHis')) }}" readonly
                        class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-black/[0.03] dark:bg-white/[0.03] text-sm font-bold text-[#171719] dark:text-white cursor-not-allowed font-mono">
                </div>

                <div>
                    <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Supplier</label>
                    <select name="supplier_id" required
                        class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm focus:outline-none focus:border-lime-400 dark:text-white">
                        <option value="">Pilih supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Tanggal</label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date', now()->format('Y-m-d')) }}" required
                        class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm focus:outline-none focus:border-lime-400 dark:text-white">
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">
            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Item Pembelian</h3>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Tambahkan produk yang dibeli</p>
                </div>
                <button type="button" @click="addItem()"
                    class="h-9 px-3 rounded-lg bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-bold flex items-center gap-1.5 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 5v14"/><path d="M5 12h14"/>
                    </svg>
                    Tambah Item
                </button>
            </div>

            <div class="p-5 space-y-3">
                <template x-for="(item, index) in items" :key="item.key">
                    <div class="rounded-xl border border-black/5 dark:border-white/10 p-4">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                            <div class="md:col-span-5">
                                <label class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Produk</label>
                                <select :name="'items['+index+'][product_id]'" x-model="item.product_id" required
                                    class="mt-1.5 w-full h-10 px-3 rounded-lg border border-black/10 dark:border-white/10 bg-transparent text-sm focus:outline-none focus:border-lime-400 dark:text-white">
                                    <option value="">Pilih produk</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Qty</label>
                                <input type="number" min="1" x-model.number="item.quantity" :name="'items['+index+'][quantity]'" required
                                    class="no-spinner mt-1.5 w-full h-10 px-3 rounded-lg border border-black/10 dark:border-white/10 bg-transparent text-sm focus:outline-none focus:border-lime-400 dark:text-white">
                            </div>
                            <div class="md:col-span-3">
                                <label class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Harga Beli</label>
                                <input type="number" min="0" x-model.number="item.unit_price" :name="'items['+index+'][unit_price]'" required
                                    class="no-spinner mt-1.5 w-full h-10 px-3 rounded-lg border border-black/10 dark:border-white/10 bg-transparent text-sm focus:outline-none focus:border-lime-400 dark:text-white">
                            </div>
                            <div class="md:col-span-2 flex items-end">
                                <button type="button" @click="removeItem(index)" x-show="items.length > 1"
                                    class="w-full h-10 rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 text-red-600 dark:text-red-400 text-xs font-bold transition">
                                    Hapus
                                </button>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-dashed border-black/5 dark:border-white/10 flex items-center justify-between">
                            <span class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Subtotal</span>
                            <span class="text-sm font-bold text-lime-600 dark:text-lime-400"
                                  x-text="formatCurrency((item.quantity || 0) * (item.unit_price || 0))"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
                <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Catatan</label>
                <textarea name="notes" rows="4" placeholder="Catatan pembelian (opsional)..."
                    class="mt-2 w-full rounded-xl border border-black/10 dark:border-white/10 bg-transparent px-4 py-3 text-sm focus:outline-none focus:border-lime-400 dark:text-white resize-none">{{ old('notes') }}</textarea>
            </div>

            <div class="rounded-2xl border-2 border-lime-400 bg-lime-50 dark:bg-lime-900/20 p-5">
                <p class="text-[10px] uppercase tracking-wider text-lime-700 dark:text-lime-400">Total Pembelian</p>
                <p class="mt-1 text-3xl font-bold text-[#171719] dark:text-white" x-text="formatCurrency(grandTotal())"></p>

                <button type="submit"
                    class="mt-5 w-full h-12 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center justify-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                    Simpan Pembelian
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function purchaseForm() {
    return {
        items: [{ key: Date.now(), product_id: '', quantity: 1, unit_price: 0 }],
        addItem() { this.items.push({ key: Date.now() + Math.random(), product_id: '', quantity: 1, unit_price: 0 }); },
        removeItem(index) { this.items.splice(index, 1); },
        grandTotal() { return this.items.reduce((sum, item) => sum + ((Number(item.quantity) || 0) * (Number(item.unit_price) || 0)), 0); },
        formatCurrency(value) { return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value || 0); }
    };
}
</script>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .no-spinner { -moz-appearance: textfield; appearance: textfield; }
</style>
</x-app-layout>
