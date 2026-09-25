<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Penerimaan Barang</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Review purchase order draft sebelum stok masuk ke gudang.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900/40 dark:bg-green-900/20 dark:text-green-300">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-300">{{ session('error') }}</div>
            @endif

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">PO Menunggu Penerimaan</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Setiap PO hanya dapat diterima satu kali.</p>
                    </div>
                    <span class="rounded-full bg-[#A8F23A]/20 px-2.5 py-1 text-xs font-semibold text-gray-900 dark:text-[#A8F23A]">{{ $purchases->total() }} PO</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Invoice</th>
                                <th class="px-5 py-3">Supplier</th>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3">Dibuat Oleh</th>
                                <th class="px-5 py-3">Item</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($purchases as $purchase)
                                <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="px-5 py-4 font-semibold text-gray-900 dark:text-white">{{ $purchase->invoice }}</td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $purchase->supplier?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $purchase->purchase_date?->format('d/m/Y') }}</td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $purchase->user?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $purchase->items_count }}</td>
                                    <td class="px-5 py-4 text-right">
                                        <form method="POST" action="{{ route('gudang.penerimaan.receive', $purchase) }}" onsubmit="return confirm('Konfirmasi barang sudah diterima dan tambah stok?')">
                                            @csrf
                                            <button type="submit" class="rounded-xl bg-[#A8F23A] px-3 py-2 text-xs font-semibold text-gray-900 transition hover:opacity-80">Terima Barang</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Tidak ada purchase order yang menunggu penerimaan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($purchases->hasPages())
                    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700">{{ $purchases->links() }}</div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
