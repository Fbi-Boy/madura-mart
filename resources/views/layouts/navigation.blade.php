@php
    $isAdmin = auth()->user()->role === 'admin';
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

                <a href="{{ route('menu.show', ['menu' => 'penjualan']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5h16v14H4z"></path><path d="M8 9h8M8 13h5"></path></svg>
                    Penjualan
                </a>

                <a href="{{ route('menu.show', ['menu' => 'pembelian']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5h16v14H4z"></path><path d="M8 9h8M8 13h6M8 17h4"></path></svg>
                    Pembelian
                </a>

                <a href="{{ route('menu.show', ['menu' => 'pesanan']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="9" r="3"></circle><path d="M3 20a6 6 0 0 1 12 0"></path><path d="M17 11h4M19 9v4"></path></svg>
                    Pesanan
                </a>

                <a href="{{ route('menu.show', ['menu' => 'produk']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="4" width="16" height="16" rx="1.5"></rect><path d="M8 8h8M8 12h8M8 16h5"></path></svg>
                    Produk
                </a>

                <a href="{{ route('menu.show', ['menu' => 'distributor']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 8h18"></path><path d="M5 8v12h14V8"></path><path d="m8 5 1-2h6l1 2"></path></svg>
                    Distributor
                </a>

                <a href="{{ route('menu.show', ['menu' => 'client']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="8" r="3"></circle><path d="M5 21a7 7 0 0 1 14 0"></path></svg>
                    Client
                </a>

                <a href="{{ route('menu.show', ['menu' => 'kurir']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="5" width="16" height="14" rx="2"></rect><path d="M8 9h8M8 13h5"></path></svg>
                    Kurir
                </a>

                <a href="{{ route('menu.show', ['menu' => 'laporan-penjualan']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19V5"></path><path d="M4 19h16"></path><path d="m7 15 3-4 3 2 4-6"></path></svg>
                    Laporan Penjualan
                </a>

                <a href="{{ route('menu.show', ['menu' => 'laporan-pembelian']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="4" width="16" height="16" rx="1.5"></rect><path d="M8 8h8M8 12h8M8 16h5"></path></svg>
                    Laporan Pembelian
                </a>

                <a href="{{ route('menu.show', ['menu' => 'stok']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 20V9"></path><path d="M10 20V5"></path><path d="M15 20v-7"></path><path d="M20 20V3"></path></svg>
                    Stok
                </a>

            </div>

        @elseif(auth()->user()->role === 'kasir')

            <div class="mt-3 space-y-0.5">

                <a href="{{ route('menu.show', ['menu' => 'transaksi-baru']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 4h18v16H3z"></path><path d="M7 8h10M7 12h10M7 16h6"></path></svg>
                    Transaksi Baru
                </a>

                <a href="{{ route('menu.show', ['menu' => 'riwayat-transaksi']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 4h14v16H5z"></path><path d="M8 8h8M8 12h8M8 16h5"></path></svg>
                    Riwayat Transaksi
                </a>

                <a href="{{ route('menu.show', ['menu' => 'retur']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5h16v14H4z"></path><path d="m8 9 8 6M16 9l-8 6"></path></svg>
                    Retur
                </a>

                <a href="{{ route('menu.show', ['menu' => 'buka-shift']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"></circle><path d="M12 8v4l3 2"></path></svg>
                    Buka Shift
                </a>

                <a href="{{ route('menu.show', ['menu' => 'tutup-shift']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"></circle><path d="M8 12h8"></path></svg>
                    Tutup Shift
                </a>

                <a href="{{ route('menu.show', ['menu' => 'riwayat-shift']) }}" class="menu-link">
                    <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"></circle><path d="M8 12h8M12 8v8"></path></svg>
                    Riwayat Shift
                </a>

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
                    <p class="text-[10px] opacity-55 truncate">{{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}</p>
                </div>

            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-2.5">
                @csrf
                <button type="submit" class="w-full h-8 flex items-center justify-center gap-2 rounded-[10px] bg-black/[0.09] hover:bg-black/[0.14] text-[12px] font-medium transition">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10 17l5-5-5-5"></path><path d="M15 12H3"></path><path d="M15 4h5v16h-5"></path>
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

    .menu-icon {
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }
</style>