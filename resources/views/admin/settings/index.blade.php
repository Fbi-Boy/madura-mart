<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-black/40 dark:text-white/40">System</p>
            <h2 class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">Pengaturan Sistem</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Atur identitas toko dan parameter operasional Madura Mart.</p>
        </div>
    </x-slot>

    <div class="py-5">
        <div class="mx-auto max-w-6xl space-y-5 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-2xl border border-[#A8F23A]/50 bg-[#A8F23A]/10 px-4 py-3 text-sm text-gray-800 dark:text-gray-100">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-200">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-4">
                @csrf
                @method('PATCH')

                @foreach(['Identitas Toko'=>['store_name','store_phone','store_email'],'Transaksi'=>['currency','order_prefix','minimum_order','tax_percent','discount_percent'],'Pembayaran'=>['payment_methods','bank_name','bank_account'],'Pengiriman'=>['shipping_enabled','shipping_fee']] as $group=>$keys)
                    <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-black/5 dark:bg-[#171719] dark:ring-white/10">
                        <div class="mb-4">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $group }}</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ match($group) {
                                    'Identitas Toko' => 'Informasi dasar yang ditampilkan sebagai identitas toko.',
                                    'Transaksi' => 'Parameter yang digunakan dalam proses transaksi.',
                                    'Pembayaran' => 'Informasi metode dan rekening pembayaran.',
                                    default => 'Pengaturan yang berkaitan dengan proses pengiriman.',
                                } }}
                            </p>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($keys as $key)
                                @php($setting = $settings[$key])

                                @if($setting['type'] === 'boolean')
                                    <label for="{{ $key }}" class="flex min-h-[76px] cursor-pointer items-center gap-3 rounded-xl bg-gray-50 px-4 py-3 dark:bg-white/[0.04]">
                                        <input id="{{ $key }}" name="{{ $key }}" type="checkbox" value="1"
                                               @checked(old($key, $setting['value']))
                                               class="rounded border-gray-300 text-[#A8F23A] focus:ring-[#A8F23A]">
                                        <span>
                                            <span class="block text-sm font-medium text-gray-900 dark:text-white">{{ $setting['label'] }}</span>
                                            <span class="mt-1 block text-xs text-gray-500 dark:text-gray-400">{{ $setting['description'] }}</span>
                                        </span>
                                    </label>
                                @else
                                    <div>
                                        <label for="{{ $key }}" class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $setting['label'] }}</label>
                                        <input id="{{ $key }}" name="{{ $key }}" type="{{ $setting['type'] }}"
                                               value="{{ old($key, $setting['value']) }}"
                                               class="mt-2 block h-10 w-full rounded-xl border-gray-200 bg-gray-50 text-sm text-gray-900 focus:border-[#A8F23A] focus:ring-[#A8F23A] dark:border-white/10 dark:bg-white/[0.04] dark:text-white">
                                        <p class="mt-1.5 text-[11px] leading-4 text-gray-500 dark:text-gray-400">{{ $setting['description'] }}</p>
                                    </div>
                                @endif

                                @error($key)
                                    <p class="text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            @endforeach
                        </div>
                    </section>
                @endforeach

                <div class="flex items-center justify-end gap-3 pt-1">
                    <a href="{{ route('dashboard') }}" class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-white/5">Batal</a>
                    <button type="submit" class="rounded-xl bg-[#A8F23A] px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:brightness-95">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
