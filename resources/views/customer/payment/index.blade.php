<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Pembayaran Pesanan</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $order->order_number }}</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total yang harus dibayar</p>
                        <p class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</p>
                    </div>
                    <span class="rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-300">
                        {{ $order->payment_status === 'rejected' ? 'Bukti ditolak · kirim ulang' : 'Menunggu pembayaran' }}
                    </span>
                </div>

                <div class="mt-6 rounded-2xl bg-gray-50 p-4 dark:bg-gray-700/50">
                    <p class="text-xs uppercase tracking-wide text-gray-400">Metode</p>
                    <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                        {{ $order->payment_method === 'qris' ? 'QRIS' : 'Transfer Bank' }}
                    </p>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Selesaikan pembayaran sesuai metode yang dipilih, lalu unggah bukti pembayaran.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mt-5 rounded-xl bg-red-50 p-4 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-300">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('customer.payment.store', $order) }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                    @csrf
                    <div>
                        <label for="payment_proof" class="text-sm font-medium text-gray-700 dark:text-gray-200">Bukti Pembayaran</label>
                        <input id="payment_proof" name="payment_proof" type="file" accept=".jpg,.jpeg,.png,.pdf" required class="mt-2 block w-full rounded-xl border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-gray-100 file:px-4 file:py-2.5 file:text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:file:bg-gray-600">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG, atau PDF. Maksimal 2 MB.</p>
                    </div>
                    <button class="w-full rounded-xl bg-[#A8F23A] px-4 py-3 text-sm font-semibold text-gray-900 transition hover:brightness-95">
                        Kirim Bukti Pembayaran
                    </button>
                </form>

                <a href="{{ route('customer.orders.show', $order) }}" class="mt-4 block text-center text-sm font-semibold text-gray-600 dark:text-gray-300">Kembali ke detail pesanan</a>
            </section>
        </div>
    </div>
</x-app-layout>
