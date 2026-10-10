<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Kasir</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Riwayat Transaksi</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Semua transaksi penjualan yang pernah dilakukan</p>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
                {{ $sales->total() }} transaksi
            </span>
            <a href="{{ route('kasir.transaksi-baru') }}"
               class="h-10 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-medium flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14"/><path d="M5 12h14"/>
                </svg>
                Transaksi Baru
            </a>
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

    {{-- ================= FILTER + SEARCH ================= --}}
    <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-4"
         x-data="{ search: '', filterMethod: 'all' }">

        <div class="flex flex-wrap items-center gap-3">

            {{-- SEARCH --}}
            <div class="relative flex-1 min-w-[220px]">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                </svg>
                <input type="text" x-model="search"
                    placeholder="Cari invoice / pelanggan / kasir..."
                    class="w-full h-10 pl-10 pr-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>

            {{-- FILTER METHOD --}}
            <div class="flex items-center gap-1.5">
                <button type="button" @click="filterMethod = 'all'"
                    :class="filterMethod === 'all'
                        ? 'bg-[#171719] dark:bg-white text-white dark:text-[#171719]'
                        : 'bg-gray-100 dark:bg-white/10 text-black/60 dark:text-white/60 hover:bg-gray-200 dark:hover:bg-white/20'"
                    class="h-9 px-3 rounded-lg text-xs font-medium transition">
                    Semua
                </button>

                <button type="button" @click="filterMethod = 'cash'"
                    :class="filterMethod === 'cash'
                        ? 'bg-lime-400 text-[#171719]'
                        : 'bg-gray-100 dark:bg-white/10 text-black/60 dark:text-white/60 hover:bg-gray-200 dark:hover:bg-white/20'"
                    class="h-9 px-3 rounded-lg text-xs font-medium transition">
                    Cash
                </button>

                <button type="button" @click="filterMethod = 'qris'"
                    :class="filterMethod === 'qris'
                        ? 'bg-purple-500 text-white'
                        : 'bg-gray-100 dark:bg-white/10 text-black/60 dark:text-white/60 hover:bg-gray-200 dark:hover:bg-white/20'"
                    class="h-9 px-3 rounded-lg text-xs font-medium transition">
                    QRIS
                </button>

                <button type="button" @click="filterMethod = 'transfer'"
                    :class="filterMethod === 'transfer'
                        ? 'bg-blue-500 text-white'
                        : 'bg-gray-100 dark:bg-white/10 text-black/60 dark:text-white/60 hover:bg-gray-200 dark:hover:bg-white/20'"
                    class="h-9 px-3 rounded-lg text-xs font-medium transition">
                    Transfer
                </button>

                <button type="button" @click="filterMethod = 'debit'"
                    :class="filterMethod === 'debit'
                        ? 'bg-orange-500 text-white'
                        : 'bg-gray-100 dark:bg-white/10 text-black/60 dark:text-white/60 hover:bg-gray-200 dark:hover:bg-white/20'"
                    class="h-9 px-3 rounded-lg text-xs font-medium transition">
                    Debit
                </button>
            </div>

        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden"
         x-data="{ search: '', filterMethod: 'all' }">

        {{-- Karena filter & search di atas terpisah, kita wrap semua dalam satu scope --}}
        {{-- Struktur di bawah pakai vanilla JS untuk filter agar lebih simpel --}}

        <div class="overflow-x-auto">
            <table class="w-full text-left" id="riwayat-table">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Invoice</th>
                        <th class="px-5 py-3.5 font-medium">Tanggal</th>
                        <th class="px-5 py-3.5 font-medium">Pelanggan</th>
                        <th class="px-5 py-3.5 font-medium">Kasir</th>
                        <th class="px-5 py-3.5 font-medium">Pembayaran</th>
                        <th class="px-5 py-3.5 text-right font-medium">Total</th>
                        <th class="px-5 py-3.5 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse ($sales as $sale)
                        @php
                            $methodColor = match($sale->payment_method) {
                                'cash' => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                'transfer' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'qris' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                'debit' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition riwayat-row"
                            data-search="{{ strtolower($sale->invoice . ' ' . ($sale->customer?->name ?? 'umum') . ' ' . $sale->user->name) }}"
                            data-method="{{ $sale->payment_method }}">

                            <td class="px-5 py-4">
                                <p class="text-xs font-semibold text-[#171719] dark:text-white font-mono">{{ $sale->invoice }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70">{{ $sale->sale_date->format('d/m/Y') }}</p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5">{{ $sale->sale_date->format('H:i') }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70">{{ $sale->customer?->name ?? 'Umum' }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-lime-400 flex items-center justify-center text-[10px] font-bold text-black shrink-0">
                                        {{ strtoupper(substr($sale->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs text-black/70 dark:text-white/70">{{ $sale->user->name }}</span>
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-medium uppercase tracking-wider {{ $methodColor }}">
                                    {{ $sale->payment_method }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <p class="text-xs font-bold text-[#171719] dark:text-white">
                                    Rp {{ number_format((float) $sale->total, 0, ',', '.') }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('kasir.transaksi-struk', $sale) }}"
                                   class="inline-flex items-center gap-1 h-8 px-3 rounded-lg bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-[11px] font-medium transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <path d="M14 2v6h6"/>
                                        <path d="M16 13H8"/><path d="M16 17H8"/>
                                    </svg>
                                    Struk
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <path d="M14 2v6h6"/>
                                    <path d="M16 13H8"/><path d="M16 17H8"/>
                                </svg>
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada transaksi</p>
                                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Transaksi yang Anda buat akan muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($sales->hasPages())
            <div class="border-t border-black/5 dark:border-white/10 px-5 py-4">
                {{ $sales->links() }}
            </div>
        @endif
    </div>

</div>

<script>
    // Filter & search realtime (client-side)
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.querySelector('input[placeholder="Cari invoice / pelanggan / kasir..."]');
        const filterButtons = document.querySelectorAll('[x-data] button[type="button"][x-on\\:click], [x-data] button[type="button"][@click]');
        const rows = document.querySelectorAll('.riwayat-row');
        const filterWrap = searchInput?.closest('[x-data]');

        let currentSearch = '';
        let currentMethod = 'all';

        function applyFilter() {
            rows.forEach(row => {
                const matchSearch = !currentSearch || row.dataset.search.includes(currentSearch.toLowerCase());
                const matchMethod = currentMethod === 'all' || row.dataset.method === currentMethod;
                row.style.display = (matchSearch && matchMethod) ? '' : 'none';
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                currentSearch = e.target.value.trim();
                applyFilter();
            });
        }

        // Button filter
        const allButtons = document.querySelectorAll('button');
        allButtons.forEach(btn => {
            const text = btn.textContent.trim().toLowerCase();
            if (['semua', 'cash', 'qris', 'transfer', 'debit'].includes(text)) {
                btn.addEventListener('click', () => {
                    currentMethod = text === 'semua' ? 'all' : text;
                    applyFilter();

                    // Update visual active state
                    allButtons.forEach(b => {
                        const bt = b.textContent.trim().toLowerCase();
                        if (['semua', 'cash', 'qris', 'transfer', 'debit'].includes(bt)) {
                            b.classList.remove('bg-lime-400', 'text-[#171719]', 'bg-purple-500', 'text-white', 'bg-blue-500', 'bg-orange-500', 'bg-[#171719]', 'dark:bg-white', 'dark:text-[#171719]');
                            b.classList.add('bg-gray-100', 'dark:bg-white/10', 'text-black/60', 'dark:text-white/60');
                        }
                    });

                    // Set active button
                    btn.classList.remove('bg-gray-100', 'dark:bg-white/10', 'text-black/60', 'dark:text-white/60');
                    if (currentMethod === 'all') {
                        btn.classList.add('bg-[#171719]', 'dark:bg-white', 'text-white', 'dark:text-[#171719]');
                    } else if (currentMethod === 'cash') {
                        btn.classList.add('bg-lime-400', 'text-[#171719]');
                    } else if (currentMethod === 'qris') {
                        btn.classList.add('bg-purple-500', 'text-white');
                    } else if (currentMethod === 'transfer') {
                        btn.classList.add('bg-blue-500', 'text-white');
                    } else if (currentMethod === 'debit') {
                        btn.classList.add('bg-orange-500', 'text-white');
                    }
                });
            }
        });
    });
</script>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
