<x-app-layout>
<div class="space-y-5 font-poppins"
     x-data="{
         actualCash: {{ $expectedCash ?? 0 }},
         expected: {{ $expectedCash ?? 0 }},
         clock: '',
         init() {
             const tick = () => this.clock = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
             tick();
             setInterval(tick, 1000);
         }
     }"
     x-init="init()">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Kasir</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Tutup Shift</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Verifikasi kas dan tutup shift kerja</p>
        </div>
        <span class="font-mono text-sm font-bold text-[#171719] dark:text-white" x-text="clock"></span>
    </div>

    @if(!$openShift)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10 px-5 py-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">Tidak ada shift aktif</p>
                <p class="mt-0.5 text-xs text-amber-700 dark:text-amber-200/80">Buka shift terlebih dahulu sebelum bisa menutupnya.</p>
                <a href="{{ route('kasir.buka-shift') }}" class="mt-3 inline-block text-xs font-semibold text-lime-700 dark:text-lime-400 hover:underline">
                    Buka shift sekarang →
                </a>
            </div>
        </div>
    @else

        {{-- ================= INFO SHIFT ================= --}}
        <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Informasi Shift</h3>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">No. Shift</p>
                    <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white font-mono">{{ $openShift->shift_number }}</p>
                </div>
                <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Cabang</p>
                    <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white truncate">
                        {{ $openShift->branch?->name ?? auth()->user()->branch?->name ?? '-' }}
                    </p>
                </div>
                <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Tipe Shift</p>
                    <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white capitalize">
                        {{ str_replace('-', ' - ', $openShift->shift_type ?? 'pagi-siang') }}
                    </p>
                </div>
                <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Waktu Buka</p>
                    <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white font-mono">
                        {{ $openShift->opened_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ================= RINGKASAN + FORM ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- KIRI: RINCIAN --}}
            <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Ringkasan Kas</h3>

                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-dashed border-black/5 dark:border-white/10">
                        <span class="text-black/60 dark:text-white/60">Modal Awal</span>
                        <span class="font-bold text-[#171719] dark:text-white">
                            Rp {{ number_format($openShift->opening_cash, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-dashed border-black/5 dark:border-white/10">
                        <span class="text-black/60 dark:text-white/60">Penjualan Cash</span>
                        <span class="font-bold text-lime-600 dark:text-lime-400">
                            +Rp {{ number_format($summary['cash_sales'] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-2 border-b border-dashed border-black/5 dark:border-white/10">
                        <span class="text-black/60 dark:text-white/60">Refund Cash</span>
                        <span class="font-bold text-red-500">
                            −Rp {{ number_format($summary['cash_refunds'] ?? 0, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center py-3 bg-lime-50 dark:bg-lime-900/20 rounded-xl px-3">
                        <span class="font-semibold text-[#171719] dark:text-white">Kas Seharusnya</span>
                        <span class="font-bold text-lg text-lime-600 dark:text-lime-400">
                            Rp {{ number_format($expectedCash, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- STATISTIK --}}
                <div class="mt-5 grid grid-cols-2 gap-3">
                    <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-3">
                        <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Total Transaksi</p>
                        <p class="mt-1 text-lg font-bold text-[#171719] dark:text-white">
                            {{ $summary['total_transactions'] ?? 0 }}
                        </p>
                    </div>
                    <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-3">
                        <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Total Omzet</p>
                        <p class="mt-1 text-lg font-bold text-[#171719] dark:text-white">
                            Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- KANAN: FORM VERIFIKASI --}}
            <form method="POST" action="{{ route('kasir.tutup-shift.store') }}"
                  class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
                @csrf
                <input type="hidden" name="shift_id" value="{{ $openShift->id }}">

                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Verifikasi Kas Akhir</h3>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Hitung uang tunai di kasir dan masukkan jumlah aktual</p>

                {{-- INPUT KAS AKTUAL --}}
                <div class="mt-4">
                    <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                        Kas Aktual (Hasil Hitung)
                    </label>
                    <div class="mt-2 relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-medium text-black/45 dark:text-white/45">
                            Rp
                        </span>
                        <input type="number" name="closing_cash" min="0" step="1000"
                            x-model.number="actualCash"
                            required
                            class="no-spinner w-full h-12 pl-12 pr-4 rounded-xl border-2 border-lime-400 dark:border-lime-400 bg-transparent text-base font-bold text-[#171719] dark:text-white focus:outline-none focus:ring-4 focus:ring-lime-200 dark:focus:ring-lime-900/40">
                    </div>
                </div>

                {{-- SELISIH --}}
                <div class="mt-4 rounded-xl p-4 transition"
                     :class="{
                        'bg-lime-50 dark:bg-lime-900/20': actualCash == expected,
                        'bg-amber-50 dark:bg-amber-900/20': actualCash > expected,
                        'bg-red-50 dark:bg-red-900/20': actualCash < expected
                     }">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Selisih</p>
                            <p class="mt-1 text-xl font-bold"
                               :class="{
                                   'text-lime-600 dark:text-lime-400': actualCash == expected,
                                   'text-amber-600 dark:text-amber-400': actualCash > expected,
                                   'text-red-600 dark:text-red-400': actualCash < expected
                               }">
                                <span x-text="(actualCash - expected >= 0 ? '+' : '') + 'Rp ' + new Intl.NumberFormat('id-ID').format(actualCash - expected)"></span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Status</p>
                            <p class="mt-1 text-xs font-bold"
                               :class="{
                                   'text-lime-600 dark:text-lime-400': actualCash == expected,
                                   'text-amber-600 dark:text-amber-400': actualCash > expected,
                                   'text-red-600 dark:text-red-400': actualCash < expected
                               }"
                               x-text="actualCash == expected ? 'SESUAI' : (actualCash > expected ? 'LEBIH' : 'KURANG')"></p>
                        </div>
                    </div>
                </div>

                {{-- CATATAN --}}
                <div class="mt-4">
                    <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                        Catatan (Opsional)
                    </label>
                    <textarea name="notes" rows="3" placeholder="Catatan tentang selisih kas, kendala shift, dll..."
                        class="mt-2 w-full rounded-xl border border-black/10 dark:border-white/10 bg-transparent px-4 py-3 text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 resize-none">{{ old('notes') }}</textarea>
                </div>

                {{-- WARNING SELISIH --}}
                <div x-show="actualCash != expected" x-transition
                     class="mt-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-500/30 p-3 flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                    </svg>
                    <p class="text-[11px] text-amber-700 dark:text-amber-200">
                        Terdapat selisih kas. Pastikan sudah dihitung dengan benar sebelum menutup shift.
                    </p>
                </div>

                {{-- ACTIONS --}}
                <div class="mt-5 flex gap-2">
                    <button type="submit"
                        class="flex-1 h-12 rounded-xl bg-red-500 hover:bg-red-600 text-white font-bold text-sm flex items-center justify-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <rect width="18" height="18" x="3" y="3" rx="2"/>
                            <path d="M9 3v18"/>
                        </svg>
                        Tutup Shift
                    </button>
                    <a href="{{ route('kasir.transaksi-baru') }}"
                       class="h-12 px-5 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center transition">
                        Batal
                    </a>
                </div>

            </form>
        </div>

    @endif

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .no-spinner { -moz-appearance: textfield; appearance: textfield; }
</style>
</x-app-layout>
