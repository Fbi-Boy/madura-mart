<x-app-layout>
<div class="space-y-4 font-poppins pb-24 lg:pb-5">

    {{-- ================= BREADCRUMB ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-4 py-3">
        <nav class="flex items-center gap-1.5 text-[10px] font-medium text-black/45 dark:text-white/45 overflow-hidden">
            <a href="{{ route('customer.catalog.index') }}" class="hover:text-lime-600 dark:hover:text-lime-400 transition shrink-0">Katalog</a>
            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="m9 18 6-6-6-6"/>
            </svg>
            <span class="text-black/60 dark:text-white/60 shrink-0">{{ $product->category?->name ?? 'Umum' }}</span>
            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="m9 18 6-6-6-6"/>
            </svg>
            <span class="text-[#171719] dark:text-white truncate">{{ $product->name }}</span>
        </nav>

        <a href="{{ route('customer.catalog.index') }}"
           class="h-8 px-3 rounded-lg bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-[11px] font-medium flex items-center gap-1.5 transition shrink-0">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- ================= MAIN LAYOUT ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        {{-- ================= KIRI: IMAGE ================= --}}
        <div class="lg:col-span-5">
            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden lg:sticky lg:top-4">

                {{-- MAIN IMAGE --}}
                <div class="relative aspect-square bg-gradient-to-br from-lime-100 via-lime-50 to-white dark:from-lime-900/30 dark:via-lime-900/10 dark:to-transparent flex items-center justify-center overflow-hidden">

                    {{-- DECORATIVE --}}
                    <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-lime-300/40 dark:bg-lime-500/10 blur-3xl"></div>
                    <div class="absolute -bottom-16 -left-16 w-48 h-48 rounded-full bg-lime-300/40 dark:bg-lime-500/10 blur-3xl"></div>

                    {{-- BIG LETTER --}}
                    <span class="relative text-[12rem] sm:text-[14rem] font-black text-lime-500 dark:text-lime-500 opacity-90 leading-none select-none">
                        {{ strtoupper(substr($product->name, 0, 1)) }}
                    </span>

                    {{-- BADGE: FLASH SALE / STOK HABIS --}}
                    @if($product->stock === 0)
                        <div class="absolute top-4 left-4 px-3 py-1.5 rounded-lg bg-red-500 text-white text-[11px] font-bold uppercase shadow-lg flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                            </svg>
                            Stok Habis
                        </div>
                    @elseif($product->stock <= 10)
                        <div class="absolute top-4 left-4 px-3 py-1.5 rounded-lg bg-gradient-to-r from-orange-500 to-red-500 text-white text-[11px] font-bold uppercase shadow-lg flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/>
                            </svg>
                            Stok Terbatas
                        </div>
                    @endif

                    {{-- WISHLIST --}}
                    <button type="button"
                        class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/90 dark:bg-black/50 backdrop-blur-sm flex items-center justify-center shadow-md hover:scale-110 transition">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>

                    {{-- KATEGORI BADGE (bottom) --}}
                    <div class="absolute bottom-4 left-4 px-3 py-1.5 rounded-lg bg-white/90 dark:bg-black/50 backdrop-blur-sm text-[11px] font-bold text-[#171719] dark:text-white shadow-sm">
                        {{ $product->category?->name ?? 'Umum' }}
                    </div>
                </div>

                {{-- THUMBNAIL STRIP (decorative) --}}
                <div class="p-3 border-t border-black/5 dark:border-white/10 flex gap-2 overflow-x-auto">
                    @for($i = 0; $i < 4; $i++)
                        <div class="shrink-0 w-16 h-16 rounded-lg bg-gradient-to-br from-lime-100 to-lime-50 dark:from-lime-900/20 dark:to-lime-900/5 flex items-center justify-center border-2 {{ $i === 0 ? 'border-lime-400' : 'border-transparent' }} cursor-pointer hover:border-lime-400 transition">
                            <span class="text-lg font-black text-lime-500">{{ strtoupper(substr($product->name, 0, 1)) }}</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- ================= KANAN: INFO ================= --}}
        <div class="lg:col-span-7 space-y-4">

            {{-- ===== CARD UTAMA PRODUK ===== --}}
            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">

                {{-- JUDUL --}}
                <div class="flex items-start gap-2">
                    <span class="shrink-0 px-2 py-0.5 rounded bg-lime-400 text-[10px] font-black uppercase text-[#171719] tracking-wider">
                        Official
                    </span>
                    <h1 class="text-base sm:text-lg font-semibold text-[#171719] dark:text-white leading-snug">
                        {{ $product->name }}
                    </h1>
                </div>

                {{-- RATING & TERJUAL (decorative) --}}
                <div class="mt-3 flex flex-wrap items-center gap-4 text-[11px]">
                    <div class="flex items-center gap-1">
                        <span class="font-bold text-[#171719] dark:text-white">4.9</span>
                        <div class="flex items-center gap-0.5">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-3 h-3 text-amber-400 fill-current" viewBox="0 0 24 24">
                                    <path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <div class="flex items-center gap-1 text-black/60 dark:text-white/60">
                        <span class="font-bold text-[#171719] dark:text-white">128</span>
                        <span>Terjual</span>
                    </div>
                    <div class="flex items-center gap-1 text-black/60 dark:text-white/60">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span>{{ $product->stock }} stok</span>
                    </div>
                </div>

                {{-- HARGA --}}
                <div class="mt-4 p-4 rounded-xl bg-gradient-to-r from-lime-50 to-lime-100/50 dark:from-lime-900/20 dark:to-lime-900/5">
                    <div class="flex items-end gap-3 flex-wrap">
                        <p class="text-3xl sm:text-4xl font-black text-lime-600 dark:text-lime-400 tracking-tight leading-none">
                            Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-black/40 dark:text-white/40 line-through mb-1">
                            Rp {{ number_format((float) $product->price * 1.3, 0, ',', '.') }}
                        </p>
                        <span class="px-2 py-0.5 rounded bg-red-500 text-white text-[10px] font-black uppercase mb-1">
                            -30%
                        </span>
                    </div>
                </div>

                {{-- SKU + KATEGORI --}}
                <div class="mt-4 grid grid-cols-2 gap-3 text-[11px]">
                    <div>
                        <p class="text-black/45 dark:text-white/45">SKU</p>
                        <p class="mt-1 font-mono font-semibold text-[#171719] dark:text-white">{{ $product->sku }}</p>
                    </div>
                    <div>
                        <p class="text-black/45 dark:text-white/45">Kategori</p>
                        <p class="mt-1 font-semibold text-[#171719] dark:text-white">{{ $product->category?->name ?? 'Umum' }}</p>
                    </div>
                </div>
            </div>

            {{-- ===== CARD PENGIRIMAN (decorative Shopee-style) ===== --}}
            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-black/45 dark:text-white/45">Pengiriman</h3>

                <div class="mt-3 space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="1" y="3" width="15" height="13"/>
                                <path d="M16 8h4l3 3v5h-7V8z"/>
                                <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-[#171719] dark:text-white">Dikirim ke alamat Anda</p>
                            <p class="text-[11px] text-black/45 dark:text-white/45 mt-0.5">Cek alamat di profil sebelum checkout</p>
                        </div>
                        <a href="{{ route('customer.address.edit') }}"
                           class="text-[11px] font-medium text-lime-600 dark:text-lime-400 hover:underline shrink-0">
                            Ubah
                        </a>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-semibold text-[#171719] dark:text-white">Garansi & Retur</p>
                            <p class="text-[11px] text-black/45 dark:text-white/45 mt-0.5">Bisa retur dalam 7 hari</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== DESKRIPSI ===== --}}
            @if ($product->description)
                <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
                    <div class="flex items-center gap-2 pb-3 border-b border-black/5 dark:border-white/10 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-[#171719] dark:text-white">Deskripsi Produk</h3>
                    </div>
                    <p class="text-xs leading-relaxed text-black/70 dark:text-white/70 whitespace-pre-line">
                        {{ $product->description }}
                    </p>
                </div>
            @endif

            {{-- ===== CARD STOK HABIS (jika habis) ===== --}}
            @if($product->stock === 0)
                <div class="rounded-2xl border border-red-200 bg-red-50 dark:border-red-500/20 dark:bg-red-500/10 p-5">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="m15 9-6 6"/><path d="m9 9 6 6"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-red-800 dark:text-red-300">Stok Habis</p>
                            <p class="mt-0.5 text-[11px] text-red-700 dark:text-red-300/80">
                                Produk ini sedang tidak tersedia. Cek produk lain di katalog.
                            </p>
                            <a href="{{ route('customer.catalog.index') }}"
                               class="inline-flex items-center gap-1.5 mt-3 h-9 px-4 rounded-xl bg-red-500 hover:bg-red-600 text-white text-xs font-bold transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                                </svg>
                                Kembali ke Katalog
                            </a>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- ================= STICKY BOTTOM BAR (Shopee-style) ================= --}}
    @if($product->stock > 0)
        <div class="fixed bottom-0 left-0 right-0 z-40 lg:static lg:z-auto">
            <div class="lg:rounded-2xl lg:border lg:border-black/5 lg:dark:border-white/10 lg:bg-white lg:dark:bg-white/5 bg-white dark:bg-gray-900 border-t border-black/10 dark:border-white/10 px-4 py-3 lg:px-5 lg:py-4 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] lg:shadow-none">

                <form method="POST" action="{{ route('customer.cart.add', $product) }}" class="flex items-center gap-3">
                    @csrf

                    {{-- QUANTITY SELECTOR --}}
                    <div class="shrink-0">
                        <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45 mb-1 hidden lg:block">Jumlah</p>
                        <div class="flex items-center h-11 rounded-xl border border-black/10 dark:border-white/10 overflow-hidden bg-white dark:bg-white/5">
                            <button type="button" onclick="this.nextElementSibling.stepDown()"
                                class="w-10 h-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-white/10 transition">
                                <svg class="w-3.5 h-3.5 text-[#171719] dark:text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M5 12h14"/>
                                </svg>
                            </button>
                            <input id="quantity" name="quantity" type="number" min="1" max="{{ $product->stock }}" value="1" required
                                class="no-spinner w-12 h-full text-center bg-transparent border-x border-black/5 dark:border-white/10 text-sm font-bold text-[#171719] dark:text-white focus:outline-none">
                            <button type="button" onclick="this.previousElementSibling.stepUp()"
                                class="w-10 h-full flex items-center justify-center hover:bg-gray-100 dark:hover:bg-white/10 transition">
                                <svg class="w-3.5 h-3.5 text-[#171719] dark:text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M12 5v14"/><path d="M5 12h14"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- SUBTOTAL DISPLAY --}}
                    <div class="hidden lg:block flex-1 min-w-0">
                        <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Subtotal</p>
                        <p class="text-lg font-black text-lime-600 dark:text-lime-400">Rp {{ number_format((float) $product->price, 0, ',', '.') }}</p>
                    </div>

                    {{-- BUTTONS --}}
                    <div class="flex-1 lg:flex-none flex items-center gap-2">
                        <button type="button"
                            class="hidden lg:flex h-11 px-5 rounded-xl border-2 border-lime-400 text-lime-600 dark:text-lime-400 hover:bg-lime-50 dark:hover:bg-lime-900/20 text-sm font-bold items-center justify-center gap-2 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                            </svg>
                            + Keranjang
                        </button>

                        <button type="submit" name="action" value="buy"
                            class="flex-1 lg:flex-none h-11 px-6 rounded-xl bg-gradient-to-r from-lime-400 to-lime-500 hover:from-lime-500 hover:to-lime-600 text-[#171719] text-sm font-black flex items-center justify-center gap-2 transition shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                            </svg>
                            Beli Sekarang
                        </button>
                    </div>
                </form>
            </div>
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
