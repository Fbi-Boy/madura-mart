<x-app-layout>

    <div
        x-data="{
            period: 'bulan',
            selectedId: 1,
            transactions: [
                {
                    id: 1,
                    invoice: 'TRX-20260924-001',
                    time: '09:14',
                    cashier: 'Rina Amelia',
                    terminal: 'POS-01',
                    customer: 'Ahmad Fauzi',
                    items: 3,
                    total: 205628,
                    method: 'QRIS',
                    status: 'Lunas'
                },
                {
                    id: 2,
                    invoice: 'TRX-20260924-002',
                    time: '09:26',
                    cashier: 'Dimas Pratama',
                    terminal: 'POS-02',
                    customer: 'Siti Aminah',
                    items: 5,
                    total: 72150,
                    method: 'Tunai',
                    status: 'Lunas'
                },
                {
                    id: 3,
                    invoice: 'TRX-20260924-003',
                    time: '09:41',
                    cashier: 'Rina Amelia',
                    terminal: 'POS-01',
                    customer: 'Budi Santoso',
                    items: 7,
                    total: 349650,
                    method: 'Debit',
                    status: 'Lunas'
                },
                {
                    id: 4,
                    invoice: 'TRX-20260924-004',
                    time: '10:05',
                    cashier: 'Bayu Nugraha',
                    terminal: 'POS-04',
                    customer: 'Maya Putri',
                    items: 2,
                    total: 94500,
                    method: 'Transfer',
                    status: 'Pending'
                },
                {
                    id: 5,
                    invoice: 'TRX-20260924-005',
                    time: '10:18',
                    cashier: 'Nabila Sari',
                    terminal: 'POS-03',
                    customer: 'Rizky Maulana',
                    items: 4,
                    total: 133200,
                    method: 'QRIS',
                    status: 'Lunas'
                },
                {
                    id: 6,
                    invoice: 'TRX-20260924-006',
                    time: '10:36',
                    cashier: 'Dimas Pratama',
                    terminal: 'POS-02',
                    customer: 'Putri Ananda',
                    items: 6,
                    total: 94350,
                    method: 'Tunai',
                    status: 'Refund'
                }
            ],

            get selectedTransaction() {
                return this.transactions.find(transaction => transaction.id === this.selectedId) ?? this.transactions[0];
            },

            formatRupiah(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(value);
            }
        }"
        class="h-full min-h-0 overflow-hidden pt-0"
    >

        {{-- HEADER --}}
        <div class="mb-3 flex items-end justify-between gap-4">

            <div>
                <h2 class="text-[17px] sm:text-[18px] font-semibold tracking-[-0.025em]
                           leading-none text-[#171719] dark:text-white">
                    Penjualan
                </h2>
            </div>

        </div>


        {{-- TOP CONTENT --}}
        <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_280px] gap-3">

            {{-- FOUR SUMMARY CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                {{-- OMZET --}}
                <div class="min-h-[132px] rounded-[15px] border border-black/[0.055]
                            dark:border-white/[0.08] bg-white dark:bg-white/[0.04]
                            p-4 shadow-[0_4px_16px_rgba(0,0,0,0.025)]">

                    <div class="flex items-start justify-between gap-3">

                        <div>
                            <p class="text-[10px] font-semibold tracking-[0.05em]
                                      text-black/45 dark:text-white/45">
                                OMZET
                            </p>

                            <p class="mt-2 text-[23px] font-bold leading-none tracking-[-0.04em]
                                      text-[#171719] dark:text-white">
                                <span x-show="period === 'minggu'">Rp 118.450.000</span>
                                <span x-show="period === 'bulan'">Rp 482.600.000</span>
                                <span x-show="period === 'tahun'">Rp 5.782.400.000</span>
                            </p>

                            <p class="mt-2 text-[10px] text-black/40 dark:text-white/40">
                                <span x-show="period === 'minggu'">7 hari terakhir</span>
                                <span x-show="period === 'bulan'">Bulan berjalan</span>
                                <span x-show="period === 'tahun'">Tahun berjalan</span>
                            </p>
                        </div>

                        <div class="flex rounded-[9px] bg-[#F2F2F0] dark:bg-white/[0.07] p-0.5">
                            <button
                                type="button"
                                @click="period = 'minggu'"
                                :class="period === 'minggu'
                                    ? 'bg-[#171719] text-white dark:bg-white dark:text-[#171719] shadow-sm'
                                    : 'text-black/45 dark:text-white/45'"
                                class="rounded-[7px] px-2 py-1 text-[9px] font-semibold transition"
                            >
                                Minggu
                            </button>

                            <button
                                type="button"
                                @click="period = 'bulan'"
                                :class="period === 'bulan'
                                    ? 'bg-[#171719] text-white dark:bg-white dark:text-[#171719] shadow-sm'
                                    : 'text-black/45 dark:text-white/45'"
                                class="rounded-[7px] px-2 py-1 text-[9px] font-semibold transition"
                            >
                                Bulan
                            </button>

                            <button
                                type="button"
                                @click="period = 'tahun'"
                                :class="period === 'tahun'
                                    ? 'bg-[#171719] text-white dark:bg-white dark:text-[#171719] shadow-sm'
                                    : 'text-black/45 dark:text-white/45'"
                                class="rounded-[7px] px-2 py-1 text-[9px] font-semibold transition"
                            >
                                Tahun
                            </button>
                        </div>

                    </div>

                    <div class="mt-4 flex items-center gap-1.5">
                        <span class="rounded-full bg-[#B9F43A] px-2 py-0.5 text-[9px] font-semibold text-[#315000]">
                            ↗ +8.4%
                        </span>

                        <span class="text-[9px] text-black/35 dark:text-white/35">
                            dari periode sebelumnya
                        </span>
                    </div>

                </div>


                {{-- TRANSAKSI HARI INI --}}
                <div class="min-h-[132px] rounded-[15px] border border-black/[0.055]
                            dark:border-white/[0.08] bg-white dark:bg-white/[0.04]
                            p-4 shadow-[0_4px_16px_rgba(0,0,0,0.025)]">

                    <div class="flex items-start justify-between">

                        <div>
                            <p class="text-[10px] font-semibold tracking-[0.05em]
                                      text-black/45 dark:text-white/45">
                                TRANSAKSI HARI INI
                            </p>

                            <p class="mt-2 text-[23px] font-bold leading-none tracking-[-0.04em]
                                      text-[#171719] dark:text-white">
                                312
                            </p>

                            <p class="mt-2 text-[10px] text-black/40 dark:text-white/40">
                                transaksi tercatat hari ini
                            </p>
                        </div>

                        <span class="rounded-full bg-[#B9F43A] px-2 py-1 text-[9px]
                                     font-semibold text-[#315000]">
                            ↗ +14%
                        </span>

                    </div>

                    <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-black/[0.06] dark:bg-white/[0.08]">
                        <div class="h-full w-[76%] rounded-full bg-[#A8F23A]"></div>
                    </div>

                </div>


                {{-- RATA-RATA TRANSAKSI --}}
                <div class="min-h-[132px] rounded-[15px] border border-black/[0.055]
                            dark:border-white/[0.08] bg-white dark:bg-white/[0.04]
                            p-4 shadow-[0_4px_16px_rgba(0,0,0,0.025)]">

                    <div class="flex items-start justify-between">

                        <div>
                            <p class="text-[10px] font-semibold tracking-[0.05em]
                                      text-black/45 dark:text-white/45">
                                RATA-RATA TRANSAKSI
                            </p>

                            <p class="mt-2 text-[23px] font-bold leading-none tracking-[-0.04em]
                                      text-[#171719] dark:text-white">
                                Rp 128.500
                            </p>

                            <p class="mt-2 text-[10px] text-black/40 dark:text-white/40">
                                rata-rata nilai per transaksi
                            </p>
                        </div>

                        <span class="rounded-full bg-[#EEF1F8] dark:bg-white/10 px-2 py-1
                                     text-[9px] font-semibold text-black/50 dark:text-white/50">
                            Stabil
                        </span>

                    </div>

                    <p class="mt-4 text-[9px] text-black/35 dark:text-white/35">
                        Basket size 4.2 SKU / pelanggan
                    </p>

                </div>


                {{-- JUMLAH MART --}}
                <div class="min-h-[132px] rounded-[15px] border border-black/[0.055]
                            dark:border-white/[0.08] bg-white dark:bg-white/[0.04]
                            p-4 shadow-[0_4px_16px_rgba(0,0,0,0.025)]">

                    <div class="flex items-start justify-between">

                        <div>
                            <p class="text-[10px] font-semibold tracking-[0.05em]
                                      text-black/45 dark:text-white/45">
                                JUMLAH MART
                            </p>

                            <p class="mt-2 text-[23px] font-bold leading-none tracking-[-0.04em]
                                      text-[#171719] dark:text-white">
                                12
                            </p>

                            <p class="mt-2 text-[10px] text-black/40 dark:text-white/40">
                                mart aktif dalam jaringan
                            </p>
                        </div>

                        <div class="flex -space-x-1.5 pt-0.5">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full
                                         border-2 border-white bg-[#A8F23A] text-[8px] font-bold
                                         text-[#171719] dark:border-[#202124]">M</span>
                            <span class="flex h-6 w-6 items-center justify-center rounded-full
                                         border-2 border-white bg-[#7C83E8] text-[8px] font-bold
                                         text-white dark:border-[#202124]">M</span>
                            <span class="flex h-6 w-6 items-center justify-center rounded-full
                                         border-2 border-white bg-[#DCE1F0] text-[8px] font-bold
                                         text-black/55 dark:border-[#202124]">+10</span>
                        </div>

                    </div>

                    <p class="mt-4 text-[9px] text-black/35 dark:text-white/35">
                        11 aktif · 1 maintenance
                    </p>

                </div>

            </div>


            {{-- REPORT CARD --}}
            <div class="min-h-[267px] rounded-[15px] bg-[#171719] text-white
                        dark:bg-white dark:text-[#171719]
                        p-5 flex flex-col justify-between">

                <div>

                    <span class="inline-flex rounded-full bg-white/10 px-2.5 py-1
                                 text-[9px] font-semibold text-white/65
                                 dark:bg-black/[0.06] dark:text-black/55">
                        Monitoring Penjualan
                    </span>

                    <h3 class="mt-5 text-[21px] font-semibold tracking-[-0.035em] leading-tight">
                        Penjualan
                    </h3>

                    <p class="mt-2.5 max-w-[230px] text-[10px] leading-[1.7]
                              text-white/50 dark:text-black/50">
                        Pantau seluruh transaksi, omzet, aktivitas kasir,
                        dan kondisi penjualan mart secara ringkas.
                    </p>

                </div>

                <div>

                    <div class="mb-4 flex items-center justify-between border-t
                                border-white/10 dark:border-black/10 pt-3">

                        <span class="text-[9px] text-white/40 dark:text-black/40">
                            Periode laporan
                        </span>

                        <span class="text-[9px] font-semibold text-white/70 dark:text-black/65">
                            September 2026
                        </span>

                    </div>

                    <button
                        type="button"
                        class="w-full h-9 rounded-[10px] bg-[#A8F23A] text-[#171719]
                               text-[10px] font-semibold transition
                               hover:brightness-95"
                    >
                        Buat Laporan
                    </button>

                </div>

            </div>

        </div>


        {{-- BOTTOM AREA --}}
        <div class="mt-3 grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_300px] gap-3">

            {{-- TRANSACTION TABLE --}}
            <div class="min-w-0 rounded-[15px] border border-black/[0.055]
                        dark:border-white/[0.08] bg-white dark:bg-white/[0.04]
                        overflow-hidden">

                <div class="flex items-center justify-between border-b border-black/[0.05]
                            dark:border-white/[0.07] px-4 py-3">

                    <div>
                        <h3 class="text-[12px] font-semibold text-[#171719] dark:text-white">
                            Riwayat Transaksi Penjualan
                        </h3>

                        <p class="mt-0.5 text-[9px] text-black/40 dark:text-white/40">
                            Pilih transaksi untuk melihat detail struk.
                        </p>
                    </div>

                    <span class="rounded-full bg-black/[0.04] dark:bg-white/[0.07]
                                 px-2.5 py-1 text-[9px] text-black/45 dark:text-white/45">
                        312 transaksi
                    </span>

                </div>


                <div class="overflow-x-auto">

                    <table class="w-full min-w-[700px] text-left">

                        <thead>
                            <tr class="border-b border-black/[0.045] dark:border-white/[0.06]">
                                <th class="px-3 py-2.5 text-[8px] font-semibold uppercase tracking-[0.06em] text-black/35 dark:text-white/35">
                                    Invoice
                                </th>
                                <th class="px-3 py-2.5 text-[8px] font-semibold uppercase tracking-[0.06em] text-black/35 dark:text-white/35">
                                    Waktu
                                </th>
                                <th class="px-3 py-2.5 text-[8px] font-semibold uppercase tracking-[0.06em] text-black/35 dark:text-white/35">
                                    Kasir
                                </th>
                                <th class="px-3 py-2.5 text-[8px] font-semibold uppercase tracking-[0.06em] text-black/35 dark:text-white/35">
                                    Item
                                </th>
                                <th class="px-3 py-2.5 text-[8px] font-semibold uppercase tracking-[0.06em] text-black/35 dark:text-white/35">
                                    Total
                                </th>
                                <th class="px-3 py-2.5 text-[8px] font-semibold uppercase tracking-[0.06em] text-black/35 dark:text-white/35">
                                    Metode
                                </th>
                                <th class="px-3 py-2.5 text-[8px] font-semibold uppercase tracking-[0.06em] text-black/35 dark:text-white/35">
                                    Status
                                </th>
                            </tr>
                        </thead>

                        <tbody>

                            <template x-for="transaction in transactions" :key="transaction.id">

                                <tr
                                    @click="selectedId = transaction.id"
                                    class="cursor-pointer border-b border-black/[0.035] dark:border-white/[0.05]
                                           transition hover:bg-black/[0.025] dark:hover:bg-white/[0.035]"
                                    :class="selectedId === transaction.id
                                        ? 'bg-[#A8F23A]/10 dark:bg-[#A8F23A]/[0.08]'
                                        : ''"
                                >

                                    <td class="px-3 py-2.5">
                                        <div class="flex items-center gap-2">

                                            <span
                                                class="h-1.5 w-1.5 rounded-full bg-[#A8F23A]"
                                                x-show="selectedId === transaction.id"
                                            ></span>

                                            <span class="text-[9px] font-semibold text-[#171719] dark:text-white"
                                                  x-text="transaction.invoice">
                                            </span>

                                        </div>
                                    </td>

                                    <td class="px-3 py-2.5 text-[9px] text-black/50 dark:text-white/50"
                                        x-text="transaction.time">
                                    </td>

                                    <td class="px-3 py-2.5">
                                        <div>
                                            <p class="text-[9px] font-medium text-[#171719] dark:text-white"
                                               x-text="transaction.cashier">
                                            </p>

                                            <p class="text-[8px] text-black/35 dark:text-white/35"
                                               x-text="transaction.terminal">
                                            </p>
                                        </div>
                                    </td>

                                    <td class="px-3 py-2.5 text-[9px] text-black/50 dark:text-white/50"
                                        x-text="transaction.items + ' SKU'">
                                    </td>

                                    <td class="px-3 py-2.5 text-[10px] font-semibold text-[#171719] dark:text-white"
                                        x-text="formatRupiah(transaction.total)">
                                    </td>

                                    <td class="px-3 py-2.5">
                                        <span class="rounded-full bg-black/[0.04] dark:bg-white/[0.07]
                                                     px-2 py-1 text-[8px] font-medium
                                                     text-black/50 dark:text-white/50"
                                              x-text="transaction.method">
                                        </span>
                                    </td>

                                    <td class="px-3 py-2.5">

                                        <span
                                            class="rounded-full px-2 py-1 text-[8px] font-semibold"
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


                <div class="flex items-center justify-between px-4 py-2.5">

                    <p class="text-[8px] text-black/35 dark:text-white/35">
                        Menampilkan 1–6 dari 312 transaksi
                    </p>

                    <div class="flex items-center gap-1">
                        <button type="button"
                                class="h-6 min-w-6 rounded-md border border-black/[0.06]
                                       dark:border-white/[0.08] text-[8px] text-black/40
                                       dark:text-white/40">
                            ‹
                        </button>

                        <button type="button"
                                class="h-6 min-w-6 rounded-md bg-[#A8F23A]
                                       text-[8px] font-semibold text-[#171719]">
                            1
                        </button>

                        <button type="button"
                                class="h-6 min-w-6 rounded-md border border-black/[0.06]
                                       dark:border-white/[0.08] text-[8px] text-black/40
                                       dark:text-white/40">
                            2
                        </button>

                        <button type="button"
                                class="h-6 min-w-6 rounded-md border border-black/[0.06]
                                       dark:border-white/[0.08] text-[8px] text-black/40
                                       dark:text-white/40">
                            3
                        </button>

                        <span class="px-1 text-[8px] text-black/30 dark:text-white/30">
                            ...
                        </span>

                        <button type="button"
                                class="h-6 min-w-6 rounded-md border border-black/[0.06]
                                       dark:border-white/[0.08] text-[8px] text-black/40
                                       dark:text-white/40">
                            52
                        </button>
                    </div>

                </div>

            </div>


            {{-- RIGHT COLUMN --}}
            <div class="flex flex-col gap-3">

                {{-- RECEIPT --}}
                <div class="rounded-[15px] border border-black/[0.055]
                            dark:border-white/[0.08] bg-white dark:bg-white/[0.04]
                            overflow-hidden">

                    <div class="flex items-center justify-between border-b
                                border-black/[0.05] dark:border-white/[0.07] px-4 py-3">

                        <div>
                            <p class="text-[11px] font-semibold text-[#171719] dark:text-white">
                                Detail Struk
                            </p>

                            <p class="mt-0.5 text-[8px] text-black/35 dark:text-white/35"
                               x-text="selectedTransaction.invoice">
                            </p>
                        </div>

                        <span
                            class="rounded-full bg-[#B9F43A] px-2 py-1 text-[8px]
                                   font-semibold text-[#315000]"
                            x-text="selectedTransaction.status"
                        ></span>

                    </div>


                    <div class="p-4">

                        <div class="grid grid-cols-2 gap-y-2 border-b border-dashed
                                    border-black/10 dark:border-white/10 pb-3">

                            <div>
                                <p class="text-[8px] text-black/35 dark:text-white/35">
                                    No. Transaksi
                                </p>

                                <p class="mt-0.5 text-[9px] font-medium text-[#171719] dark:text-white"
                                   x-text="selectedTransaction.invoice">
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-[8px] text-black/35 dark:text-white/35">
                                    Waktu
                                </p>

                                <p class="mt-0.5 text-[9px] font-medium text-[#171719] dark:text-white"
                                   x-text="selectedTransaction.time">
                                </p>
                            </div>

                            <div>
                                <p class="text-[8px] text-black/35 dark:text-white/35">
                                    Kasir
                                </p>

                                <p class="mt-0.5 text-[9px] font-medium text-[#171719] dark:text-white"
                                   x-text="selectedTransaction.cashier">
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-[8px] text-black/35 dark:text-white/35">
                                    Terminal
                                </p>

                                <p class="mt-0.5 text-[9px] font-medium text-[#171719] dark:text-white"
                                   x-text="selectedTransaction.terminal">
                                </p>
                            </div>

                        </div>


                        <div class="py-3 space-y-2">

                            <div class="flex items-center justify-between">
                                <span class="text-[8px] text-black/50 dark:text-white/50">
                                    Sembako Full Cream 1L
                                </span>

                                <span class="text-[8px] font-medium text-[#171719] dark:text-white">
                                    Rp 38.000
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-[8px] text-black/50 dark:text-white/50">
                                    Roti Gandum Toast
                                </span>

                                <span class="text-[8px] font-medium text-[#171719] dark:text-white">
                                    Rp 28.000
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-[8px] text-black/50 dark:text-white/50">
                                    Beras Premium Pandan
                                </span>

                                <span class="text-[8px] font-medium text-[#171719] dark:text-white">
                                    Rp 85.000
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-[8px] text-black/50 dark:text-white/50">
                                    Minyak Goreng
                                </span>

                                <span class="text-[8px] font-medium text-[#171719] dark:text-white">
                                    Rp 37.000
                                </span>
                            </div>

                        </div>


                        <div class="border-t border-dashed border-black/10
                                    dark:border-white/10 pt-3">

                            <div class="flex items-center justify-between">
                                <span class="text-[8px] text-black/45 dark:text-white/45">
                                    Subtotal
                                </span>

                                <span class="text-[8px] font-medium text-[#171719] dark:text-white">
                                    Rp 195.000
                                </span>
                            </div>

                            <div class="mt-1 flex items-center justify-between">
                                <span class="text-[8px] text-black/45 dark:text-white/45">
                                    Diskon Member
                                </span>

                                <span class="text-[8px] font-medium text-[#171719] dark:text-white">
                                    - Rp 9.750
                                </span>
                            </div>

                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-[9px] font-semibold text-[#171719] dark:text-white">
                                    Total Akhir
                                </span>

                                <span class="text-[17px] font-bold tracking-[-0.03em] text-[#171719] dark:text-white"
                                      x-text="formatRupiah(selectedTransaction.total)">
                                </span>
                            </div>

                        </div>


                        <button
                            type="button"
                            class="mt-3 w-full h-8 rounded-[9px] bg-[#A8F23A]
                                   text-[9px] font-semibold text-[#171719]"
                        >
                            ✓ Verifikasi Struk
                        </button>

                        <div class="mt-2 grid grid-cols-2 gap-2">

                            <button
                                type="button"
                                class="h-7 rounded-[8px] bg-[#FFF0F0] text-[8px]
                                       font-semibold text-[#A52A2A]
                                       dark:bg-[#FF5757]/10 dark:text-[#FF8585]"
                            >
                                Tandai Anomali
                            </button>

                            <button
                                type="button"
                                class="h-7 rounded-[8px] bg-black/[0.04] text-[8px]
                                       font-semibold text-black/55
                                       dark:bg-white/[0.07] dark:text-white/55"
                            >
                                Log Audit Kasir
                            </button>

                        </div>

                    </div>

                </div>


                {{-- ANOMALY REPORT --}}
                <div class="rounded-[15px] border border-[#F1D7D7]
                            dark:border-[#FF5757]/20
                            bg-[#FFF9F9] dark:bg-[#FF5757]/[0.06]
                            p-4">

                    <div class="flex items-start gap-2.5">

                        <div class="flex h-7 w-7 shrink-0 items-center justify-center
                                    rounded-[8px] bg-[#FFE4E4] text-[#B42323]
                                    dark:bg-[#FF5757]/10 dark:text-[#FF8585]">

                            <svg class="h-4 w-4"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="1.8"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">

                                <path d="M12 3 2.8 19a1.5 1.5 0 0 0 1.3 2.2h15.8a1.5 1.5 0 0 0 1.3-2.2L12 3Z"></path>
                                <path d="M12 9v4"></path>
                                <path d="M12 17h.01"></path>

                            </svg>

                        </div>

                        <div class="min-w-0">

                            <h4 class="text-[10px] font-semibold text-[#8A2020]
                                       dark:text-[#FF8585]">
                                Laporkan Anomali Transaksi
                            </h4>

                            <p class="mt-1 text-[8px] leading-[1.55]
                                      text-[#9B6A6A] dark:text-white/45">
                                Temukan transaksi yang tidak wajar?
                                Laporkan untuk ditinjau supervisor.
                            </p>

                        </div>

                    </div>

                    <button
                        type="button"
                        class="mt-3 w-full h-8 rounded-[9px] border border-[#E7BEBE]
                               dark:border-[#FF5757]/20 text-[8px] font-semibold
                               text-[#8A2020] dark:text-[#FF8585]
                               hover:bg-[#FFEAEA] dark:hover:bg-[#FF5757]/10 transition"
                    >
                        Laporkan Transaksi Terpilih
                    </button>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
