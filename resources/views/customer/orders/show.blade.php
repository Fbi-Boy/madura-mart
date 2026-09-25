<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Detail Pesanan</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->order_number }} · {{ $order->order_date?->format('d M Y H:i') }}</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <span class="inline-flex rounded-full bg-[#A8F23A]/20 px-3 py-1 text-xs font-semibold text-gray-900 dark:text-[#A8F23A]">{{ ucfirst($order->status) }}</span>
                        <h1 class="mt-3 text-2xl font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $order->delivery_address ?: 'Alamat pengiriman belum tersedia' }}</p>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-xs text-gray-400">Kurir</p>
                        <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ $order->courier?->name ?? 'Belum ditugaskan' }}</p>
                    </div>
                </div>

                <div class="mt-6 divide-y divide-gray-100 rounded-2xl border border-gray-100 dark:divide-gray-700 dark:border-gray-700">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between gap-4 p-4">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $item->product?->name ?? 'Produk tidak tersedia' }}</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $item->quantity }} {{ $item->product?->unit }} · Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}/unit</p>
                            </div>
                            <p class="shrink-0 font-semibold text-gray-900 dark:text-white">Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 rounded-2xl bg-gray-50 p-4 dark:bg-gray-700/50">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-gray-400">Pembayaran</p>
                            <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $order->payment_method === 'qris' ? 'QRIS' : 'Transfer Bank' }}</p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Status: {{ ucfirst($order->payment_status) }}</p>
                        </div>
                        @if ($order->payment_status !== 'paid' && $order->status !== 'cancelled')
                            <a href="{{ route('customer.payment.show', $order) }}" class="rounded-xl bg-[#A8F23A] px-3 py-2 text-xs font-semibold text-gray-900">Bayar / Kirim Bukti</a>
                        @endif
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between border-t border-gray-100 pt-5 dark:border-gray-700">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Pesanan</span>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</span>
                </div>

                <a href="{{ route('customer.orders.index') }}" class="mt-6 inline-flex rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">Kembali ke Pesanan</a>
            </section>
        </div>
    </div>
</x-app-layout>
