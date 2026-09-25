<x-app-layout>
<div class="space-y-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div><p class="text-sm text-black/45 dark:text-white/45">Kasir</p><h2 class="mt-1 text-2xl font-semibold text-[#171719] dark:text-white">Riwayat Transaksi</h2></div>
        <a href="{{ route('kasir.transaksi-baru') }}" class="rounded-xl bg-[#A8F23A] px-4 py-2.5 text-sm font-semibold text-[#171719]">+ Transaksi Baru</a>
    </div>
    @if (session('success'))<div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>@endif
    <div class="overflow-hidden rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-black/5 text-xs uppercase text-black/45 dark:border-white/10 dark:text-white/45">
                    <tr><th class="px-5 py-4">Invoice</th><th class="px-5 py-4">Tanggal</th><th class="px-5 py-4">Pelanggan</th><th class="px-5 py-4">Kasir</th><th class="px-5 py-4">Pembayaran</th><th class="px-5 py-4 text-right">Total</th></tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse ($sales as $sale)
                        <tr>
                            <td class="px-5 py-4 font-medium text-[#171719] dark:text-white">{{ $sale->invoice }}</td>
                            <td class="px-5 py-4 text-black/60 dark:text-white/60">{{ $sale->sale_date->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4 text-black/60 dark:text-white/60">{{ $sale->customer?->name ?? 'Umum' }}</td>
                            <td class="px-5 py-4 text-black/60 dark:text-white/60">{{ $sale->user->name }}</td>
                            <td class="px-5 py-4 uppercase text-black/60 dark:text-white/60">{{ $sale->payment_method }}</td>
                            <td class="px-5 py-4 text-right"><div class="flex items-center justify-end gap-3"><span class="font-semibold text-[#171719] dark:text-white">Rp {{ number_format((float) $sale->total, 0, ',', '.') }}</span><a href="{{ route('kasir.transaksi-struk', $sale) }}" class="text-xs font-semibold text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Struk</a></div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-sm text-black/40 dark:text-white/40">Belum ada transaksi penjualan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($sales->hasPages())<div class="border-t border-black/5 px-5 py-4 dark:border-white/10">{{ $sales->links() }}</div>@endif
    </div>
</div>
</x-app-layout>