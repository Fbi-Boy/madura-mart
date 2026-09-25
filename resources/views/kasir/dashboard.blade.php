<x-app-layout>
    <div class="space-y-6">

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.14em] text-black/40 dark:text-white/40">
                    Point of Sale
                </p>
                <h1 class="mt-1 text-2xl font-semibold tracking-[-0.03em] text-[#171719] dark:text-white">
                    Dashboard Kasir
                </h1>
                <p class="mt-1 text-sm text-black/45 dark:text-white/45">
                    Fokus pada shift, transaksi, pembayaran, dan tindakan cepat.
                </p>
            </div>

            @if($openShift)
                <div class="inline-flex items-center gap-2 rounded-full border border-[#A8F23A]/30 bg-[#A8F23A]/10 px-3 py-1.5 text-xs font-semibold text-[#4d6800] dark:text-[#A8F23A]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#75c51e]"></span>
                    Shift {{ $openShift->shift_number }} aktif
                </div>
            @else
                <a href="{{ route('kasir.buka-shift') }}"
                   class="inline-flex items-center justify-center rounded-xl bg-[#171719] px-4 py-2.5 text-xs font-semibold text-white transition hover:opacity-90 dark:bg-white dark:text-[#171719]">
                    Buka shift
                </a>
            @endif
        </div>

        @if(!$openShift)
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-500/20 dark:bg-amber-500/10">
                <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">Belum ada shift aktif.</p>
                <p class="mt-1 text-xs text-amber-700/80 dark:text-amber-200/70">
                    Buka shift terlebih dahulu sebelum membuat transaksi baru.
                </p>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-black/[0.06] bg-white p-4 shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">
                <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-black/40 dark:text-white/40">Omzet hari ini</p>
                <p class="mt-2 text-2xl font-semibold tracking-[-0.035em] text-[#171719] dark:text-white">
                    Rp {{ number_format($todayRevenue, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-black/40 dark:text-white/40">{{ $todayTransactions }} transaksi paid</p>
            </div>

            <div class="rounded-2xl border border-black/[0.06] bg-white p-4 shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">
                <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-black/40 dark:text-white/40">Kas shift</p>
                <p class="mt-2 text-2xl font-semibold tracking-[-0.035em] text-[#171719] dark:text-white">
                    Rp {{ number_format($expectedCash, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-black/40 dark:text-white/40">
                    {{ $openShift ? 'Opening cash + penjualan cash' : 'Tidak ada shift aktif' }}
                </p>
            </div>

            <div class="rounded-2xl border border-black/[0.06] bg-white p-4 shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">
                <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-black/40 dark:text-white/40">Cash</p>
                <p class="mt-2 text-2xl font-semibold tracking-[-0.035em] text-[#171719] dark:text-white">
                    Rp {{ number_format($paymentSummary['cash'], 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Pembayaran tunai hari ini</p>
            </div>

            <div class="rounded-2xl border border-black/[0.06] bg-white p-4 shadow-[0_8px_24px_rgba(0,0,0,0.035)] dark:border-white/[0.08] dark:bg-white/[0.04] dark:shadow-none">
                <p class="text-[10px] font-semibold uppercase tracking-[0.08em] text-black/40 dark:text-white/40">Non-cash</p>
                <p class="mt-2 text-2xl font-semibold tracking-[-0.035em] text-[#171719] dark:text-white">
                    Rp {{ number_format($paymentSummary['transfer'] + $paymentSummary['qris'], 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-black/40 dark:text-white/40">
                    Transfer + QRIS
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-[1.35fr_.65fr]">
            <section class="rounded-2xl border border-black/[0.06] bg-white p-5 dark:border-white/[0.08] dark:bg-white/[0.04]">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Transaksi terbaru</h2>
                        <p class="mt-1 text-xs text-black/40 dark:text-white/40">Aktivitas penjualan kasir ini.</p>
                    </div>

                    <div class="flex gap-2">
                        @if($openShift)
                            <a href="{{ route('kasir.transaksi-baru') }}"
                               class="rounded-xl bg-[#A8F23A] px-3 py-2 text-xs font-bold text-[#233600] hover:brightness-95">
                                + Transaksi baru
                            </a>
                        @endif
                        <a href="{{ route('kasir.riwayat-transaksi') }}"
                           class="rounded-xl border border-black/[0.07] px-3 py-2 text-xs font-semibold text-black/60 hover:bg-black/[0.03] dark:border-white/[0.08] dark:text-white/60 dark:hover:bg-white/[0.04]">
                            Riwayat
                        </a>
                    </div>
                </div>

                <div class="mt-5 overflow-x-auto">
                    <table class="w-full min-w-[600px] text-left">
                        <thead>
                            <tr class="border-b border-black/[0.06] text-[10px] uppercase tracking-[0.08em] text-black/35 dark:border-white/[0.08] dark:text-white/35">
                                <th class="pb-3 font-semibold">Invoice</th>
                                <th class="pb-3 font-semibold">Customer</th>
                                <th class="pb-3 font-semibold">Metode</th>
                                <th class="pb-3 text-right font-semibold">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black/[0.05] dark:divide-white/[0.06]">
                            @forelse($recentSales as $sale)
                                <tr>
                                    <td class="py-3 text-xs font-semibold text-[#171719] dark:text-white">
                                        {{ $sale->invoice }}
                                        <span class="mt-0.5 block text-[10px] font-normal text-black/35 dark:text-white/35">
                                            {{ $sale->sale_date->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-xs text-black/55 dark:text-white/55">{{ $sale->customer?->name ?? 'Umum' }}</td>
                                    <td class="py-3 text-xs capitalize text-black/55 dark:text-white/55">{{ $sale->payment_method }}</td>
                                    <td class="py-3 text-right text-xs font-semibold text-[#171719] dark:text-white">
                                        Rp {{ number_format($sale->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-10 text-center text-xs text-black/35 dark:text-white/35">Belum ada transaksi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="rounded-2xl border border-black/[0.06] bg-white p-5 dark:border-white/[0.08] dark:bg-white/[0.04]">
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Pembayaran hari ini</h2>
                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Distribusi omzet berdasarkan metode.</p>

                <div class="mt-5 space-y-3">
                    @foreach([
                        ['label' => 'Cash', 'value' => $paymentSummary['cash']],
                        ['label' => 'QRIS', 'value' => $paymentSummary['qris']],
                        ['label' => 'Transfer', 'value' => $paymentSummary['transfer']],
                    ] as $payment)
                        <div class="rounded-xl bg-black/[0.025] p-3 dark:bg-white/[0.035]">
                            <div class="flex items-center justify-between gap-3">
                                <span class="text-xs font-medium text-black/55 dark:text-white/55">{{ $payment['label'] }}</span>
                                <span class="text-xs font-semibold text-[#171719] dark:text-white">
                                    Rp {{ number_format($payment['value'], 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-black/[0.05] dark:bg-white/[0.08]">
                                @php
                                    $width = $todayRevenue > 0 ? min(100, ($payment['value'] / $todayRevenue) * 100) : 0;
                                @endphp
                                <div class="h-full rounded-full bg-[#A8F23A]" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 rounded-xl border border-black/[0.06] p-3 dark:border-white/[0.08]">
                    <p class="text-[10px] uppercase tracking-[0.08em] text-black/35 dark:text-white/35">Shift</p>
                    <p class="mt-1 text-xs font-semibold text-[#171719] dark:text-white">
                        {{ $openShift ? 'Shift sedang berjalan' : 'Belum dibuka' }}
                    </p>
                    @if($openShift)
                        <a href="{{ route('kasir.tutup-shift') }}" class="mt-2 inline-block text-xs font-semibold text-[#4d6800] hover:underline dark:text-[#A8F23A]">
                            Kelola penutupan shift →
                        </a>
                    @endif
                </div>
            </section>
        </div>

        <section class="rounded-2xl border border-black/[0.06] bg-white p-5 dark:border-white/[0.08] dark:bg-white/[0.04]">
            <div>
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Stok yang perlu diperhatikan</h2>
                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Produk aktif dengan stok lima unit atau kurang.</p>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-5">
                @forelse($lowStockProducts as $product)
                    <div class="rounded-xl bg-black/[0.025] p-3 dark:bg-white/[0.035]">
                        <p class="truncate text-xs font-semibold text-[#171719] dark:text-white">{{ $product->name }}</p>
                        <p class="mt-1 text-[10px] text-black/35 dark:text-white/35">{{ $product->sku }}</p>
                        <p class="mt-3 text-lg font-semibold {{ $product->stock === 0 ? 'text-red-600 dark:text-red-300' : 'text-amber-600 dark:text-amber-300' }}">
                            {{ $product->stock }}
                            <span class="text-[10px] font-medium">{{ $product->unit }}</span>
                        </p>
                    </div>
                @empty
                    <div class="sm:col-span-2 lg:col-span-5 rounded-xl bg-[#A8F23A]/10 p-4 text-xs text-[#4d6800] dark:text-[#A8F23A]">
                        Tidak ada stok kritis.
                    </div>
                @endforelse
            </div>
        </section>

    </div>
</x-app-layout>
