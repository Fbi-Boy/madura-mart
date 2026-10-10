<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HERO / HEADER ================= --}}
    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-gradient-to-br from-[#171719] via-[#1F1F23] to-[#2A2A2E] dark:from-[#0B1120] dark:via-[#0F1629] dark:to-[#1E293B] p-6 sm:p-8 text-white overflow-hidden relative">

        {{-- GLOW ACCENTS --}}
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-lime-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">

            {{-- KIRI: WELCOME --}}
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white/10 backdrop-blur-sm border border-white/10">
                    <span class="w-2 h-2 rounded-full bg-lime-400 animate-pulse"></span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-lime-400">System Command Center</span>
                </div>

                <h1 class="mt-4 text-2xl sm:text-3xl font-bold tracking-tight">
                    Selamat datang, {{ auth()->user()->name }} 👋
                </h1>
                <p class="mt-2 text-sm text-white/60 leading-relaxed max-w-xl">
                    Satu ringkasan untuk melihat pengguna, katalog, transaksi, pesanan, dan area operasional yang membutuhkan perhatian.
                </p>

                {{-- QUICK ACCESS BUTTONS --}}
                <div class="mt-5 flex flex-wrap gap-2">
                    <a href="{{ route('admin.users.index') }}"
                       class="h-10 px-4 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 text-white text-xs font-medium flex items-center gap-2 transition backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Users
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                       class="h-10 px-4 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 text-white text-xs font-medium flex items-center gap-2 transition backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                        Products
                    </a>

                    <a href="{{ route('admin.monitoring.penjualan') }}"
                       class="h-10 px-4 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 text-white text-xs font-medium flex items-center gap-2 transition backdrop-blur-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                        Sales
                    </a>

                    <a href="{{ route('admin.report.stok') }}"
                       class="h-10 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-bold flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <rect x="2" y="7" width="20" height="14" rx="2"/>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                        </svg>
                        Stock
                    </a>
                </div>
            </div>

            {{-- KANAN: SYSTEM STATUS CARD --}}
            <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm p-5 min-w-[240px]">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-xl {{ $systemHealth['database'] && $systemHealth['storage'] ? 'bg-lime-400' : 'bg-red-500' }} flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#171719]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-white/50">System Status</p>
                        <p class="text-xs font-bold {{ $systemHealth['database'] && $systemHealth['storage'] ? 'text-lime-400' : 'text-red-400' }}">
                            {{ $systemHealth['database'] && $systemHealth['storage'] ? 'All Systems Operational' : 'Attention Required' }}
                        </p>
                    </div>
                </div>

                <div class="space-y-2 pt-3 border-t border-white/10">
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-white/50">Database</span>
                        <span class="font-semibold {{ $systemHealth['database'] ? 'text-lime-400' : 'text-red-400' }}">
                            {{ $systemHealth['database'] ? 'Connected' : 'Down' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-white/50">Storage</span>
                        <span class="font-semibold {{ $systemHealth['storage'] ? 'text-lime-400' : 'text-red-400' }}">
                            {{ $systemHealth['storage'] ? 'Writable' : 'Read-only' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-white/50">Cache</span>
                        <span class="font-semibold text-white">{{ $systemHealth['cache_driver'] }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="text-white/50">Queue</span>
                        <span class="font-semibold text-white">{{ $systemHealth['queue_driver'] }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ================= KPI CARDS (4) ================= --}}
    @php
        $kpis = [
            ['label' => 'Total Users',       'value' => $totalUsers,       'hint' => 'akun sistem',      'icon' => 'users',    'color' => 'blue'],
            ['label' => 'Produk Aktif',      'value' => $activeProducts,   'hint' => 'siap digunakan',   'icon' => 'box',      'color' => 'lime'],
            ['label' => 'Customer Aktif',    'value' => $activeCustomers,  'hint' => 'customer terdaftar','icon' => 'user',     'color' => 'purple'],
            ['label' => 'Kurir Aktif',       'value' => $activeCouriers,   'hint' => 'siap antar',        'icon' => 'truck',    'color' => 'amber'],
        ];
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($kpis as $kpi)
            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">{{ $kpi['label'] }}</p>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center
                        {{ $kpi['color'] === 'blue' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                        {{ $kpi['color'] === 'lime' ? 'bg-lime-100 dark:bg-lime-900/30 text-lime-600 dark:text-lime-400' : '' }}
                        {{ $kpi['color'] === 'purple' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400' : '' }}
                        {{ $kpi['color'] === 'amber' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : '' }}">
                        @if($kpi['icon'] === 'users')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        @elseif($kpi['icon'] === 'box')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                <path d="m3.27 6.96 8.73 5.05 8.73-5.05"/><path d="M12 22.08V12"/>
                            </svg>
                        @elseif($kpi['icon'] === 'user')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        @elseif($kpi['icon'] === 'truck')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="1" y="3" width="15" height="13"/>
                                <path d="M16 8h4l3 3v5h-7V8z"/>
                                <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                            </svg>
                        @endif
                    </div>
                </div>
                <p class="text-xl font-bold text-[#171719] dark:text-white">{{ number_format($kpi['value'], 0, ',', '.') }}</p>
                <p class="mt-1 text-[10px] text-black/45 dark:text-white/45">{{ $kpi['hint'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- ================= REVENUE + ATTENTION ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- OMZET --}}
        <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
                <span class="text-[10px] uppercase tracking-wider text-lime-600 dark:text-lime-400 font-bold">Revenue</span>
            </div>
            <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Omzet Bulan Berjalan</p>
            <p class="mt-2 text-2xl font-black text-[#171719] dark:text-white">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
            <p class="mt-1 text-[10px] text-black/45 dark:text-white/45">Penjualan berstatus paid</p>
        </div>

        {{-- PURCHASES --}}
        <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
                <span class="text-[10px] uppercase tracking-wider text-blue-600 dark:text-blue-400 font-bold">Purchases</span>
            </div>
            <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Pembelian Bulan Berjalan</p>
            <p class="mt-2 text-2xl font-black text-[#171719] dark:text-white">Rp {{ number_format($monthlyPurchases, 0, ',', '.') }}</p>
            <p class="mt-1 text-[10px] text-black/45 dark:text-white/45">Pembelian berstatus received</p>
        </div>

        {{-- PERLU PERHATIAN --}}
        <div class="rounded-2xl border-2 border-amber-400 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-500/30 p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-400 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#171719]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 9v4"/><path d="M12 17h.01"/>
                        <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-amber-700 dark:text-amber-400 font-bold">Perlu Perhatian</p>
                    <p class="text-[10px] text-amber-700/70 dark:text-amber-300/70">Tindak lanjut</p>
                </div>
            </div>

            <div class="space-y-2">
                <a href="{{ route('admin.monitoring.pesanan') }}"
                   class="flex items-center justify-between p-2.5 rounded-lg bg-white/60 dark:bg-white/5 hover:bg-white dark:hover:bg-white/10 transition">
                    <span class="text-[11px] text-black/70 dark:text-white/70">Pesanan berjalan</span>
                    <span class="text-sm font-bold text-[#171719] dark:text-white">{{ $pendingOrders }}</span>
                </a>
                <a href="{{ route('admin.report.pengiriman') }}"
                   class="flex items-center justify-between p-2.5 rounded-lg bg-white/60 dark:bg-white/5 hover:bg-white dark:hover:bg-white/10 transition">
                    <span class="text-[11px] text-black/70 dark:text-white/70">Pengiriman aktif</span>
                    <span class="text-sm font-bold text-[#171719] dark:text-white">{{ $activeDeliveries }}</span>
                </a>
                <a href="{{ route('admin.monitoring.produk') }}"
                   class="flex items-center justify-between p-2.5 rounded-lg bg-white/60 dark:bg-white/5 hover:bg-white dark:hover:bg-white/10 transition">
                    <span class="text-[11px] text-black/70 dark:text-white/70">Stok rendah</span>
                    <span class="text-sm font-bold text-[#171719] dark:text-white">{{ $lowStockProducts }}</span>
                </a>
                <a href="{{ route('admin.monitoring.pembelian') }}"
                   class="flex items-center justify-between p-2.5 rounded-lg bg-white/60 dark:bg-white/5 hover:bg-white dark:hover:bg-white/10 transition">
                    <span class="text-[11px] text-black/70 dark:text-white/70">Menunggu terima</span>
                    <span class="text-sm font-bold text-[#171719] dark:text-white">{{ $pendingReceiving }}</span>
                </a>
            </div>
        </div>
    </div>

    {{-- ================= CONTROL CENTER (6) ================= --}}
    <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
            <h2 class="text-sm font-bold text-[#171719] dark:text-white">Control Center</h2>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Akses cepat ke pengaturan sistem</p>
        </div>

        @php
            $controlCenter = [
                ['title' => 'Manajemen User',    'description' => 'Akun, role, status, dan reset password.',          'route' => 'admin.users.index',              'icon' => 'users',    'color' => 'blue'],
                ['title' => 'Role & Permission', 'description' => 'Atur akses role dan override permission.',         'route' => 'admin.roles.index',              'icon' => 'shield',   'color' => 'purple'],
                ['title' => 'Pengaturan Sistem', 'description' => 'Identitas toko, transaksi, pembayaran.',           'route' => 'admin.settings.index',           'icon' => 'settings', 'color' => 'lime'],
                ['title' => 'Activity Log',      'description' => 'Telusuri aktivitas pengguna dan sistem.',          'route' => 'admin.activity-logs.index',      'icon' => 'activity', 'color' => 'amber'],
                ['title' => 'Audit Log',         'description' => 'Periksa perubahan data dengan objek terkait.',     'route' => 'admin.audit-logs.index',         'icon' => 'clipboard','color' => 'red'],
                ['title' => 'System Monitoring', 'description' => 'Database, cache, storage, dan kondisi aplikasi.',  'route' => 'admin.system-monitoring.index',  'icon' => 'server',   'color' => 'emerald'],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 p-4">
            @foreach ($controlCenter as $control)
                <a href="{{ route($control['route']) }}"
                   class="group rounded-xl border border-black/5 dark:border-white/10 p-4 hover:border-lime-400 hover:shadow-md hover:-translate-y-0.5 transition-all">

                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center
                            {{ $control['color'] === 'blue' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                            {{ $control['color'] === 'purple' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400' : '' }}
                            {{ $control['color'] === 'lime' ? 'bg-lime-100 dark:bg-lime-900/30 text-lime-600 dark:text-lime-400' : '' }}
                            {{ $control['color'] === 'amber' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : '' }}
                            {{ $control['color'] === 'red' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : '' }}
                            {{ $control['color'] === 'emerald' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : '' }}
                            group-hover:scale-110 transition-transform">
                            @if($control['icon'] === 'users')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                                </svg>
                            @elseif($control['icon'] === 'shield')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                            @elseif($control['icon'] === 'settings')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                </svg>
                            @elseif($control['icon'] === 'activity')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                                </svg>
                            @elseif($control['icon'] === 'clipboard')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                    <rect x="8" y="2" width="8" height="4" rx="1"/>
                                </svg>
                            @elseif($control['icon'] === 'server')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="2" y="3" width="20" height="14" rx="2"/>
                                    <path d="M8 21h8"/><path d="M12 17v4"/>
                                </svg>
                            @endif
                        </div>

                        <div class="shrink-0 w-7 h-7 rounded-lg bg-gray-100 dark:bg-white/10 flex items-center justify-center group-hover:bg-lime-400 transition">
                            <svg class="w-3.5 h-3.5 text-black/50 dark:text-white/50 group-hover:text-[#171719] transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M7 17 17 7"/><path d="M7 7h10v10"/>
                            </svg>
                        </div>
                    </div>

                    <p class="text-xs font-bold text-[#171719] dark:text-white">{{ $control['title'] }}</p>
                    <p class="mt-1 text-[10px] text-black/45 dark:text-white/45 leading-relaxed">{{ $control['description'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ================= ROLE DISTRIBUTION + RECENT ORDERS ================= --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

        {{-- ROLE DISTRIBUTION --}}
        <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#171719] dark:text-white">Distribusi Role</h3>
                    <p class="text-[10px] text-black/45 dark:text-white/45">Komposisi akun by role</p>
                </div>
            </div>

            <div class="space-y-2">
                @foreach ($roleSummary as $role => $count)
                    <div class="flex items-center justify-between gap-3 p-3 rounded-xl bg-gray-50 dark:bg-white/5">
                        <div class="flex items-center gap-2 min-w-0">
                            <div class="w-6 h-6 rounded-lg bg-lime-400 flex items-center justify-center text-[10px] font-bold text-[#171719] shrink-0">
                                {{ strtoupper(substr($role, 0, 1)) }}
                            </div>
                            <span class="text-xs text-black/70 dark:text-white/70 capitalize truncate">
                                {{ str_replace('-', ' ', $role) }}
                            </span>
                        </div>
                        <span class="text-sm font-bold text-[#171719] dark:text-white shrink-0">
                            {{ number_format($count, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- RECENT ORDERS (2/3) --}}
        <section class="xl:col-span-2 rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">
            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
                <div>
                    <h3 class="text-sm font-bold text-[#171719] dark:text-white">Pesanan Terbaru</h3>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Monitoring lintas customer</p>
                </div>
                <a href="{{ route('admin.monitoring.pesanan') }}"
                   class="text-[11px] font-medium text-lime-600 dark:text-lime-400 hover:underline flex items-center gap-1">
                    Lihat Semua
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[620px] text-left">
                    <thead class="border-b border-black/5 dark:border-white/10">
                        <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                            <th class="px-5 py-3.5 font-medium">Order</th>
                            <th class="px-5 py-3.5 font-medium">Customer</th>
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
                                    <p class="text-xs font-semibold text-[#171719] dark:text-white font-mono">{{ $order->order_number }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-xs text-black/70 dark:text-white/70">{{ $order->customer?->name ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <p class="text-xs font-bold text-[#171719] dark:text-white">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-16 text-center">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                        <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                                    </svg>
                                    <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada order</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    {{-- ================= ACTIVITY LOG ================= --}}
    <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
            <div>
                <h3 class="text-sm font-bold text-[#171719] dark:text-white">Aktivitas Sistem Terbaru</h3>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Jejak aktivitas terakhir di sistem</p>
            </div>
            <a href="{{ route('admin.activity-logs.index') }}"
               class="h-9 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-bold flex items-center gap-1.5 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                </svg>
                Buka Audit Log
            </a>
        </div>

        <div class="divide-y divide-black/5 dark:divide-white/10 max-h-[400px] overflow-y-auto">
            @forelse ($recentActivities as $activity)
                <div class="px-5 py-3 flex items-start gap-3 hover:bg-gray-50 dark:hover:bg-white/5 transition">
                    <div class="mt-1.5 w-2 h-2 rounded-full bg-lime-500 shrink-0"></div>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded-md bg-lime-100 dark:bg-lime-900/30 text-[9px] font-bold uppercase tracking-wider text-lime-700 dark:text-lime-400">
                                {{ $activity->action }}
                            </span>
                            <span class="text-[10px] text-black/45 dark:text-white/45">{{ $activity->user?->name ?? 'System' }}</span>
                        </div>
                        <p class="text-[11px] text-black/70 dark:text-white/70 leading-relaxed">{{ $activity->description }}</p>
                        <p class="mt-0.5 text-[10px] text-black/40 dark:text-white/40 font-mono">
                            {{ $activity->created_at?->format('d/m/Y H:i') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="px-5 py-16 text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                    </svg>
                    <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada aktivitas tercatat</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ================= RECENT USERS ================= --}}
    <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
            <h3 class="text-sm font-bold text-[#171719] dark:text-white">Akun Terbaru</h3>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Aktivitas pendaftaran akun terbaru</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 p-4">
            @forelse ($recentUsers as $user)
                <div class="rounded-xl border border-black/5 dark:border-white/10 p-4 hover:border-lime-400 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-lime-400 flex items-center justify-center text-sm font-bold text-[#171719] shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-bold text-[#171719] dark:text-white truncate">{{ $user->name }}</p>
                            <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45 truncate font-mono">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="mt-3 flex items-center justify-between gap-2">
                        <span class="px-2 py-0.5 rounded-md bg-lime-100 dark:bg-lime-900/30 text-[9px] font-bold uppercase tracking-wider text-lime-700 dark:text-lime-400">
                            {{ str_replace('-', ' ', $user->role) }}
                        </span>
                        @if($user->branch)
                            <span class="text-[10px] text-black/45 dark:text-white/45 truncate">
                                {{ $user->branch->name }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center">
                    <p class="text-sm text-black/50 dark:text-white/50">Belum ada akun</p>
                </div>
            @endforelse
        </div>
    </section>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
