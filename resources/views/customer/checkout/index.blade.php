<x-app-layout>
<div class="space-y-4 font-poppins pb-24 lg:pb-5">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Belanja</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Checkout</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Konfirmasi alamat, pembayaran, dan produk sebelum pesanan dibuat</p>
        </div>

        <a href="{{ route('customer.cart.index') }}"
           class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    @if ($items->isEmpty())
        {{-- ================= EMPTY STATE ================= --}}
        <div class="rounded-2xl border border-dashed border-black/10 dark:border-white/10 bg-white dark:bg-white/5 p-16 text-center">
            <div class="w-20 h-20 rounded-2xl bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
            </div>
            <p class="text-base font-bold text-[#171719] dark:text-white">Tidak ada item untuk checkout</p>
            <p class="mt-1 text-xs text-black/45 dark:text-white/45">Keranjang Anda kosong atau sudah diproses</p>
            <a href="{{ route('customer.cart.index') }}"
               class="inline-flex items-center gap-1.5 mt-5 h-11 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                </svg>
                Kembali ke Keranjang
            </a>
        </div>
    @else

        <form id="checkout-form" method="POST" action="{{ route('customer.checkout.store') }}" class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            @csrf

            {{-- ============ KIRI: FORM (8/12) ============ --}}
            <div class="lg:col-span-8 space-y-4">

                {{-- ===== ALAMAT PENGIRIMAN ===== --}}
                <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

                    {{-- HEADER --}}
                    <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-[#171719] dark:text-white">Alamat Pengiriman</h2>
                                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pilih alamat untuk pesanan ini</p>
                            </div>
                        </div>

                        <a href="{{ route('customer.address.edit') }}"
                           class="text-[11px] font-medium text-lime-600 dark:text-lime-400 hover:underline shrink-0">
                            Kelola Alamat
                        </a>
                    </div>

                    {{-- ADDRESS LIST --}}
                    <div class="p-4 space-y-2">
                        @forelse ($addresses ?? [] as $address)
                            <label class="block cursor-pointer rounded-xl border-2 p-4 transition
                                {{ $address->is_default ? 'border-lime-400 bg-lime-50 dark:bg-lime-900/20' : 'border-black/5 dark:border-white/10 hover:border-lime-400' }}">
                                <div class="flex gap-3">
                                    <input type="radio" name="address_id" value="{{ $address->id }}"
                                           form="checkout-form"
                                           @checked($address->is_default)
                                           required
                                           class="mt-1 w-4 h-4 text-lime-500 focus:ring-lime-400 border-gray-300">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="text-xs font-bold text-[#171719] dark:text-white">{{ $address->label }}</p>
                                            @if($address->is_default)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-lime-400 text-[#171719] text-[9px] font-bold uppercase tracking-wider">
                                                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                                        <path d="M20 6 9 17l-5-5"/>
                                                    </svg>
                                                    Utama
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-2 space-y-1">
                                            <p class="text-xs text-black/70 dark:text-white/70">
                                                <span class="font-semibold">{{ $address->recipient_name }}</span> · {{ $address->phone }}
                                            </p>
                                            <p class="text-[11px] text-black/60 dark:text-white/60 leading-relaxed">
                                                {{ $address->address }}, {{ $address->city }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="rounded-xl border border-dashed border-black/10 dark:border-white/10 p-8 text-center">
                                <svg class="w-10 h-10 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <p class="text-xs font-semibold text-[#171719] dark:text-white">Belum ada alamat</p>
                                <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Tambahkan alamat dulu untuk melanjutkan</p>
                                <a href="{{ route('customer.address.edit') }}"
                                   class="inline-flex items-center gap-1.5 mt-3 h-9 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-bold transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M12 5v14"/><path d="M5 12h14"/>
                                    </svg>
                                    Tambah Alamat
                                </a>
                            </div>
                        @endforelse
                    </div>
                </section>

                {{-- ===== METODE PEMBAYARAN ===== --}}
                <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

                    <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-[#171719] dark:text-white">Metode Pembayaran</h2>
                                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pilih cara pembayaran pesanan</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 space-y-2">
                        @forelse ($paymentMethods ?? [] as $value => $label)
                            @php
                                $methodIcon = match($value) {
                                    'qris'          => 'qr',
                                    'bank_transfer' => 'bank',
                                    'transfer'      => 'bank',
                                    default         => 'card',
                                };
                            @endphp
                            <label class="block cursor-pointer rounded-xl border-2 p-4 transition
                                {{ old('payment_method') === $value ? 'border-lime-400 bg-lime-50 dark:bg-lime-900/20' : 'border-black/5 dark:border-white/10 hover:border-lime-400' }}">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" value="{{ $value }}"
                                           form="checkout-form"
                                           required
                                           @checked(old('payment_method') === $value)
                                           class="w-4 h-4 text-lime-500 focus:ring-lime-400 border-gray-300">

                                    <div class="w-9 h-9 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center shrink-0">
                                        @if($methodIcon === 'qr')
                                            <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <rect width="5" height="5" x="3" y="3" rx="1"/>
                                                <rect width="5" height="5" x="16" y="3" rx="1"/>
                                                <rect width="5" height="5" x="3" y="16" rx="1"/>
                                                <path d="M21 16h-3a2 2 0 0 0-2 2v3"/>
                                                <path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/>
                                            </svg>
                                        @elseif($methodIcon === 'bank')
                                            <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path d="M3 21h18"/><path d="M3 10h18"/><path d="M5 6l7-3 7 3"/>
                                                <path d="M4 10v11"/><path d="M20 10v11"/><path d="M8 14v3"/><path d="M12 14v3"/><path d="M16 14v3"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                                            </svg>
                                        @endif
                                    </div>

                                    <div class="flex-1">
                                        <p class="text-xs font-bold text-[#171719] dark:text-white">{{ $label }}</p>
                                        <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">
                                            @if($methodIcon === 'qr')
                                                Scan QR untuk bayar
                                            @elseif($methodIcon === 'bank')
                                                Transfer via bank
                                            @else
                                                Pembayaran langsung
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="rounded-xl border border-dashed border-black/10 dark:border-white/10 p-6 text-center">
                                <p class="text-xs text-black/45 dark:text-white/45">Belum ada metode pembayaran tersedia</p>
                            </div>
                        @endforelse

                        @error('payment_method')
                            <p class="mt-1 text-[10px] font-medium text-red-500">{{ $message }}</p>
                        @enderror
                        @error('address_id')
                            <p class="mt-1 text-[10px] font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </section>

                {{-- ===== ITEM PESANAN ===== --}}
                <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

                    <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                    <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-bold text-[#171719] dark:text-white">Item Pesanan</h2>
                                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">{{ count($items) }} produk akan dipesan</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg bg-lime-100 dark:bg-lime-900/30 text-[10px] font-bold text-lime-700 dark:text-lime-400">
                            {{ count($items) }} item
                        </span>
                    </div>

                    <div class="divide-y divide-black/5 dark:divide-white/10">
                        @foreach ($items as $item)
                            <div class="p-4 flex gap-3">
                                {{-- IMAGE --}}
                                <div class="shrink-0 w-14 h-14 rounded-xl bg-gradient-to-br from-lime-100 to-lime-50 dark:from-lime-900/30 dark:to-lime-900/10 flex items-center justify-center">
                                    <span class="text-xl font-black text-lime-500 opacity-90">
                                        {{ strtoupper(substr($item['product']->name, 0, 1)) }}
                                    </span>
                                </div>

                                {{-- INFO --}}
                                <div class="flex-1 min-w-0 flex flex-col justify-between">
                                    <div>
                                        <p class="text-xs font-semibold text-[#171719] dark:text-white truncate">
                                            {{ $item['product']->name }}
                                        </p>
                                        <p class="mt-1 text-[10px] text-black/45 dark:text-white/45 font-mono">
                                            {{ $item['product']->sku }}
                                        </p>
                                    </div>

                                    <div class="flex items-center justify-between gap-3 mt-2">
                                        <p class="text-[10px] text-black/60 dark:text-white/60">
                                            {{ $item['quantity'] }} {{ $item['product']->unit }} × Rp {{ number_format((float) $item['product']->price, 0, ',', '.') }}
                                        </p>
                                        <p class="text-xs font-bold text-[#171719] dark:text-white">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

            </div>

            {{-- ============ KANAN: RINGKASAN (4/12) ============ --}}
            <aside class="lg:col-span-4">
                <div class="lg:sticky lg:top-4 space-y-4">

                    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

                        {{-- HEADER --}}
                        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-bold text-[#171719] dark:text-white">Ringkasan Pesanan</h3>
                            </div>
                        </div>

                        {{-- BODY --}}
                        <div class="p-5 space-y-3">

                            <div class="flex justify-between text-xs">
                                <span class="text-black/60 dark:text-white/60">Subtotal ({{ count($items) }} produk)</span>
                                <span class="font-semibold text-[#171719] dark:text-white">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between text-xs">
                                <span class="text-black/60 dark:text-white/60">Ongkos Kirim</span>
                                <span class="font-semibold text-lime-600 dark:text-lime-400">
                                    Dihitung nanti
                                </span>
                            </div>

                            <div class="pt-3 border-t border-dashed border-black/10 dark:border-white/10">
                                <div class="flex items-end justify-between gap-3">
                                    <span class="text-xs font-semibold text-black/60 dark:text-white/60">Total</span>
                                    <span class="text-2xl font-black text-lime-600 dark:text-lime-400 leading-none">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            @if(($minimumOrder ?? 0) > 0)
                                <div class="rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-500/30 p-3 flex items-start gap-2">
                                    <svg class="w-3.5 h-3.5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                                    </svg>
                                    <p class="text-[10px] text-amber-700 dark:text-amber-200">
                                        Minimum belanja <span class="font-bold">Rp {{ number_format($minimumOrder, 0, ',', '.') }}</span>
                                    </p>
                                </div>
                            @endif

                            {{-- NOTES --}}
                            <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-3">
                                <p class="text-[10px] text-black/45 dark:text-white/45">
                                    <span class="font-semibold">Catatan:</span> Harga dan stok akan diverifikasi ulang saat pesanan disimpan.
                                </p>
                            </div>

                            {{-- DESKTOP BUTTON --}}
                            <button type="submit" form="checkout-form"
                                class="hidden lg:flex w-full h-12 rounded-xl bg-gradient-to-r from-lime-400 to-lime-500 hover:from-lime-500 hover:to-lime-600 text-[#171719] text-sm font-black items-center justify-center gap-2 transition shadow-md hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                                Buat Pesanan
                            </button>

                            <a href="{{ route('customer.cart.index') }}"
                               class="hidden lg:flex w-full h-10 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-xs font-medium items-center justify-center gap-2 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                                </svg>
                                Kembali ke Keranjang
                            </a>

                        </div>
                    </div>

                    {{-- TRUST --}}
                    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-4 space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-[#171719] dark:text-white">Pembayaran Aman</p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5">Transaksi dijamin aman</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <rect x="1" y="3" width="15" height="13"/>
                                    <path d="M16 8h4l3 3v5h-7V8z"/>
                                    <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-[#171719] dark:text-white">Pengiriman Cepat</p>
                                <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5">Dikirim hari yang sama</p>
                            </div>
                        </div>
                    </div>

                </div>
            </aside>

        </form>

        {{-- ================= STICKY BOTTOM (Mobile) ================= --}}
        <div class="fixed bottom-0 left-0 right-0 z-40 lg:hidden">
            <div class="bg-white dark:bg-gray-900 border-t border-black/10 dark:border-white/10 px-4 py-3 shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
                <div class="flex items-center gap-3">
                    <div class="shrink-0">
                        <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Total</p>
                        <p class="text-base font-black text-lime-600 dark:text-lime-400 leading-none">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </p>
                    </div>
                    <button type="submit" form="checkout-form"
                        class="flex-1 h-11 rounded-xl bg-gradient-to-r from-lime-400 to-lime-500 text-[#171719] text-sm font-black flex items-center justify-center gap-2 transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                        Buat Pesanan
                    </button>
                </div>
            </div>
        </div>

    @endif

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
