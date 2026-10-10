<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Kasir</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Buat Retur Baru</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pilih transaksi asal & produk yang akan diretur</p>
        </div>
        <a href="#" class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center transition">
            ← Kembali
        </a>
    </div>

    <form method="POST" action="#" class="space-y-5">
        @csrf

        {{-- ================= INFO RETUR ================= --}}
        <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Informasi Retur</h3>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">

                <div>
                    <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">No. Retur</label>
                    <div class="mt-2 h-11 flex items-center px-4 rounded-xl border border-black/5 dark:border-white/10 bg-gray-50 dark:bg-white/5">
                        <span class="text-sm font-bold text-[#171719] dark:text-white font-mono italic">RTR-260930-001</span>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Tanggal</label>
                    <div class="mt-2 h-11 flex items-center px-4 rounded-xl border border-black/5 dark:border-white/10 bg-gray-50 dark:bg-white/5">
                        <span class="text-sm font-bold text-[#171719] dark:text-white font-mono">
                            {{ now()->format('d/m/Y H:i') }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Kasir</label>
                    <div class="mt-2 h-11 flex items-center px-4 rounded-xl border border-black/5 dark:border-white/10 bg-gray-50 dark:bg-white/5">
                        <span class="text-sm font-bold text-[#171719] dark:text-white">{{ auth()->user()->name }}</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- ================= CARI TRANSAKSI ASAL ================= --}}
        <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Transaksi Asal</h3>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Cari & pilih invoice yang akan diretur</p>

            <div class="mt-4 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                </svg>
                <input type="text"
                    placeholder="Scan invoice / cari pelanggan..."
                    class="w-full h-12 pl-11 pr-4 rounded-xl border-2 border-lime-400 text-sm bg-transparent focus:outline-none focus:ring-4 focus:ring-lime-200 dark:text-white">
            </div>

            {{-- CONTOH HASIL DROPDOWN --}}
            <div class="mt-3 space-y-2">
                <button type="button" class="w-full text-left p-3 rounded-xl border border-black/5 dark:border-white/10 hover:border-lime-400 transition">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold text-[#171719] dark:text-white font-mono">INV-260930-008</p>
                            <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5">30/09/2026 12:17 · Budi Santoso</p>
                        </div>
                        <p class="text-xs font-bold text-lime-600 dark:text-lime-400">Rp 160.950</p>
                    </div>
                </button>

                <button type="button" class="w-full text-left p-3 rounded-xl border border-black/5 dark:border-white/10 hover:border-lime-400 transition">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold text-[#171719] dark:text-white font-mono">INV-260930-005</p>
                            <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5">30/09/2026 11:42 · Umum</p>
                        </div>
                        <p class="text-xs font-bold text-lime-600 dark:text-lime-400">Rp 85.000</p>
                    </div>
                </button>
            </div>
        </div>

        {{-- ================= PILIH ITEM YANG DIRETUR ================= --}}
        <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">

            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Item yang Diretur</h3>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pilih item & isi jumlah yang akan diretur</p>
                </div>
                <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-2 py-1 rounded font-medium">
                    2 item
                </span>
            </div>

            <div class="p-4 space-y-3">

                {{-- ITEM 1 --}}
                <div class="rounded-xl border border-black/5 dark:border-white/10 p-4">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" checked class="mt-1 w-4 h-4 rounded border-gray-300 text-lime-500 focus:ring-lime-400">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-[#171719] dark:text-white">Keripik Singkong 100g</p>
                                    <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5">SKU: KRK-001 · Rp 18.000</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-[10px] text-black/45 dark:text-white/45">Dibeli</p>
                                    <p class="text-xs font-bold text-[#171719] dark:text-white">2x</p>
                                </div>
                            </div>

                            <div class="mt-3 grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Qty Retur</label>
                                    <input type="number" value="1" min="1" max="2"
                                        class="no-spinner mt-1 w-full h-10 px-3 rounded-lg border border-black/10 dark:border-white/10 bg-transparent text-sm font-bold text-[#171719] dark:text-white focus:outline-none focus:border-lime-400">
                                </div>
                                <div>
                                    <label class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Subtotal</label>
                                    <div class="mt-1 h-10 flex items-center px-3 rounded-lg bg-lime-50 dark:bg-lime-900/20">
                                        <span class="text-sm font-bold text-lime-600 dark:text-lime-400">Rp 18.000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ITEM 2 --}}
                <div class="rounded-xl border border-black/5 dark:border-white/10 p-4">
                    <div class="flex items-start gap-3">
                        <input type="checkbox" class="mt-1 w-4 h-4 rounded border-gray-300 text-lime-500 focus:ring-lime-400">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-[#171719] dark:text-white">Teh Botol Sosro 350ml</p>
                                    <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5">SKU: TEH-002 · Rp 5.000</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-[10px] text-black/45 dark:text-white/45">Dibeli</p>
                                    <p class="text-xs font-bold text-[#171719] dark:text-white">5x</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- ================= DETAIL REFUND ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- FORM REFUND --}}
            <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Detail Refund</h3>

                <div class="mt-4 space-y-4">

                    {{-- METODE REFUND --}}
                    <div>
                        <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Metode Refund</label>
                        <div class="mt-2 grid grid-cols-4 gap-1.5">
                            <button type="button" class="h-9 rounded-lg border-2 border-lime-400 bg-lime-400 text-black text-[11px] font-medium flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/>
                                </svg>
                                Cash
                            </button>
                            <button type="button" class="h-9 rounded-lg border-2 border-black/10 dark:border-white/10 text-black/60 dark:text-white/60 text-[11px] font-medium flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/>
                                    <rect width="5" height="5" x="3" y="16" rx="1"/>
                                </svg>
                                QRIS
                            </button>
                            <button type="button" class="h-9 rounded-lg border-2 border-black/10 dark:border-white/10 text-black/60 dark:text-white/60 text-[11px] font-medium flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path d="m16 3 4 4-4 4"/><path d="M20 7H4"/><path d="m8 21-4-4 4-4"/><path d="M4 17h16"/>
                                </svg>
                                Transfer
                            </button>
                            <button type="button" class="h-9 rounded-lg border-2 border-black/10 dark:border-white/10 text-black/60 dark:text-white/60 text-[11px] font-medium flex items-center justify-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                                </svg>
                                Debit
                            </button>
                        </div>
                        <p class="mt-1.5 text-[10px] text-black/45 dark:text-white/45">
                            ⓘ Metode refund harus sama dengan metode pembayaran transaksi asal
                        </p>
                    </div>

                    {{-- ALASAN --}}
                    <div>
                        <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Alasan Retur</label>
                        <textarea rows="3" placeholder="Contoh: Produk rusak, salah beli, kadaluarsa..."
                            class="mt-2 w-full rounded-xl border border-black/10 dark:border-white/10 bg-transparent px-4 py-3 text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 resize-none"></textarea>
                    </div>

                </div>
            </div>

            {{-- RINGKASAN --}}
            <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Ringkasan Refund</h3>

                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-dashed border-black/5 dark:border-white/10">
                        <span class="text-black/60 dark:text-white/60">Item Dipilih</span>
                        <span class="font-bold text-[#171719] dark:text-white">1 dari 2</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-dashed border-black/5 dark:border-white/10">
                        <span class="text-black/60 dark:text-white/60">Total Qty Retur</span>
                        <span class="font-bold text-[#171719] dark:text-white">1 unit</span>
                    </div>

                    <div class="flex justify-between items-center py-3 bg-lime-50 dark:bg-lime-900/20 rounded-xl px-3">
                        <span class="font-semibold text-[#171719] dark:text-white">Total Refund</span>
                        <span class="font-bold text-lg text-lime-600 dark:text-lime-400">Rp 18.000</span>
                    </div>

                    <div class="rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-500/30 p-3 mt-3">
                        <div class="flex gap-2">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                            </svg>
                            <p class="text-[10px] text-amber-700 dark:text-amber-200">
                                Stok produk akan otomatis bertambah setelah retur disimpan.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="mt-5 flex gap-2">
                    <button type="submit"
                        class="flex-1 h-11 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center justify-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                        Simpan Retur
                    </button>
                    <button type="reset"
                        class="h-11 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium transition">
                        Reset
                    </button>
                </div>
            </div>
        </div>

    </form>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .no-spinner { -moz-appearance: textfield; appearance: textfield; }
</style>
</x-app-layout>
