<x-app-layout>
<div class="space-y-4 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Belanja</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Pesanan Saya</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Riwayat seluruh pesanan yang terhubung dengan akun Anda</p>
        </div>

        <a href="{{ route('customer.catalog.index') }}"
           class="h-10 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 5v14"/><path d="M5 12h14"/>
            </svg>
            Belanja Lagi
        </a>
    </div>

    @if (!$customer)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                Profil customer belum terhubung dengan akun ini.
            </p>
        </div>
    @endif

    {{-- ================= FILTER TABS ================= --}}
    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-2">
        <div class="flex flex-wrap gap-1.5">

            <a href="{{ route('customer.orders.index') }}"
               class="h-10 px-4 rounded-xl text-xs font-medium flex items-center gap-2 transition
                   {{ !request('status') ? 'bg-[#171719] dark:bg-white text-white dark:text-[#171719]' : 'text-black/60 dark:text-white/60 hover:bg-gray-100 dark:hover:bg-white/10' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <path d="M14 2v6h6"/>
                </svg>
                Semua
            </a>

            <a href="{{ route('customer.orders.index', ['status' => 'pending']) }}"
               class="h-10 px-4 rounded-xl text-xs font-medium flex items-center gap-2 transition
                   {{ request('status') === 'pending' ? 'bg-amber-500 text-white' : 'text-black/60 dark:text-white/60 hover:bg-gray-100 dark:hover:bg-white/10' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                </svg>
                Menunggu
            </a>

            <a href="{{ route('customer.orders.index', ['status' => 'processing']) }}"
               class="h-10 px-4 rounded-xl text-xs font-medium flex items-center gap-2 transition
                   {{ request('status') === 'processing' ? 'bg-purple-500 text-white' : 'text-black/60 dark:text-white/60 hover:bg-gray-100 dark:hover:bg-white/10' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                    <path d="M21 3v5h-5"/>
                </svg>
                Diproses
            </a>

            <a href="{{ route('customer.orders.index', ['status' => 'shipped']) }}"
               class="h-10 px-4 rounded-xl text-xs font-medium flex items-center gap-2 transition
                   {{ request('status') === 'shipped' ? 'bg-blue-500 text-white' : 'text-black/60 dark:text-white/60 hover:bg-gray-100 dark:hover:bg-white/10' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="1" y="3" width="15" height="13"/>
                    <path d="M16 8h4l3 3v5h-7V8z"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
                Dikirim
            </a>

            <a href="{{ route('customer.orders.index', ['status' => 'delivered']) }}"
               class="h-10 px-4 rounded-xl text-xs font-medium flex items-center gap-2 transition
                   {{ request('status') === 'delivered' ? 'bg-lime-400 text-[#171719]' : 'text-black/60 dark:text-white/60 hover:bg-gray-100 dark:hover:bg-white/10' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
                Selesai
            </a>

            <a href="{{ route('customer.orders.index', ['status' => 'cancelled']) }}"
               class="h-10 px-4 rounded-xl text-xs font-medium flex items-center gap-2 transition
                   {{ request('status') === 'cancelled' ? 'bg-red-500 text-white' : 'text-black/60 dark:text-white/60 hover:bg-gray-100 dark:hover:bg-white/10' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                </svg>
                Dibatalkan
            </a>

        </div>
    </div>

    {{-- ================= INFO BAR ================= --}}
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-semibold text-[#171719] dark:text-white">Riwayat Pesanan</p>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                <span class="font-bold text-lime-600 dark:text-lime-400">{{ $orders->total() }}</span> pesanan tercatat
            </p>
        </div>
    </div>

    {{-- ================= ORDER LIST ================= --}}
    <div class="space-y-3">

        @forelse ($orders as $order)
            @php
                $statusColor = match($order->status) {
                    'delivered' => ['bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400', 'Selesai', 'check'],
                    'shipped'   => ['bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400', 'Dikirim', 'truck'],
                    'cancelled' => ['bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400', 'Dibatalkan', 'x'],
                    'processing'=> ['bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400', 'Diproses', 'refresh'],
                    default     => ['bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400', 'Menunggu', 'clock'],
                };
            @endphp

            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden hover:border-lime-400 hover:shadow-md transition-all">

                {{-- HEADER --}}
                <div class="px-4 sm:px-5 py-3 border-b border-black/5 dark:border-white/10 flex flex-wrap items-center justify-between gap-3 bg-gray-50 dark:bg-white/[0.02]">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg bg-lime-400 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-[#171719]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-bold text-[#171719] dark:text-white font-mono truncate">{{ $order->order_number }}</p>
                            <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5">{{ $order->order_date?->format('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusColor[0] }}">
                        @if($statusColor[2] === 'clock')
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                            </svg>
                        @elseif($statusColor[2] === 'refresh')
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/>
                            </svg>
                        @elseif($statusColor[2] === 'truck')
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <rect x="1" y="3" width="15" height="13"/>
                                <path d="M16 8h4l3 3v5h-7V8z"/>
                            </svg>
                        @elseif($statusColor[2] === 'check')
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        @elseif($statusColor[2] === 'x')
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                            </svg>
                        @endif
                        {{ $statusColor[1] }}
                    </span>
                </div>

                {{-- BODY --}}
                <div class="p-4 sm:p-5">

                    <div class="flex gap-4">

                        {{-- ICON PRODUK --}}
                        <div class="shrink-0 w-16 h-16 rounded-xl bg-gradient-to-br from-lime-100 to-lime-50 dark:from-lime-900/30 dark:to-lime-900/10 flex items-center justify-center">
                            <span class="text-2xl font-black text-lime-500 opacity-90">
                                {{ strtoupper(substr($order->items->first()?->product?->name ?? 'P', 0, 1)) }}
                            </span>
                        </div>

                        {{-- INFO --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-[#171719] dark:text-white truncate">
                                {{ $order->items->first()?->product?->name ?? 'Produk' }}
                                @if($order->items_count > 1)
                                    <span class="text-black/45 dark:text-white/45"> +{{ $order->items_count - 1 }} lainnya</span>
                                @endif
                            </p>

                            <div class="mt-2 flex flex-wrap items-center gap-3 text-[10px] text-black/45 dark:text-white/45">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <path d="M14 2v6h6"/>
                                    </svg>
                                    {{ $order->items_count }} item
                                </span>

                                @if($order->courier?->name)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                        {{ $order->courier->name }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- TOTAL --}}
                        <div class="shrink-0 text-right">
                            <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Total</p>
                            <p class="mt-0.5 text-base font-black text-lime-600 dark:text-lime-400 leading-none">
                                Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                            </p>
                        </div>

                    </div>

                    {{-- ACTIONS --}}
                    <div class="mt-4 pt-4 border-t border-dashed border-black/5 dark:border-white/10 flex flex-wrap items-center justify-end gap-2">

                        @if($order->status === 'pending' && $order->payment_status !== 'paid')
                            <a href="{{ route('customer.payment.show', $order) }}"
                               class="h-9 px-4 rounded-lg bg-lime-400 hover:bg-lime-500 text-[#171719] text-[11px] font-bold flex items-center gap-1.5 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                                </svg>
                                Bayar Sekarang
                            </a>
                        @endif

                        <a href="{{ route('customer.orders.show', $order) }}"
                           class="h-9 px-4 rounded-lg bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-[11px] font-medium flex items-center gap-1.5 transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            Lihat Detail
                        </a>

                    </div>

                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-black/10 dark:border-white/10 bg-white dark:bg-white/5 p-16 text-center">
                <div class="w-20 h-20 rounded-2xl bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/>
                    </svg>
                </div>
                <p class="text-base font-bold text-[#171719] dark:text-white">Belum ada pesanan</p>
                <p class="mt-1 text-xs text-black/45 dark:text-white/45">Yuk mulai belanja untuk membuat pesanan pertama Anda</p>
                <a href="{{ route('customer.catalog.index') }}"
                   class="inline-flex items-center gap-1.5 mt-5 h-11 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                        <path d="M21 3v5h-5"/>
                    </svg>
                    Mulai Belanja
                </a>
            </div>
        @endforelse

    </div>

    {{-- ================= PAGINATION ================= --}}
    @if ($orders->hasPages())
        <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 px-5 py-4">
            {{ $orders->links() }}
        </div>
    @endif

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
