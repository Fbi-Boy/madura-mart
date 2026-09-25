<x-app-layout>
<div class="space-y-5">
    <div>
        <p class="text-sm text-black/45 dark:text-white/45">Report</p>
        <h2 class="mt-1 text-2xl font-semibold text-[#171719] dark:text-white">Laporan Pembelian</h2>
    </div>

    <form method="GET" class="grid grid-cols-1 gap-3 rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5 sm:grid-cols-3">
        <div>
            <label for="from" class="text-sm font-medium text-[#171719] dark:text-white">Dari</label>
            <input id="from" type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="mt-2 w-full rounded-xl border border-black/10 bg-transparent px-3 py-2.5 dark:border-white/10">
        </div>
        <div>
            <label for="to" class="text-sm font-medium text-[#171719] dark:text-white">Sampai</label>
            <input id="to" type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="mt-2 w-full rounded-xl border border-black/10 bg-transparent px-3 py-2.5 dark:border-white/10">
        </div>
        <div class="flex items-end">
            <button class="w-full rounded-xl bg-[#171719] px-4 py-2.5 font-semibold text-white transition hover:opacity-85">Filter</button>
        </div>
    </form>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <p class="text-sm text-black/45 dark:text-white/45">Total Transaksi</p>
            <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">{{ number_format($totalTransactions, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <p class="text-sm text-black/45 dark:text-white/45">Total Pembelian</p>
            <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">Rp {{ number_format($totalPurchases, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <p class="text-sm text-black/45 dark:text-white/45">Diterima</p>
            <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">{{ number_format($receivedTransactions, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <p class="text-sm text-black/45 dark:text-white/45">Draft</p>
            <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">{{ number_format($draftTransactions, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="border-b border-black/5 text-xs uppercase text-black/45 dark:border-white/10 dark:text-white/45">
                    <tr>
                        <th class="px-5 py-4">Invoice</th>
                        <th class="px-5 py-4">Supplier</th>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Dibuat Oleh</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse($purchases as $purchase)
                        <tr class="transition hover:bg-black/[0.02] dark:hover:bg-white/[0.03]">
                            <td class="px-5 py-4 font-medium text-[#171719] dark:text-white">{{ $purchase->invoice }}</td>
                            <td class="px-5 py-4 text-black/65 dark:text-white/65">{{ $purchase->supplier?->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-black/55 dark:text-white/55">{{ $purchase->purchase_date?->format('d/m/Y') }}</td>
                            <td class="px-5 py-4 text-black/65 dark:text-white/65">{{ $purchase->user?->name ?? '-' }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium @if($purchase->status === 'received') bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300 @elseif($purchase->status === 'cancelled') bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300 @else bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-300 @endif">
                                    {{ ucfirst($purchase->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-semibold text-[#171719] dark:text-white">Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-black/40 dark:text-white/40">Tidak ada pembelian pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($purchases->hasPages())
            <div class="border-t border-black/5 px-5 py-4 dark:border-white/10">{{ $purchases->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
