<x-app-layout>
<div class="space-y-4 font-poppins pb-24 lg:pb-5">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Belanja</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Keranjang Belanja</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                @if($items->isEmpty())
                    Keranjang Anda masih kosong
                @else
                    {{ $items->count() }} produk siap untuk checkout
                @endif
            </p>
        </div>

        <a href="{{ route('customer.catalog.index') }}"
           class="h-10 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M12 5v14"/><path d="M5 12h14"/>
            </svg>
            Lanjut Belanja
        </a>
    </div>

    {{-- ================= ALERT STATUS ================= --}}
    @if (session('status'))
        <div class="rounded-2xl border border-lime-200 bg-lime-50 dark:border-lime-500/20 dark:bg-lime-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-lime-600 dark:text-lime-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
            <p class="text-sm font-medium text-lime-800 dark:text-lime-300">{{ session('status') }}</p>
        </div>
    @endif

    {{-- ================= EMPTY STATE ================= --}}
    @if ($items->isEmpty())
        <div class="rounded-2xl border border-dashed border-black/10 dark:border-white/10 bg-white dark:bg-white/5 p-16 text-center">
            <div class="w-20 h-20 rounded-2xl bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                </svg>
            </div>
            <p class="text-base font-bold text-[#171719] dark:text-white">Keranjang masih kosong</p>
            <p class="mt-1 text-xs text-black/45 dark:text-white/45">Yuk pilih produk favorit Anda dari katalog</p>
            <a href="{{ route('customer.catalog.index') }}"
               class="inline-flex items-center gap-1.5 mt-5 h-11 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/>
                    <path d="M21 3v5h-5"/>
                </svg>
                Buka Katalog
            </a>
        </div>
    @else

        {{-- ================= MAIN LAYOUT ================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

            {{-- KIRI: DAFTAR ITEM --}}
            <section class="lg:col-span-8 space-y-4">

                <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 px-4 py-3 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-5 h-5 rounded-md border-2 border-lime-400 bg-lime-400 flex items-center justify-center shrink-0">
                            <svg class="w-3 h-3 text-[#171719]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        </div>
                        <p class="text-xs font-medium text-[#171719] dark:text-white">
                            Semua <span class="font-bold">{{ $items->count() }}</span> produk dipilih
                        </p>
                    </div>
                    <span class="text-[10px] text-black/45 dark:text-white/45">Dari Madura Mart</span>
                </div>

                <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

                    @foreach ($items as $item)
                        @php
                            $productSlug = $item['product']->slug ?? null;
                            $productUrl = !empty($productSlug)
                                ? route('customer.catalog.show', $productSlug)
                                : route('customer.catalog.index');
                        @endphp

                        <div class="p-4 border-b border-black/5 dark:border-white/10 last:border-b-0 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition">
                            <div class="flex gap-4">

                                <div class="pt-1 shrink-0">
                                    <div class="w-5 h-5 rounded-md border-2 border-lime-400 bg-lime-400 flex items-center justify-center">
                                        <svg class="w-3 h-3 text-[#171719]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                            <path d="M20 6 9 17l-5-5"/>
                                        </svg>
                                    </div>
                                </div>

                                <a href="{{ $productUrl }}"
                                   class="shrink-0 w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-gradient-to-br from-lime-100 to-lime-50 dark:from-lime-900/30 dark:to-lime-900/10 flex items-center justify-center relative overflow-hidden">
                                    <span class="text-3xl font-black text-lime-500 dark:text-lime-500 opacity-90">
                                        {{ strtoupper(substr($item['product']->name, 0, 1)) }}
                                    </span>

                                    @if($item['product']->stock <= 10 && $item['product']->stock > 0)
                                        <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-amber-500 text-white text-[8px] font-bold uppercase">
                                            Terbatas
                                        </span>
                                    @endif
                                </a>

                                <div class="flex-1 min-w-0 flex flex-col justify-between gap-3">

                                    <div>
                                        <a href="{{ $productUrl }}"
                                           class="text-xs sm:text-sm font-semibold text-[#171719] dark:text-white leading-snug line-clamp-2 hover:text-lime-600 dark:hover:text-lime-400 transition">
                                            {{ $item['product']->name }}
                                        </a>
                                        <p class="mt-1 text-[10px] text-black/45 dark:text-white/45 font-mono">
                                            SKU: {{ $item['product']->sku }}
                                        </p>
                                        <div class="mt-1.5 flex items-center gap-2">
                                            <span class="px-1.5 py-0.5 rounded bg-lime-100 dark:bg-lime-900/30 text-[9px] font-bold uppercase text-lime-700 dark:text-lime-400">
                                                Stok: {{ $item['product']->stock }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap items-end justify-between gap-3">

                                        <div>
                                            <p class="text-base sm:text-lg font-black text-lime-600 dark:text-lime-400 leading-none">
                                                Rp {{ number_format((float) $item['product']->price, 0, ',', '.') }}
                                            </p>
                                            <p class="text-[10px] text-black/45 dark:text-white/45 mt-1">
                                                per {{ $item['product']->unit }}
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-2">

                                            <form method="POST" action="{{ route('customer.cart.remove') }}">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                                <button type="submit"
                                                    onclick="return confirm('Hapus produk ini dari keranjang?')"
                                                    class="w-9 h-9 rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 flex items-center justify-center transition"
                                                    title="Hapus">
                                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                    </svg>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('customer.cart.update') }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="product_id" value="{{ $item['product']->id }}">

                                                <div class="flex items-center h-9 rounded-lg border border-black/10 dark:border-white/10 overflow-hidden bg-white dark:bg-white/5">
                                                    <button type="button"
                                                        onclick="this.nextElementSibling.stepDown(); this.closest('form').querySelector('.btn-update').click();"
                                                        class="w-8 h-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-white/10 transition">
                                                        <svg class="w-3 h-3 text-[#171719] dark:text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                            <path d="M5 12h14"/>
                                                        </svg>
                                                    </button>
                                                    <input name="quantity" type="number" min="1" max="{{ $item['product']->stock }}"
                                                        value="{{ $item['quantity'] }}"
                                                        class="no-spinner w-10 h-full text-center bg-transparent border-x border-black/5 dark:border-white/10 text-xs font-bold text-[#171719] dark:text-white focus:outline-none">
                                                    <button type="button"
                                                        onclick="this.previousElementSibling.stepUp(); this.closest('form').querySelector('.btn-update').click();"
                                                        class="w-8 h-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-white/10 transition">
                                                        <svg class="w-3 h-3 text-[#171719] dark:text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                            <path d="M12 5v14"/><path d="M5 12h14"/>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <button type="submit" class="btn-update hidden">Update</button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-2 border-t border-dashed border-black/5 dark:border-white/10">
                                        <span class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Subtotal</span>
                                        <span class="text-sm font-bold text-[#171719] dark:text-white">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('customer.catalog.index') }}"
                   class="lg:hidden flex items-center justify-center gap-2 h-11 rounded-2xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                    </svg>
                    Lanjut Belanja
                </a>

            </section>

            {{-- KANAN: RINGKASAN --}}
            <aside class="lg:col-span-4">
                <div class="lg:sticky lg:top-4 space-y-4">

                    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

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

                        <div class="p-5 space-y-3">

                            <div class="flex justify-between text-xs">
                                <span class="text-black/60 dark:text-white/60">
                                    Subtotal ({{ $items->count() }} produk)
                                </span>
                                <span class="font-semibold text-[#171719] dark:text-white">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between text-xs">
                                <span class="text-black/60 dark:text-white/60">Ongkos Kirim</span>
                                <span class="font-semibold text-lime-600 dark:text-lime-400">
                                    Dihitung saat checkout
                                </span>
                            </div>

                            <div class="pt-3 border-t border-dashed border-black/10 dark:border-white/10">
                                <div class="flex items-end justify-between gap-3">
                                    <span class="text-xs font-semibold text-black/60 dark:text-white/60">Total Sementara</span>
                                    <span class="text-2xl font-black text-lime-600 dark:text-lime-400 leading-none">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <form method="GET" action="{{ route('customer.checkout.index') }}" class="pt-2 hidden lg:block">
                                <button type="submit"
                                    class="w-full h-12 rounded-xl bg-gradient-to-r from-lime-400 to-lime-500 hover:from-lime-500 hover:to-lime-600 text-[#171719] text-sm font-black flex items-center justify-center gap-2 transition shadow-md hover:shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                                    </svg>
                                    Lanjut ke Checkout
                                </button>
                            </form>

                        </div>
                    </div>

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

        </div>

        {{-- STICKY CHECKOUT MOBILE --}}
        <div class="fixed bottom-0 left-0 right-0 z-40 lg:hidden">
            <div class="bg-white dark:bg-gray-900 border-t border-black/10 dark:border-white/10 px-4 py-3 shadow-[0_-4px_20px_rgba(0,0,0,0.08)]">
                <div class="flex items-center gap-3">
                    <div class="shrink-0">
                        <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Total</p>
                        <p class="text-base font-black text-lime-600 dark:text-lime-400 leading-none">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </p>
                    </div>
                    <form method="GET" action="{{ route('customer.checkout.index') }}" class="flex-1">
                        <button type="submit"
                            class="w-full h-11 rounded-xl bg-gradient-to-r from-lime-400 to-lime-500 text-[#171719] text-sm font-black flex items-center justify-center gap-2 transition shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                            </svg>
                            Checkout ({{ $items->count() }})
                        </button>
                    </form>
                </div>
            </div>
        </div>

    @endif

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .no-spinner { -moz-appearance: textfield; appearance: textfield; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
</x-app-layout>
