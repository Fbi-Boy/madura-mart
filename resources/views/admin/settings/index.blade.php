<x-app-layout>
    <x-slot name="header"><div><h2 class="text-xl font-semibold text-gray-900 dark:text-white">Pengaturan Sistem</h2><p class="text-sm text-gray-500 dark:text-gray-400">Kontrol identitas, transaksi, pembayaran, pengiriman, pajak, dan diskon.</p></div></x-slot>
    <div class="py-6"><div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))<div class="rounded-2xl border border-[#A8F23A]/50 bg-[#A8F23A]/10 px-4 py-3 text-sm">{{ session('success') }}</div>@endif
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">@csrf @method('PATCH')
            @foreach(['Identitas Toko'=>['store_name','store_phone','store_email'],'Transaksi'=>['currency','order_prefix','minimum_order','tax_percent','discount_percent'],'Pembayaran'=>['payment_methods','bank_name','bank_account'],'Pengiriman'=>['shipping_enabled','shipping_fee']] as $group=>$keys)
                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $group }}</h3>
                    <div class="mt-5 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($keys as $key)
                            @php($setting=$settings[$key])
                            <div class="{{ $setting['type']==='boolean' ? 'flex items-center gap-3 rounded-xl border border-gray-200 p-4 dark:border-gray-700' : '' }}">
                                @if($setting['type']==='boolean')
                                    <input id="{{ $key }}" name="{{ $key }}" type="checkbox" value="1" @checked(old($key,$setting['value'])) class="rounded border-gray-300 text-[#A8F23A] focus:ring-[#A8F23A]">
                                    <div><label for="{{ $key }}" class="text-sm font-medium text-gray-900 dark:text-white">{{ $setting['label'] }}</label><p class="mt-1 text-xs text-gray-500">{{ $setting['description'] }}</p></div>
                                @else
                                    <label for="{{ $key }}" class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $setting['label'] }}</label>
                                    <input id="{{ $key }}" name="{{ $key }}" type="{{ $setting['type'] }}" value="{{ old($key,$setting['value']) }}" class="mt-2 block w-full rounded-xl border-gray-300 bg-white text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $setting['description'] }}</p>
                                @endif
                                @error($key)<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                        @endforeach
                    </div>
                </section>
            @endforeach
            <div class="flex justify-end"><button class="rounded-xl bg-[#A8F23A] px-5 py-2.5 text-sm font-semibold text-gray-900">Simpan Pengaturan</button></div>
        </form>
    </div></div>
</x-app-layout>