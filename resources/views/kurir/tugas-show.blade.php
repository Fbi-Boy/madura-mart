<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-gray-400">Logistics Operations</p>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Detail Pengiriman</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->order_number }}</p>
            </div>
            <a href="{{ route('kurir.pengiriman.index') }}" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">Kembali ke Tugas</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr]">
                <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-gray-400">Pesanan</p>
                            <h1 class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</h1>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $order->order_date?->format('d M Y, H:i') }}</p>
                        </div>
                        <span class="w-fit rounded-full bg-[#A8F23A]/20 px-3 py-1.5 text-xs font-semibold text-gray-900 dark:text-[#A8F23A]">{{ ucfirst($order->status) }}</span>
                    </div>

                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-700/50">
                            <p class="text-xs uppercase tracking-wide text-gray-400">Penerima</p>
                            <p class="mt-2 font-semibold text-gray-900 dark:text-white">{{ $order->customer?->name ?? '-' }}</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $order->customer?->phone ?? '-' }}</p>
                        </div>
                        <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-700/50">
                            <p class="text-xs uppercase tracking-wide text-gray-400">Alamat</p>
                            <p class="mt-2 text-sm leading-6 text-gray-700 dark:text-gray-200">{{ $order->delivery_address ?: ($order->customer?->address ?? '-') }}</p>
                            @if ($order->customer?->city)<p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $order->customer->city }}</p>@endif
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Item Pesanan</h3>
                        <div class="mt-3 divide-y divide-gray-100 rounded-xl border border-gray-200 dark:divide-gray-700 dark:border-gray-700">
                            @forelse ($order->items as $item)
                                <div class="flex items-center justify-between gap-4 p-4">
                                    <div class="min-w-0">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $item->product?->name ?? 'Produk tidak tersedia' }}</p>
                                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $item->product?->sku ?? '-' }} · {{ $item->product?->unit ?? '-' }}</p>
                                    </div>
                                    <div class="shrink-0 text-right">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $item->quantity }}x</p>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="p-5 text-sm text-gray-400">Belum ada item pada pesanan ini.</p>
                            @endforelse
                        </div>
                    </div>
                </section>

                <section class="space-y-6">
                    <div class="rounded-2xl border border-[#A8F23A]/40 bg-[#A8F23A]/10 p-6 dark:bg-[#A8F23A]/5">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-gray-500 dark:text-gray-400">Aksi Berikutnya</p>
                        @php
                            $nextStatus = ['pending' => 'processing', 'processing' => 'shipped', 'shipped' => 'delivered'][$order->status] ?? null;
                            $nextLabel = ['processing' => 'Tandai Diproses', 'shipped' => 'Tandai Dikirim', 'delivered' => 'Tandai Selesai'][$nextStatus] ?? null;
                        @endphp
                        @if ($nextStatus)
                            <h3 class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">{{ $nextLabel }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Perubahan status dicatat ke activity log dan hanya dapat dilakukan untuk pesanan milik akun kurir ini.</p>
                            <form method="POST" action="{{ route('kurir.pengiriman.status', $order) }}" class="mt-5">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $nextStatus }}">
                                <button class="w-full rounded-xl bg-[#A8F23A] px-4 py-3 text-sm font-semibold text-gray-900 hover:brightness-95">{{ $nextLabel }}</button>
                            </form>
                        @else
                            <h3 class="mt-2 text-lg font-semibold text-gray-900 dark:text-white">Pengiriman selesai</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Tidak ada status lanjutan untuk pesanan ini.</p>
                        @endif
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-gray-400">Ringkasan</p>
                        <div class="mt-4 space-y-3">
                            <div class="flex justify-between gap-4 text-sm"><span class="text-gray-500 dark:text-gray-400">Pembayaran</span><span class="font-medium text-gray-900 dark:text-white">{{ ucfirst($order->payment_status) }}</span></div>
                            <div class="flex justify-between gap-4 text-sm"><span class="text-gray-500 dark:text-gray-400">Metode</span><span class="font-medium text-gray-900 dark:text-white">{{ strtoupper($order->payment_method ?? '-') }}</span></div>
                            <div class="flex justify-between gap-4 border-t border-gray-100 pt-3 dark:border-gray-700"><span class="font-semibold text-gray-900 dark:text-white">Total</span><span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</span></div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
