@php
    $isAdmin = in_array(auth()->user()->role, ['admin', 'super-admin'], true);
@endphp

<nav class="w-[228px] h-[calc(100vh-2rem)] shrink-0 self-start rounded-[22px]
                  bg-[#202124] dark:bg-[#F7F7F5]
                  text-white dark:text-[#171719]
                  flex flex-col overflow-hidden
                  shadow-[0_12px_30px_rgba(0,0,0,0.08)]
                  dark:shadow-[0_12px_30px_rgba(0,0,0,0.06)]
                  transition-colors duration-200">

    <div class="px-4 pt-5 pb-4">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
            <div class="w-9 h-9 shrink-0 rounded-[11px] bg-white dark:bg-[#171719]
                        text-[#202124] dark:text-white flex items-center justify-center
                        font-black text-base">
                M
            </div>
            <span class="text-[15px] font-bold tracking-tight whitespace-nowrap">
                WAR<span class="text-[#A8F23A]">-MART</span>
            </span>
        </a>
    </div>

    <div class="flex-1 px-3 overflow-y-auto [&::-webkit-scrollbar]:hidden"
         style="scrollbar-width:none;-ms-overflow-style:none;">

        <a href="{{ route('dashboard') }}"
           class="h-11 flex items-center gap-3 px-3 rounded-[13px]
                  bg-[#A8F23A] text-[#171719] text-[13px] font-semibold transition">
            <svg class="w-[19px] h-[19px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m3 10 9-7 9 7"></path>
                <path d="M5 9v11h14V9"></path>
                <path d="M9 20v-6h6v6"></path>
            </svg>
            <span>Dashboard</span>
        </a>

        @if($isAdmin)

            <div class="mt-3 space-y-0.5">
                <a href="{{ route('admin.monitoring.penjualan') }}" class="menu-link">Penjualan</a>
                <a href="{{ route('admin.monitoring.pembelian') }}" class="menu-link">Pembelian</a>
                <a href="{{ route('admin.monitoring.pesanan') }}" class="menu-link">Pesanan</a>
                <a href="{{ route('admin.monitoring.produk') }}" class="menu-link">Produk</a>
                <a href="{{ route('admin.monitoring.distributor') }}" class="menu-link">Distributor</a>
                <a href="{{ route('admin.monitoring.client') }}" class="menu-link">Client</a>
                <a href="{{ route('admin.monitoring.kurir') }}" class="menu-link">Kurir</a>
                <a href="{{ route('admin.report.penjualan') }}" class="menu-link">Laporan Penjualan</a>
                <a href="{{ route('admin.report.pembelian') }}" class="menu-link">Laporan Pembelian</a>
                <a href="{{ route('admin.report.stok') }}" class="menu-link">Stok</a>
                <a href="{{ route('admin.categories.index') }}" class="menu-link">Kategori</a>
                <a href="{{ route('admin.products.index') }}" class="menu-link">Produk Master</a>
                <a href="{{ route('admin.suppliers.index') }}" class="menu-link">Supplier</a>
                <a href="{{ route('admin.customers.index') }}" class="menu-link">Customer</a>
                <a href="{{ route('admin.distributors.index') }}" class="menu-link">Distributor Master</a>
                <a href="{{ route('admin.couriers.index') }}" class="menu-link">Kurir Master</a>
                <a href="{{ route('admin.users.index') }}" class="menu-link">User & Staff</a>
            </div>

        @elseif(auth()->user()->role === 'kasir')

            <div class="mt-3 space-y-0.5">
                <a href="{{ route('kasir.transaksi-baru') }}" class="menu-link">Transaksi Baru</a>
                <a href="{{ route('kasir.riwayat-transaksi') }}" class="menu-link">Riwayat Transaksi</a>
                <a href="{{ route('kasir.retur') }}" class="menu-link">Retur</a>
                <a href="{{ route('kasir.buka-shift') }}" class="menu-link">Buka Shift</a>
                <a href="{{ route('kasir.tutup-shift') }}" class="menu-link">Tutup Shift</a>
                <a href="{{ route('kasir.riwayat-shift') }}" class="menu-link">Riwayat Shift</a>
            </div>

        @endif

    </div>

    <div class="px-3 pb-3 pt-3">
        <div class="rounded-[18px] bg-[#A8F23A] text-[#171719] p-3">

            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 shrink-0 rounded-full bg-white flex items-center justify-center text-[13px] font-bold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <div class="min-w-0 flex-1">
                    <p class="text-[12px] font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] opacity-55 truncate">
                        {{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-2.5">
                @csrf
                <button type="submit"
                        class="w-full h-8 flex items-center justify-center gap-2 rounded-[10px]
                               bg-black/[0.09] hover:bg-black/[0.14] text-[12px] font-medium transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10 17l5-5-5-5"></path>
                        <path d="M15 12H3"></path>
                        <path d="M15 4h5v16h-5"></path>
                    </svg>
                    Logout
                </button>
            </form>

        </div>
    </div>

</nav>

<style>
    .menu-link {
        height: 40px;
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 0 12px;
        border-radius: 11px;
        font-size: 13px;
        color: rgb(255 255 255 / 0.72);
        transition: 0.2s;
    }

    .dark .menu-link {
        color: rgb(0 0 0 / 0.68);
    }

    .menu-link:hover {
        background: rgb(255 255 255 / 0.06);
        color: white;
    }

    .dark .menu-link:hover {
        background: rgb(0 0 0 / 0.04);
        color: black;
    }
</style>
