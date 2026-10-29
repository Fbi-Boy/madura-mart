<x-app-layout>
@php
    $nextInvoice = \App\Models\Sale::nextInvoiceNumber();
@endphp

<div class="space-y-5 font-poppins" x-data="saleForm()" x-init="init()">

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

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-12 items-stretch lg:min-h-[calc(100vh-180px)]">

        {{-- ============ KIRI: SCANNER + KERANJANG ============ --}}
        <div class="lg:col-span-7 flex flex-col gap-4 min-h-0">

            {{-- KOTAK SCANNER --}}
            <div class="shrink-0 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-4 space-y-3">

                <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-black/5 dark:border-white/10">
                    <div class="flex items-center gap-3 flex-wrap">
                        <span class="inline-flex items-center gap-2 rounded-xl bg-lime-100 dark:bg-lime-900/30 px-3 py-1.5 text-xs text-lime-700 dark:text-lime-400">
                            <span class="w-2 h-2 rounded-full bg-lime-500 animate-pulse"></span>
                            Shift Aktif
                            <span class="font-mono" x-text="shiftCountdown"></span>
                        </span>
                        <span class="text-sm text-[#171719] dark:text-white" x-text="nowFormatted"></span>
                    </div>

                    <p class="text-sm text-[#171719] dark:text-white">
                        No. Invoice : <span class="italic" x-text="invoiceNumber"></span>
                    </p>
                </div>

                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/>
                            <path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/>
                            <path d="M7 8v8"/><path d="M10 8v8"/><path d="M13 8v8"/><path d="M16 8v8"/>
                        </svg>
                        <input x-ref="barcode" x-model="barcode" @keydown.enter.prevent="scanBarcode()"
                            type="text" inputmode="numeric" autocomplete="off"
                            placeholder="Scan barcode / SKU lalu Enter..."
                            class="w-full h-14 pl-12 pr-4 rounded-xl border-2 border-lime-400 text-base bg-transparent focus:outline-none focus:ring-4 focus:ring-lime-200 dark:text-white">
                    </div>
                    <button type="button" @click="bukaScanner()"
                        class="h-14 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] flex items-center gap-2 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                        </svg>
                        <span class="hidden sm:inline">Kamera</span>
                    </button>
                </div>

                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                    </svg>
                    <input type="text" x-model="searchProduk" @keydown.escape="searchProduk = ''"
                        placeholder="Cari nama produk / SKU..."
                        class="w-full h-11 pl-11 pr-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">

                    <div x-show="searchProduk.trim().length > 0" x-transition x-cloak
                        class="absolute z-30 mt-1 left-0 right-0 max-h-72 overflow-y-auto bg-white dark:bg-gray-800 rounded-xl border border-black/10 dark:border-white/10 shadow-xl">
                        <template x-for="p in filteredProducts" :key="p.id">
                            <button type="button" @click="addProduct(p); searchProduk = ''"
                                class="w-full text-left px-4 py-2.5 hover:bg-lime-50 dark:hover:bg-white/5 flex items-center justify-between gap-3 border-b border-black/5 dark:border-white/5 last:border-0 transition">
                                <div class="min-w-0">
                                    <p class="text-sm text-[#171719] dark:text-white truncate" x-text="p.name"></p>
                                    <p class="text-[10px] text-black/45 dark:text-white/45">
                                        SKU: <span x-text="p.sku"></span> · Stok: <span x-text="p.stock"></span> <span x-text="p.unit"></span>
                                    </p>
                                </div>
                                <p class="text-sm text-lime-600 dark:text-lime-400 shrink-0" x-text="formatCurrency(p.price)"></p>
                            </button>
                        </template>
                        <div x-show="filteredProducts.length === 0" class="px-4 py-6 text-center text-sm text-black/40 dark:text-white/40">
                            Produk tidak ditemukan
                        </div>
                    </div>
                </div>
            </div>

            {{-- KERANJANG --}}
            <div class="flex-1 min-h-0 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 flex flex-col overflow-hidden">
                <div class="px-4 py-3 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-2 shrink-0">
                    <h3 class="text-sm text-[#171719] dark:text-white">Keranjang</h3>
                    <div class="flex items-center gap-2">

                        <button type="button" x-show="heldTransactions.length > 0"
                            @click="resumeModalOpen = true"
                            class="text-xs text-amber-600 hover:text-amber-700 flex items-center gap-1 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="3" rx="2"/>
                                <path d="M9 3v18"/>
                            </svg>
                            Transaksi Ditahan (<span x-text="heldTransactions.length"></span>)
                        </button>

                        <button type="button" @click="kosongkan()"
                            class="text-xs text-red-500 hover:text-red-700 flex items-center gap-1 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                <path d="M21 3v5h-5"/>
                                <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                                <path d="M8 16H3v5"/>
                            </svg>
                            Reset
                        </button>
                        <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-2 py-1 rounded"
                            x-text="items.filter(i => i.product_id).length + ' item'"></span>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-3 space-y-2">
                    <template x-for="(item, index) in items" :key="item.key">
                        <div x-show="item.product_id"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-white/5 transition border border-black/5 dark:border-white/5">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm truncate text-[#171719] dark:text-white" x-text="getProductName(item.product_id)"></p>
                                <p class="text-[10px] text-black/45 dark:text-white/45" x-text="formatCurrency(item.price) + ' × ' + item.quantity"></p>
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <button type="button" @click="decreaseQty(item)" class="w-7 h-7 rounded bg-gray-100 dark:bg-white/10 text-sm">−</button>
                                <input type="number" min="1" x-model.number="item.quantity"
                                    class="w-12 text-center text-sm bg-transparent border-0 focus:ring-0 p-0">
                                <button type="button" @click="increaseQty(item)" class="w-7 h-7 rounded bg-gray-100 dark:bg-white/10 text-sm">+</button>
                            </div>
                            <div class="w-24 text-right text-sm text-[#171719] dark:text-white shrink-0" x-text="formatCurrency(item.price * item.quantity)"></div>
                            <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 text-sm shrink-0">✕</button>
                        </div>
                    </template>
                    <div x-show="items.filter(i => i.product_id).length === 0" class="flex flex-col items-center justify-center h-full min-h-[220px] text-black/40 dark:text-white/40 text-sm">
                        <svg class="w-12 h-12 mb-3 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        Keranjang masih kosong<br>
                        <span class="text-xs">Scan barcode atau cari produk</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============ KANAN: RINGKASAN ============ --}}
        <div class="lg:col-span-5 flex flex-col min-h-0">
            <div class="flex-1 rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5 flex flex-col min-h-0">

                <h3 class="text-sm text-[#171719] dark:text-white shrink-0">Ringkasan Pembayaran</h3>

                <div class="mt-4 space-y-3 text-sm shrink-0">
                    <div class="flex justify-between">
                        <span class="text-black/45 dark:text-white/45">Subtotal</span>
                        <span class="text-[#171719] dark:text-white" x-text="formatCurrency(total())"></span>
                    </div>

                    {{-- DISKON (underline only) --}}
                    <div class="flex justify-between items-center">
                        <span class="text-black/45 dark:text-white/45">Diskon</span>
                        <div class="flex items-center gap-1">
                            <span class="text-xs text-red-500">−Rp</span>
                            <input type="number" min="0" step="500" x-model.number="diskon"
                                placeholder="0"
                                class="no-spinner appearance-none bg-transparent border-0 border-b border-dashed border-black/20 dark:border-white/20 focus:ring-0 focus:outline-none focus:border-red-500 w-20 text-right text-sm p-0 pb-0.5 text-[#171719] dark:text-white">
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-black/45 dark:text-white/45">Pajak (11%)</span>
                        <span class="text-[#171719] dark:text-white" x-text="'+' + formatCurrency(pajak())"></span>
                    </div>

                    <div class="border-t border-dashed border-black/10 dark:border-white/10 my-2"></div>

                    <div class="flex justify-between items-center">
                        <span class="text-[#171719] dark:text-white">TOTAL</span>
                        <span class="text-2xl text-lime-600 dark:text-lime-400" x-text="formatCurrency(grandTotal())"></span>
                    </div>
                </div>

                <div class="mt-4 shrink-0">
                    <label class="text-xs text-black/45 dark:text-white/45">Metode Pembayaran</label>

                    <div class="mt-2 grid grid-cols-4 gap-1.5">
                        <button type="button" @click="paymentMethod = 'cash'"
                            :class="paymentMethod === 'cash' ? 'bg-lime-400 border-lime-400 text-black shadow' : 'bg-transparent border-black/10 dark:border-white/10 text-black/60 dark:text-white/60 hover:border-lime-400 hover:text-lime-600'"
                            class="h-9 rounded-lg border-2 flex items-center justify-center gap-1 text-[11px] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="12" x="2" y="6" rx="2"/>
                                <circle cx="12" cy="12" r="2"/>
                                <path d="M6 12h.01M18 12h.01"/>
                            </svg>
                            Cash
                        </button>

                        <button type="button" @click="paymentMethod = 'transfer'"
                            :class="paymentMethod === 'transfer' ? 'bg-blue-500 border-blue-500 text-white shadow' : 'bg-transparent border-black/10 dark:border-white/10 text-black/60 dark:text-white/60 hover:border-blue-500 hover:text-blue-500'"
                            class="h-9 rounded-lg border-2 flex items-center justify-center gap-1 text-[11px] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m16 3 4 4-4 4"/>
                                <path d="M20 7H4"/>
                                <path d="m8 21-4-4 4-4"/>
                                <path d="M4 17h16"/>
                            </svg>
                            Transfer
                        </button>

                        <button type="button" @click="paymentMethod = 'qris'"
                            :class="paymentMethod === 'qris' ? 'bg-purple-500 border-purple-500 text-white shadow' : 'bg-transparent border-black/10 dark:border-white/10 text-black/60 dark:text-white/60 hover:border-purple-500 hover:text-purple-500'"
                            class="h-9 rounded-lg border-2 flex items-center justify-center gap-1 text-[11px] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="5" height="5" x="3" y="3" rx="1"/>
                                <rect width="5" height="5" x="16" y="3" rx="1"/>
                                <rect width="5" height="5" x="3" y="16" rx="1"/>
                                <path d="M21 16h-3a2 2 0 0 0-2 2v3"/>
                                <path d="M21 21v.01"/>
                                <path d="M12 7v3a2 2 0 0 1-2 2H7"/>
                                <path d="M3 12h.01"/>
                                <path d="M12 3h.01"/>
                                <path d="M12 16v.01"/>
                                <path d="M16 12h1"/>
                                <path d="M21 12v.01"/>
                                <path d="M12 21v-1"/>
                            </svg>
                            QRIS
                        </button>

                        <button type="button" @click="paymentMethod = 'debit'"
                            :class="paymentMethod === 'debit' ? 'bg-orange-500 border-orange-500 text-white shadow' : 'bg-transparent border-black/10 dark:border-white/10 text-black/60 dark:text-white/60 hover:border-orange-500 hover:text-orange-500'"
                            class="h-9 rounded-lg border-2 flex items-center justify-center gap-1 text-[11px] transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="14" x="2" y="5" rx="2"/>
                                <line x1="2" x2="22" y1="10" y2="10"/>
                            </svg>
                            Debit
                        </button>
                    </div>
                </div>

                {{-- NOMINAL + KEMBALIAN (underline only) --}}
                <div class="mt-4 shrink-0 grid grid-cols-2 gap-5">

                    {{-- NOMINAL DIBAYAR --}}
                    <div>
                        <label class="text-xs text-black/45 dark:text-white/45">Nominal Dibayar</label>
                        <input type="number" min="0" step="100" x-model.number="paidAmount"
                            :readonly="paymentMethod !== 'cash'"
                            placeholder="0"
                            class="no-spinner appearance-none mt-1 w-full bg-transparent border-0 border-b border-dashed border-black/20 dark:border-white/20 focus:ring-0 focus:outline-none focus:border-lime-500 text-sm p-0 pb-1.5 text-[#171719] dark:text-white read-only:cursor-not-allowed read-only:opacity-60">
                    </div>

                    {{-- KEMBALIAN --}}
                    <div>
                        <label class="text-xs text-black/45 dark:text-white/45">Kembalian</label>
                        <div class="mt-1 border-b border-dashed border-black/20 dark:border-white/20 pb-1.5">
                            <p class="text-sm text-lime-600 dark:text-lime-400 font-bold" x-text="formatCurrency(change())"></p>
                        </div>
                    </div>

                </div>

                {{-- SPACER --}}
                <div class="flex-1 min-h-[20px]"></div>

                {{-- TOMBOL AKSI --}}
                <div class="shrink-0 space-y-2">
                    <button type="button" @click="submitTransaksi()"
                        :disabled="items.filter(i => i.product_id).length === 0 || isSubmitting"
                        class="w-full h-12 rounded-xl bg-lime-400 hover:bg-lime-500 disabled:opacity-40 disabled:cursor-not-allowed text-[#171719] flex items-center justify-center gap-2 transition">
                        <span x-show="!isSubmitting">💳 BAYAR <span class="text-xs opacity-60">(F2)</span></span>
                        <span x-show="isSubmitting">⏳ Memproses...</span>
                    </button>

                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="tahanTransaksi()"
                            :disabled="items.filter(i => i.product_id).length === 0"
                            class="h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 hover:bg-amber-200 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-400 disabled:opacity-40 disabled:cursor-not-allowed text-xs font-medium flex items-center justify-center gap-1.5 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="3" rx="2"/>
                                <path d="M9 3v18"/>
                            </svg>
                            Tahan
                        </button>

                        <button type="button" @click="batalTransaksi()"
                            :disabled="items.filter(i => i.product_id).length === 0"
                            class="h-10 rounded-xl bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 disabled:opacity-40 disabled:cursor-not-allowed text-xs font-medium flex items-center justify-center gap-1.5 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 6 6 18"/>
                                <path d="m6 6 12 12"/>
                            </svg>
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= MODAL SCANNER ================= --}}
    <div x-show="scannerOpen" x-transition x-cloak
        class="fixed inset-0 z-50 bg-black/80 backdrop-blur flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-4 w-full max-w-md">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-[#171719] dark:text-white">Scan Barcode via Kamera</h3>
                <button type="button" @click="tutupScanner()" class="text-gray-500 hover:text-black dark:hover:text-white text-lg">✕</button>
            </div>
            <div id="reader" class="rounded-xl overflow-hidden bg-black aspect-square"></div>
            <p class="text-xs text-center text-black/45 dark:text-white/45 mt-3">Arahkan kamera ke barcode produk</p>
        </div>
    </div>

    {{-- ================= MODAL STRUK ================= --}}
    <div x-show="strukOpen" x-transition x-cloak
        class="fixed inset-0 z-50 bg-black/70 backdrop-blur flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 w-full max-w-sm my-8">
            <div class="text-center mb-4">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-lime-100 dark:bg-lime-900/30 mb-2">
                    <svg class="w-7 h-7 text-lime-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>
                <h3 class="text-[#171719] dark:text-white text-lg">Transaksi Berhasil</h3>
            </div>

            <div id="strukArea" class="bg-white text-black rounded-xl p-6 font-mono text-[12px] leading-relaxed border border-black/10 w-full max-w-sm">
                <div class="text-center border-b border-dashed border-black/30 pb-2 mb-2">
                    <p class="text-base font-bold">WAR-MART</p>
                    <p>Jl. Contoh No. 123</p>
                    <p>Jakarta Selatan</p>
                </div>
                <div class="flex justify-between"><span>No</span><span x-text="lastTransaction?.invoice || '-'"></span></div>
                <div class="flex justify-between"><span>Kasir</span><span x-text="lastTransaction?.kasir || '-'"></span></div>
                <div class="flex justify-between"><span>Tanggal</span><span x-text="lastTransaction?.date || '-'"></span></div>
                <div class="border-t border-dashed border-black/30 my-2"></div>
                <template x-for="(it, i) in (lastTransaction?.items || [])" :key="i">
                    <div class="mb-1">
                        <p x-text="it.name"></p>
                        <div class="flex justify-between">
                            <span x-text="'  ' + it.qty + ' × ' + formatCurrency(it.price)"></span>
                            <span x-text="formatCurrency(it.qty * it.price)"></span>
                        </div>
                    </div>
                </template>
                <div class="border-t border-dashed border-black/30 my-2"></div>
                <div class="flex justify-between"><span>Subtotal</span><span x-text="formatCurrency(lastTransaction?.subtotal || 0)"></span></div>
                <div x-show="(lastTransaction?.diskon || 0) > 0" class="flex justify-between">
                    <span>Diskon</span><span x-text="'-' + formatCurrency(lastTransaction?.diskon || 0)"></span>
                </div>
                <div class="flex justify-between"><span>Pajak</span><span x-text="formatCurrency(lastTransaction?.pajak || 0)"></span></div>
                <div class="border-t border-dashed border-black/30 my-2"></div>
                <div class="flex justify-between text-sm font-bold"><span>TOTAL</span><span x-text="formatCurrency(lastTransaction?.total || 0)"></span></div>
                <div class="flex justify-between"><span>Bayar</span><span x-text="formatCurrency(lastTransaction?.paid || 0)"></span></div>
                <div class="flex justify-between"><span>Kembali</span><span x-text="formatCurrency(lastTransaction?.change || 0)"></span></div>
                <div class="border-t border-dashed border-black/30 mt-2 pt-2 text-center">
                    <p>Terima kasih 🙏</p>
                </div>
            </div>

            <button type="button" @click="cetakStruk()"
                class="mt-4 w-full h-11 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] font-bold text-sm transition flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                    <path d="M6 9V3a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6"/>
                    <rect x="6" y="14" width="12" height="8" rx="1"/>
                </svg>
                Unduh PDF Struk
            </button>

            <button type="button" @click="tutupStruk()"
                class="mt-2 w-full h-10 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-xs transition">
                Tutup & Transaksi Baru
            </button>
        </div>
    </div>

    {{-- ================= MODAL TRANSAKSI DITAHAN ================= --}}
    <div x-show="resumeModalOpen" x-transition x-cloak
        class="fixed inset-0 z-50 bg-black/70 backdrop-blur flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 w-full max-w-md">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-[#171719] dark:text-white font-semibold">Transaksi Ditahan</h3>
                <button type="button" @click="resumeModalOpen = false" class="text-gray-500 hover:text-black dark:hover:text-white text-lg">✕</button>
            </div>

            <div class="max-h-80 overflow-y-auto space-y-2">
                <template x-for="(held, index) in heldTransactions" :key="held.id">
                    <div class="border border-black/5 dark:border-white/10 rounded-xl p-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm text-[#171719] dark:text-white" x-text="held.items.length + ' item'"></p>
                            <p class="text-[10px] text-black/45 dark:text-white/45" x-text="held.held_at"></p>
                            <p class="text-xs text-lime-600 dark:text-lime-400 mt-1" x-text="formatCurrency(held.total)"></p>
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <button type="button" @click="resumeHeld(index)"
                                class="px-3 h-8 rounded-lg bg-lime-400 hover:bg-lime-500 text-black text-xs transition">
                                Lanjutkan
                            </button>
                            <button type="button" @click="deleteHeld(index)"
                                class="px-3 h-8 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-600 text-xs transition">
                                Hapus
                            </button>
                        </div>
                    </div>
                </template>
                <p x-show="heldTransactions.length === 0" class="text-center text-sm text-black/40 dark:text-white/40 py-6">
                    Tidak ada transaksi yang ditahan
                </p>
            </div>
        </div>
    </div>

</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
function saleForm() {
    const products    = @json($productData);
    const nextInvoice = {{ $nextInvoice ?? 1 }};
    const csrfToken   = '{{ csrf_token() }}';
    const storeUrl    = '{{ route('kasir.transaksi-baru.store') }}';
    const receiptUrl  = @json(route('kasir.transaksi-struk', ['sale' => '__SALE_ID__']));

    return {
        products,
        items: [{ key: Date.now(), product_id: '', quantity: 1, price: 0 }],
        barcode: '',
        searchProduk: '',
        paymentMethod: 'cash',
        paidAmount: 0,
        diskon: 0,
        scannerOpen: false,
        strukOpen: false,
        resumeModalOpen: false,
        lastTransaction: null,
        html5QrCode: null,
        shiftCountdown: '',
        invoiceNumber: '',
        nowISO: '',
        nowFormatted: '',
        isSubmitting: false,
        heldTransactions: [],

        init() {
            const pad = (n) => String(n).padStart(3, '0');
            const d = new Date();
            const ymd = String(d.getFullYear()).slice(2)
                + String(d.getMonth() + 1).padStart(2, '0')
                + String(d.getDate()).padStart(2, '0');
            this.invoiceNumber = `INV-${ymd}-${pad(nextInvoice)}`;

            let remaining = 2 * 60 * 60;
            const tick = () => {
                const dd = new Date();
                this.nowFormatted = dd.toLocaleDateString('id-ID', {
                    day: '2-digit', month: '2-digit', year: 'numeric'
                });
                this.nowISO = dd.getFullYear() + '-'
                    + String(dd.getMonth() + 1).padStart(2, '0') + '-'
                    + String(dd.getDate()).padStart(2, '0');

                const h = Math.floor(remaining / 3600);
                const m = Math.floor((remaining % 3600) / 60);
                const s = remaining % 60;
                this.shiftCountdown = String(h).padStart(2, '0') + ':'
                    + String(m).padStart(2, '0') + ':'
                    + String(s).padStart(2, '0');
                if (remaining > 0) remaining--;
            };
            tick();
            setInterval(tick, 1000);

            const held = localStorage.getItem('held_transactions');
            if (held) {
                try { this.heldTransactions = JSON.parse(held) || []; }
                catch (e) { this.heldTransactions = []; }
            }

            this.$nextTick(() => this.$refs.barcode?.focus());

            window.addEventListener('keydown', (e) => {
                if (e.key === 'F1') { e.preventDefault(); this.$refs.barcode?.focus(); }
                if (e.key === 'F2') { e.preventDefault(); if (this.items.filter(i => i.product_id).length) this.submitTransaksi(); }
                if (e.key === 'F4') { e.preventDefault(); this.kosongkan(); }
            });
        },

        get filteredProducts() {
            const q = this.searchProduk.toLowerCase().trim();
            if (!q) return [];
            return this.products.filter(p =>
                (p.name || '').toLowerCase().includes(q) ||
                String(p.sku || '').toLowerCase().includes(q)
            ).slice(0, 20);
        },
        getProductName(id) {
            const p = this.products.find(p => String(p.id) === String(id));
            return p ? p.name : '';
        },
        addProduct(p) {
            const existing = this.items.find(i => String(i.product_id) === String(p.id));
            if (existing) { existing.quantity = Number(existing.quantity || 0) + 1; }
            else {
                const empty = this.items.find(i => !i.product_id);
                if (empty) { empty.product_id = p.id; empty.price = p.price; empty.quantity = 1; }
                else { this.items.push({ key: Date.now() + Math.random(), product_id: p.id, quantity: 1, price: p.price }); }
            }
        },
        scanBarcode() {
            const code = this.barcode.trim().toLowerCase();
            if (!code) return;
            const product = this.products.find(p => String(p.sku || '').toLowerCase() === code);
            if (!product) { alert('SKU/barcode tidak ditemukan.'); this.barcode = ''; this.$nextTick(() => this.$refs.barcode?.focus()); return; }
            this.addProduct(product);
            this.barcode = '';
            this.$nextTick(() => this.$refs.barcode?.focus());
        },
        bukaScanner() {
            this.scannerOpen = true;
            this.$nextTick(() => {
                this.html5QrCode = new Html5Qrcode("reader");
                this.html5QrCode.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: 250 },
                    (decodedText) => { this.barcode = decodedText; this.scanBarcode(); this.tutupScanner(); },
                    () => {}
                ).catch(err => { alert('Gagal membuka kamera: ' + err); this.scannerOpen = false; });
            });
        },
        tutupScanner() {
            if (this.html5QrCode) { this.html5QrCode.stop().then(() => { this.html5QrCode.clear(); this.html5QrCode = null; }).catch(() => {}); }
            this.scannerOpen = false;
        },
        increaseQty(item) { item.quantity = Number(item.quantity || 0) + 1; },
        decreaseQty(item) { item.quantity = Math.max(1, Number(item.quantity || 1) - 1); },
        removeItem(index) { this.items.splice(index, 1); },
        kosongkan() {
            if (confirm('Kosongkan keranjang?')) {
                this.items = [{ key: Date.now(), product_id: '', quantity: 1, price: 0 }];
                this.paidAmount = 0;
                this.diskon = 0;
            }
        },

        total() {
            return this.items.reduce((s, i) => s + ((Number(i.price) || 0) * (Number(i.quantity) || 0)), 0);
        },
        diskonValue() {
            const d = Number(this.diskon) || 0;
            return Math.min(Math.max(0, d), this.total());
        },
        afterDiskon() {
            return this.total() - this.diskonValue();
        },
        pajak() {
            return Math.round(this.afterDiskon() * 0.11);
        },
        grandTotal() {
            return this.afterDiskon() + this.pajak();
        },
        change() {
            const paid = this.paymentMethod === 'cash' ? Number(this.paidAmount) || 0 : this.grandTotal();
            return Math.max(0, paid - this.grandTotal());
        },
        formatCurrency(v) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(v || 0);
        },

        tahanTransaksi() {
            const validItems = this.items.filter(i => i.product_id);
            if (!validItems.length) return;
            if (!confirm('Tahan transaksi ini? Bisa dilanjutkan nanti.')) return;

            this.heldTransactions.push({
                id: Date.now(),
                items: JSON.parse(JSON.stringify(validItems)),
                diskon: Number(this.diskon) || 0,
                held_at: new Date().toLocaleString('id-ID'),
                total: this.grandTotal(),
            });

            localStorage.setItem('held_transactions', JSON.stringify(this.heldTransactions));

            this.items = [{ key: Date.now(), product_id: '', quantity: 1, price: 0 }];
            this.paidAmount = 0;
            this.diskon = 0;
            alert('Transaksi berhasil ditahan.');
        },

        batalTransaksi() {
            const validItems = this.items.filter(i => i.product_id);
            if (!validItems.length) return;
            if (!confirm('Batalkan transaksi ini? Semua item di keranjang akan dihapus.')) return;

            this.items = [{ key: Date.now(), product_id: '', quantity: 1, price: 0 }];
            this.paidAmount = 0;
            this.diskon = 0;
            this.lastTransaction = null;
            this.$nextTick(() => this.$refs.barcode?.focus());
        },

        resumeHeld(index) {
            const held = this.heldTransactions[index];
            if (!held) return;

            if (this.items.filter(i => i.product_id).length > 0) {
                if (!confirm('Keranjang saat ini akan digantikan. Lanjutkan?')) return;
            }

            this.items = held.items.map(it => ({
                ...it,
                key: Date.now() + Math.random(),
            }));
            this.diskon = Number(held.diskon) || 0;

            this.heldTransactions.splice(index, 1);
            localStorage.setItem('held_transactions', JSON.stringify(this.heldTransactions));
            this.resumeModalOpen = false;
            this.$nextTick(() => this.$refs.barcode?.focus());
        },

        deleteHeld(index) {
            if (!confirm('Hapus transaksi yang ditahan ini?')) return;
            this.heldTransactions.splice(index, 1);
            localStorage.setItem('held_transactions', JSON.stringify(this.heldTransactions));
        },

        async submitTransaksi() {
            const validItems = this.items.filter(i => i.product_id);
            if (!validItems.length) return;

            if (this.paymentMethod === 'cash' && Number(this.paidAmount) < this.grandTotal()) {
                alert('Nominal pembayaran kurang dari total tagihan.');
                return;
            }

            this.isSubmitting = true;

            try {
                const payload = {
                    invoice: this.invoiceNumber,
                    customer_id: '',
                    sale_date: this.nowISO,
                    payment_method: this.paymentMethod,
                    paid_amount: this.paymentMethod === 'cash' ? this.paidAmount : this.grandTotal(),
                    diskon: this.diskonValue(),
                    items: validItems.map(i => ({
                        product_id: i.product_id,
                        quantity: i.quantity,
                    })),
                };

                const res = await fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(payload),
                });

                const text = await res.text();
                let data;

                try { data = JSON.parse(text); }
                catch (e) {
                    console.error('Server returned non-JSON:', text.substring(0, 1000));
                    alert('Server error. Cek Console (F12).\n\nPreview:\n' + text.substring(0, 300));
                    return;
                }

                if (!res.ok) {
                    if (data.errors) {
                        const msgs = Object.values(data.errors).flat().join('\n');
                        alert('Validasi gagal:\n' + msgs);
                    } else {
                        alert(data.message || 'Terjadi kesalahan.');
                    }
                    return;
                }

                this.lastTransaction = data.sale;
                this.strukOpen = true;

            } catch (err) {
                alert('Gagal menyimpan transaksi: ' + err.message);
            } finally {
                this.isSubmitting = false;
            }
        },

        cetakStruk() {
            const saleId = this.lastTransaction?.id;

            if (!saleId) {
                alert('ID transaksi tidak ditemukan. Silakan periksa data transaksi.');
                return;
            }

            const url = receiptUrl.replace('__SALE_ID__', encodeURIComponent(saleId));
            window.location.href = `${url}?download=pdf`;
        },

        tutupStruk() {
            this.strukOpen = false;
            this.items = [{ key: Date.now(), product_id: '', quantity: 1, price: 0 }];
            this.paidAmount = 0;
            this.diskon = 0;
            this.lastTransaction = null;
            window.location.reload();
        }
    };
}
</script>

<style>
    [x-cloak] { display: none !important; }
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }

    /* Hilangkan spinner input number */
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .no-spinner {
        -moz-appearance: textfield;
        appearance: textfield;
    }
</style>
</x-app-layout>
