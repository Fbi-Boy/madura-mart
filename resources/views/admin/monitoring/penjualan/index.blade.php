<x-app-layout>

    <div
        x-data="{
            period: 'bulan',
            selectedId: 1,

            transactions: [
                { id: 1, invoice: 'TRX-20260924-001', time: '09:14', cashier: 'Rina Amelia', terminal: 'POS-01', items: 3, total: 205628, method: 'QRIS', status: 'Lunas' },
                { id: 2, invoice: 'TRX-20260924-002', time: '09:26', cashier: 'Dimas Pratama', terminal: 'POS-02', items: 5, total: 72150, method: 'Tunai', status: 'Lunas' },
                { id: 3, invoice: 'TRX-20260924-003', time: '09:41', cashier: 'Rina Amelia', terminal: 'POS-01', items: 7, total: 349650, method: 'Debit', status: 'Lunas' },
                { id: 4, invoice: 'TRX-20260924-004', time: '10:05', cashier: 'Bayu Nugraha', terminal: 'POS-04', items: 2, total: 94500, method: 'Transfer', status: 'Pending' },
                { id: 5, invoice: 'TRX-20260924-005', time: '10:18', cashier: 'Nabila Sari', terminal: 'POS-03', items: 4, total: 133200, method: 'QRIS', status: 'Lunas' }
            ],

            get selectedTransaction() {
                return this.transactions.find(t => t.id === this.selectedId) ?? this.transactions[0];
            },

            formatRupiah(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(value);
            }
        }"
        class="h-full min-h-0 flex flex-col overflow-hidden"
    >

        {{-- HEADER --}}
        <div class="h-[30px] shrink-0 flex items-center">
            <h2 class="text-[15px] font-semibold tracking-[-0.025em] leading-none text-[#171719] dark:text-white">
                Penjualan
            </h2>
        </div>

        {{-- TOP: SEMUA CARD DALAM 1 BARIS --}}
        <div class="h-[128px] shrink-0 grid grid-cols-5 gap-2.5">

            {{-- OMZET --}}
            <div class="min-w-0 rounded-[13px] border border-black/[0.055] dark:border-white/[0.08] bg-white dark:bg-white/[0.04] p-3 shadow-[0_3px_12px_rgba(0,0,0,0.025)]">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[8px] font-semibold tracking-[0.05em] text-black/45 dark:text-white/45">OMZET</p>

                        <p class="mt-2 text-[17px] font-bold leading-none tracking-[-0.04em] text-[#171719] dark:text-white">
                            <span x-show="period === 'minggu'">Rp 118.450.000</span>
                            <span x-show="period === 'bulan'">Rp 482.600.000</span>
                            <span x-show="period === 'tahun'">Rp 5.782.400.000</span>
                        </p>

                        <p class="mt-1.5 text-[8px] text-black/35 dark:text-white/35">
                            <span x-show="period === 'minggu'">7 hari terakhir</span>
                            <span x-show="period === 'bulan'">Bulan berjalan</span>
                            <span x-show="period === 'tahun'">Tahun berjalan</span>
                        </p>
                    </div>
                </div>

                <div class="mt-2.5 flex rounded-[7px] bg-[#F2F2F0] dark:bg-white/[0.07] p-0.5 w-fit">
                    <button type="button" @click="period = 'minggu'" :class="period === 'minggu' ? 'bg-[#171719] text-white dark:bg-white dark:text-[#171719]' : 'text-black/45 dark:text-white/45'" class="rounded-[5px] px-1.5 py-0.5 text-[7px] font-semibold">Minggu</button>
                    <button type="button" @click="period = 'bulan'" :class="period === 'bulan' ? 'bg-[#171719] text-white dark:bg-white dark:text-[#171719]' : 'text-black/45 dark:text-white/45'" class="rounded-[5px] px-1.5 py-0.5 text-[7px] font-semibold">Bulan</button>
                    <button type="button" @click="period = 'tahun'" :class="period === 'tahun' ? 'bg-[#171719] text-white dark:bg-white dark:text-[#171719]' : 'text-black/45 dark:text-white/45'" class="rounded-[5px] px-1.5 py-0.5 text-[7px] font-semibold">Tahun</button>
                </div>

                <div class="mt-2">
                    <span class="rounded-full bg-[#B9F43A] px-1.5 py-0.5 text-[7px] font-semibold text-[#315000]">↗ +8.4%</span>
                </div>
            </div>

            {{-- TRANSAKSI HARI INI --}}
            <div class="min-w-0 rounded-[13px] border border-black/[0.055] dark:border-white/[0.08] bg-white dark:bg-white/[0.04] p-3 shadow-[0_3px_12px_rgba(0,0,0,0.025)]">
                <p class="text-[8px] font-semibold tracking-[0.05em] text-black/45 dark:text-white/45">TRANSAKSI HARI INI</p>

                <div class="mt-2 flex items-end justify-between gap-2">
                    <p class="text-[22px] font-bold leading-none tracking-[-0.04em] text-[#171719] dark:text-white">312</p>
                    <span class="rounded-full bg-[#B9F43A] px-1.5 py-0.5 text-[7px] font-semibold text-[#315000]">↗ +14%</span>
                </div>

                <p class="mt-1.5 text-[8px] text-black/35 dark:text-white/35">transaksi tercatat hari ini</p>

                <div class="mt-3 h-1 rounded-full bg-black/[0.06] dark:bg-white/[0.08]">
                    <div class="h-full w-[76%] rounded-full bg-[#A8F23A]"></div>
                </div>
            </div>

            {{-- RATA-RATA --}}
            <div class="min-w-0 rounded-[13px] border border-black/[0.055] dark:border-white/[0.08] bg-white dark:bg-white/[0.04] p-3 shadow-[0_3px_12px_rgba(0,0,0,0.025)]">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="text-[8px] font-semibold tracking-[0.05em] text-black/45 dark:text-white/45">RATA-RATA TRANSAKSI</p>
                        <p class="mt-2 text-[18px] font-bold leading-none tracking-[-0.04em] text-[#171719] dark:text-white">Rp 128.500</p>
                        <p class="mt-1.5 text-[8px] text-black/35 dark:text-white/35">nilai rata-rata per transaksi</p>
                    </div>

                    <span class="shrink-0 rounded-full bg-black/[0.04] dark:bg-white/10 px-1.5 py-0.5 text-[7px] font-semibold text-black/45 dark:text-white/45">Stabil</span>
                </div>

                <p class="mt-3 text-[7px] text-black/30 dark:text-white/30">Basket size 4.2 SKU / pelanggan</p>
            </div>

            {{-- JUMLAH MART --}}
            <div class="min-w-0 rounded-[13px] border border-black/[0.055] dark:border-white/[0.08] bg-white dark:bg-white/[0.04] p-3 shadow-[0_3px_12px_rgba(0,0,0,0.025)]">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <p class="text-[8px] font-semibold tracking-[0.05em] text-black/45 dark:text-white/45">JUMLAH MART</p>
                        <p class="mt-2 text-[22px] font-bold leading-none tracking-[-0.04em] text-[#171719] dark:text-white">12</p>
                        <p class="mt-1.5 text-[8px] text-black/35 dark:text-white/35">mart aktif dalam jaringan</p>
                    </div>

                    <div class="flex -space-x-1.5">
                        <span class="flex h-5 w-5 items-center justify-center rounded-full border-2 border-white bg-[#A8F23A] text-[7px] font-bold text-[#171719] dark:border-[#202124]">M</span>
                        <span class="flex h-5 w-5 items-center justify-center rounded-full border-2 border-white bg-[#7C83E8] text-[7px] font-bold text-white dark:border-[#202124]">M</span>
                        <span class="flex h-5 w-5 items-center justify-center rounded-full border-2 border-white bg-[#DCE1F0] text-[6px] font-bold text-black/55 dark:border-[#202124]">+10</span>
                    </div>
                </div>

                <p class="mt-3 text-[7px] text-black/30 dark:text-white/30">11 aktif · 1 maintenance</p>
            </div>

            {{-- LAPORAN --}}
            <div class="min-w-0 rounded-[13px] bg-[#171719] text-white dark:bg-white dark:text-[#171719] p-3 flex flex-col justify-between">
                <div>
                    <span class="inline-flex rounded-full bg-white/10 dark:bg-black/[0.06] px-2 py-0.5 text-[7px] font-semibold text-white/60 dark:text-black/50">
                        Monitoring
                    </span>

                    <h3 class="mt-2 text-[15px] font-semibold tracking-[-0.03em]">Penjualan</h3>

                    <p class="mt-1 text-[7px] leading-[1.45] text-white/45 dark:text-black/45">
                        Pantau transaksi, omzet, kasir, dan kondisi penjualan mart.
                    </p>
                </div>

                <button type="button" class="h-7 w-full rounded-[8px] bg-[#A8F23A] text-[8px] font-semibold text-[#171719]">
                    Buat Laporan
                </button>
            </div>

        </div>


        {{-- BOTTOM: TABLE + DETAIL --}}
        <div class="mt-2.5 flex-1 min-h-0 grid grid-cols-[minmax(0,1fr)_280px] gap-2.5">

            {{-- TABLE --}}
            <div class="min-w-0 min-h-0 rounded-[13px] border border-black/[0.055] dark:border-white/[0.08] bg-white dark:bg-white/[0.04] overflow-hidden flex flex-col">

                <div class="h-[43px] shrink-0 flex items-center justify-between border-b border-black/[0.05] dark:border-white/[0.07] px-3">
                    <div>
                        <h3 class="text-[10px] font-semibold text-[#171719] dark:text-white">Riwayat Transaksi</h3>
                        <p class="text-[7px] text-black/35 dark:text-white/35">Pilih transaksi untuk melihat struk.</p>
                    </div>

                    <span class="rounded-full bg-black/[0.04] dark:bg-white/[0.07] px-2 py-1 text-[7px] text-black/40 dark:text-white/40">
                        312 transaksi
                    </span>
                </div>

                <div class="flex-1 min-h-0 overflow-hidden">
                    <table class="w-full table-fixed text-left">

                        <thead>
                            <tr class="border-b border-black/[0.045] dark:border-white/[0.06]">
                                <th class="w-[23%] px-2.5 py-2 text-[7px] font-semibold uppercase tracking-[0.05em] text-black/30 dark:text-white/30">Invoice</th>
                                <th class="w-[10%] px-2 py-2 text-[7px] font-semibold uppercase tracking-[0.05em] text-black/30 dark:text-white/30">Waktu</th>
                                <th class="w-[20%] px-2 py-2 text-[7px] font-semibold uppercase tracking-[0.05em] text-black/30 dark:text-white/30">Kasir</th>
                                <th class="w-[9%] px-2 py-2 text-[7px] font-semibold uppercase tracking-[0.05em] text-black/30 dark:text-white/30">Item</th>
                                <th class="w-[15%] px-2 py-2 text-[7px] font-semibold uppercase tracking-[0.05em] text-black/30 dark:text-white/30">Total</th>
                                <th class="w-[11%] px-2 py-2 text-[7px] font-semibold uppercase tracking-[0.05em] text-black/30 dark:text-white/30">Metode</th>
                                <th class="w-[12%] px-2 py-2 text-[7px] font-semibold uppercase tracking-[0.05em] text-black/30 dark:text-white/30">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <template x-for="transaction in transactions" :key="transaction.id">
                                <tr
                                    @click="selectedId = transaction.id"
                                    class="h-[40px] cursor-pointer border-b border-black/[0.035] dark:border-white/[0.05] transition hover:bg-black/[0.025] dark:hover:bg-white/[0.035]"
                                    :class="selectedId === transaction.id ? 'bg-[#A8F23A]/10 dark:bg-[#A8F23A]/[0.08]' : ''"
                                >
                                    <td class="px-2.5">
                                        <div class="flex items-center gap-1.5">
                                            <span x-show="selectedId === transaction.id" class="h-1.5 w-1.5 shrink-0 rounded-full bg-[#A8F23A]"></span>
                                            <span class="truncate text-[8px] font-semibold text-[#171719] dark:text-white" x-text="transaction.invoice"></span>
                                        </div>
                                    </td>

                                    <td class="px-2 text-[8px] text-black/45 dark:text-white/45" x-text="transaction.time"></td>

                                    <td class="px-2">
                                        <p class="truncate text-[8px] font-medium text-[#171719] dark:text-white" x-text="transaction.cashier"></p>
                                        <p class="truncate text-[7px] text-black/30 dark:text-white/30" x-text="transaction.terminal"></p>
                                    </td>

                                    <td class="px-2 text-[8px] text-black/45 dark:text-white/45" x-text="transaction.items + ' SKU'"></td>

                                    <td class="px-2 text-[8px] font-semibold text-[#171719] dark:text-white" x-text="formatRupiah(transaction.total)"></td>

                                    <td class="px-2">
                                        <span class="rounded-full bg-black/[0.04] dark:bg-white/[0.07] px-1.5 py-0.5 text-[7px] text-black/45 dark:text-white/45" x-text="transaction.method"></span>
                                    </td>

                                    <td class="px-2">
                                        <span
                                            class="rounded-full px-1.5 py-0.5 text-[7px] font-semibold"
                                            :class="{
                                                'bg-[#B9F43A] text-[#315000]': transaction.status === 'Lunas',
                                                'bg-[#FFF0B8] text-[#765700]': transaction.status === 'Pending',
                                                'bg-[#FFD6D6] text-[#8A2020]': transaction.status === 'Refund'
                                            }"
                                            x-text="transaction.status"
                                        ></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>

                    </table>
                </div>

                <div class="h-[31px] shrink-0 flex items-center justify-between border-t border-black/[0.045] dark:border-white/[0.06] px-3">
                    <p class="text-[7px] text-black/30 dark:text-white/30">1–5 dari 312 transaksi</p>

                    <div class="flex items-center gap-1">
                        <button type="button" class="h-5 min-w-5 rounded-md border border-black/[0.06] dark:border-white/[0.08] text-[7px] text-black/35 dark:text-white/35">‹</button>
                        <button type="button" class="h-5 min-w-5 rounded-md bg-[#A8F23A] text-[7px] font-semibold text-[#171719]">1</button>
                        <button type="button" class="h-5 min-w-5 rounded-md border border-black/[0.06] dark:border-white/[0.08] text-[7px] text-black/35 dark:text-white/35">2</button>
                        <button type="button" class="h-5 min-w-5 rounded-md border border-black/[0.06] dark:border-white/[0.08] text-[7px] text-black/35 dark:text-white/35">3</button>
                        <span class="text-[7px] text-black/25 dark:text-white/25">...</span>
                        <button type="button" class="h-5 min-w-5 rounded-md border border-black/[0.06] dark:border-white/[0.08] text-[7px] text-black/35 dark:text-white/35">52</button>
                    </div>
                </div>

            </div>


            {{-- RIGHT --}}
            <div class="min-h-0 flex flex-col gap-2.5">

                {{-- STRUK --}}
                <div class="min-h-0 flex-1 rounded-[13px] border border-black/[0.055] dark:border-white/[0.08] bg-white dark:bg-white/[0.04] overflow-hidden">

                    <div class="h-[42px] flex items-center justify-between border-b border-black/[0.05] dark:border-white/[0.07] px-3">
                        <div>
                            <p class="text-[10px] font-semibold text-[#171719] dark:text-white">Detail Struk</p>
                            <p class="text-[7px] text-black/30 dark:text-white/30" x-text="selectedTransaction.invoice"></p>
                        </div>

                        <span class="rounded-full bg-[#B9F43A] px-1.5 py-0.5 text-[7px] font-semibold text-[#315000]" x-text="selectedTransaction.status"></span>
                    </div>

                    <div class="p-3">

                        <div class="grid grid-cols-2 gap-y-1.5 border-b border-dashed border-black/10 dark:border-white/10 pb-2.5">
                            <div>
                                <p class="text-[7px] text-black/30 dark:text-white/30">No. Transaksi</p>
                                <p class="text-[8px] font-medium text-[#171719] dark:text-white" x-text="selectedTransaction.invoice"></p>
                            </div>

                            <div class="text-right">
                                <p class="text-[7px] text-black/30 dark:text-white/30">Waktu</p>
                                <p class="text-[8px] font-medium text-[#171719] dark:text-white" x-text="selectedTransaction.time"></p>
                            </div>

                            <div>
                                <p class="text-[7px] text-black/30 dark:text-white/30">Kasir</p>
                                <p class="text-[8px] font-medium text-[#171719] dark:text-white" x-text="selectedTransaction.cashier"></p>
                            </div>

                            <div class="text-right">
                                <p class="text-[7px] text-black/30 dark:text-white/30">Terminal</p>
                                <p class="text-[8px] font-medium text-[#171719] dark:text-white" x-text="selectedTransaction.terminal"></p>
                            </div>
                        </div>

                        <div class="py-2.5 space-y-1.5">
                            <div class="flex justify-between gap-2">
                                <span class="truncate text-[7px] text-black/45 dark:text-white/45">Sembako Full Cream 1L</span>
                                <span class="text-[7px] font-medium text-[#171719] dark:text-white">Rp 38.000</span>
                            </div>
                            <div class="flex justify-between gap-2">
                                <span class="truncate text-[7px] text-black/45 dark:text-white/45">Roti Gandum Toast</span>
                                <span class="text-[7px] font-medium text-[#171719] dark:text-white">Rp 28.000</span>
                            </div>
                            <div class="flex justify-between gap-2">
                                <span class="truncate text-[7px] text-black/45 dark:text-white/45">Beras Premium Pandan</span>
                                <span class="text-[7px] font-medium text-[#171719] dark:text-white">Rp 85.000</span>
                            </div>
                            <div class="flex justify-between gap-2">
                                <span class="truncate text-[7px] text-black/45 dark:text-white/45">Minyak Goreng</span>
                                <span class="text-[7px] font-medium text-[#171719] dark:text-white">Rp 37.000</span>
                            </div>
                        </div>

                        <div class="border-t border-dashed border-black/10 dark:border-white/10 pt-2">
                            <div class="flex justify-between">
                                <span class="text-[7px] text-black/40 dark:text-white/40">Subtotal</span>
                                <span class="text-[7px] font-medium text-[#171719] dark:text-white">Rp 195.000</span>
                            </div>

                            <div class="mt-1 flex justify-between">
                                <span class="text-[7px] text-black/40 dark:text-white/40">Diskon Member</span>
                                <span class="text-[7px] font-medium text-[#171719] dark:text-white">- Rp 9.750</span>
                            </div>

                            <div class="mt-1.5 flex items-center justify-between">
                                <span class="text-[8px] font-semibold text-[#171719] dark:text-white">Total Akhir</span>
                                <span class="text-[14px] font-bold tracking-[-0.03em] text-[#171719] dark:text-white" x-text="formatRupiah(selectedTransaction.total)"></span>
                            </div>
                        </div>

                        <button type="button" class="mt-2.5 w-full h-7 rounded-[8px] bg-[#A8F23A] text-[8px] font-semibold text-[#171719]">
                            ✓ Verifikasi Struk
                        </button>

                        <div class="mt-1.5 grid grid-cols-2 gap-1.5">
                            <button type="button" class="h-6 rounded-[7px] bg-[#FFF0F0] text-[7px] font-semibold text-[#A52A2A] dark:bg-[#FF5757]/10 dark:text-[#FF8585]">
                                Tandai Anomali
                            </button>

                            <button type="button" class="h-6 rounded-[7px] bg-black/[0.04] text-[7px] font-semibold text-black/50 dark:bg-white/[0.07] dark:text-white/50">
                                Log Audit
                            </button>
                        </div>

                    </div>
                </div>


                {{-- ANOMALI --}}
                <div class="h-[96px] shrink-0 rounded-[13px] border border-[#F1D7D7] dark:border-[#FF5757]/20 bg-[#FFF9F9] dark:bg-[#FF5757]/[0.06] p-3">

                    <div class="flex items-start gap-2">

                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-[7px] bg-[#FFE4E4] text-[#B42323] dark:bg-[#FF5757]/10 dark:text-[#FF8585]">
                            <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 3 2.8 19a1.5 1.5 0 0 0 1.3 2.2h15.8a1.5 1.5 0 0 0 1.3-2.2L12 3Z"></path>
                                <path d="M12 9v4"></path>
                                <path d="M12 17h.01"></path>
                            </svg>
                        </div>

                        <div class="min-w-0">
                            <h4 class="text-[9px] font-semibold text-[#8A2020] dark:text-[#FF8585]">
                                Laporkan Anomali Transaksi
                            </h4>

                            <p class="mt-0.5 text-[7px] leading-[1.45] text-[#9B6A6A] dark:text-white/40">
                                Temukan transaksi yang tidak wajar? Laporkan untuk ditinjau supervisor.
                            </p>
                        </div>

                    </div>

                    <button type="button" class="mt-2 w-full h-6 rounded-[7px] border border-[#E7BEBE] dark:border-[#FF5757]/20 text-[7px] font-semibold text-[#8A2020] dark:text-[#FF8585]">
                        Laporkan Transaksi Terpilih
                    </button>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
