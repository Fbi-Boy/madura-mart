<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Struk Transaksi</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $sale->invoice }}</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-md px-4 sm:px-6 lg:px-8">
            <section class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 print:border-0 print:p-0 print:shadow-none">
                <div class="text-center">
                    <p class="text-lg font-bold text-gray-900 dark:text-white print:text-black">MADURA MART</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 print:text-gray-600">Struk transaksi penjualan</p>
                </div>

                <div class="mt-5 border-y border-dashed border-gray-300 py-4 text-xs text-gray-600 dark:border-gray-600 dark:text-gray-300 print:text-gray-700">
                    <div class="flex justify-between gap-4"><span>Invoice</span><span class="font-semibold">{{ $sale->invoice }}</span></div>
                    <div class="mt-1 flex justify-between gap-4"><span>Tanggal</span><span>{{ $sale->sale_date->format('d/m/Y H:i') }}</span></div>
                    <div class="mt-1 flex justify-between gap-4"><span>Kasir</span><span>{{ $sale->user->name }}</span></div>
                    <div class="mt-1 flex justify-between gap-4"><span>Pelanggan</span><span>{{ $sale->customer?->name ?? 'Umum' }}</span></div>
                </div>

                <div class="space-y-3 py-4">
                    @foreach ($sale->items as $item)
                        <div class="flex justify-between gap-4 text-sm">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white print:text-black">{{ $item->product?->name ?? 'Produk' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 print:text-gray-600">{{ $item->quantity }} {{ $item->product?->unit }} × Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}</p>
                            </div>
                            <p class="font-semibold text-gray-900 dark:text-white print:text-black">Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-dashed border-gray-300 pt-4 dark:border-gray-600">
                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300 print:text-gray-700">
                        <span>Pembayaran</span>
                        <span class="uppercase">{{ $sale->payment_method }}</span>
                    </div>
                    <div class="mt-2 flex justify-between text-base font-bold text-gray-900 dark:text-white print:text-black">
                        <span>Total</span>
                        <span>Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <p class="mt-6 text-center text-xs text-gray-400">Terima kasih telah berbelanja di Madura Mart.</p>

                <div class="mt-6 flex gap-2 print:hidden">
                    <button type="button" onclick="window.print()" class="flex-1 rounded-xl bg-[#A8F23A] px-4 py-2.5 text-sm font-semibold text-gray-900">Cetak Struk</button>
                    <a href="{{ route('kasir.riwayat-transaksi') }}" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:border-gray-700 dark:text-gray-200">Kembali</a>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
