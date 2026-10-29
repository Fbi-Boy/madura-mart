
<x-app-layout>
    <div class="space-y-5 font-poppins">
        <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white px-5 py-4 dark:border-white/10 dark:bg-white/5">
            <div>
                <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Kasir</p>
                <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Riwayat Transaksi</h1>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Semua transaksi penjualan yang pernah dilakukan</p>
            </div>

            <div class="flex items-center gap-3">
                <span class="rounded-xl bg-lime-100 px-3 py-1.5 text-xs font-medium text-lime-700 dark:bg-lime-900/30 dark:text-lime-400">
                    {{ $sales->total() }} transaksi
                </span>

                <a href="{{ route('kasir.transaksi-baru') }}"
                   class="flex h-10 items-center gap-1.5 rounded-xl bg-lime-400 px-4 text-sm font-medium text-[#171719] transition hover:bg-lime-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Transaksi Baru
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="flex items-start gap-3 rounded-2xl border border-lime-200 bg-lime-50 px-4 py-3 dark:border-lime-500/20 dark:bg-lime-500/10">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="m20 6-11 11-5-5"/>
                </svg>
                <p class="text-sm font-medium text-lime-800 dark:text-lime-300">{{ session('success') }}</p>
            </div>
        @endif

        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative min-w-[220px] flex-1">
                    <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-4-4"/>
                    </svg>

                    <input
                        id="search-transaksi"
                        type="text"
                        placeholder="Cari invoice / pelanggan / kasir..."
                        class="h-10 w-full rounded-xl border border-black/5 bg-gray-50 pl-10 pr-4 text-sm focus:border-lime-400 focus:outline-none dark:border-white/10 dark:bg-white/5 dark:text-white"
                    >
                </div>

                <div class="flex flex-wrap items-center gap-1.5" id="filter-metode">
                    <button type="button" data-method="all" class="filter-button rounded-lg bg-[#171719] px-3 py-2 text-xs font-medium text-white transition dark:bg-white dark:text-[#171719]">
                        Semua
                    </button>
                    <button type="button" data-method="cash" class="filter-button rounded-lg bg-gray-100 px-3 py-2 text-xs font-medium text-black/60 transition hover:bg-gray-200 dark:bg-white/10 dark:text-white/60 dark:hover:bg-white/20">
                        Cash
                    </button>
                    <button type="button" data-method="qris" class="filter-button rounded-lg bg-gray-100 px-3 py-2 text-xs font-medium text-black/60 transition hover:bg-gray-200 dark:bg-white/10 dark:text-white/60 dark:hover:bg-white/20">
                        QRIS
                    </button>
                    <button type="button" data-method="transfer" class="filter-button rounded-lg bg-gray-100 px-3 py-2 text-xs font-medium text-black/60 transition hover:bg-gray-200 dark:bg-white/10 dark:text-white/60 dark:hover:bg-white/20">
                        Transfer
                    </button>
                    <button type="button" data-method="debit" class="filter-button rounded-lg bg-gray-100 px-3 py-2 text-xs font-medium text-black/60 transition hover:bg-gray-200 dark:bg-white/10 dark:text-white/60 dark:hover:bg-white/20">
                        Debit
                    </button>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5">
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
                                $methodColor = match ($sale->payment_method) {
                                    'cash' => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                    'transfer' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'qris' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                    'debit' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                    default => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
                                };

                                $customerName = $sale->customer?->name ?? 'Umum';
                                $cashierName = $sale->user?->name ?? '-';
                            @endphp

                            <tr
                                class="riwayat-row transition hover:bg-gray-50 dark:hover:bg-white/5"
                                data-search="{{ strtolower($sale->invoice . ' ' . $customerName . ' ' . $cashierName) }}"
                                data-method="{{ $sale->payment_method }}"
                            >
                                <td class="px-5 py-4">
                                    <p class="font-mono text-xs font-semibold text-[#171719] dark:text-white">
                                        {{ $sale->invoice }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-xs text-black/70 dark:text-white/70">
                                        {{ $sale->sale_date->format('d/m/Y') }}
                                    </p>
                                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">
                                        {{ $sale->sale_date->format('H:i') }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-xs text-black/70 dark:text-white/70">{{ $customerName }}</p>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-lime-400 text-[10px] font-bold text-black">
                                            {{ strtoupper(substr($cashierName, 0, 1)) }}
                                        </div>
                                        <span class="text-xs text-black/70 dark:text-white/70">{{ $cashierName }}</span>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-md px-2 py-1 text-[10px] font-medium uppercase tracking-wider {{ $methodColor }}">
                                        {{ $sale->payment_method }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <p class="text-xs font-bold text-[#171719] dark:text-white">
                                        Rp {{ number_format((float) $sale->total, 0, ',', '.') }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a
                                            href="{{ route('kasir.transaksi-struk', $sale) }}"
                                            class="inline-flex h-8 items-center gap-1 rounded-lg bg-gray-100 px-3 text-[11px] font-medium text-[#171719] transition hover:bg-gray-200 dark:bg-white/10 dark:text-white dark:hover:bg-white/20"
                                        >
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                <path d="M14 2v6h6M16 13H8M16 17H8"/>
                                            </svg>
                                            Struk
                                        </a>

                                        <a
                                            href="{{ route('kasir.transaksi-pdf', $sale) }}"
                                            class="inline-flex h-8 items-center gap-1 rounded-lg bg-lime-400 px-3 text-[11px] font-medium text-[#171719] transition hover:bg-lime-500"
                                        >
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M12 3v12m-5-5 5 5 5-5M5 19h14"/>
                                            </svg>
                                            PDF
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <svg class="mx-auto mb-3 h-12 w-12 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <path d="M14 2v6h6M16 13H8M16 17H8"/>
                                    </svg>
                                    <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada transaksi</p>
                                    <p class="mt-1 text-xs text-black/40 dark:text-white/40">Transaksi yang Anda buat akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse

                        <tr id="filter-empty" class="hidden">
                            <td colspan="7" class="px-5 py-10 text-center text-sm text-black/50 dark:text-white/50">
                                Tidak ada transaksi yang sesuai dengan pencarian atau filter.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if ($sales->hasPages())
                <div class="border-t border-black/5 px-5 py-4 dark:border-white/10">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-transaksi');
            const filterButtons = document.querySelectorAll('.filter-button');
            const rows = document.querySelectorAll('.riwayat-row');
            const emptyRow = document.getElementById('filter-empty');

            let currentSearch = '';
            let currentMethod = 'all';

            const activeClasses = {
                all: ['bg-[#171719]', 'dark:bg-white', 'text-white', 'dark:text-[#171719]'],
                cash: ['bg-lime-400', 'text-[#171719]'],
                qris: ['bg-purple-500', 'text-white'],
                transfer: ['bg-blue-500', 'text-white'],
                debit: ['bg-orange-500', 'text-white']
            };

            function applyFilter() {
                let visibleCount = 0;

                rows.forEach(row => {
                    const searchText = row.dataset.search || '';
                    const method = row.dataset.method || '';

                    const matchesSearch = searchText.includes(currentSearch);
                    const matchesMethod = currentMethod === 'all' || method === currentMethod;
                    const visible = matchesSearch && matchesMethod;

                    row.classList.toggle('hidden', !visible);

                    if (visible) {
                        visibleCount++;
                    }
                });

                emptyRow?.classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
            }

            function updateActiveButton(selectedButton) {
                filterButtons.forEach(button => {
                    button.className = 'filter-button rounded-lg bg-gray-100 px-3 py-2 text-xs font-medium text-black/60 transition hover:bg-gray-200 dark:bg-white/10 dark:text-white/60 dark:hover:bg-white/20';
                });

                const method = selectedButton.dataset.method;
                selectedButton.className = 'filter-button rounded-lg px-3 py-2 text-xs font-medium transition ' + activeClasses[method].join(' ');
            }

            searchInput?.addEventListener('input', event => {
                currentSearch = event.target.value.trim().toLowerCase();
                applyFilter();
            });

            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    currentMethod = button.dataset.method;
                    updateActiveButton(button);
                    applyFilter();
                });
            });

            applyFilter();
        });
    </script>

    <style>
        .font-poppins,
        .font-poppins * {
            font-family: 'Poppins', system-ui, -apple-system, sans-serif;
        }
    </style>
</x-app-layout>
