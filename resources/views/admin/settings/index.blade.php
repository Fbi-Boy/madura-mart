<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Pengaturan Sistem</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola identitas dan nilai default operasional Madura Mart.</p>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-2xl border border-[#A8F23A]/50 bg-[#A8F23A]/10 px-4 py-3 text-sm text-gray-800 dark:text-gray-100">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Identitas Toko</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Informasi kontak yang menjadi referensi sistem.</p>
                    </div>

                    <div class="grid gap-5 md:grid-cols-3">
                        @foreach (['store_name', 'store_phone', 'store_email'] as $key)
                            @php($setting = $settings[$key])
                            <div>
                                <label for="{{ $key }}" class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $setting['label'] }}</label>
                                <input
                                    id="{{ $key }}"
                                    name="{{ $key }}"
                                    type="{{ $setting['type'] }}"
                                    value="{{ old($key, $setting['value']) }}"
                                    class="mt-2 block w-full rounded-xl border-gray-300 bg-white text-sm text-gray-900 focus:border-[#A8F23A] focus:ring-[#A8F23A] dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                >
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $setting['description'] }}</p>
                                @error($key)
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Default Transaksi</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Nilai dasar yang disiapkan untuk kebutuhan operasional.</p>
                    </div>

                    <div class="grid gap-5 md:grid-cols-3">
                        @foreach (['tax_percent', 'discount_percent', 'shipping_fee'] as $key)
                            @php($setting = $settings[$key])
                            <div>
                                <label for="{{ $key }}" class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $setting['label'] }}</label>
                                <input
                                    id="{{ $key }}"
                                    name="{{ $key }}"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    value="{{ old($key, $setting['value']) }}"
                                    class="mt-2 block w-full rounded-xl border-gray-300 bg-white text-sm text-gray-900 focus:border-[#A8F23A] focus:ring-[#A8F23A] dark:border-gray-600 dark:bg-gray-900 dark:text-white"
                                >
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $setting['description'] }}</p>
                                @error($key)
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </section>

                <div class="flex justify-end">
                    <button type="submit" class="rounded-xl bg-[#A8F23A] px-5 py-2.5 text-sm font-semibold text-gray-900 transition hover:opacity-80">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
