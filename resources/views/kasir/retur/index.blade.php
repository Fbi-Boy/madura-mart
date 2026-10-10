<x-app-layout>
@php
    // ===== DUMMY DATA (hapus nanti kalau controller sudah ada) =====
    $returns = collect([
        (object) [
            'id' => 1,
            'return_number' => 'RTR-20260930-001',
            'sale' => (object) ['invoice' => 'INV-260930-008'],
            'customer' => (object) ['name' => 'Budi Santoso'],
            'return_date' => \Carbon\Carbon::parse('2026-09-30 13:15'),
            'refund_method' => 'cash',
            'total' => 35000,
            'reason' => 'Produk rusak',
            'user' => (object) ['name' => 'Ahmad'],
            'items_count' => 1,
        ],
        (object) [
            'id' => 2,
            'return_number' => 'RTR-20260930-002',
            'sale' => (object) ['invoice' => 'INV-260930-005'],
            'customer' => null,
            'return_date' => \Carbon\Carbon::parse('2026-09-30 14:42'),
            'refund_method' => 'qris',
            'total' => 18000,
            'reason' => 'Salah beli',
            'user' => (object) ['name' => 'Ahmad'],
            'items_count' => 2,
        ],
    ]);
@endphp

<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Kasir</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Retur Penjualan</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Kelola pengembalian produk dan refund pelanggan</p>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
                {{ $returns->count() }} retur
            </span>
            <a href="#" {{-- route('kasir.retur.create') --}}
               class="h-10 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-medium flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14"/><path d="M5 12h14"/>
                </svg>
                Buat Retur
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

    {{-- ================= STAT CARDS ================= --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Total Retur --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Total Retur</p>
                <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                        <path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ $returns->count() }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Semua periode</p>
        </div>

        {{-- Retur Hari Ini --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Retur Hari Ini</p>
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">2</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">30 Sep 2026</p>
        </div>

        {{-- Nilai Refund --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Nilai Refund</p>
                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">Rp 53.000</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Total dikembalikan</p>
        </div>

        {{-- Produk Diretur --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Produk Diretur</p>
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">3</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Unit dikembalikan</p>
        </div>
    </div>

    {{-- ================= FILTER + SEARCH ================= --}}
    <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-4">
        <div class="flex flex-wrap items-center gap-3">

            <div class="relative flex-1 min-w-[220px]">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>
                </svg>
                <input type="text"
                    placeholder="Cari no. retur / invoice / pelanggan..."
                    class="w-full h-10 pl-10 pr-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>

            <div class="flex items-center gap-1.5">
                <button type="button" class="h-9 px-3 rounded-lg text-xs font-medium bg-[#171719] dark:bg-white text-white dark:text-[#171719] transition">
                    Semua
                </button>
                <button type="button" class="h-9 px-3 rounded-lg text-xs font-medium bg-gray-100 dark:bg-white/10 text-black/60 dark:text-white/60 hover:bg-gray-200 dark:hover:bg-white/20 transition">
                    Cash
                </button>
                <button type="button" class="h-9 px-3 rounded-lg text-xs font-medium bg-gray-100 dark:bg-white/10 text-black/60 dark:text-white/60 hover:bg-gray-200 dark:hover:bg-white/20 transition">
                    QRIS
                </button>
                <button type="button" class="h-9 px-3 rounded-lg text-xs font-medium bg-gray-100 dark:bg-white/10 text-black/60 dark:text-white/60 hover:bg-gray-200 dark:hover:bg-white/20 transition">
                    Transfer
                </button>
            </div>
        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
            <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Daftar Retur</h2>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Riwayat lengkap semua retur penjualan</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">No. Retur</th>
                        <th class="px-5 py-3.5 font-medium">Invoice Asal</th>
                        <th class="px-5 py-3.5 font-medium">Pelanggan</th>
                        <th class="px-5 py-3.5 font-medium">Tanggal</th>
                        <th class="px-5 py-3.5 font-medium">Alasan</th>
                        <th class="px-5 py-3.5 font-medium">Refund</th>
                        <th class="px-5 py-3.5 text-right font-medium">Total</th>
                        <th class="px-5 py-3.5 text-right font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse($returns as $return)
                        @php
                            $methodColor = match($return->refund_method) {
                                'cash' => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                'transfer' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'qris' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                'debit' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">

                            <td class="px-5 py-4">
                                <p class="text-xs font-semibold text-[#171719] dark:text-white font-mono">{{ $return->return_number }}</p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5">{{ $return->items_count }} item</p>
                            </td>

                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70 font-mono">{{ $return->sale->invoice }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70">{{ $return->customer?->name ?? 'Umum' }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70">{{ $return->return_date->format('d/m/Y') }}</p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5">{{ $return->return_date->format('H:i') }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70 truncate max-w-[180px]">{{ $return->reason ?? '—' }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-medium uppercase tracking-wider {{ $methodColor }}">
                                    {{ $return->refund_method }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <p class="text-xs font-bold text-[#171719] dark:text-white">
                                    Rp {{ number_format($return->total, 0, ',', '.') }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-right">
                                <a href="#"
                                   class="inline-flex items-center gap-1 h-8 px-3 rounded-lg bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-[11px] font-medium transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <path d="M14 2v6h6"/>
                                        <path d="M16 13H8"/><path d="M16 17H8"/>
                                    </svg>
                                    Detail
                                </a>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-16 text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                                    <path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                                </svg>
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada retur</p>
                                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Retur yang Anda buat akan muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION (placeholder) --}}
        <div class="border-t border-black/5 dark:border-white/10 px-5 py-4 flex items-center justify-between">
            <p class="text-[11px] text-black/45 dark:text-white/45">Menampilkan 1–2 dari 2 retur</p>
            <div class="flex gap-1">
                <button class="h-8 px-3 rounded-lg bg-gray-100 dark:bg-white/10 text-xs text-black/60 dark:text-white/60">← Prev</button>
                <button class="h-8 px-3 rounded-lg bg-lime-400 text-xs font-bold text-[#171719]">1</button>
                <button class="h-8 px-3 rounded-lg bg-gray-100 dark:bg-white/10 text-xs text-black/60 dark:text-white/60">Next →</button>
            </div>
        </div>

    </div>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
