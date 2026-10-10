<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Procurement Operations</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Supplier</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Kelola data supplier dan pantau performa pembelian</p>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
                {{ $suppliers->total() }} supplier
            </span>
        </div>
    </div>

    {{-- ================= ALERT SUCCESS ================= --}}
    @if (session('success'))
        <div class="rounded-2xl border border-lime-200 bg-lime-50 dark:border-lime-500/20 dark:bg-lime-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-lime-600 dark:text-lime-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
            <p class="text-sm font-medium text-lime-800 dark:text-lime-300">{{ session('success') }}</p>
        </div>
    @endif

    {{-- ================= SUMMARY CARDS ================= --}}
    @php
        $totalSuppliers = $suppliers->total();
        $activeCount = $suppliers->getCollection()->where('is_active', true)->count();
        $inactiveCount = $suppliers->getCollection()->where('is_active', false)->count();
        $totalPurchases = $suppliers->getCollection()->sum('purchases_count');
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Total Supplier</p>
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ $totalSuppliers }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">semua supplier</p>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Aktif</p>
                <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ $activeCount }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">siap ditransaksikan</p>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Nonaktif</p>
                <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-white/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M5 12h14"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ $inactiveCount }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">tidak aktif</p>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Total Pembelian</p>
                <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                        <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ $totalPurchases }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">transaksi PO</p>
        </div>
    </div>

    {{-- ================= FILTER + SEARCH ================= --}}
    <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-4"
         x-data="{ search: '' }">

        <div class="flex flex-wrap items-center gap-3">

            {{-- SEARCH --}}
            <div class="relative flex-1 min-w-[220px]">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                </svg>
                <input type="text" x-model="search"
                    placeholder="Cari nama supplier / kode / kontak..."
                    class="w-full h-10 pl-10 pr-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>

            {{-- STATUS FILTER --}}
            <div class="flex items-center gap-1.5">
                <button type="button" class="h-9 px-3 rounded-lg text-xs font-medium bg-[#171719] dark:bg-white text-white dark:text-[#171719] transition">
                    Semua
                </button>
                <button type="button" class="h-9 px-3 rounded-lg text-xs font-medium bg-gray-100 dark:bg-white/10 text-black/60 dark:text-white/60 hover:bg-gray-200 dark:hover:bg-white/20 transition">
                    Aktif
                </button>
                <button type="button" class="h-9 px-3 rounded-lg text-xs font-medium bg-gray-100 dark:bg-white/10 text-black/60 dark:text-white/60 hover:bg-gray-200 dark:hover:bg-white/20 transition">
                    Nonaktif
                </button>
            </div>
        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
            <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Daftar Supplier</h2>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Kelola data dan pantau performa supplier</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[860px] text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Supplier</th>
                        <th class="px-5 py-3.5 font-medium">Kontak</th>
                        <th class="px-5 py-3.5 font-medium">Kota</th>
                        <th class="px-5 py-3.5 text-center font-medium">Total PO</th>
                        <th class="px-5 py-3.5 text-center font-medium">Status</th>
                        <th class="px-5 py-3.5 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse ($suppliers as $supplier)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition riwayat-row"
                            data-search="{{ strtolower($supplier->name . ' ' . $supplier->code . ' ' . ($supplier->contact_person ?? '') . ' ' . ($supplier->phone ?? '')) }}">

                            {{-- SUPPLIER --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-lime-400 flex items-center justify-center text-sm font-bold text-[#171719] shrink-0">
                                        {{ strtoupper(substr($supplier->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-[#171719] dark:text-white truncate max-w-[200px]">
                                            {{ $supplier->name }}
                                        </p>
                                        <p class="text-[10px] text-black/45 dark:text-white/45 font-mono mt-0.5">
                                            {{ $supplier->code }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            {{-- KONTAK --}}
                            <td class="px-5 py-4">
                                @if($supplier->contact_person || $supplier->phone)
                                    <p class="text-xs text-black/70 dark:text-white/70">{{ $supplier->contact_person ?? '-' }}</p>
                                    @if($supplier->phone)
                                        <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 font-mono">{{ $supplier->phone }}</p>
                                    @endif
                                @else
                                    <span class="text-xs text-black/40 dark:text-white/40">—</span>
                                @endif
                            </td>

                            {{-- KOTA --}}
                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70">{{ $supplier->city ?? '-' }}</p>
                            </td>

                            {{-- TOTAL PO --}}
                            <td class="px-5 py-4 text-center">
                                @if(($supplier->purchases_count ?? 0) > 0)
                                    <span class="inline-flex px-2.5 py-1 rounded-lg bg-lime-100 dark:bg-lime-900/30 text-[11px] font-bold text-lime-700 dark:text-lime-400">
                                        {{ $supplier->purchases_count }}
                                    </span>
                                @else
                                    <span class="text-[11px] text-black/40 dark:text-white/40">0</span>
                                @endif
                            </td>

                            {{-- STATUS --}}
                            <td class="px-5 py-4 text-center">
                                @if($supplier->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-lime-100 dark:bg-lime-900/30 text-[10px] font-bold uppercase tracking-wider text-lime-700 dark:text-lime-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-lime-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-md bg-gray-100 dark:bg-white/10 text-[10px] font-bold uppercase tracking-wider text-black/50 dark:text-white/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="#" {{-- route edit --}}
                                       class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white flex items-center justify-center transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>
                                    <a href="#" {{-- route detail --}}
                                       class="h-8 px-3 rounded-lg bg-lime-400 hover:bg-lime-500 text-[#171719] text-[11px] font-bold flex items-center gap-1 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada supplier terdaftar</p>
                                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Tambahkan supplier untuk mulai melakukan pembelian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($suppliers->hasPages())
            <div class="border-t border-black/5 dark:border-white/10 px-5 py-4">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
