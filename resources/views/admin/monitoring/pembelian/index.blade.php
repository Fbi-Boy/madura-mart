<x-app-layout>

    <div
        x-data="{
            selectedId: 1,

            purchases: [
                {
                    id: 1,
                    invoice: 'PO-202501-0842',
                    supplier: 'PT Sumber Makmur',
                    date: '24 Jan 2025',
                    items: 24,
                    total: 342850000,
                    status: 'Approval',
                    payment: 'Jatuh Tempo'
                },
                {
                    id: 2,
                    invoice: 'PO-202501-0838',
                    supplier: 'CV Berkah Jaya',
                    date: '23 Jan 2025',
                    items: 18,
                    total: 185600000,
                    status: 'Dikirim',
                    payment: 'Belum Lunas'
                },
                {
                    id: 3,
                    invoice: 'PO-202501-0831',
                    supplier: 'PT Mitra Pangan',
                    date: '22 Jan 2025',
                    items: 32,
                    total: 275400000,
                    status: 'Diterima',
                    payment: 'Lunas'
                },
                {
                    id: 4,
                    invoice: 'PO-202501-0825',
                    supplier: 'CV Sentosa Abadi',
                    date: '21 Jan 2025',
                    items: 12,
                    total: 92400000,
                    status: 'Approval',
                    payment: 'Jatuh Tempo'
                },
                {
                    id: 5,
                    invoice: 'PO-202501-0819',
                    supplier: 'PT Makmur Bersama',
                    date: '20 Jan 2025',
                    items: 27,
                    total: 198750000,
                    status: 'Diterima',
                    payment: 'Lunas'
                }
            ],

            get selectedPurchase() {
                return this.purchases.find(p => p.id === this.selectedId) ?? this.purchases[0];
            },

            formatRupiah(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(value);
            }
        }"
        class="min-h-max pb-8"
    >

        {{-- =====================================================
            HEADER MONITORING PEMBELIAN
        ====================================================== --}}
        <section class="mb-4">

            {{-- TOP LABEL --}}
            <div class="flex flex-wrap items-center gap-2 mb-2">

                <span
                    class="inline-flex h-8 items-center gap-1.5 rounded-md
                           border border-[#A8F23A]/40
                           bg-[#A8F23A]/[0.12]
                           px-3 text-[10px] font-semibold
                           text-[#58752B]"
                >
                    <span class="w-1.5 h-1.5 rounded-full bg-[#A8F23A]"></span>
                    Audit Pengadaan &amp; PO
                </span>

                <span
                    class="inline-flex h-8 items-center gap-1.5 rounded-md
                           bg-[#E8F5C9]
                           px-2.5 text-[9px] font-semibold
                           uppercase tracking-[0.04em]
                           text-[#58752B]"
                >
                    <svg
                        class="w-3 h-3 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        aria-hidden="true"
                    >
                        <path d="M3 12h4l2-4 4 8 2-4h6"></path>
                    </svg>
                    REAL-TIME SUPPLIER INFLOW
                </span>

                <span class="text-[9px] text-black/30 dark:text-white/30">
                    Sinkronisasi: 2 Menit Lalu (Hub Gudang Pusat)
                </span>

            </div>


            {{-- TITLE + ACTION --}}
            <div class="flex flex-col xl:flex-row xl:items-end xl:justify-between gap-4">

                <div class="min-w-0">

                    <h1
                        class="text-[30px] sm:text-[34px] leading-none
                               font-bold tracking-[-0.035em]
                               text-[#171719] dark:text-white"
                    >
                        Monitoring Pembelian &amp; Suplai Barang
                    </h1>

                    <p
                        class="mt-2 max-w-[650px]
                               text-[11px] sm:text-[12px]
                               leading-[1.5]
                               text-black/45 dark:text-white/45"
                    >
                        Pengawasan menyeluruh arus pengadaan internal, verifikasi berkas PO multi-vendor,
                        deteksi deviasi harga beli kontrak distributor, dan kendali jatuh tempo faktur AP.
                    </p>

                </div>


                {{-- FILTER ACTION --}}
                <div class="flex flex-wrap items-center gap-2 shrink-0">

                    <button
                        type="button"
                        class="h-11 rounded-full
                               bg-white dark:bg-white/[0.06]
                               border border-black/[0.05] dark:border-white/[0.08]
                               px-4
                               text-[10px] font-medium
                               text-black/60 dark:text-white/60
                               shadow-[0_3px_12px_rgba(0,0,0,0.025)]"
                    >
                        <span class="inline-flex items-center gap-1.5">
                            <svg
                                class="w-3 h-3 shrink-0"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <rect x="3" y="4" width="18" height="17" rx="2"></rect>
                                <path d="M16 2v4"></path>
                                <path d="M8 2v4"></path>
                                <path d="M3 10h18"></path>
                            </svg>
                            <span>Bulan Ini (Jan 2025)</span>
                        </span>
                    </button>

                    <button
                        type="button"
                        class="h-11 rounded-full
                               bg-white dark:bg-white/[0.06]
                               border border-black/[0.05] dark:border-white/[0.08]
                               px-4
                               text-[10px] font-medium
                               text-black/60 dark:text-white/60
                               shadow-[0_3px_12px_rgba(0,0,0,0.025)]"
                    >
                        <span class="inline-flex items-center gap-1.5">
                            <svg
                                class="w-3 h-3 shrink-0"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <line x1="4" y1="6" x2="20" y2="6"></line>
                                <line x1="4" y1="12" x2="20" y2="12"></line>
                                <line x1="4" y1="18" x2="20" y2="18"></line>
                                <circle cx="9" cy="6" r="2"></circle>
                                <circle cx="15" cy="12" r="2"></circle>
                                <circle cx="11" cy="18" r="2"></circle>
                            </svg>
                            <span>Filter Status</span>
                        </span>
                    </button>

                    <button
                        type="button"
                        class="h-11 rounded-full
                               bg-[#273142]
                               px-4
                               text-[10px] font-semibold
                               text-white
                               shadow-[0_4px_14px_rgba(39,49,66,0.12)]"
                    >
                        <span class="inline-flex items-center gap-1.5">
                            <svg
                                class="w-3.5 h-3.5 shrink-0 text-[#A8F23A]"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.9"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                aria-hidden="true"
                            >
                                <path d="M12 3v11"></path>
                                <path d="m8 10 4 4 4-4"></path>
                                <path d="M4 18v2a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-2"></path>
                            </svg>
                            <span>Unduh Rekap Audit PO</span>
                        </span>
                    </button>

                </div>

            </div>

        </section>


        {{-- =====================================================
            PURCHASE ORDER AKTIF
        ====================================================== --}}
        <section
            class="rounded-[14px]
                   border border-black/[0.055] dark:border-white/[0.08]
                   bg-white dark:bg-white/[0.04]
                   overflow-hidden"
        >

            <div
                class="min-h-[64px] flex flex-col sm:flex-row sm:items-center
                       justify-between gap-3
                       border-b border-black/[0.05] dark:border-white/[0.07]
                       px-4 py-3"
            >

                <div>
                    <h3 class="text-[14px] font-semibold text-[#171719] dark:text-white">
                        Purchase Order Aktif
                    </h3>

                    <p class="mt-1 text-[9px] text-black/35 dark:text-white/35">
                        Monitoring pengadaan barang dan status supplier.
                    </p>
                </div>

                <div class="flex items-center gap-2">

                    <button
                        type="button"
                        class="h-9 rounded-full
                               border border-black/[0.07] dark:border-white/[0.1]
                               px-4 text-[10px] font-semibold
                               text-black/55 dark:text-white/55"
                    >
                        Lihat Semua
                    </button>

                    <button
                        type="button"
                        class="h-9 rounded-full
                               bg-[#20242B] dark:bg-white
                               px-4 text-[10px] font-semibold
                               text-white dark:text-[#171719]"
                    >
                        + Buat PO
                    </button>

                </div>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full min-w-[850px]">

                    <thead>
                        <tr class="border-b border-black/[0.04] dark:border-white/[0.06]">

                            <th class="px-4 py-3 text-left text-[10px] font-semibold uppercase tracking-[0.05em] text-black/35 dark:text-white/35">
                                Nomor PO
                            </th>

                            <th class="px-4 py-3 text-left text-[10px] font-semibold uppercase tracking-[0.05em] text-black/35 dark:text-white/35">
                                Supplier
                            </th>

                            <th class="px-4 py-3 text-left text-[10px] font-semibold uppercase tracking-[0.05em] text-black/35 dark:text-white/35">
                                Tanggal
                            </th>

                            <th class="px-4 py-3 text-left text-[10px] font-semibold uppercase tracking-[0.05em] text-black/35 dark:text-white/35">
                                SKU
                            </th>

                            <th class="px-4 py-3 text-left text-[10px] font-semibold uppercase tracking-[0.05em] text-black/35 dark:text-white/35">
                                Nilai
                            </th>

                            <th class="px-4 py-3 text-left text-[10px] font-semibold uppercase tracking-[0.05em] text-black/35 dark:text-white/35">
                                Status
                            </th>

                            <th class="px-4 py-3 text-left text-[10px] font-semibold uppercase tracking-[0.05em] text-black/35 dark:text-white/35">
                                Pembayaran
                            </th>

                            <th class="px-4 py-3 text-right text-[10px] font-semibold uppercase tracking-[0.05em] text-black/35 dark:text-white/35">
                                Aksi
                            </th>

                        </tr>
                    </thead>


                    <tbody>

                        <template
                            x-for="purchase in purchases"
                            :key="purchase.id"
                        >

                            <tr
                                @click="selectedId = purchase.id"
                                class="h-[54px] cursor-pointer
                                       border-b border-black/[0.035]
                                       dark:border-white/[0.05]
                                       transition
                                       hover:bg-black/[0.02]
                                       dark:hover:bg-white/[0.025]"
                                :class="selectedId === purchase.id
                                    ? 'bg-[#A8F23A]/[0.07]'
                                    : ''"
                            >

                                <td class="px-4">

                                    <div class="flex items-center gap-2">

                                        <span
                                            class="h-1.5 w-1.5 rounded-full bg-[#A8F23A]"
                                            x-show="selectedId === purchase.id"
                                        ></span>

                                        <span
                                            class="text-[9px] font-semibold text-[#171719] dark:text-white"
                                            x-text="purchase.invoice"
                                        ></span>

                                    </div>

                                </td>


                                <td class="px-4">

                                    <span
                                        class="text-[10px] text-[#171719] dark:text-white"
                                        x-text="purchase.supplier"
                                    ></span>

                                </td>


                                <td
                                    class="px-4 text-[10px] text-black/45 dark:text-white/45"
                                    x-text="purchase.date"
                                ></td>


                                <td
                                    class="px-4 text-[10px] text-black/45 dark:text-white/45"
                                    x-text="purchase.items + ' SKU'"
                                ></td>


                                <td
                                    class="px-4 text-[9px] font-semibold text-[#171719] dark:text-white"
                                    x-text="formatRupiah(purchase.total)"
                                ></td>


                                <td class="px-4">

                                    <span
                                        class="rounded-full px-2 py-1 text-[9px] font-semibold"
                                        :class="{
                                            'bg-[#FFF0C2] text-[#856400]': purchase.status === 'Approval',
                                            'bg-[#E7ECFF] text-[#5868AA]': purchase.status === 'Dikirim',
                                            'bg-[#E8F5C9] text-[#58752B]': purchase.status === 'Diterima'
                                        }"
                                        x-text="purchase.status"
                                    ></span>

                                </td>


                                <td class="px-4">

                                    <span
                                        class="rounded-full px-2 py-1 text-[9px] font-semibold"
                                        :class="{
                                            'bg-[#FFF0C2] text-[#856400]': purchase.payment === 'Jatuh Tempo',
                                            'bg-[#FFE8E8] text-[#B04444]': purchase.payment === 'Belum Lunas',
                                            'bg-[#E8F5C9] text-[#58752B]': purchase.payment === 'Lunas'
                                        }"
                                        x-text="purchase.payment"
                                    ></span>

                                </td>


                                <td class="px-4 text-right">

                                    <button
                                        type="button"
                                        @click.stop="selectedId = purchase.id"
                                        class="text-[10px] font-semibold text-[#5969B1]"
                                    >
                                        Detail
                                    </button>

                                </td>

                            </tr>

                        </template>

                    </tbody>

                </table>

            </div>


            {{-- PAGINATION --}}
            <div
                class="h-[46px] flex items-center justify-between
                       border-t border-black/[0.04] dark:border-white/[0.06]
                       px-4"
            >

                <p class="text-[9px] text-black/35 dark:text-white/35">
                    1–5 dari 28 purchase order
                </p>

                <div class="flex items-center gap-1">

                    <button
                        type="button"
                        class="h-6 min-w-6 rounded-md
                               border border-black/[0.07] dark:border-white/[0.1]
                               text-[8px] text-black/40 dark:text-white/40"
                    >
                        ‹
                    </button>

                    <button
                        type="button"
                        class="h-6 min-w-6 rounded-md
                               bg-[#A8F23A] text-[10px] font-semibold text-[#171719]"
                    >
                        1
                    </button>

                    <button
                        type="button"
                        class="h-6 min-w-6 rounded-md
                               border border-black/[0.07] dark:border-white/[0.1]
                               text-[8px] text-black/40 dark:text-white/40"
                    >
                        2
                    </button>

                    <button
                        type="button"
                        class="h-6 min-w-6 rounded-md
                               border border-black/[0.07] dark:border-white/[0.1]
                               text-[8px] text-black/40 dark:text-white/40"
                    >
                        3
                    </button>

                    <span class="px-1 text-[8px] text-black/25 dark:text-white/25">
                        ...
                    </span>

                    <button
                        type="button"
                        class="h-6 min-w-6 rounded-md
                               border border-black/[0.07] dark:border-white/[0.1]
                               text-[8px] text-black/40 dark:text-white/40"
                    >
                        6
                    </button>

                    <button
                        type="button"
                        class="h-6 min-w-6 rounded-md
                               border border-black/[0.07] dark:border-white/[0.1]
                               text-[8px] text-black/40 dark:text-white/40"
                    >
                        ›
                    </button>

                </div>

            </div>

        </section>


        {{-- =====================================================
            DETAIL + STATUS
        ====================================================== --}}
        <section class="mt-3 grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_300px] gap-3">


            {{-- DETAIL PO --}}
            <div
                class="rounded-[14px]
                       border border-black/[0.055] dark:border-white/[0.08]
                       bg-white dark:bg-white/[0.04]
                       overflow-hidden"
            >

                <div
                    class="min-h-[64px] flex items-center justify-between
                           border-b border-black/[0.05] dark:border-white/[0.07]
                           px-4"
                >

                    <div>

                        <p class="text-[9px] text-black/35 dark:text-white/35">
                            Detail Purchase Order
                        </p>

                        <h3
                            class="mt-1 text-[14px] font-semibold text-[#171719] dark:text-white"
                            x-text="selectedPurchase.invoice"
                        ></h3>

                    </div>


                    <span
                        class="rounded-full bg-[#FFF0C2] px-2.5 py-1
                               text-[9px] font-semibold text-[#856400]"
                        x-text="selectedPurchase.status"
                    ></span>

                </div>


                <div class="p-4">

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                        <div>
                            <p class="text-[9px] text-black/35 dark:text-white/35">
                                Supplier
                            </p>

                            <p
                                class="mt-1 text-[9px] font-semibold text-[#171719] dark:text-white"
                                x-text="selectedPurchase.supplier"
                            ></p>
                        </div>

                        <div>
                            <p class="text-[9px] text-black/35 dark:text-white/35">
                                Tanggal
                            </p>

                            <p
                                class="mt-1 text-[9px] font-semibold text-[#171719] dark:text-white"
                                x-text="selectedPurchase.date"
                            ></p>
                        </div>

                        <div>
                            <p class="text-[9px] text-black/35 dark:text-white/35">
                                Total SKU
                            </p>

                            <p
                                class="mt-1 text-[9px] font-semibold text-[#171719] dark:text-white"
                                x-text="selectedPurchase.items + ' SKU'"
                            ></p>
                        </div>

                        <div>
                            <p class="text-[9px] text-black/35 dark:text-white/35">
                                Total Pembelian
                            </p>

                            <p
                                class="mt-1 text-[9px] font-bold text-[#171719] dark:text-white"
                                x-text="formatRupiah(selectedPurchase.total)"
                            ></p>
                        </div>

                    </div>


                    {{-- PROGRESS --}}
                    <div class="mt-5">

                        <p class="text-[10px] font-semibold text-[#171719] dark:text-white">
                            Progress Pengadaan
                        </p>

                        <div class="mt-3 grid grid-cols-5 gap-1">

                            <div>
                                <div class="h-1 rounded-full bg-[#A8F23A]"></div>
                                <p class="mt-1.5 text-[9px] font-semibold text-black/50 dark:text-white/50">
                                    Draft
                                </p>
                            </div>

                            <div>
                                <div class="h-1 rounded-full bg-[#A8F23A]"></div>
                                <p class="mt-1.5 text-[9px] font-semibold text-black/50 dark:text-white/50">
                                    Approval
                                </p>
                            </div>

                            <div>
                                <div class="h-1 rounded-full bg-[#A8F23A]"></div>
                                <p class="mt-1.5 text-[9px] font-semibold text-black/50 dark:text-white/50">
                                    Purchase Order
                                </p>
                            </div>

                            <div>
                                <div class="h-1 rounded-full bg-black/10 dark:bg-white/10"></div>
                                <p class="mt-1.5 text-[9px] text-black/35 dark:text-white/35">
                                    Diterima
                                </p>
                            </div>

                            <div>
                                <div class="h-1 rounded-full bg-black/10 dark:bg-white/10"></div>
                                <p class="mt-1.5 text-[9px] text-black/35 dark:text-white/35">
                                    Selesai
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- PERLU PERHATIAN --}}
            <div
                class="rounded-[14px]
                       border border-black/[0.055] dark:border-white/[0.08]
                       bg-white dark:bg-white/[0.04]
                       p-4"
            >

                <div class="flex items-center justify-between">

                    <h3 class="text-[11px] font-semibold text-[#171719] dark:text-white">
                        Perlu Perhatian
                    </h3>

                    <span
                        class="w-6 h-6 rounded-full
                               bg-[#FFE7E7]
                               flex items-center justify-center
                               text-[8px] font-bold text-[#B04444]"
                    >
                        4
                    </span>

                </div>


                <div class="mt-4 space-y-3">

                    <div class="flex items-start gap-2">

                        <span class="mt-1 w-1.5 h-1.5 shrink-0 rounded-full bg-[#E94B4B]"></span>

                        <div>
                            <p class="text-[10px] font-semibold text-[#171719] dark:text-white">
                                3 invoice belum dibayar
                            </p>

                            <p class="mt-0.5 text-[9px] text-black/35 dark:text-white/35">
                                Perlu ditindaklanjuti
                            </p>
                        </div>

                    </div>


                    <div class="flex items-start gap-2">

                        <span class="mt-1 w-1.5 h-1.5 shrink-0 rounded-full bg-[#E8B83E]"></span>

                        <div>
                            <p class="text-[10px] font-semibold text-[#171719] dark:text-white">
                                2 PO menunggu approval
                            </p>

                            <p class="mt-0.5 text-[9px] text-black/35 dark:text-white/35">
                                Menunggu supervisor
                            </p>
                        </div>

                    </div>


                    <div class="flex items-start gap-2">

                        <span class="mt-1 w-1.5 h-1.5 shrink-0 rounded-full bg-[#A8F23A]"></span>

                        <div>
                            <p class="text-[10px] font-semibold text-[#171719] dark:text-white">
                                4 barang belum lengkap
                            </p>

                            <p class="mt-0.5 text-[9px] text-black/35 dark:text-white/35">
                                Periksa penerimaan gudang
                            </p>
                        </div>

                    </div>


                    <div class="flex items-start gap-2">

                        <span class="mt-1 w-1.5 h-1.5 shrink-0 rounded-full bg-[#7786D8]"></span>

                        <div>
                            <p class="text-[10px] font-semibold text-[#171719] dark:text-white">
                                1 supplier perlu evaluasi
                            </p>

                            <p class="mt-0.5 text-[9px] text-black/35 dark:text-white/35">
                                Review performa supplier
                            </p>
                        </div>

                    </div>

                </div>


                <button
                    type="button"
                    class="mt-5 w-full h-9 rounded-full
                           bg-[#F0F1F5] dark:bg-white/[0.07]
                           text-[10px] font-semibold
                           text-black/55 dark:text-white/55"
                >
                    Lihat Semua
                </button>

            </div>

        </section>

    </div>

</x-app-layout>