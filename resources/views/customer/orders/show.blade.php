<x-app-layout>
<div class="space-y-4 font-poppins">

    {{-- ================= BREADCRUMB ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-4 py-3">
        <nav class="flex items-center gap-1.5 text-[10px] font-medium text-black/45 dark:text-white/45 overflow-hidden">
            <a href="{{ route('customer.orders.index') }}" class="hover:text-lime-600 dark:hover:text-lime-400 transition shrink-0">Pesanan</a>
            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="m9 18 6-6-6-6"/>
            </svg>
            <span class="text-[#171719] dark:text-white font-mono truncate">{{ $order->order_number }}</span>
        </nav>

        <a href="{{ route('customer.orders.index') }}"
           class="h-8 px-3 rounded-lg bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-[11px] font-medium flex items-center gap-1.5 transition shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    @php
        $statusColor = match($order->status) {
            'delivered' => ['bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400', 'Selesai'],
            'shipped'   => ['bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', 'Dikirim'],
            'cancelled' => ['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', 'Dibatalkan'],
            'processing'=> ['bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400', 'Diproses'],
            default     => ['bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'Menunggu'],
        };
    @endphp

    {{-- ================= HERO ORDER STATUS ================= --}}
    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-gradient-to-br from-[#171719] to-[#2A2A2E] dark:from-[#0B1120] dark:to-[#1E293B] p-6 relative overflow-hidden">
        <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-lime-400/20 blur-3xl pointer-events-none"></div>

        <div class="flex flex-wrap items-start justify-between gap-4 relative">
            <div>
                <p class="text-[10px] uppercase tracking-wider text-white/50">Order Number</p>
                <p class="mt-1 text-xl font-bold text-white font-mono">{{ $order->order_number }}</p>
                <p class="mt-2 text-xs text-white/60">{{ $order->order_date?->format('d M Y, H:i') }}</p>
            </div>

            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold uppercase tracking-wider {{ $statusColor[0] }}">
                {{ $statusColor[1] }}
            </span>
        </div>
    </div>

    {{-- ================= TRACKING TIMELINE ================= --}}
    <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">

        <div class="flex items-center gap-3 mb-5">
            <div class="w-9 h-9 rounded-xl bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="1" y="3" width="15" height="13"/>
                    <path d="M16 8h4l3 3v5h-7V8z"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Perjalanan Pesanan</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Tahap saat ini berdasarkan status order</p>
            </div>
        </div>

        {{-- TIMELINE HORIZONTAL --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach ($trackingSteps as $index => $step)
                @php
                    $isCancelled = $step['key'] === 'cancelled';
                    $isCurrent = $isCancelled
                        ? $order->status === 'cancelled'
                        : $currentStatusIndex !== false && $index <= $currentStatusIndex;
                @endphp

                <div class="relative rounded-xl border-2 p-3 transition-all
                    {{ $isCurrent
                        ? ($isCancelled
                            ? 'border-red-400 bg-red-50 dark:bg-red-900/20'
                            : 'border-lime-400 bg-lime-50 dark:bg-lime-900/20 shadow-md')
                        : 'border-black/5 dark:border-white/10 bg-gray-50 dark:bg-white/5' }}">

                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-xs font-bold
                            {{ $isCurrent
                                ? ($isCancelled
                                    ? 'bg-red-500 text-white'
                                    : 'bg-lime-400 text-[#171719]')
                                : 'bg-gray-200 dark:bg-white/10 text-black/40 dark:text-white/40' }}">
                            @if($isCurrent && !$isCancelled)
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                            @elseif($isCurrent && $isCancelled)
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                                </svg>
                            @else
                                {{ $index + 1 }}
                            @endif
                        </span>
                        <span class="text-[11px] font-bold leading-tight
                            {{ $isCurrent ? 'text-[#171719] dark:text-white' : 'text-black/40 dark:text-white/40' }}">
                            {{ $step['label'] }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- RIWAYAT STATUS DETAIL --}}
        @if($order->statusHistories->count())
            <div class="mt-5 pt-5 border-t border-dashed border-black/5 dark:border-white/10">
                <p class="text-[10px] font-bold uppercase tracking-wider text-black/45 dark:text-white/45 mb-3">Riwayat Perubahan</p>

                <div class="space-y-2">
                    @foreach ($order->statusHistories->sortByDesc('created_at') as $history)
                        <div class="flex items-start gap-3 p-3 rounded-xl bg-gray-50 dark:bg-white/5">
                            <div class="mt-1.5 w-2 h-2 rounded-full bg-lime-500 shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="text-xs font-bold text-[#171719] dark:text-white capitalize">
                                        {{ $history->to_status }}
                                    </span>
                                    <time class="text-[10px] text-black/45 dark:text-white/45 font-mono">
                                        {{ $history->created_at->format('d M Y, H:i') }}
                                    </time>
                                </div>
                                @if ($history->note)
                                    <p class="mt-1 text-[11px] text-black/60 dark:text-white/60 italic">{{ $history->note }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    {{-- ================= MAIN GRID ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- KIRI: ITEMS (2/3) --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- ITEM LIST --}}
            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

                <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-[#171719] dark:text-white">Daftar Item</h3>
                    </div>
                    <span class="px-2.5 py-1 rounded-lg bg-lime-100 dark:bg-lime-900/30 text-[10px] font-bold text-lime-700 dark:text-lime-400">
                        {{ $order->items->count() }} item
                    </span>
                </div>

                <div class="divide-y divide-black/5 dark:divide-white/10">
                    @foreach ($order->items as $item)
                        <div class="p-4 flex gap-3">
                            {{-- IMAGE --}}
                            <div class="shrink-0 w-16 h-16 rounded-xl bg-gradient-to-br from-lime-100 to-lime-50 dark:from-lime-900/30 dark:to-lime-900/10 flex items-center justify-center">
                                <span class="text-2xl font-black text-lime-500 opacity-90">
                                    {{ strtoupper(substr($item->product?->name ?? 'P', 0, 1)) }}
                                </span>
                            </div>

                            {{-- INFO --}}
                            <div class="flex-1 min-w-0 flex flex-col justify-between">
                                <div>
                                    <p class="text-xs font-semibold text-[#171719] dark:text-white leading-snug line-clamp-2">
                                        {{ $item->product?->name ?? 'Produk tidak tersedia' }}
                                    </p>
                                    <p class="mt-1 text-[10px] text-black/45 dark:text-white/45 font-mono">
                                        SKU: {{ $item->product?->sku }}
                                    </p>
                                </div>

                                <div class="flex items-end justify-between gap-3 mt-2">
                                    <div>
                                        <p class="text-[10px] text-black/45 dark:text-white/45">
                                            {{ $item->quantity }} {{ $item->product?->unit }} × Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <p class="text-sm font-bold text-[#171719] dark:text-white">
                                        Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- TOTAL --}}
                <div class="px-5 py-4 bg-lime-50 dark:bg-lime-900/20 border-t border-lime-200 dark:border-lime-500/20">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-lime-800 dark:text-lime-300">Total Pesanan</span>
                        <span class="text-xl font-black text-lime-600 dark:text-lime-400">
                            Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </section>

        </div>

        {{-- KANAN: INFO (1/3) --}}
        <div class="space-y-4">

            {{-- PENGIRIMAN --}}
            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-[#171719] dark:text-white">Alamat Kirim</h3>
                </div>

                <p class="text-xs leading-relaxed text-black/70 dark:text-white/70">
                    {{ $order->delivery_address ?: 'Alamat pengiriman belum tersedia' }}
                </p>

                <div class="mt-4 pt-4 border-t border-dashed border-black/5 dark:border-white/10">
                    <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Kurir</p>
                    @if($order->courier?->name)
                        <div class="mt-1.5 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-lime-400 flex items-center justify-center text-[11px] font-bold text-[#171719]">
                                {{ strtoupper(substr($order->courier->name, 0, 1)) }}
                            </div>
                            <span class="text-xs font-semibold text-[#171719] dark:text-white">{{ $order->courier->name }}</span>
                        </div>
                    @else
                        <p class="mt-1.5 text-xs italic text-black/40 dark:text-white/40">Belum ditugaskan</p>
                    @endif
                </div>
            </section>

            {{-- PEMBAYARAN --}}
            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center">
                        <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-[#171719] dark:text-white">Pembayaran</h3>
                </div>

                <div class="space-y-3">
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Metode</p>
                        <p class="mt-1 text-xs font-semibold text-[#171719] dark:text-white">
                            {{ $order->payment_method === 'qris' ? 'QRIS' : 'Transfer Bank' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Status</p>
                        @php
                            $paymentStatusColor = match($order->payment_status) {
                                'paid'     => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                default    => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                            };
                        @endphp
                        <span class="inline-flex mt-1 px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $paymentStatusColor }}">
                            {{ $order->payment_status }}
                        </span>
                    </div>

                    @if ($order->payment_status === 'rejected')
                        <div class="p-3 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-500/30">
                            <p class="text-[10px] font-semibold text-red-700 dark:text-red-300">Alasan Penolakan</p>
                            <p class="mt-1 text-[11px] text-red-600 dark:text-red-300">{{ $order->payment_rejection_reason }}</p>
                        </div>
                    @endif

                    @if ($order->payment_status !== 'paid' && $order->status !== 'cancelled')
                        <a href="{{ route('customer.payment.show', $order) }}"
                           class="w-full h-10 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-bold flex items-center justify-center gap-2 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            Bayar / Kirim Bukti
                        </a>
                    @endif
                </div>
            </section>


            {{-- ACTIONS --}}
            <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5 space-y-2">
                @if ($order->payment_status === 'paid')
                    <a href="{{ route('customer.orders.invoice', $order) }}"
                       class="w-full h-10 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-bold flex items-center justify-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <path d="m7 10 5 5 5-5"/>
                            <path d="M12 15V3"/>
                        </svg>
                        Unduh Invoice PDF
                    </a>
                @endif

                <a href="{{ route('customer.orders.index') }}"
                   class="w-full h-10 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-xs font-medium flex items-center justify-center gap-2 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                    </svg>
                    Kembali ke Pesanan
                </a>

                @if ($order->status === 'pending' && $order->payment_status !== 'paid')
                    <form method="POST" action="{{ route('customer.orders.cancel', $order) }}"
                          onsubmit="return confirm('Batalkan pesanan ini? Stok akan dikembalikan ke sistem.')">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="w-full h-10 rounded-xl bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 text-xs font-bold flex items-center justify-center gap-2 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                            </svg>
                            Batalkan Pesanan
                        </button>
                    </form>
                @endif
            </section>

        </div>
    </div>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
</x-app-layout>
