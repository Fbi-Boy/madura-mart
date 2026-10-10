<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Kasir</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Riwayat Shift</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Semua shift kerja Anda yang pernah dijalani</p>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
                {{ $shifts->total() }} shift
            </span>
            <a href="{{ route('kasir.buka-shift') }}"
               class="h-10 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-medium flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                </svg>
                Buka Shift
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

    {{-- ================= SUMMARY CARDS ================= --}}
    @php
        $totalShifts = $shifts->total();
        $activeShifts = $shifts->getCollection()->whereNull('closed_at')->count();
        $totalExpected = $shifts->getCollection()->sum('expected_cash');
        $totalActual = $shifts->getCollection()->sum('closing_cash');
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

        {{-- Total Shift --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Total Shift</p>
                <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ $totalShifts }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Shift di halaman ini</p>
        </div>

        {{-- Shift Aktif --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Shift Aktif</p>
                <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">{{ $activeShifts }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Belum ditutup</p>
        </div>

        {{-- Total Kas Seharusnya --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Total Kas Expected</p>
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">Rp {{ number_format($totalExpected ?? 0, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Akumulasi halaman ini</p>
        </div>

        {{-- Total Kas Aktual --}}
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Total Kas Aktual</p>
                <div class="w-8 h-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
            </div>
            <p class="text-xl font-bold text-[#171719] dark:text-white">Rp {{ number_format($totalActual ?? 0, 0, ',', '.') }}</p>
            <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Hasil hitung kasir</p>
        </div>
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Daftar Shift</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Riwayat lengkap beserta selisih kas</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Shift</th>
                        <th class="px-5 py-3.5 font-medium">Tipe</th>
                        <th class="px-5 py-3.5 font-medium">Waktu Buka</th>
                        <th class="px-5 py-3.5 font-medium">Waktu Tutup</th>
                        <th class="px-5 py-3.5 text-right font-medium">Kas Expected</th>
                        <th class="px-5 py-3.5 text-right font-medium">Kas Aktual</th>
                        <th class="px-5 py-3.5 text-right font-medium">Selisih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse ($shifts as $shift)
                        @php
                            $isActive = is_null($shift->closed_at);
                            $expected = (float) ($shift->expected_cash ?? 0);
                            $actual = (float) ($shift->closing_cash ?? 0);
                            $selisih = $actual - $expected;

                            // Warna selisih
                            if ($isActive) {
                                $selisihClass = 'text-black/40 dark:text-white/40';
                                $selisihLabel = '—';
                            } elseif ($selisih == 0) {
                                $selisihClass = 'text-lime-600 dark:text-lime-400';
                                $selisihLabel = 'SESUAI';
                            } elseif ($selisih > 0) {
                                $selisihClass = 'text-amber-600 dark:text-amber-400';
                                $selisihLabel = 'LEBIH';
                            } else {
                                $selisihClass = 'text-red-600 dark:text-red-400';
                                $selisihLabel = 'KURANG';
                            }

                            // Badge tipe shift
                            $shiftTypeLabel = match($shift->shift_type ?? null) {
                                'pagi-siang' => 'Pagi - Siang',
                                'siang-malam' => 'Siang - Malam',
                                default => 'Reguler',
                            };

                            $shiftTypeColor = match($shift->shift_type ?? null) {
                                'pagi-siang' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'siang-malam' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400',
                                default => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">

                            {{-- SHIFT NUMBER + STATUS --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    @if($isActive)
                                        <span class="w-2 h-2 rounded-full bg-lime-500 animate-pulse shrink-0"></span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600 shrink-0"></span>
                                    @endif
                                    <div>
                                        <p class="text-xs font-semibold text-[#171719] dark:text-white font-mono">{{ $shift->shift_number }}</p>
                                        @if($isActive)
                                            <p class="text-[10px] text-lime-600 dark:text-lime-400 mt-0.5">Aktif</p>
                                        @else
                                            <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5">Selesai</p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- TIPE SHIFT --}}
                            <td class="px-5 py-4">
                                <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-medium uppercase tracking-wider {{ $shiftTypeColor }}">
                                    {{ $shiftTypeLabel }}
                                </span>
                            </td>

                            {{-- WAKTU BUKA --}}
                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70 font-mono">{{ $shift->opened_at->format('d/m/Y') }}</p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 font-mono">{{ $shift->opened_at->format('H:i') }} WIB</p>
                            </td>

                            {{-- WAKTU TUTUP --}}
                            <td class="px-5 py-4">
                                @if($shift->closed_at)
                                    <p class="text-xs text-black/70 dark:text-white/70 font-mono">{{ $shift->closed_at->format('d/m/Y') }}</p>
                                    <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 font-mono">{{ $shift->closed_at->format('H:i') }} WIB</p>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-lime-600 dark:text-lime-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-lime-500 animate-pulse"></span>
                                        Sedang berjalan
                                    </span>
                                @endif
                            </td>

                            {{-- KAS EXPECTED --}}
                            <td class="px-5 py-4 text-right">
                                <p class="text-xs font-bold text-[#171719] dark:text-white">
                                    Rp {{ number_format($expected, 0, ',', '.') }}
                                </p>
                            </td>

                            {{-- KAS AKTUAL --}}
                            <td class="px-5 py-4 text-right">
                                @if($isActive)
                                    <p class="text-xs text-black/40 dark:text-white/40">—</p>
                                @else
                                    <p class="text-xs font-bold text-[#171719] dark:text-white">
                                        Rp {{ number_format($actual, 0, ',', '.') }}
                                    </p>
                                @endif
                            </td>

                            {{-- SELISIH --}}
                            <td class="px-5 py-4 text-right">
                                @if($isActive)
                                    <p class="text-xs {{ $selisihClass }}">—</p>
                                @else
                                    <p class="text-xs font-bold {{ $selisihClass }}">
                                        {{ $selisih >= 0 ? '+' : '' }}Rp {{ number_format(abs($selisih), 0, ',', '.') }}
                                    </p>
                                    <p class="text-[10px] font-medium {{ $selisihClass }} mt-0.5">{{ $selisihLabel }}</p>
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                                </svg>
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada riwayat shift</p>
                                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Shift yang Anda buka akan muncul di sini.</p>
                                <a href="{{ route('kasir.buka-shift') }}"
                                   class="inline-flex items-center gap-1.5 mt-4 h-9 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-medium transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                                    </svg>
                                    Buka Shift Pertama
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if ($shifts->hasPages())
            <div class="border-t border-black/5 dark:border-white/10 px-5 py-4">
                {{ $shifts->links() }}
            </div>
        @endif
    </div>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
