<nav class="w-64 min-h-screen shrink-0 bg-[#5B3FA6] dark:bg-[#30205C] text-white flex flex-col transition-colors duration-200">

    <div class="h-20 px-6 flex items-center">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#F4C430] text-[#5B3FA6] flex items-center justify-center font-black text-lg">
                M
            </div>
            <div>
                <p class="text-[15px] font-bold leading-none">Madura Mart</p>
                <p class="text-[10px] text-white/55 mt-1">Management System</p>
            </div>
        </a>
    </div>

    <div class="px-5 pb-5">
        <div class="rounded-2xl bg-white/10 border border-white/10 p-3 flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-white text-[#6C4AB6] flex items-center justify-center font-semibold text-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-white/55 mt-0.5">{{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}</p>
            </div>
        </div>
    </div>

    <div class="flex-1 px-4 overflow-y-auto">
        <p class="px-3 mb-2 text-[10px] font-bold uppercase tracking-[0.12em] text-white/45">Menu</p>

        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-xl text-sm font-medium text-[#F4C430]">
            <span class="w-2 h-2 rounded-full bg-[#F4C430]"></span>
            Dashboard
        </a>

        @if(auth()->user()->role === 'admin')

            <details class="group mb-1">
                <summary class="list-none cursor-pointer flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-white/75 hover:text-[#F4C430]">
                    <span class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-white/50"></span>Transaksi</span>
                    <span class="text-xs transition-transform group-open:rotate-180">⌄</span>
                </summary>
                <div class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Penjualan</a>
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Pembelian</a>
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Pesanan</a>
                </div>
            </details>

            <details class="group mb-1">
                <summary class="list-none cursor-pointer flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-white/75 hover:text-[#F4C430]">
                    <span class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-white/50"></span>Master Data</span>
                    <span class="text-xs transition-transform group-open:rotate-180">⌄</span>
                </summary>
                <div class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Produk</a>
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Distributor</a>
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Client</a>
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Kurir</a>
                </div>
            </details>

            <details class="group mb-1">
                <summary class="list-none cursor-pointer flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-white/75 hover:text-[#F4C430]">
                    <span class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-white/50"></span>Laporan</span>
                    <span class="text-xs transition-transform group-open:rotate-180">⌄</span>
                </summary>
                <div class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Penjualan</a>
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Pembelian</a>
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Stok</a>
                </div>
            </details>

            <details class="group mb-1">
                <summary class="list-none cursor-pointer flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-white/75 hover:text-[#F4C430]">
                    <span class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-white/50"></span>Manajemen</span>
                    <span class="text-xs transition-transform group-open:rotate-180">⌄</span>
                </summary>
                <div class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Pengguna</a>
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Pengaturan Sistem</a>
                </div>
            </details>

        @elseif(auth()->user()->role === 'kasir')

            <details class="group mb-1">
                <summary class="list-none cursor-pointer flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-white/75 hover:text-[#F4C430]">
                    <span class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-white/50"></span>Penjualan</span>
                    <span class="text-xs transition-transform group-open:rotate-180">⌄</span>
                </summary>
                <div class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Transaksi Baru</a>
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Riwayat Transaksi</a>
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Retur</a>
                </div>
            </details>

            <details class="group mb-1">
                <summary class="list-none cursor-pointer flex items-center justify-between px-3 py-2.5 rounded-xl text-sm text-white/75 hover:text-[#F4C430]">
                    <span class="flex items-center gap-3"><span class="w-2 h-2 rounded-full bg-white/50"></span>Shift</span>
                    <span class="text-xs transition-transform group-open:rotate-180">⌄</span>
                </summary>
                <div class="ml-8 mt-1 space-y-1">
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Buka Shift</a>
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Tutup Shift</a>
                    <a href="#" class="block px-3 py-2 rounded-lg text-xs text-white/60 hover:text-[#F4C430]">Riwayat Shift</a>
                </div>
            </details>

        @endif
    </div>

    <div class="p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-white/65 hover:text-white hover:bg-white/10 transition">
                <span class="text-base">↪</span>
                Keluar
            </button>
        </form>
    </div>
</nav>