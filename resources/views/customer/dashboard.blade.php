<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HERO / HEADER ================= --}}
    <div class="rounded-2xl border border-black/5 bg-gradient-to-br from-[#171719] to-[#2A2A2E] dark:border-white/10 px-6 py-6 sm:px-8 sm:py-7 text-white overflow-hidden relative">

        {{-- Glow accent --}}
        <div class="absolute -top-12 -right-12 w-48 h-48 rounded-full bg-lime-400/20 blur-3xl pointer-events-none"></div>

        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between relative">
            <div>
                <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-lime-400">Personal Shopping</p>
                <h1 class="mt-2 text-2xl sm:text-3xl font-bold tracking-tight">
                    Halo, {{ auth()->user()->name }} 👋
                </h1>
                <p class="mt-2 max-w-xl text-sm text-white/60 leading-relaxed">
                    Kelola pesanan Anda dari satu tempat. Status dan total di bawah diambil langsung dari data order akun Anda.
                </p>
            </div>

            <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm px-5 py-4 min-w-[200px]">
                <p class="text-[10px] font-medium uppercase tracking-wider text-white/50">Total Pembayaran</p>
                <p class="mt-1 text-2xl font-bold text-lime-400">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                <p class="mt-0.5 text-[10px] text-white/40">terkonfirmasi</p>
            </div>
        </div>
    </div>

    {{-- ================= QUICK ACCESS ================= --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <a href="{{ route('customer.catalog.index') }}"
           class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-4 hover:border-lime-400 hover:shadow-md transition-all group">
            <div class="w-10 h-10 rounded-xl bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-[#171719] dark:text-white">Jelajahi Produk</p>
            <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Cari produk & stok tersedia</p>
        </a>

        <a href="{{ route('customer.cart.index') }}"
           class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-4 hover:border-lime-400 hover:shadow-md transition-all group">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                </div>
                @if($cartItemCount > 0)
                    <span class="shrink-0 px-2 py-0.5 rounded-lg bg-lime-100 dark:bg-lime-900/30 text-[10px] font-bold text-lime-700 dark:text-lime-400">
                        {{ $cartItemCount }}
                    </span>
                @endif
            </div>
            <p class="text-xs font-bold text-[#171719] dark:text-white">Keranjang</p>
            <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Lanjut checkout</p>
        </a>

        <a href="{{ route('customer.address.edit') }}"
           class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-4 hover:border-lime-400 hover:shadow-md transition-all group">
            <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-[#171719] dark:text-white">Alamat</p>
            <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Kelola alamat kirim</p>
        </a>

        <a href="{{ route('customer.orders.index') }}"
           class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-4 hover:border-lime-400 hover:shadow-md transition-all group">
            <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <path d="M14 2v6h6"/>
                    <path d="M16 13H8"/><path d="M16 17H8"/>
                </svg>
            </div>
            <p class="text-xs font-bold text-[#171719] dark:text-white">Riwayat Order</p>
            <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Lihat semua pesanan</p>
        </a>
    </div>

    {{-- ================= KPI CARDS ================= --}}
    @php
        $kpis = [
            ['label' => 'Total Pesanan',    'value' => $totalOrders,     'hint' => 'semua order akun',    'icon' => 'shopping-bag', 'color' => 'lime'],
            ['label' => 'Pesanan Aktif',    'value' => $activeOrders,    'hint' => 'sedang berjalan',     'icon' => 'clock',        'color' => 'amber'],
            ['label' => 'Pesanan Selesai',  'value' => $completedOrders, 'hint' => 'status delivered',    'icon' => 'check',        'color' => 'emerald'],
            ['label' => 'Dibatalkan',       'value' => $cancelledOrders, 'hint' => 'status cancelled',    'icon' => 'x',            'color' => 'red'],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @foreach ($kpis as $kpi)
            <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">{{ $kpi['label'] }}</p>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center
                        {{ $kpi['color'] === 'lime' ? 'bg-lime-100 dark:bg-lime-900/30 text-lime-600 dark:text-lime-400' : '' }}
                        {{ $kpi['color'] === 'amber' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : '' }}
                        {{ $kpi['color'] === 'emerald' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : '' }}
                        {{ $kpi['color'] === 'red' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : '' }}">
                        @if($kpi['icon'] === 'shopping-bag')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                            </svg>
                        @elseif($kpi['icon'] === 'clock')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                            </svg>
                        @elseif($kpi['icon'] === 'check')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        @elseif($kpi['icon'] === 'x')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                            </svg>
                        @endif
                    </div>
                </div>
                <p class="text-xl font-bold text-[#171719] dark:text-white">{{ number_format($kpi['value'], 0, ',', '.') }}</p>
                <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">{{ $kpi['hint'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- ================= MAIN GRID ================= --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- PESANAN TERBARU (2/3) --}}
        <section class="xl:col-span-2 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">

            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Pesanan Terbaru</h2>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Aktivitas order terbaru akun Anda</p>
                </div>
                <a href="{{ route('customer.orders.index') }}"
                   class="text-[11px] font-medium text-lime-600 dark:text-lime-400 hover:underline flex items-center gap-1">
                    Lihat Semua
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] text-left">
                    <thead class="border-b border-black/5 dark:border-white/10">
                        <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                            <th class="px-5 py-3.5 font-medium">Order</th>
                            <th class="px-5 py-3.5 font-medium">Tanggal</th>
                            <th class="px-5 py-3.5 font-medium">Kurir</th>
                            <th class="px-5 py-3.5 font-medium">Status</th>
                            <th class="px-5 py-3.5 text-right font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/10">
                        @forelse ($recentOrders as $order)
                            @php
                                $statusColor = match($order->status) {
                                    'delivered' => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                    'shipped'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    'processing'=> 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                    default     => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                                <td class="px-5 py-4">
                                    <a href="{{ route('customer.orders.show', $order) }}"
                                       class="text-xs font-semibold text-[#171719] dark:text-white font-mono hover:text-lime-600 dark:hover:text-lime-400 transition">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-xs text-black/70 dark:text-white/70 font-mono">{{ $order->order_date?->format('d/m/Y') }}</p>
                                    <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 font-mono">{{ $order->order_date?->format('H:i') }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    @if($order->courier?->name)
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-lime-400 flex items-center justify-center text-[10px] font-bold text-black shrink-0">
                                                {{ strtoupper(substr($order->courier->name, 0, 1)) }}
                                            </div>
                                            <span class="text-xs text-black/70 dark:text-white/70 truncate">{{ $order->courier->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-[11px] text-black/40 dark:text-white/40 italic">Belum ditugaskan</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <p class="text-xs font-bold text-[#171719] dark:text-white">
                                        Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                        <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                                    </svg>
                                    <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada pesanan</p>
                                    <p class="mt-1 text-xs text-black/40 dark:text-white/40">Yuk mulai belanja di katalog!</p>
                                    <a href="{{ route('customer.catalog.index') }}"
                                       class="inline-flex items-center gap-1.5 mt-4 h-9 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-medium transition">
                                        Buka Katalog
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- STATUS PESANAN (1/3) --}}
        <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Status Pesanan</h3>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Distribusi status order akun Anda</p>

            <div class="mt-4 space-y-2">
                @php
                    $statusItems = [
                        'pending'    => ['label' => 'Menunggu',  'color' => 'amber',   'icon' => 'clock'],
                        'processing' => ['label' => 'Diproses',  'color' => 'purple',  'icon' => 'refresh'],
                        'shipped'    => ['label' => 'Dikirim',   'color' => 'blue',    'icon' => 'truck'],
                        'delivered'  => ['label' => 'Selesai',   'color' => 'lime',    'icon' => 'check'],
                        'cancelled'  => ['label' => 'Dibatalkan','color' => 'red',     'icon' => 'x'],
                    ];
                @endphp

                @foreach ($statusItems as $key => $data)
                    <div class="flex items-center justify-between gap-3 rounded-xl bg-gray-50 dark:bg-white/5 px-3 py-2.5">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-lg flex items-center justify-center
                                {{ $data['color'] === 'amber' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : '' }}
                                {{ $data['color'] === 'purple' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400' : '' }}
                                {{ $data['color'] === 'blue' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                                {{ $data['color'] === 'lime' ? 'bg-lime-100 dark:bg-lime-900/30 text-lime-600 dark:text-lime-400' : '' }}
                                {{ $data['color'] === 'red' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : '' }}">
                                @if($data['icon'] === 'clock')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                                    </svg>
                                @elseif($data['icon'] === 'refresh')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                        <path d="M21 3v5h-5"/>
                                    </svg>
                                @elseif($data['icon'] === 'truck')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <rect x="1" y="3" width="15" height="13"/>
                                        <path d="M16 8h4l3 3v5h-7V8z"/>
                                    </svg>
                                @elseif($data['icon'] === 'check')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>
                                @elseif($data['icon'] === 'x')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                                    </svg>
                                @endif
                            </div>
                            <span class="text-xs text-black/70 dark:text-white/70">{{ $data['label'] }}</span>
                        </div>
                        <span class="text-sm font-bold text-[#171719] dark:text-white">
                            {{ number_format($statusSummary[$key] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    {{-- ================= STATUS PEMBAYARAN ================= --}}
    <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
        <div class="flex items-start justify-between gap-4 mb-4">
            <div>
                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Status Pembayaran</h3>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pesanan yang membutuhkan perhatian pembayaran</p>
            </div>
            <span class="rounded-lg bg-lime-100 dark:bg-lime-900/30 px-2.5 py-1 text-[10px] font-bold text-lime-700 dark:text-lime-400">
                LIVE
            </span>
        </div>

        {{-- STAT --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <a href="{{ route('customer.orders.index') }}"
               class="rounded-xl border border-black/5 dark:border-white/10 p-4 hover:border-lime-400 transition group">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                        </svg>
                    </div>
                    <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Belum Bayar</p>
                </div>
                <p class="text-2xl font-bold text-[#171719] dark:text-white">
                    {{ number_format($paymentAttention['unpaid'], 0, ',', '.') }}
                </p>
                <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Belum mengirim bukti pembayaran</p>
            </a>

            <a href="{{ route('customer.orders.index') }}"
               class="rounded-xl border border-black/5 dark:border-white/10 p-4 hover:border-lime-400 transition group">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Menunggu Verifikasi</p>
                </div>
                <p class="text-2xl font-bold text-[#171719] dark:text-white">
                    {{ number_format($paymentAttention['verification'], 0, ',', '.') }}
                </p>
                <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Bukti pembayaran sudah dikirim</p>
            </a>
        </div>

        {{-- LIST ORDER PERLU DITINDAK --}}
        @if ($pendingPaymentOrders->isNotEmpty())
            <div class="mt-5 pt-5 border-t border-dashed border-black/5 dark:border-white/10">
                <div class="mb-3 flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold text-[#171719] dark:text-white">Perlu ditindaklanjuti</p>
                        <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Buka detail untuk selesaikan pembayaran</p>
                    </div>
                    <a href="{{ route('customer.orders.index') }}"
                       class="text-[11px] font-medium text-lime-600 dark:text-lime-400 hover:underline">
                        Semua order
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach ($pendingPaymentOrders as $order)
                        <a href="{{ route('customer.orders.show', $order) }}"
                           class="rounded-xl bg-gray-50 dark:bg-white/5 p-3 hover:bg-lime-50 dark:hover:bg-lime-900/10 transition">
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-xs font-semibold text-[#171719] dark:text-white font-mono">
                                    {{ $order->order_number }}
                                </span>
                                @if($order->payment_proof)
                                    <span class="shrink-0 px-2 py-0.5 rounded-md bg-amber-100 dark:bg-amber-900/30 text-[10px] font-bold text-amber-700 dark:text-amber-400">
                                        Verifikasi
                                    </span>
                                @else
                                    <span class="shrink-0 px-2 py-0.5 rounded-md bg-red-100 dark:bg-red-900/30 text-[10px] font-bold text-red-700 dark:text-red-400">
                                        Belum Bayar
                                    </span>
                                @endif
                            </div>
                            <p class="mt-1 text-[10px] text-black/45 dark:text-white/45">
                                Rp {{ number_format((float) $order->total, 0, ',', '.') }} · {{ $order->order_date?->format('d/m/Y H:i') }}
                            </p>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="mt-5 rounded-xl bg-lime-50 dark:bg-lime-900/20 p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-lime-600 dark:text-lime-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
                <p class="text-xs font-medium text-lime-700 dark:text-lime-400">
                    Tidak ada pembayaran yang membutuhkan tindakan saat ini.
                </p>
            </div>
        @endif
    </section>

    {{-- ================= WARNING CUSTOMER NOT LINKED ================= --}}
    @if (!$customer)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 9v4"/><path d="M12 17h.01"/>
                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">Profil customer belum terhubung</p>
                <p class="mt-0.5 text-[11px] text-amber-700 dark:text-amber-200/80">
                    Dashboard tetap aman dan tidak menampilkan order milik customer lain. Hubungkan email akun dengan data customer untuk melihat pesanan.
                </p>
            </div>
        </div>
    @endif

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
