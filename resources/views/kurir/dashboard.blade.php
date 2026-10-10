<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Logistics Operations</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Dashboard Kurir</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pantau tugas pengiriman dan status pesanan</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('kurir.pengiriman.index') }}"
               class="h-10 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-medium flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <rect x="1" y="3" width="15" height="13"/>
                    <path d="M16 8h4l3 3v5h-7V8z"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
                Tugas
            </a>
            <a href="{{ route('kurir.pengiriman.riwayat') }}"
               class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                </svg>
                Riwayat
            </a>
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
                {{ now()->translatedFormat('d M Y') }}
            </span>
        </div>
    </div>

    {{-- ================= COURIER INFO ================= --}}
    <div class="rounded-2xl border-2 border-lime-400 bg-lime-50 dark:bg-lime-900/20 p-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-lime-400 flex items-center justify-center text-lg font-bold text-[#171719] shrink-0">
                    {{ strtoupper(substr($courier?->name ?? auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <p class="text-[10px] font-medium uppercase tracking-wider text-lime-700 dark:text-lime-400">Courier Workspace</p>
                    <h2 class="mt-0.5 text-base font-bold text-[#171719] dark:text-white">
                        {{ $courier?->name ?? 'Tugas Pengiriman' }}
                    </h2>
                    <p class="mt-0.5 text-[11px] text-black/60 dark:text-white/60">
                        {{ $courier ? 'Tugas ditampilkan berdasarkan kurir yang terhubung melalui email akun.' : 'Akun belum terhubung ke data kurir.' }}
                    </p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-lime-400 text-[#171719] text-xs font-bold">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
                Aktif
            </span>
        </div>
    </div>

    {{-- ================= WARNING PERLU PERHATIAN ================= --}}
    @if ($staleShipments > 0)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10 px-4 py-3 flex items-start justify-between gap-3 flex-wrap">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">Pengiriman Perlu Perhatian</p>
                    <p class="mt-0.5 text-[11px] text-amber-700 dark:text-amber-200/80">
                        {{ $staleShipments }} pesanan berstatus shipped sejak 2 hari lalu atau lebih.
                    </p>
                </div>
            </div>
            <a href="{{ route('kurir.pengiriman.riwayat') }}"
               class="h-9 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold flex items-center gap-1.5 transition">
                Buka Riwayat
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                </svg>
            </a>
        </div>
    @endif

    {{-- ================= KPI CARDS ================= --}}
    @php
        $kpis = [
            ['label' => 'Pesanan Hari Ini', 'value' => number_format($todayOrders, 0, ',', '.'), 'hint' => 'pesanan masuk', 'icon' => 'inbox', 'color' => 'lime'],
            ['label' => 'Perlu Diproses', 'value' => number_format($pendingOrders, 0, ',', '.'), 'hint' => 'pending + processing', 'icon' => 'clock', 'color' => 'amber'],
            ['label' => 'Sedang Dikirim', 'value' => number_format($shippingOrders, 0, ',', '.'), 'hint' => 'status shipped', 'icon' => 'truck', 'color' => 'blue'],
            ['label' => 'Rute Aktif', 'value' => number_format($activeDeliveryOrders, 0, ',', '.'), 'hint' => 'processing + shipped', 'icon' => 'map', 'color' => 'purple'],
            ['label' => 'Total Selesai', 'value' => number_format($deliveredOrders, 0, ',', '.'), 'hint' => 'status delivered', 'icon' => 'check', 'color' => 'emerald'],
            ['label' => 'Gagal', 'value' => number_format($failedDeliveries, 0, ',', '.'), 'hint' => 'perlu tindak lanjut', 'icon' => 'x', 'color' => 'red'],
        ];
    @endphp

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-6">
        @foreach ($kpis as $kpi)
            <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">{{ $kpi['label'] }}</p>
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center
                        {{ $kpi['color'] === 'lime' ? 'bg-lime-100 dark:bg-lime-900/30 text-lime-600 dark:text-lime-400' : '' }}
                        {{ $kpi['color'] === 'amber' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : '' }}
                        {{ $kpi['color'] === 'blue' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                        {{ $kpi['color'] === 'purple' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400' : '' }}
                        {{ $kpi['color'] === 'emerald' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : '' }}
                        {{ $kpi['color'] === 'red' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : '' }}">
                        @if($kpi['icon'] === 'inbox')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
                                <path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                            </svg>
                        @elseif($kpi['icon'] === 'clock')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                            </svg>
                        @elseif($kpi['icon'] === 'truck')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="1" y="3" width="15" height="13"/>
                                <path d="M16 8h4l3 3v5h-7V8z"/>
                                <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                            </svg>
                        @elseif($kpi['icon'] === 'map')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        @elseif($kpi['icon'] === 'check')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        @elseif($kpi['icon'] === 'x')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                            </svg>
                        @endif
                    </div>
                </div>
                <p class="text-xl font-bold text-[#171719] dark:text-white">{{ $kpi['value'] }}</p>
                <p class="mt-1 text-[10px] text-black/45 dark:text-white/45">{{ $kpi['hint'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- ================= PROGRESS ================= --}}
    <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Progress Pengiriman</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                    Persentase tugas non-cancelled yang sudah mencapai status selesai
                </p>
            </div>
            <div class="text-right">
                <p class="text-2xl font-bold text-lime-600 dark:text-lime-400">{{ number_format($deliveryRate, 1, ',', '.') }}%</p>
                <p class="text-[10px] text-black/45 dark:text-white/45">completion rate</p>
            </div>
        </div>
        <div class="mt-4 h-2 rounded-full overflow-hidden bg-gray-100 dark:bg-white/10">
            <div class="h-full rounded-full bg-lime-400 transition-all duration-500" style="width: {{ min($deliveryRate, 100) }}%"></div>
        </div>
    </section>

    {{-- ================= WARNING STALE DELIVERIES ================= --}}
    @if ($staleDeliveries > 0)
        <div class="rounded-2xl border border-orange-200 bg-orange-50 dark:border-orange-500/20 dark:bg-orange-500/10 px-4 py-3 flex items-start justify-between gap-3 flex-wrap">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 9v4"/><path d="M12 17h.01"/>
                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-orange-800 dark:text-orange-200">
                        {{ $staleDeliveries }} pengiriman belum selesai >3 hari
                    </p>
                    <p class="mt-0.5 text-[11px] text-orange-700 dark:text-orange-200/80">
                        Tinjau status tugas agar pengiriman yang tertahan tidak terlewat.
                    </p>
                </div>
            </div>
            <a href="{{ route('kurir.pengiriman.index') }}"
               class="h-9 px-4 rounded-xl bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold flex items-center gap-1.5 transition">
                Tinjau Tugas
            </a>
        </div>
    @endif

    {{-- ================= MAIN GRID ================= --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- TUGAS PENGIRIMAN (2/3) --}}
        <section class="xl:col-span-2 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">
            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Tugas Pengiriman</h2>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pesanan terbaru yang terkait tugas kurir</p>
                </div>
                <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-2.5 py-1 rounded-lg font-medium">
                    {{ $recentOrders->count() }} data
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-left">
                    <thead class="border-b border-black/5 dark:border-white/10">
                        <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                            <th class="px-5 py-3.5 font-medium">Pesanan</th>
                            <th class="px-5 py-3.5 font-medium">Customer</th>
                            <th class="px-5 py-3.5 font-medium">Tanggal</th>
                            <th class="px-5 py-3.5 font-medium">Status</th>
                            <th class="px-5 py-3.5 text-right font-medium">Aksi</th>
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
                                $nextStatus = ['pending' => 'processing', 'processing' => 'shipped', 'shipped' => 'delivered'][$order->status] ?? null;
                                $nextLabel = ['processing' => 'Proses', 'shipped' => 'Kirim', 'delivered' => 'Selesai'][$nextStatus] ?? null;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                                <td class="px-5 py-4">
                                    <p class="text-xs font-semibold text-[#171719] dark:text-white font-mono">{{ $order->order_number }}</p>
                                    <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 truncate max-w-[200px]">{{ $order->delivery_address }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-xs text-black/70 dark:text-white/70">{{ $order->customer?->name ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-xs text-black/70 dark:text-white/70 font-mono">{{ $order->order_date?->format('d/m/Y') }}</p>
                                    <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 font-mono">{{ $order->order_date?->format('H:i') }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if ($nextStatus)
                                        <form method="POST" action="{{ route('kurir.pengiriman.status', $order) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $nextStatus }}">
                                            <button type="submit"
                                                class="h-8 px-3 rounded-lg bg-lime-400 hover:bg-lime-500 text-[#171719] text-[11px] font-bold transition">
                                                {{ $nextLabel }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[11px] text-black/40 dark:text-white/40">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <rect x="1" y="3" width="15" height="13"/>
                                        <path d="M16 8h4l3 3v5h-7V8z"/>
                                        <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                                    </svg>
                                    <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada tugas pengiriman</p>
                                    <p class="mt-1 text-xs text-black/40 dark:text-white/40">Tugas akan muncul saat ada pesanan baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- SIDEBAR KANAN (1/3) --}}
        <div class="space-y-5">

            {{-- PRIORITAS --}}
            <section class="rounded-2xl border border-lime-200 bg-white dark:border-lime-500/20 dark:bg-white/5 p-5">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Prioritas Pengiriman</h3>
                        <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pesanan shipped yang perlu dituntaskan</p>
                    </div>
                    <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-2 py-1 rounded-lg font-bold">
                        {{ $priorityOrders->count() }}
                    </span>
                </div>

                <div class="space-y-2">
                    @forelse ($priorityOrders as $order)
                        <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-3">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold text-[#171719] dark:text-white font-mono">{{ $order->order_number }}</p>
                                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45 truncate">{{ $order->customer?->name ?? 'Customer' }}</p>
                                </div>
                                <span class="shrink-0 px-2 py-0.5 rounded-md bg-blue-100 dark:bg-blue-900/30 text-[10px] font-bold text-blue-700 dark:text-blue-400">
                                    SHIPPED
                                </span>
                            </div>
                            <p class="mt-2 text-[10px] text-black/45 dark:text-white/45 truncate">
                                {{ $order->delivery_address }}
                            </p>
                        </div>
                    @empty
                        <div class="rounded-xl bg-lime-50 dark:bg-lime-900/20 p-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-lime-600 dark:text-lime-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            <span class="text-xs font-medium text-lime-700 dark:text-lime-400">Tidak ada pengiriman prioritas</span>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- STATUS SUMMARY --}}
            <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Status Pengiriman</h3>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Distribusi tugas pada akun kurir</p>

                <div class="mt-4 space-y-2">
                    @php
                        $statusItems = [
                            'pending'    => ['label' => 'Menunggu', 'color' => 'amber', 'icon' => 'clock'],
                            'processing' => ['label' => 'Diproses', 'color' => 'purple', 'icon' => 'refresh'],
                            'shipped'    => ['label' => 'Dikirim', 'color' => 'blue', 'icon' => 'truck'],
                            'delivered'  => ['label' => 'Selesai', 'color' => 'lime', 'icon' => 'check'],
                            'failed'     => ['label' => 'Gagal', 'color' => 'red', 'icon' => 'x'],
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
    </div>

    {{-- ================= AKTIVITAS TERBARU ================= --}}
    <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">
        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Aktivitas Status Terbaru</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Perubahan status pada pesanan yang ditangani</p>
            </div>
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-2.5 py-1 rounded-lg font-medium">
                {{ $recentDeliveryUpdates->count() }} aktivitas
            </span>
        </div>

        <div class="divide-y divide-black/5 dark:divide-white/10 max-h-[400px] overflow-y-auto">
            @forelse ($recentDeliveryUpdates as $activity)
                <div class="px-5 py-3 flex items-start gap-3 hover:bg-gray-50 dark:hover:bg-white/5 transition">
                    <div class="mt-1.5 w-2 h-2 rounded-full bg-lime-500 shrink-0"></div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-black/70 dark:text-white/70">{{ $activity->description }}</p>
                        <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45 font-mono">
                            {{ $activity->created_at?->format('d/m/Y H:i') }}
                            · {{ $activity->user?->name ?? 'Sistem' }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="px-5 py-16 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                    <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada perubahan status</p>
                    <p class="mt-1 text-xs text-black/40 dark:text-white/40">Aktivitas pengiriman akan muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </section>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
