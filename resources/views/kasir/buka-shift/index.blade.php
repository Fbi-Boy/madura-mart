<x-app-layout>
<div class="space-y-5 font-poppins"
     x-data="{
         shiftType: 'pagi-siang',
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
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Buka Shift</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Mulai shift kerja Anda hari ini</p>
        </div>
        <span class="font-mono text-sm font-bold text-[#171719] dark:text-white" x-text="clock"></span>
    </div>

    @if($openShift)
        {{-- ================= SUDAH ADA SHIFT AKTIF ================= --}}
        <div class="rounded-2xl border border-lime-200 bg-lime-50 dark:border-lime-500/20 dark:bg-lime-500/10 p-5">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-lime-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#171719]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-[#171719] dark:text-white">Shift Sedang Berjalan</p>
                    <p class="mt-1 text-xs text-black/60 dark:text-white/60">
                        <strong>{{ $openShift->shift_number }}</strong> · Dibuka {{ $openShift->opened_at->format('d/m/Y H:i') }}
                    </p>

                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs">
                        <div>
                            <p class="text-black/45 dark:text-white/45">Cabang</p>
                            <p class="mt-0.5 font-bold text-[#171719] dark:text-white truncate">
                                {{ $openShift->branch?->name ?? auth()->user()->branch?->name ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-black/45 dark:text-white/45">Tipe Shift</p>
                            <p class="mt-0.5 font-bold text-[#171719] dark:text-white capitalize">
                                {{ str_replace('-', ' - ', $openShift->shift_type ?? 'pagi-siang') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-black/45 dark:text-white/45">Modal Awal</p>
                            <p class="mt-0.5 font-bold text-[#171719] dark:text-white">
                                Rp {{ number_format($openShift->opening_cash, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('kasir.transaksi-baru') }}"
                   class="h-10 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-medium flex items-center gap-1.5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 5v14"/><path d="M5 12h14"/>
                    </svg>
                    Mulai Transaksi
                </a>
                <a href="{{ route('kasir.tutup-shift') }}"
                   class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18"/>
                    </svg>
                    Tutup Shift
                </a>
            </div>
        </div>
    @else

        {{-- ================= FORM BUKA SHIFT ================= --}}
        <form method="POST" action="{{ route('kasir.buka-shift.store') }}" class="space-y-5">
            @csrf

            {{-- INFO CABANG & KASIR --}}
            <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Informasi Penugasan</h3>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Data ini diambil otomatis dari profil Anda</p>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- CABANG --}}
                    <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/>
                                <path d="M9 21v-6h6v6"/><path d="M9 9h.01"/><path d="M15 9h.01"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Cabang</p>
                            <p class="text-sm font-bold text-[#171719] dark:text-white truncate">
                                {{ auth()->user()->branch?->name ?? 'Belum ada cabang' }}
                            </p>
                        </div>
                    </div>

                    {{-- KASIR --}}
                    <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-lime-400 flex items-center justify-center text-sm font-bold text-[#171719] shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Kasir</p>
                            <p class="text-sm font-bold text-[#171719] dark:text-white truncate">{{ auth()->user()->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PILIH TIPE SHIFT --}}
            <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Pilih Tipe Shift</h3>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pilih shift sesuai jadwal Anda hari ini</p>

                <input type="hidden" name="shift_type" :value="shiftType">

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3">

                    {{-- SHIFT PAGI - SIANG --}}
                    <button type="button" @click="shiftType = 'pagi-siang'"
                        :class="shiftType === 'pagi-siang'
                            ? 'border-lime-400 bg-lime-50 dark:bg-lime-900/20 shadow-md'
                            : 'border-black/10 dark:border-white/10 hover:border-lime-400'"
                        class="text-left rounded-xl border-2 p-4 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="4"/>
                                    <path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/>
                                    <path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/>
                                    <path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>
                                </svg>
                            </div>
                            <div x-show="shiftType === 'pagi-siang'"
                                 class="w-5 h-5 rounded-full bg-lime-400 flex items-center justify-center">
                                <svg class="w-3 h-3 text-[#171719]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-3 text-sm font-bold text-[#171719] dark:text-white">Pagi - Siang</p>
                        <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">08:00 – 16:00 WIB</p>
                    </button>

                    {{-- SHIFT SIANG - MALAM --}}
                    <button type="button" @click="shiftType = 'siang-malam'"
                        :class="shiftType === 'siang-malam'
                            ? 'border-lime-400 bg-lime-50 dark:bg-lime-900/20 shadow-md'
                            : 'border-black/10 dark:border-white/10 hover:border-lime-400'"
                        class="text-left rounded-xl border-2 p-4 transition-all">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9z"/>
                                </svg>
                            </div>
                            <div x-show="shiftType === 'siang-malam'"
                                 class="w-5 h-5 rounded-full bg-lime-400 flex items-center justify-center">
                                <svg class="w-3 h-3 text-[#171719]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-3 text-sm font-bold text-[#171719] dark:text-white">Siang - Malam</p>
                        <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">16:00 – 24:00 WIB</p>
                    </button>

                </div>
            </div>

            {{-- MODAL AWAL + WAKTU BUKA --}}
            <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Detail Shift</h3>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- MODAL AWAL --}}
                    <div>
                        <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                            Modal Awal Kas
                        </label>
                        <div class="mt-2 relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-medium text-black/45 dark:text-white/45">
                                Rp
                            </span>
                            <input type="number" name="opening_cash" min="0" step="1000"
                                value="{{ old('opening_cash', 0) }}"
                                required
                                class="no-spinner w-full h-11 pl-12 pr-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm font-bold text-[#171719] dark:text-white focus:outline-none focus:border-lime-400">
                        </div>
                        <p class="mt-1.5 text-[10px] text-black/45 dark:text-white/45">Uang tunai yang ada di kasir saat ini</p>
                        @error('opening_cash')
                            <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- WAKTU BUKA (READONLY) --}}
                    <div>
                        <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                            Waktu Buka
                        </label>
                        <div class="mt-2 h-11 flex items-center px-4 rounded-xl border border-black/5 dark:border-white/10 bg-gray-50 dark:bg-white/5">
                            <svg class="w-4 h-4 text-black/45 dark:text-white/45 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                            </svg>
                            <span class="ml-2 text-sm font-bold text-[#171719] dark:text-white font-mono">
                                {{ now()->format('d/m/Y H:i') }}
                            </span>
                        </div>
                        <input type="hidden" name="opened_at" value="{{ now()->format('Y-m-d\TH:i') }}">
                        <p class="mt-1.5 text-[10px] text-black/45 dark:text-white/45">Otomatis mengikuti waktu sekarang</p>
                    </div>
                </div>

                {{-- NO. SHIFT (AUTO, HIDDEN) --}}
                <input type="hidden" name="shift_number"
                       value="{{ old('shift_number', 'SHIFT-' . now()->format('YmdHis')) }}">
            </div>

            {{-- WARNING --}}
            <div class="rounded-2xl border border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10 px-4 py-3 flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                </svg>
                <div>
                    <p class="text-xs font-semibold text-amber-800 dark:text-amber-200">Perhatian</p>
                    <p class="mt-0.5 text-[11px] text-amber-700 dark:text-amber-200/80">
                        Setelah shift dibuka, Anda tidak dapat membuka shift baru sampai shift ini ditutup.
                    </p>
                </div>
            </div>

            {{-- ACTIONS --}}
            <div class="flex items-center gap-3">
                <button type="submit"
                    class="h-12 px-6 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                    </svg>
                    Buka Shift Sekarang
                </button>

                <a href="{{ route('dashboard') }}"
                   class="h-12 px-5 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center transition">
                    Batal
                </a>
            </div>

        </form>
    @endif

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .no-spinner { -moz-appearance: textfield; appearance: textfield; }
</style>
</x-app-layout>
