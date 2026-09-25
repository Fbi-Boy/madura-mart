<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Verifikasi Pembayaran</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Periksa bukti pembayaran customer sebelum mengonfirmasi pesanan.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="rounded-xl border border-[#A8F23A]/40 bg-[#A8F23A]/10 px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                    {{ session('status') }}
                </div>
            @endif

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Menunggu Verifikasi</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $orders->total() }} pembayaran dengan bukti yang siap diperiksa.</p>
                </div>

                @if ($orders->isEmpty())
                    <div class="p-10 text-center">
                        <p class="font-semibold text-gray-900 dark:text-white">Tidak ada pembayaran yang menunggu verifikasi.</p>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Semua bukti pembayaran sudah diproses.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($orders as $order)
                            <div class="space-y-4 p-5 lg:flex lg:items-center lg:justify-between lg:gap-6 lg:space-y-0">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                                        <span class="rounded-full bg-yellow-50 px-2.5 py-1 text-[11px] font-semibold text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-300">Pending</span>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $order->customer?->name ?? 'Customer' }} · {{ $order->customer?->email }}</p>
                                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Metode: {{ $order->payment_method === 'qris' ? 'QRIS' : 'Transfer Bank' }}</p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.payment-verification.proof', $order) }}" class="rounded-xl border border-gray-200 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700">Lihat Bukti</a>
                                    <form method="POST" action="{{ route('admin.payment-verification.update', $order) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="payment_status" value="paid">
                                        <button class="rounded-xl bg-[#A8F23A] px-3 py-2 text-xs font-semibold text-gray-900 transition hover:brightness-95">Konfirmasi</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.payment-verification.update', $order) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="payment_status" value="rejected">
                                        <button class="rounded-xl border border-red-200 px-3 py-2 text-xs font-semibold text-red-700 transition hover:bg-red-50 dark:border-red-900/50 dark:text-red-300 dark:hover:bg-red-900/20">Tolak</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                        {{ $orders->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
