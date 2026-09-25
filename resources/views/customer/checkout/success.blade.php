<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Pesanan Berhasil</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Pesanan kamu sudah tercatat di Madura Mart.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-[#A8F23A]/40 bg-white p-6 shadow-sm dark:border-[#A8F23A]/20 dark:bg-gray-800 sm:p-8">
                <span class="inline-flex rounded-full bg-[#A8F23A]/20 px-3 py-1 text-xs font-semibold text-gray-900 dark:text-[#A8F23A]">Order dibuat</span>
                <h1 class="mt-4 text-2xl font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $order->order_date?->format('d M Y H:i') }} · {{ ucfirst($order->status) }}</p>

                <div class="mt-6 space-y-3">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between gap-4 rounded-xl bg-gray-50 p-4 dark:bg-gray-700/50">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $item->product?->name }}</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $item->quantity }} {{ $item->product?->unit }}</p>
                            </div>
                            <p class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex items-center justify-between border-t border-gray-100 pt-5 dark:border-gray-700">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total</span>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</span>
                </div>

                <a href="{{ route('dashboard') }}" class="mt-6 inline-flex rounded-xl bg-[#A8F23A] px-4 py-2.5 text-sm font-semibold text-gray-900">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>
