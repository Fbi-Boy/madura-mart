<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Keranjang Belanja</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Atur produk sebelum masuk ke tahap checkout.</p>
            </div>
            <a href="{{ route('customer.catalog.index') }}" class="inline-flex w-fit rounded-xl bg-gray-900 px-4 py-2 text-xs font-semibold text-white dark:bg-[#A8F23A] dark:text-gray-900">Lanjut Belanja</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-5 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-xl border border-[#A8F23A]/50 bg-[#A8F23A]/10 px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ session('status') }}</div>
            @endif

            @if ($items->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center dark:border-gray-700 dark:bg-gray-800">
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">Keranjang masih kosong</p>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih produk yang tersedia dari katalog.</p>
                    <a href="{{ route('customer.catalog.index') }}" class="mt-5 inline-flex rounded-xl bg-[#A8F23A] px-4 py-2.5 text-sm font-semibold text-gray-900">Buka Katalog</a>
                </div>
            @else
                <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
                    <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $items->count() }} produk di keranjang</h3>
                        </div>
                        <div class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($items as $item)
                                <div class="p-5">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $item['product']->name }}</p>
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">SKU {{ $item['product']->sku }} · Stok {{ $item['product']->stock }} {{ $item['product']->unit }}</p>
                                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Rp {{ number_format((float) $item['product']->price, 0, ',', '.') }} / {{ $item['product']->unit }}</p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <form method="POST" action="{{ route('customer.cart.update') }}" class="flex items-center gap-2">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                                <input name="quantity" type="number" min="1" max="{{ $item['product']->stock }}" value="{{ $item['quantity'] }}" class="w-20 rounded-lg border-gray-300 text-center text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                                                <button class="rounded-lg bg-gray-900 px-3 py-2 text-xs font-semibold text-white dark:bg-gray-700">Update</button>
                                            </form>
                                            <form method="POST" action="{{ route('customer.cart.remove') }}">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="product_id" value="{{ $item['product']->id }}">
                                                <button class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600 dark:border-red-900/50 dark:text-red-300">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                    <p class="mt-3 text-right font-semibold text-gray-900 dark:text-white">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <aside class="h-fit rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ringkasan</p>
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-300">Total sementara</span>
                            <span class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <form method="GET" action="{{ route('customer.checkout.index') }}" class="mt-5">
                            <button class="w-full rounded-xl bg-[#A8F23A] px-4 py-3 text-sm font-semibold text-gray-900 transition hover:brightness-95">Lanjut ke Checkout</button>
                        </form>
                    </aside>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
