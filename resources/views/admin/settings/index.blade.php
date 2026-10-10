<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Admin</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Pengaturan Sistem</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Kontrol identitas, transaksi, pembayaran, pengiriman, pajak, dan diskon</p>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
                {{ count($settings) }} pengaturan
            </span>
        </div>
    </div>

    {{-- ================= ALERT SUCCESS ================= --}}
    @if(session('success'))
        <div class="rounded-2xl border border-lime-200 bg-lime-50 dark:border-lime-500/20 dark:bg-lime-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-lime-600 dark:text-lime-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
            <p class="text-sm font-medium text-lime-800 dark:text-lime-300">{{ session('success') }}</p>
        </div>
    @endif

    {{-- ================= FORM ================= --}}
    <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5" x-data="{ activeTab: 'identity' }">
        @csrf
        @method('PATCH')

        {{-- ================= TAB NAVIGATION ================= --}}
        <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-2">
            <div class="flex flex-wrap gap-1.5">

                <button type="button" @click="activeTab = 'identity'"
                    :class="activeTab === 'identity'
                        ? 'bg-[#171719] dark:bg-white text-white dark:text-[#171719]'
                        : 'bg-transparent text-black/60 dark:text-white/60 hover:bg-gray-100 dark:hover:bg-white/10'"
                    class="h-10 px-4 rounded-xl text-xs font-medium flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/>
                    </svg>
                    Identitas Toko
                </button>

                <button type="button" @click="activeTab = 'transaction'"
                    :class="activeTab === 'transaction'
                        ? 'bg-[#171719] dark:bg-white text-white dark:text-[#171719]'
                        : 'bg-transparent text-black/60 dark:text-white/60 hover:bg-gray-100 dark:hover:bg-white/10'"
                    class="h-10 px-4 rounded-xl text-xs font-medium flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                    </svg>
                    Transaksi
                </button>

                <button type="button" @click="activeTab = 'payment'"
                    :class="activeTab === 'payment'
                        ? 'bg-[#171719] dark:bg-white text-white dark:text-[#171719]'
                        : 'bg-transparent text-black/60 dark:text-white/60 hover:bg-gray-100 dark:hover:bg-white/10'"
                    class="h-10 px-4 rounded-xl text-xs font-medium flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                    </svg>
                    Pembayaran
                </button>

                <button type="button" @click="activeTab = 'shipping'"
                    :class="activeTab === 'shipping'
                        ? 'bg-[#171719] dark:bg-white text-white dark:text-[#171719]'
                        : 'bg-transparent text-black/60 dark:text-white/60 hover:bg-gray-100 dark:hover:bg-white/10'"
                    class="h-10 px-4 rounded-xl text-xs font-medium flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                        <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                    </svg>
                    Pengiriman
                </button>

            </div>
        </div>

        {{-- ================= TAB CONTENT ================= --}}
        @foreach([
            'identity'    => ['title' => 'Identitas Toko',    'desc' => 'Nama toko, kontak, dan informasi dasar',       'icon' => 'store',   'keys' => ['store_name','store_phone','store_email']],
            'transaction' => ['title' => 'Pengaturan Transaksi','desc' => 'Mata uang, prefix order, pajak, dan diskon', 'icon' => 'receipt', 'keys' => ['currency','order_prefix','minimum_order','tax_percent','discount_percent']],
            'payment'     => ['title' => 'Pengaturan Pembayaran','desc' => 'Metode pembayaran dan rekening bank',      'icon' => 'card',    'keys' => ['payment_methods','bank_name','bank_account']],
            'shipping'    => ['title' => 'Pengaturan Pengiriman','desc' => 'Pengiriman dan biaya kirim',                 'icon' => 'truck',   'keys' => ['shipping_enabled','shipping_fee']],
        ] as $tabKey => $group)

            <section x-show="activeTab === '{{ $tabKey }}'" x-transition.opacity
                class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5">

                {{-- SECTION HEADER --}}
                <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center shrink-0">
                        @if($group['icon'] === 'store')
                            <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/>
                            </svg>
                        @elseif($group['icon'] === 'receipt')
                            <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/>
                                <path d="M16 13H8"/><path d="M16 17H8"/>
                            </svg>
                        @elseif($group['icon'] === 'card')
                            <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                            </svg>
                        @elseif($group['icon'] === 'truck')
                            <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
                                <circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>
                            </svg>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-sm font-semibold text-[#171719] dark:text-white">{{ $group['title'] }}</h2>
                        <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">{{ $group['desc'] }}</p>
                    </div>
                </div>

                {{-- SECTION BODY --}}
                <div class="p-5 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($group['keys'] as $key)
                        @php($setting = $settings[$key])

                        @if($setting['type'] === 'boolean')
                            {{-- TOGGLE BOOLEAN --}}
                            <label for="{{ $key }}"
                                class="rounded-xl border border-black/5 dark:border-white/10 p-4 flex items-center justify-between gap-3 cursor-pointer hover:border-lime-400 transition group">
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-[#171719] dark:text-white">{{ $setting['label'] }}</p>
                                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">{{ $setting['description'] }}</p>
                                </div>
                                <div class="relative shrink-0">
                                    <input id="{{ $key }}" name="{{ $key }}" type="checkbox" value="1"
                                        @checked(old($key, $setting['value']))
                                        class="peer sr-only">
                                    <div class="w-11 h-6 rounded-full bg-gray-200 dark:bg-white/10 peer-checked:bg-lime-400 transition-all"></div>
                                    <div class="absolute top-0.5 left-0.5 w-5 h-5 rounded-full bg-white shadow-sm transition-all peer-checked:translate-x-5"></div>
                                </div>
                            </label>
                        @else
                            {{-- INPUT REGULAR --}}
                            <div class="{{ in_array($setting['type'], ['textarea']) ? 'md:col-span-2 lg:col-span-3' : '' }}">
                                <label for="{{ $key }}" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                                    {{ $setting['label'] }}
                                </label>
                                <input id="{{ $key }}" name="{{ $key }}" type="{{ $setting['type'] }}"
                                    value="{{ old($key, $setting['value']) }}"
                                    class="no-spinner mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
                                <p class="mt-1.5 text-[10px] text-black/45 dark:text-white/45">{{ $setting['description'] }}</p>
                                @error($key)
                                    <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
        @endforeach

        {{-- ================= FIXED SAVE BAR ================= --}}
        <div class="sticky bottom-4 z-20 flex justify-end">
            <div class="rounded-2xl border border-black/5 bg-white/95 dark:border-white/10 dark:bg-gray-900/95 backdrop-blur-md shadow-lg px-4 py-3 flex items-center gap-3">

                <div class="hidden sm:flex items-center gap-2 pr-3 border-r border-black/5 dark:border-white/10">
                    <div class="w-2 h-2 rounded-full bg-lime-500 animate-pulse"></div>
                    <span class="text-[11px] text-black/45 dark:text-white/45">Belum disimpan</span>
                </div>

                <button type="reset"
                    class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-xs font-medium transition">
                    Reset
                </button>

                <button type="submit"
                    class="h-10 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-bold flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </div>

    </form>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .no-spinner { -moz-appearance: textfield; appearance: textfield; }
</style>
</x-app-layout>
