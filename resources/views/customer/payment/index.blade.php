<x-app-layout>
<div class="space-y-4 font-poppins max-w-3xl mx-auto">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Customer</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Pembayaran Pesanan</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45 font-mono">{{ $order->order_number }}</p>
        </div>

        <a href="{{ route('customer.orders.show', $order) }}"
           class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- ================= HERO PEMBAYARAN ================= --}}
    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-gradient-to-br from-[#171719] to-[#2A2A2E] dark:from-[#0B1120] dark:to-[#1E293B] p-6 relative overflow-hidden">

        {{-- GLOW ACCENT --}}
        <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-lime-400/20 blur-3xl pointer-events-none"></div>

        <div class="relative">
            <div class="flex flex-wrap items-start justify-between gap-4">

                <div>
                    <p class="text-[10px] uppercase tracking-wider text-white/50">Total Tagihan</p>
                    <p class="mt-2 text-3xl sm:text-4xl font-black text-lime-400 tracking-tight leading-none">
                        Rp {{ number_format((float) $order->total, 0, ',', '.') }}
                    </p>
                    <p class="mt-2 text-xs text-white/60">Bayar sebelum tenggat agar pesanan tidak dibatalkan</p>
                </div>

                {{-- STATUS BADGE --}}
                @php
                    $statusLabel = match($order->payment_status) {
                        'rejected' => 'Bukti Ditolak',
                        'paid'     => 'Sudah Dibayar',
                        default    => 'Menunggu Pembayaran',
                    };
                    $statusColor = match($order->payment_status) {
                        'rejected' => 'bg-red-500 text-white',
                        'paid'     => 'bg-lime-400 text-[#171719]',
                        default    => 'bg-amber-500 text-white',
                    };
                @endphp

                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                    @if($order->payment_status === 'paid')
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                    @elseif($order->payment_status === 'rejected')
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path d="M18 6 6 18"/><path d="m6 6 12 12"/>
                        </svg>
                    @else
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                        </svg>
                    @endif
                    {{ $statusLabel }}
                </span>

            </div>
        </div>
    </div>

    {{-- ================= ALERT REJECTED ================= --}}
    @if ($order->payment_status === 'rejected' && $order->payment_rejection_reason)
        <div class="rounded-2xl border border-red-200 bg-red-50 dark:border-red-500/20 dark:bg-red-500/10 p-5">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-bold text-red-800 dark:text-red-300">Bukti Pembayaran Ditolak</p>
                    <p class="mt-1 text-[11px] text-red-700 dark:text-red-200/80 leading-relaxed">
                        {{ $order->payment_rejection_reason }}
                    </p>
                    <p class="mt-2 text-[10px] font-medium text-red-700 dark:text-red-300 uppercase tracking-wider">
                        Silakan kirim ulang bukti pembayaran yang benar
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- ================= ALERT ERROR ================= --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 dark:border-red-500/20 dark:bg-red-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
            </svg>
            <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ $errors->first() }}</p>
        </div>
    @endif

    {{-- ================= METODE PEMBAYARAN ================= --}}
    <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center shrink-0">
                @if($order->payment_method === 'qris')
                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="5" height="5" x="3" y="3" rx="1"/>
                        <rect width="5" height="5" x="16" y="3" rx="1"/>
                        <rect width="5" height="5" x="3" y="16" rx="1"/>
                        <path d="M21 16h-3a2 2 0 0 0-2 2v3"/>
                        <path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/>
                    </svg>
                @else
                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 21h18"/><path d="M3 10h18"/><path d="M5 6l7-3 7 3"/>
                        <path d="M4 10v11"/><path d="M20 10v11"/><path d="M8 14v3"/><path d="M12 14v3"/><path d="M16 14v3"/>
                    </svg>
                @endif
            </div>
            <div>
                <h2 class="text-sm font-bold text-[#171719] dark:text-white">Metode Pembayaran</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Selesaikan pembayaran sesuai metode yang dipilih</p>
            </div>
        </div>

        <div class="p-5 space-y-4">

            {{-- METODE DISPLAY --}}
            <div class="rounded-xl bg-lime-50 dark:bg-lime-900/20 border border-lime-200 dark:border-lime-500/20 p-4">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-lime-400 flex items-center justify-center shrink-0">
                            @if($order->payment_method === 'qris')
                                <svg class="w-5 h-5 text-[#171719]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <rect width="5" height="5" x="3" y="3" rx="1"/>
                                    <rect width="5" height="5" x="16" y="3" rx="1"/>
                                    <rect width="5" height="5" x="3" y="16" rx="1"/>
                                    <path d="M21 16h-3a2 2 0 0 0-2 2v3"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-[#171719]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M3 21h18"/><path d="M3 10h18"/><path d="M5 6l7-3 7 3"/>
                                    <path d="M4 10v11"/><path d="M20 10v11"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-wider text-lime-700 dark:text-lime-400">Metode Dipilih</p>
                            <p class="mt-0.5 text-sm font-bold text-[#171719] dark:text-white">
                                {{ $order->payment_method === 'qris' ? 'QRIS' : 'Transfer Bank' }}
                            </p>
                        </div>
                    </div>

                    <svg class="w-5 h-5 text-lime-600 dark:text-lime-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>
            </div>

            {{-- INFO INSTRUKSI --}}
            @if($order->payment_method === 'qris')
                <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-black/45 dark:text-white/45 mb-2">Cara Bayar QRIS</p>
                    <ol class="space-y-1.5 text-[11px] text-black/70 dark:text-white/70 leading-relaxed pl-4 list-decimal">
                        <li>Buka aplikasi e-wallet atau mobile banking Anda</li>
                        <li>Pilih menu Scan QR / Bayar</li>
                        <li>Arahkan kamera ke QR code yang tersedia</li>
                        <li>Konfirmasi nominal <span class="font-bold text-lime-600 dark:text-lime-400">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</span></li>
                        <li>Simpan bukti pembayaran untuk diunggah</li>
                    </ol>
                </div>
            @else
                <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-black/45 dark:text-white/45 mb-2">Cara Transfer Bank</p>
                    <ol class="space-y-1.5 text-[11px] text-black/70 dark:text-white/70 leading-relaxed pl-4 list-decimal">
                        <li>Transfer sesuai total tagihan <span class="font-bold text-lime-600 dark:text-lime-400">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</span></li>
                        <li>Pastikan nama penerima sesuai</li>
                        <li>Simpan bukti transfer Anda</li>
                        <li>Unggah bukti di form bawah</li>
                    </ol>
                </div>
            @endif

        </div>
    </section>

    {{-- ================= FORM UPLOAD BUKTI ================= --}}
    <form method="POST" action="{{ route('customer.payment.store', $order) }}"
          enctype="multipart/form-data"
          class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden"
          x-data="{ filename: '' }">
        @csrf

        {{-- HEADER --}}
        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-[#171719] dark:text-white">Unggah Bukti Pembayaran</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Format JPG, PNG, atau PDF · Maks 2 MB</p>
            </div>
        </div>

        {{-- BODY --}}
        <div class="p-5 space-y-4">

            {{-- DROP ZONE --}}
            <label for="payment_proof"
                   class="block cursor-pointer rounded-xl border-2 border-dashed border-black/10 dark:border-white/10 hover:border-lime-400 bg-gray-50 dark:bg-white/5 transition p-6 text-center">

                <input id="payment_proof" name="payment_proof" type="file"
                       accept=".jpg,.jpeg,.png,.pdf" required
                       class="hidden"
                       @change="filename = $event.target.files[0]?.name || ''">

                {{-- EMPTY STATE --}}
                <div x-show="!filename">
                    <div class="w-14 h-14 rounded-2xl bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-[#171719] dark:text-white">Klik untuk pilih file</p>
                    <p class="mt-1 text-[10px] text-black/45 dark:text-white/45">atau seret file ke area ini</p>
                </div>

                {{-- FILE SELECTED --}}
                <div x-show="filename" x-cloak class="flex items-center gap-3 text-left">
                    <div class="w-12 h-12 rounded-xl bg-lime-400 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6 text-[#171719]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-bold text-[#171719] dark:text-white truncate" x-text="filename"></p>
                        <p class="mt-0.5 text-[10px] text-lime-600 dark:text-lime-400">File siap diunggah</p>
                    </div>
                    <svg class="w-5 h-5 text-lime-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                </div>
            </label>

            @error('payment_proof')
                <p class="text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror

            {{-- INFO --}}
            <div class="rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-500/30 p-3 flex items-start gap-2">
                <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                </svg>
                <p class="text-[10px] text-amber-700 dark:text-amber-200 leading-relaxed">
                    Pastikan bukti pembayaran jelas terbaca: nominal, tanggal, dan nama penerima.
                </p>
            </div>

        </div>

        {{-- FOOTER --}}
        <div class="px-5 py-4 border-t border-black/5 dark:border-white/10 flex flex-wrap items-center justify-end gap-2">
            <a href="{{ route('customer.orders.show', $order) }}"
               class="h-11 px-5 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
                Batal
            </a>

            <button type="submit"
                class="h-11 px-6 rounded-xl bg-gradient-to-r from-lime-400 to-lime-500 hover:from-lime-500 hover:to-lime-600 text-[#171719] text-sm font-black flex items-center gap-2 transition shadow-md hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
                Kirim Bukti Pembayaran
            </button>
        </div>

    </form>

    {{-- ================= LINK ================= --}}
    <div class="text-center pb-4">
        <a href="{{ route('customer.orders.show', $order) }}"
           class="inline-flex items-center gap-1.5 text-[11px] font-medium text-black/50 dark:text-white/50 hover:text-lime-600 dark:hover:text-lime-400 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
            </svg>
            Kembali ke Detail Pesanan
        </a>
    </div>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    [x-cloak] { display: none !important; }
</style>
</x-app-layout>
