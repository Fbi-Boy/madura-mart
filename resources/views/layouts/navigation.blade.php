@php
    $isAdmin = in_array(auth()->user()->role, ['admin', 'super-admin'], true);
    $user = auth()->user();

    // Helper untuk cek menu aktif
    $isActive = function ($patterns) {
        foreach ((array) $patterns as $pattern) {
            if (request()->routeIs($pattern)) return true;
        }
        return false;
    };
@endphp

<nav style="width:260px; height:100vh;"
     class="shrink-0 bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 flex flex-col overflow-hidden font-poppins">

    {{-- ================= LOGO WARMART ================= --}}
    <div class="h-16 shrink-0 flex items-center px-5 border-b border-gray-200 dark:border-gray-800">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">

            {{-- ICON KOTAK LIME + HURUF W --}}
            <div class="w-9 h-9 rounded-xl bg-lime-400 flex items-center justify-center shrink-0
                        shadow-[0_4px_12px_rgba(168,242,58,0.35)]
                        group-hover:scale-105 transition-transform duration-200">
                <span class="text-black text-lg leading-none"
                    style="font-family: 'Poppins', sans-serif; font-weight: 900; letter-spacing: -0.02em;">
                    W
                </span>
        </div>

        {{-- WORDMARK: WAR hitam + MART lime --}}
        <span class="text-lg leading-none"
              style="font-family: 'Poppins', sans-serif; font-weight: 900; letter-spacing: -0.03em;">
            <span class="text-gray-900 dark:text-white">WAR</span><span class="text-lime-500">MART</span>
        </span>

    </a>
