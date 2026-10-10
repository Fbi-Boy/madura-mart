<x-app-layout>
<div class="space-y-4 font-poppins max-w-3xl mx-auto">

    {{-- ================= SUCCESS HERO ================= --}}
    <div class="rounded-2xl border-2 border-lime-400 bg-gradient-to-br from-lime-50 to-white dark:from-lime-900/20 dark:to-transparent p-6 sm:p-8 relative overflow-hidden">

        {{-- DECORATIVE --}}
        <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-lime-300/40 dark:bg-lime-500/10 blur-3xl pointer-events-none"></div>

        {{-- SUCCESS ICON --}}
        <div class="relative text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-lime-400 shadow-lg shadow-lime-400/40 mb-4">
                <svg class="w-10 h-10 text-[#171719]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
            </div>

            <h1 class="text-xl sm:text-2xl font-bold text-[#171719] dark:text-white">
                Pesanan Berhasil Dibuat! 🎉
            </h1>
            <p class="mt-2 text-xs text-black/60 dark:text-white/60">
                Pesanan Anda sudah tercatat di Madura Mart
            </p>
        </div>

        {{-- ORDER NUMBER --}}
        <div class="relative mt-6 p-4 rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-sm border border-lime-200 dark:border-lime-500/20">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Order Number</p>
                    <p class="mt-1 text-base font-bold text-[#171719] dark:text-white font-mono">{{ $order->order_number }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Tanggal</p>
                    <p class="mt-1 text-xs font-semibold text-[#171719] dark:text-white">
                        {{ $order->order_date?->format('d M Y, H:i') }}
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- ================= STATUS CARD ================= --}}
    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold text-[#171719] dark:text-white">
                    @if($order->payment_status === 'paid')
                        Pembayaran Terkonfirmasi ✓
                    @else
                        Menunggu Pembayaran
                    @endif
                </p>
                <p class="mt-1 text-[11px] text-black/60 dark:text-white/60 leading-relaxed">
                    @if($order->payment_status === 'paid')
                        Pembayaran Anda sudah diverifikasi. Pesanan akan segera diproses.
                    @else
                        Segera selesaikan pembayaran Anda agar pesanan bisa diproses.
                    @endif
                </p>

                @if($order->payment_status !== 'paid' && $order->status !== 'cancelled')
                    <a href="{{ route('customer.payment.show', $order) }}"
                       class="inline-flex items-center gap-1.5 mt-3 h-9 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-bold transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                        Bayar Sekarang
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ================= ITEM LIST ================= --}}
    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <h2 class="text-sm font-bold text-[#171719] dark:text-white">Item Pesanan</h2>
            </div>
            <span class="px-2.5 py-1 rounded-lg bg-lime-100 dark:bg-lime-900/30 text-[10px] font-bold text-lime-700 dark:text-lime-400">
                {{ $order->items->count() }} item
            </span>
        </div>

        <div class="divide-y divide-black/5 dark:divide-white/10">
            @foreach ($order->items as $item)
                <div class="p-4 flex gap-3">
                    <div class="shrink-0 w-14 h-14 rounded-xl bg-gradient-to-br from-lime-100 to-lime-50 dark:from-lime-900/30 dark:to-lime-900/10 flex items-center justify-center">
                        <span class="text-xl font-black text-lime-500 opacity-90">
                            {{ strtoupper(substr($item->product?->name ?? 'P', 0, 1)) }}
                        </span>
                    </div>

                    <div class="flex-1 min-w-0 flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-semibold text-[#171719] dark:text-white truncate">
                                {{ $item->product?->name ?? 'Produk tidak tersedia' }}
                            </p>
                            <p class="mt-1 text-[10px] text-black/45 dark:text-white/45">
                                {{ $item->quantity }} {{ $item->product?->unit }} × Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                            </p>
                        </div>

                        <div class="mt-2 text-right">
                            <p class="text-xs font-bold text-[#171719] dark:text-white">
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

    </div>

    {{-- ================= INFO ALAMAT & KURIR ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <h3 class="text-xs font-bold text-[#171719] dark:text-white">Alamat Kirim</h3>
            </div>
            <p class="text-[11px] leading-relaxed text-black/70 dark:text-white/70">
                {{ $order->delivery_address ?: 'Alamat akan dikonfirmasi' }}
            </p>
        </div>

        <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="1" y="3" width="15" height="13"/>
                        <path d="M16 8h4l3 3v5h-7V8z"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                </div>
                <h3 class="text-xs font-bold text-[#171719] dark:text-white">Kurir</h3>
            </div>
            @if($order->courier?->name)
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-lime-400 flex items-center justify-center text-[11px] font-bold text-[#171719]">
                        {{ strtoupper(substr($order->courier->name, 0, 1)) }}
                    </div>
                    <span class="text-xs font-semibold text-[#171719] dark:text-white">{{ $order->courier->name }}</span>
                </div>
            @else
                <p class="text-[11px] italic text-black/40 dark:text-white/40">
                    Kurir akan ditugaskan
                </p>
            @endif
        </div>
    </div>

    {{-- ================= NEXT STEPS ================= --}}
    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
        <h3 class="text-xs font-bold uppercase tracking-wider text-black/45 dark:text-white/45 mb-4">Langkah Selanjutnya</h3>

        <div class="space-y-3">

            <div class="flex items-start gap-3">
                <div class="w-7 h-7 rounded-full bg-lime-400 flex items-center justify-center text-[11px] font-bold text-[#171719] shrink-0">
                    1
                </div>
                <div>
                    <p class="text-xs font-semibold text-[#171719] dark:text-white">
                        @if($order->payment_status === 'paid')
                            Pembayaran Diterima
                        @else
                            Selesaikan Pembayaran
                        @endif
                    </p>
                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">
                        @if($order->payment_status === 'paid')
                            Pembayaran sudah terverifikasi ✓
                        @else
                            Kirim bukti pembayaran untuk diproses
                        @endif
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-7 h-7 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center text-[11px] font-bold text-black/40 dark:text-white/40 shrink-0">
                    2
                </div>
                <div>
                    <p class="text-xs font-semibold text-black/40 dark:text-white/40">Pesanan Diproses</p>
                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Kami siapkan pesanan Anda</p>
                </div>
            </div>

            <div class="flex items-start gap-3">
                <div class="w-7 h-7 rounded-full bg-gray-200 dark:bg-white/10 flex items-center justify-center text-[11px] font-bold text-black/40 dark:text-white/40 shrink-0">
                    3
                </div>
                <div>
                    <p class="text-xs font-semibold text-black/40 dark:text-white/40">Pengiriman</p>
                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Kurir antar ke alamat Anda</p>
                </div>
            </div>

        </div>
    </div>

    {{-- ================= ACTIONS ================= --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <a href="{{ route('customer.orders.show', $order) }}"
           class="h-12 rounded-xl bg-gradient-to-r from-lime-400 to-lime-500 hover:from-lime-500 hover:to-lime-600 text-[#171719] text-sm font-black flex items-center justify-center gap-2 transition shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            Lihat Detail Pesanan
        </a>

        <a href="{{ route('customer.catalog.index') }}"
           class="h-12 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-bold flex items-center justify-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
            </svg>
            Lanjut Belanja
        </a>
    </div>

    {{-- LINK KE DASHBOARD --}}
    <div class="text-center pb-4">
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-1.5 text-[11px] font-medium text-black/50 dark:text-white/50 hover:text-lime-600 dark:hover:text-lime-400 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="m3 10 9-7 9 7"/><path d="M5 9v11h14V9"/><path d="M9 20v-6h6v6"/>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
