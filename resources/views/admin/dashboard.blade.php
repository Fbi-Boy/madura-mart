<x-app-layout>

    <div class="pt-0">

        <h2 class="text-[22px] font-bold tracking-[-0.02em] leading-none
                   text-[#171719] dark:text-white mb-5">
            Dashboard
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">

            {{-- OMZET --}}
            <div class="min-h-[156px] rounded-[14px] border border-black/[0.05] dark:border-white/[0.08]
                        bg-white dark:bg-white/[0.04] p-4 shadow-[0_4px_16px_rgba(0,0,0,0.025)]">

                <div class="flex items-start justify-between">
                    <div class="w-10 h-10 rounded-[11px] bg-[#E8F0FF] dark:bg-[#A8F23A]/15
                                flex items-center justify-center text-[#273D8C] dark:text-[#A8F23A]">
                        <svg class="w-[21px] h-[21px]" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="6" width="18" height="12" rx="2"></rect>
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M7 9h.01"></path>
                            <path d="M17 15h.01"></path>
                        </svg>
                    </div>

                    <span class="rounded-full bg-[#B9F43A] px-2 py-1 text-[10px] font-semibold text-[#315000]">
                        ↗ +8.4%
                    </span>
                </div>

                <p class="mt-4 text-[10px] font-semibold tracking-[0.03em] text-black/45 dark:text-white/45">
                    OMZET BULAN INI
                </p>

                <p class="mt-1 text-[25px] font-bold leading-none tracking-[-0.035em] text-[#171719] dark:text-white">
                    Rp 482.600.000
                </p>

                <p class="mt-1.5 text-[11px] text-black/40 dark:text-white/40">
                    Target bulan tercapai 82%
                </p>
            </div>

            {{-- RATA-RATA TRANSAKSI --}}
            <div class="min-h-[156px] rounded-[14px] border border-black/[0.05] dark:border-white/[0.08]
                        bg-white dark:bg-white/[0.04] p-4 shadow-[0_4px_16px_rgba(0,0,0,0.025)]">

                <div class="flex items-start justify-between">
                    <div class="w-10 h-10 rounded-[11px] bg-[#E8F0FF] dark:bg-[#A8F23A]/15
                                flex items-center justify-center text-[#5267D8] dark:text-[#A8F23A]">
                        <svg class="w-[20px] h-[20px]" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="5" y="3" width="14" height="18" rx="2"></rect>
                            <path d="M8 17v-4"></path>
                            <path d="M12 17V9"></path>
                            <path d="M16 17v-7"></path>
                        </svg>
                    </div>

                    <span class="rounded-full bg-[#EEF1F8] dark:bg-white/10 px-2 py-1 text-[10px] font-semibold text-black/55 dark:text-white/55">
                        ≋ Stabil
                    </span>
                </div>

                <p class="mt-4 text-[10px] font-semibold tracking-[0.03em] text-black/45 dark:text-white/45">
                    RATA-RATA TRANSAKSI
                </p>

                <p class="mt-1 text-[25px] font-bold leading-none tracking-[-0.035em] text-[#171719] dark:text-white">
                    Rp 128.500
                </p>

                <p class="mt-1.5 text-[11px] text-black/40 dark:text-white/40">
                    Basket size 4.2 SKU/pelanggan
                </p>
            </div>

            {{-- TRANSAKSI HARI INI --}}
            <div class="min-h-[156px] rounded-[14px] border border-black/[0.05] dark:border-white/[0.08]
                        bg-white dark:bg-white/[0.04] p-4 shadow-[0_4px_16px_rgba(0,0,0,0.025)]">

                <div class="flex items-start justify-between">
                    <div class="w-10 h-10 rounded-[11px] bg-[#E8F0FF] dark:bg-[#A8F23A]/15
                                flex items-center justify-center text-[#173C77] dark:text-[#A8F23A]">
                        <svg class="w-[21px] h-[21px]" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="9" cy="20" r="1"></circle>
                            <circle cx="19" cy="20" r="1"></circle>
                            <path d="M3 4h2l2.4 11.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 1.9-1.5L21 8H7"></path>
                            <path d="M16 5l2 2-2 2"></path>
                            <path d="M18 7h-5"></path>
                        </svg>
                    </div>

                    <span class="rounded-full bg-[#B9F43A] px-2 py-1 text-[10px] font-semibold text-[#315000]">
                        ↗ +14%
                    </span>
                </div>

                <p class="mt-4 text-[10px] font-semibold tracking-[0.03em] text-black/45 dark:text-white/45">
                    TRANSAKSI HARI INI
                </p>

                <p class="mt-1 text-[25px] font-bold leading-none tracking-[-0.035em] text-[#171719] dark:text-white">
                    312 Transaksi
                </p>

                <p class="mt-1.5 text-[11px] text-black/40 dark:text-white/40">
                    Puncak kunjungan: 12.00 - 13.30
                </p>
            </div>

            {{-- TERMINAL KASIR --}}
            <div class="min-h-[156px] rounded-[14px] border border-black/[0.05] dark:border-white/[0.08]
                        bg-white dark:bg-white/[0.04] p-4 shadow-[0_4px_16px_rgba(0,0,0,0.025)]">

                <div class="flex items-start justify-between">
                    <div class="w-10 h-10 rounded-[11px] bg-[#E8F0FF] dark:bg-[#A8F23A]/15
                                flex items-center justify-center text-[#526A1A] dark:text-[#A8F23A]">
                        <svg class="w-[21px] h-[21px]" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="3" width="16" height="18" rx="2"></rect>
                            <path d="M8 7h8"></path>
                            <path d="M8 11h2"></path>
                            <path d="M14 11h2"></path>
                            <path d="M8 15h2"></path>
                            <path d="M14 15h2"></path>
                        </svg>
                    </div>

                    <div class="flex -space-x-1.5">
                        <span class="w-5 h-5 rounded-full bg-[#C8F53A] border-2 border-white dark:border-[#202124] text-[7px] font-bold flex items-center justify-center">K</span>
                        <span class="w-5 h-5 rounded-full bg-[#7776E8] border-2 border-white dark:border-[#202124] text-[7px] font-bold text-white flex items-center justify-center">K</span>
                        <span class="w-5 h-5 rounded-full bg-[#3B3B6D] border-2 border-white dark:border-[#202124] text-[7px] font-bold text-white flex items-center justify-center">K</span>
                        <span class="w-5 h-5 rounded-full bg-[#DDE2F0] border-2 border-white dark:border-[#202124] text-[7px] font-bold text-black/50 flex items-center justify-center">+1</span>
                    </div>
                </div>

                <p class="mt-4 text-[10px] font-semibold tracking-[0.03em] text-black/45 dark:text-white/45">
                    TERMINAL KASIR POS
                </p>

                <p class="mt-1 text-[25px] font-bold leading-none tracking-[-0.035em] text-[#171719] dark:text-white">
                    4 Kasir Aktif
                </p>

                <p class="mt-1.5 text-[11px] leading-4 text-black/40 dark:text-white/40">
                    POS-01, POS-02, POS-03,<br>
                    POS-04 Online
                </p>
            </div>

        </div>

    </div>

</x-app-layout>