</div>

    {{-- ================= MENU SCROLL ================= --}}
    <div class="sidebar-scroll flex-1 overflow-y-auto px-3 py-4">

        {{-- ===== MENU UTAMA ===== --}}
        <p class="menu-title">Menu Utama</p>
        <div class="space-y-1 mb-5">
            <a href="{{ route('dashboard') }}"
               class="menu-item {{ $isActive('dashboard') ? 'menu-active' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="m3 10 9-7 9 7"/><path d="M5 9v11h14V9"/><path d="M9 20v-6h6v6"/>
                </svg>
                <span>Dashboard</span>
            </a>
        </div>

        @if($isAdmin)
            {{-- ===== MANAJEMEN ===== --}}
            <p class="menu-title">Manajemen</p>
            <div class="space-y-1 mb-5">
                @if($user->hasPermission('sales.manage'))
                <a href="{{ route('admin.monitoring.penjualan') }}"
                   class="menu-item {{ $isActive('admin.monitoring.penjualan') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                    <span>Penjualan</span>
                </a>
                @endif
                @if($user->hasPermission('purchases.manage'))
                <a href="{{ route('admin.monitoring.pembelian') }}"
                   class="menu-item {{ $isActive('admin.monitoring.pembelian') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <span>Pembelian</span>
                </a>
                @endif
                @if($user->hasPermission('orders.manage'))
                <a href="{{ route('admin.monitoring.pesanan') }}"
                   class="menu-item {{ $isActive('admin.monitoring.pesanan') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/>
                    </svg>
                    <span>Pesanan</span>
                </a>
                @endif
                @if($user->hasPermission('products.manage'))
                <a href="{{ route('admin.monitoring.produk') }}"
                   class="menu-item {{ $isActive('admin.monitoring.produk') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <path d="m3.27 6.96 8.73 5.05 8.73-5.05"/><path d="M12 22.08V12"/>
                    </svg>
                    <span>Produk</span>
                </a>
                @endif
                <a href="{{ route('admin.monitoring.distributor') }}"
                   class="menu-item {{ $isActive('admin.monitoring.distributor') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="1" y="3" width="15" height="13"/>
                        <path d="M16 8h4l3 3v5h-7V8z"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    <span>Distributor</span>
                </a>
                <a href="{{ route('admin.monitoring.client') }}"
                   class="menu-item {{ $isActive('admin.monitoring.client') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span>Client</span>
                </a>
                <a href="{{ route('admin.monitoring.kurir') }}"
                   class="menu-item {{ $isActive('admin.monitoring.kurir') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="1" y="3" width="15" height="13"/>
                        <path d="M16 8h4l3 3v5h-7V8z"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    <span>Kurir</span>
                </a>
                <a href="{{ route('admin.monitoring.supplier') }}"
                   class="menu-item {{ $isActive('admin.monitoring.supplier') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>Supplier</span>
                </a>
                @if($user->hasPermission('payments.verify'))
                <a href="{{ route('admin.payment-verification.index') }}"
                   class="menu-item {{ $isActive('admin.payment-verification.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="1" y="4" width="22" height="16" rx="2"/><path d="M1 10h22"/>
                    </svg>
                    <span>Verifikasi Pembayaran</span>
                </a>
                @endif
            </div>

            {{-- ===== MASTER DATA ===== --}}
            <p class="menu-title">Master Data</p>
            <div class="space-y-1 mb-5">
                <a href="{{ route('admin.categories.index') }}"
                   class="menu-item {{ $isActive('admin.categories.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span>Kategori</span>
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="menu-item {{ $isActive('admin.products.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <path d="m3.27 6.96 8.73 5.05 8.73-5.05"/><path d="M12 22.08V12"/>
                    </svg>
                    <span>Produk Master</span>
                </a>
                @if($user->hasPermission('suppliers.manage'))
                <a href="{{ route('admin.suppliers.index') }}"
                   class="menu-item {{ $isActive('admin.suppliers.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>Supplier</span>
                </a>
                @endif
                <a href="{{ route('admin.customers.index') }}"
                   class="menu-item {{ $isActive('admin.customers.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span>Customer</span>
                </a>
                <a href="{{ route('admin.distributors.index') }}"
                   class="menu-item {{ $isActive('admin.distributors.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="1" y="3" width="15" height="13"/>
                        <path d="M16 8h4l3 3v5h-7V8z"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    <span>Distributor Master</span>
                </a>
                <a href="{{ route('admin.couriers.index') }}"
                   class="menu-item {{ $isActive('admin.couriers.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="1" y="3" width="15" height="13"/>
                        <path d="M16 8h4l3 3v5h-7V8z"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    <span>Kurir Master</span>
                </a>
                <a href="{{ route('admin.units.index') }}"
                   class="menu-item {{ $isActive('admin.units.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>Satuan</span>
                </a>
            </div>

            {{-- ===== LAPORAN ===== --}}
            @if($user->hasPermission('reports.view'))
            <p class="menu-title">Laporan</p>
            <div class="space-y-1 mb-5">
                <a href="{{ route('admin.report.penjualan') }}"
                   class="menu-item {{ $isActive('admin.report.penjualan') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/>
                    </svg>
                    <span>Laporan Penjualan</span>
                </a>
                <a href="{{ route('admin.report.pembelian') }}"
                   class="menu-item {{ $isActive('admin.report.pembelian') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/>
                    </svg>
                    <span>Laporan Pembelian</span>
                </a>
                <a href="{{ route('admin.report.stok') }}"
                   class="menu-item {{ $isActive('admin.report.stok') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/>
                    </svg>
                    <span>Laporan Stok</span>
                </a>
                <a href="{{ route('admin.report.transaksi') }}"
                   class="menu-item {{ $isActive('admin.report.transaksi') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/>
                    </svg>
                    <span>Laporan Transaksi</span>
                </a>
                <a href="{{ route('admin.report.pengiriman') }}"
                   class="menu-item {{ $isActive('admin.report.pengiriman') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/>
                    </svg>
                    <span>Laporan Pengiriman</span>
                </a>
                <a href="{{ route('admin.report.pelanggan') }}"
                   class="menu-item {{ $isActive('admin.report.pelanggan') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/>
                    </svg>
                    <span>Laporan Pelanggan</span>
                </a>
            </div>
            @endif

            {{-- ===== SISTEM ===== --}}
            @if($user->role === 'super-admin')
            <p class="menu-title">Sistem</p>
            <div class="space-y-1 mb-5">
                <a href="{{ route('admin.users.index') }}"
                   class="menu-item {{ $isActive('admin.users.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>User & Staff</span>
                </a>
                <a href="{{ route('admin.roles.index') }}"
                   class="menu-item {{ $isActive('admin.roles.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <span>Role & Permission</span>
                </a>
                <a href="{{ route('admin.settings.index') }}"
                   class="menu-item {{ $isActive('admin.settings.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                    <span>Pengaturan Sistem</span>
                </a>
                <a href="{{ route('admin.activity-logs.index') }}"
                   class="menu-item {{ $isActive('admin.activity-logs.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                    </svg>
                    <span>Activity Log</span>
                </a>
                <a href="{{ route('admin.audit-logs.index') }}"
                   class="menu-item {{ $isActive('admin.audit-logs.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                    </svg>
                    <span>Audit Log</span>
                </a>
                <a href="{{ route('admin.system-monitoring.index') }}"
                   class="menu-item {{ $isActive('admin.system-monitoring.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="2" y="3" width="20" height="14" rx="2"/>
                        <path d="M8 21h8"/><path d="M12 17v4"/>
                    </svg>
                    <span>System Monitoring</span>
                </a>
            </div>
            @endif

        @elseif($user->role === 'kasir')
            {{-- ===== KASIR ===== --}}
            <p class="menu-title">Kasir</p>
            <div class="space-y-1 mb-5">
                <a href="{{ route('kasir.transaksi-baru') }}"
                   class="menu-item {{ $isActive('kasir.transaksi-baru') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    <span>Transaksi Baru</span>
                </a>
                <a href="{{ route('kasir.riwayat-transaksi') }}"
                   class="menu-item {{ $isActive('kasir.riwayat-transaksi') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                    <span>Riwayat Transaksi</span>
                </a>
            </div>

            {{-- ===== SHIFT ===== --}}
            <p class="menu-title">Shift</p>
            <div class="space-y-1 mb-5">
                <a href="{{ route('kasir.buka-shift') }}"
                   class="menu-item {{ $isActive('kasir.buka-shift') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                    </svg>
                    <span>Buka Shift</span>
                </a>
                <a href="{{ route('kasir.riwayat-shift') }}"
                   class="menu-item {{ $isActive('kasir.riwayat-shift') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/>
                    </svg>
                    <span>Riwayat Shift</span>
                </a>
            </div>

        @elseif($user->role === 'gudang')
            <p class="menu-title">Gudang</p>
            <div class="space-y-1 mb-5">
                <a href="{{ route('gudang.stock-opname.index') }}"
                   class="menu-item {{ $isActive('gudang.stock-opname.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <path d="m3.27 6.96 8.73 5.05 8.73-5.05"/><path d="M12 22.08V12"/>
                    </svg>
                    <span>Stock Opname</span>
                </a>
                <a href="{{ route('gudang.penerimaan.index') }}"
                   class="menu-item {{ $isActive('gudang.penerimaan.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                    <span>Penerimaan Barang</span>
                </a>
                <a href="{{ route('gudang.barang-keluar.index') }}"
                   class="menu-item {{ $isActive('gudang.barang-keluar.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                    <span>Barang Keluar</span>
                </a>
                <a href="{{ route('gudang.riwayat-stok.index') }}"
                   class="menu-item {{ $isActive('gudang.riwayat-stok.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                    <span>Riwayat Stok</span>
                </a>
            </div>

        @elseif($user->role === 'purchasing')
            <p class="menu-title">Purchasing</p>
            <div class="space-y-1 mb-5">
                @if($user->hasPermission('purchases.manage'))
                <a href="{{ route('purchasing.purchases.index') }}"
                   class="menu-item {{ $isActive('purchasing.purchases.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                    <span>Purchase Order</span>
                </a>
                @endif
                @if($user->hasPermission('suppliers.manage'))
                <a href="{{ route('purchasing.suppliers.index') }}"
                   class="menu-item {{ $isActive('purchasing.suppliers.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>Supplier</span>
                </a>
                @endif
            </div>

        @elseif($user->role === 'kurir')
            <p class="menu-title">Kurir</p>
            <div class="space-y-1 mb-5">
                <a href="{{ route('kurir.pengiriman.index') }}"
                   class="menu-item {{ $isActive('kurir.pengiriman.index') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="1" y="3" width="15" height="13"/>
                        <path d="M16 8h4l3 3v5h-7V8z"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    <span>Tugas Pengiriman</span>
                </a>
                <a href="{{ route('kurir.pengiriman.riwayat') }}"
                   class="menu-item {{ $isActive('kurir.pengiriman.riwayat') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                    <span>Riwayat Pengiriman</span>
                </a>
            </div>

        @elseif($user->role === 'customer')
            <p class="menu-title">Customer</p>
            <div class="space-y-1 mb-5">
                <a href="{{ route('customer.catalog.index') }}"
                   class="menu-item {{ $isActive('customer.catalog.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                    </svg>
                    <span>Katalog</span>
                </a>
                <a href="{{ route('customer.cart.index') }}"
                   class="menu-item {{ $isActive('customer.cart.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    <span>Keranjang</span>
                </a>
                <a href="{{ route('customer.orders.index') }}"
                   class="menu-item {{ $isActive('customer.orders.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <path d="M14 2v6h6"/>
                    </svg>
                    <span>Pesanan Saya</span>
                </a>
                <a href="{{ route('customer.address.edit') }}"
                   class="menu-item {{ $isActive('customer.address.*') ? 'menu-active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <span>Alamat</span>
                </a>
            </div>
        @endif

    </div>

    {{-- ================= PROFILE CARD ================= --}}
    <div class="p-4 shrink-0 bg-white dark:bg-gray-900">
        <div class="rounded-2xl bg-gray-100 dark:bg-[#1E293B] p-3">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 shrink-0 rounded-xl bg-[#A8F23A] flex items-center justify-center text-[15px] font-black text-black">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[13px] font-bold text-gray-800 dark:text-white truncate">{{ $user->name }}</p>
                    <p class="text-[10px] font-medium text-gray-500 dark:text-gray-400 truncate uppercase tracking-wider">
                        {{ str_replace('_', ' ', ucfirst($user->role)) }}
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M15 4h5v16h-5"/>
                    </svg>
                    Log Out
                </button>
            </form>
        </div>
    </div>

</nav>

<style>
    .font-poppins, .font-poppins * {
        font-family: 'Poppins', system-ui, -apple-system, sans-serif;
    }

    /* =============== JUDUL KATEGORI MENU =============== */
    .menu-title {
        padding: 0 12px;
        margin-bottom: 8px;
        font-family: 'Poppins', sans-serif;
        font-size: 10px;
        font-weight: 500;           /* ← diubah: 400 untuk normal, 500 untuk medium */
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: #9CA3AF;
    }
    .dark .menu-title {
        color: #6B7280;
    }

    /* =============== MENU ITEM =============== */
    .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        height: 42px;
        padding: 0 14px;
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-size: 13px;
        font-weight: 500;
        color: #4B5563;
        text-decoration: none;
        transition: all 0.2s ease;
        position: relative;
    }
    .dark .menu-item {
        color: #9CA3AF;
    }

    .menu-item:hover {
        background: #F3F4F6;
        color: #111827;
    }
    .dark .menu-item:hover {
        background: #1F2937;
        color: #F9FAFB;
    }

    /* =============== MENU AKTIF (HIJAU NEON) =============== */
    .menu-active {
        background: #A8F23A !important;
        color: #171719 !important;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(168, 242, 58, 0.35);
    }
    .menu-active:hover {
        background: #A8F23A !important;
        color: #171719 !important;
    }

    /* =============== SCROLLBAR ESTETIK =============== */
    .sidebar-scroll {
        scrollbar-width: thin;
        scrollbar-color: transparent transparent;
        transition: scrollbar-color 0.3s ease;
    }
    .sidebar-scroll:hover {
        scrollbar-color: #D1D5DB transparent;
    }
    .dark .sidebar-scroll:hover {
        scrollbar-color: #374151 transparent;
    }
    .sidebar-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .sidebar-scroll::-webkit-scrollbar-thumb {
        background: transparent;
        border-radius: 999px;
        transition: background 0.3s ease;
    }
    .sidebar-scroll:hover::-webkit-scrollbar-thumb {
        background: #D1D5DB;
    }
    .dark .sidebar-scroll:hover::-webkit-scrollbar-thumb {
        background: #374151;
    }

    /* =============== TOMBOL LOGOUT =============== */
    .btn-logout {
        width: 100%;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 12px;
        font-family: 'Poppins', sans-serif;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
        background: #111111;
        color: #FFFFFF;
    }
    .btn-logout:hover {
        background: #000000;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    .dark .btn-logout {
        background: #E5E7EB;
        color: #111111;
    }
    .dark .btn-logout:hover {
        background: #F9FAFB;
        box-shadow: 0 4px 12px rgba(255, 255, 255, 0.1);
    }
</style>
