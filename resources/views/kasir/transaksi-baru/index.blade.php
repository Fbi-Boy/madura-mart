<x-app-layout>
<div class="space-y-5" x-data="saleForm()">
    <div>
        <p class="text-sm text-black/45 dark:text-white/45">Kasir</p>
        <h2 class="mt-1 text-2xl font-semibold text-[#171719] dark:text-white">Transaksi Baru</h2>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="font-semibold">Periksa input transaksi.</p>
            <ul class="mt-1 list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('kasir.transaksi-baru.store') }}" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 gap-4 rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5 md:grid-cols-3">
            <div>
                <label class="text-sm font-medium text-[#171719] dark:text-white">No. Invoice</label>
                <input name="invoice" value="{{ old('invoice', 'INV-'.now()->format('YmdHis')) }}" required
                    class="mt-2 w-full rounded-xl border border-black/10 bg-transparent px-3 py-2.5 text-sm dark:border-white/10">
            </div>
            <div>
                <label class="text-sm font-medium text-[#171719] dark:text-white">Pelanggan</label>
                <select name="customer_id" class="mt-2 w-full rounded-xl border border-black/10 bg-transparent px-3 py-2.5 text-sm dark:border-white/10">
                    <option value="">Umum / tanpa pelanggan</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-medium text-[#171719] dark:text-white">Tanggal</label>
                <input type="datetime-local" name="sale_date" value="{{ old('sale_date', now()->format('Y-m-d\TH:i')) }}" required
                    class="mt-2 w-full rounded-xl border border-black/10 bg-transparent px-3 py-2.5 text-sm dark:border-white/10">
            </div>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <div class="mb-4 flex items-center justify-between gap-4">
                <div>
                    <h3 class="font-semibold text-[#171719] dark:text-white">Item Penjualan</h3>
                    <p class="mt-1 text-xs text-black/45 dark:text-white/45">Stok akan dikurangi otomatis setelah transaksi tersimpan.</p>
                </div>
                <div class="flex w-full max-w-md items-center gap-2">
                    <input x-ref="barcode" x-model="barcode" @keydown.enter.prevent="scanBarcode()"
                        type="text" inputmode="numeric" autocomplete="off"
                        placeholder="Scan barcode / SKU lalu Enter"
                        class="w-full rounded-xl border border-black/10 bg-transparent px-3 py-2.5 text-sm dark:border-white/10">
                    <button type="button" @click="scanBarcode()" class="rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white">Scan</button>
                </div>
                <button type="button" @click="addItem()" class="rounded-xl bg-[#A8F23A] px-4 py-2 text-sm font-semibold text-[#171719]">+ Tambah Item</button>
            </div>

            <div class="space-y-3">
                <template x-for="(item, index) in items" :key="item.key">
                    <div class="grid grid-cols-1 gap-3 rounded-xl border border-black/5 p-3 dark:border-white/10 md:grid-cols-[1fr_140px_180px_40px]">
                        <div>
                            <label class="text-xs text-black/45 dark:text-white/45">Produk</label>
                            <select :name="'items['+index+'][product_id]'" x-model="item.product_id" @change="syncPrice(item)"
                                class="mt-1 w-full rounded-lg border border-black/10 bg-transparent px-3 py-2 text-sm dark:border-white/10" required>
                                <option value="">Pilih produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->sku }} — {{ $product->name }} (stok: {{ $product->stock }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs text-black/45 dark:text-white/45">Qty</label>
                            <input type="number" min="1" :name="'items['+index+'][quantity]'" x-model.number="item.quantity"
                                class="mt-1 w-full rounded-lg border border-black/10 bg-transparent px-3 py-2 text-sm dark:border-white/10" required>
                        </div>
                        <div class="flex items-end">
                            <div>
                                <p class="text-xs text-black/45 dark:text-white/45">Subtotal</p>
                                <p class="mt-1 font-semibold text-[#171719] dark:text-white" x-text="formatCurrency(item.price * item.quantity)"></p>
                            </div>
                        </div>
                        <div class="flex items-end justify-end">
                            <button type="button" @click="removeItem(index)" class="rounded-lg px-2 py-2 text-red-500 hover:bg-red-50" x-show="items.length > 1">×</button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-[1fr_280px]">
            <div class="rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
                <label class="text-sm font-medium text-[#171719] dark:text-white">Catatan</label>
                <textarea name="notes" rows="4" class="mt-2 w-full rounded-xl border border-black/10 bg-transparent px-3 py-2.5 text-sm dark:border-white/10">{{ old('notes') }}</textarea>
            </div>
            <div class="rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
                <label class="text-sm font-medium text-[#171719] dark:text-white">Metode Pembayaran</label>
                <select name="payment_method" x-model="paymentMethod" class="mt-2 w-full rounded-xl border border-black/10 bg-transparent px-3 py-2.5 text-sm dark:border-white/10">
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="qris">QRIS</option>
                    <option value="debit">Debit / Kartu</option>
                </select>
                <div class="mt-4">
                    <label class="text-sm font-medium text-[#171719] dark:text-white">Nominal Dibayar</label>
                    <input type="number" name="paid_amount" min="0" step="0.01" x-model.number="paidAmount"
                        :readonly="paymentMethod !== 'cash'"
                        :value="paymentMethod === 'cash' ? paidAmount : total()"
                        class="mt-2 w-full rounded-xl border border-black/10 bg-transparent px-3 py-2.5 text-sm dark:border-white/10 read-only:bg-black/[0.03] dark:read-only:bg-white/[0.03]">
                    <p class="mt-1 text-[11px] text-black/40 dark:text-white/40">Untuk non-cash, nominal otomatis mengikuti total transaksi.</p>
                </div>
                <div class="mt-4 rounded-xl bg-[#A8F23A]/10 p-3 dark:bg-[#A8F23A]/5">
                    <p class="text-xs text-black/45 dark:text-white/45">Kembalian</p>
                    <p class="mt-1 text-lg font-semibold text-[#171719] dark:text-white" x-text="formatCurrency(change())"></p>
                </div>
                <div class="mt-5 border-t border-black/5 pt-4 dark:border-white/10">
                    <p class="text-sm text-black/45 dark:text-white/45">Total</p>
                    <p class="mt-1 text-2xl font-bold text-[#171719] dark:text-white" x-text="formatCurrency(total())"></p>
                </div>
                <button type="submit" class="mt-5 w-full rounded-xl bg-[#171719] px-4 py-3 text-sm font-semibold text-white">Simpan Transaksi</button>
            </div>
        </div>
    </form>
</div>

<script>
function saleForm() {
    const products = @json($productData);

    return {
        products,
        paymentMethod: 'cash',
        paidAmount: 0,
        barcode: '',
        scanBarcode() {
            const code = this.barcode.trim().toLowerCase();
            if (!code) return;

            const product = this.products.find(product => String(product.sku).toLowerCase() === code);
            if (!product) {
                alert('SKU/barcode tidak ditemukan.');
                this.barcode = '';
                this.$nextTick(() => this.$refs.barcode?.focus());
                return;
            }

            const existing = this.items.find(item => String(item.product_id) === String(product.id));
            if (existing) {
                existing.quantity = Number(existing.quantity || 0) + 1;
            } else {
                this.items.push({
                    key: Date.now() + this.items.length,
                    product_id: product.id,
                    quantity: 1,
                    price: product.price,
                });
            }

            this.barcode = '';
            this.$nextTick(() => this.$refs.barcode?.focus());
        },
        items: [{ key: Date.now(), product_id: '', quantity: 1, price: 0 }],
        addItem() {
            this.items.push({ key: Date.now() + this.items.length, product_id: '', quantity: 1, price: 0 });
        },
        removeItem(index) {
            this.items.splice(index, 1);
        },
        syncPrice(item) {
            const product = this.products.find(product => String(product.id) === String(item.product_id));
            item.price = product ? product.price : 0;
        },
        total() {
            return this.items.reduce((sum, item) => sum + ((Number(item.price) || 0) * (Number(item.quantity) || 0)), 0);
        },
        change() {
            const paid = this.paymentMethod === 'cash' ? Number(this.paidAmount) || 0 : this.total();
            return Math.max(0, paid - this.total());
        },
        formatCurrency(value) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value || 0);
        }
    };
}
</script>
</x-app-layout>