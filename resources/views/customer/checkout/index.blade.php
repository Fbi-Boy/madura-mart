<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Checkout</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Konfirmasi alamat, pembayaran, dan produk sebelum pesanan dibuat.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if ($items->isEmpty())
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-10 text-center dark:border-gray-700 dark:bg-gray-800">
                    <p class="font-semibold text-gray-900 dark:text-white">Tidak ada item untuk checkout.</p>
                    <a href="{{ route('customer.cart.index') }}" class="mt-4 inline-flex rounded-xl bg-[#A8F23A] px-4 py-2.5 text-sm font-semibold text-gray-900">Kembali ke Keranjang</a>
                </div>
            @else
                <div class="grid gap-6 lg:grid-cols-[1fr_320px]">
                    <section class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">Alamat Pengiriman</h3>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pilih alamat milik kamu untuk pesanan ini.</p>
                            </div>
                            <a href="{{ route('customer.address.edit') }}" class="text-xs font-semibold text-gray-700 hover:underline dark:text-gray-200">Kelola alamat</a>
                        </div>

                        <div class="mt-4 space-y-3">
                            @forelse ($addresses ?? [] as $address)
                                <label class="block cursor-pointer rounded-xl border p-4 transition {{ $address->is_default ? 'border-lime-400 bg-lime-50/50 dark:bg-lime-950/10' : 'border-gray-200 dark:border-gray-700' }}">
                                    <div class="flex gap-3">
                                        <input type="radio" name="address_id" value="{{ $address->id }}" form="checkout-form" required @checked($address->is_default) class="mt-1">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-medium text-gray-900 dark:text-white">{{ $address->label }}</p>
                                                @if($address->is_default)<span class="rounded-full bg-lime-100 px-2 py-0.5 text-[11px] font-semibold text-lime-800">Utama</span>@endif
                                            </div>
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $address->recipient_name }} · {{ $address->phone }}</p>
                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $address->address }}, {{ $address->city }}</p>
                                        </div>
                                    </div>
                                </label>
                            @empty
                                <div class="rounded-xl border border-dashed border-gray-300 p-5 text-center dark:border-gray-700">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada alamat pengiriman.</p>
                                    <a href="{{ route('customer.address.edit') }}" class="mt-2 inline-block text-sm font-semibold underline">Tambah alamat</a>
                                </div>
                            @endforelse
                        </div>

                        <h3 class="mt-6 font-semibold text-gray-900 dark:text-white">Item Pesanan</h3>
                        <div class="mt-4 space-y-3">
                            @foreach ($items as $item)
                                <div class="flex items-center justify-between gap-4 rounded-xl border border-gray-100 p-4 dark:border-gray-700">
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $item['product']->name }}</p>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $item['quantity'] }} {{ $item['product']->unit }} × Rp {{ number_format((float) $item['product']->price, 0, ',', '.') }}</p>
                                    </div>
                                    <p class="shrink-0 font-semibold text-gray-900 dark:text-white">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <aside class="h-fit rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Pesanan</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($total, 0, ',', '.') }}</p>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Harga dan stok akan diverifikasi ulang saat pesanan disimpan. @if(($minimumOrder ?? 0) > 0) Minimum belanja: Rp {{ number_format($minimumOrder, 0, ',', '.') }}. @endif</p>
                        <form id="checkout-form" method="POST" action="{{ route('customer.checkout.store') }}" class="mt-5 space-y-4">
                            @csrf
                            <div>
                                <label for="payment_method" class="text-sm font-medium text-gray-700 dark:text-gray-200">Metode Pembayaran</label>
                                <select id="payment_method" name="payment_method" required class="mt-2 w-full rounded-xl border-gray-300 bg-white text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                    <option value="">Pilih metode</option>
                                    @foreach ($paymentMethods ?? [] as $value => $label)
                                        <option value="{{ $value }}" @selected(old('payment_method') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('payment_method')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                                @error('address_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                            </div>
                            <button class="w-full rounded-xl bg-[#A8F23A] px-4 py-3 text-sm font-semibold text-gray-900 transition hover:brightness-95">Buat Pesanan</button>
                        </form>
                        <a href="{{ route('customer.cart.index') }}" class="mt-3 block text-center text-xs font-semibold text-gray-600 dark:text-gray-300">Kembali ke keranjang</a>
                    </aside>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
