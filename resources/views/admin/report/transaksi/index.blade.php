<x-app-layout>
    <div class="space-y-5">
        <div>
            <p class="text-sm text-black/45 dark:text-white/45">Report</p>
            <h2 class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">Laporan Transaksi</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ringkasan transaksi kasir berdasarkan periode dan status.</p>
        </div>

        <form method="GET" class="grid grid-cols-1 gap-3 rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-gray-800 sm:grid-cols-3">
            <div>
                <label for="from" class="text-sm font-medium text-gray-700 dark:text-gray-200">Dari</label>
                <input id="from" type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="mt-2 w-full rounded-xl border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label for="to" class="text-sm font-medium text-gray-700 dark:text-gray-200">Sampai</label>
                <input id="to" type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="mt-2 w-full rounded-xl border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
            </div>
            <div>
                <label for="status" class="text-sm font-medium text-gray-700 dark:text-gray-200">Status</label>
                <select id="status" name="status" class="mt-2 w-full rounded-xl border-gray-200 bg-white px-3 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-white">
                    <option value="">Semua status</option>
                    @foreach (['paid' => 'Paid', 'cancelled' => 'Cancelled'] as $value => $label)
                        <option value="{{ $value }}" @selected($status === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-3">
                <button type="submit" class="w-full rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white transition hover:opacity-90 dark:bg-white dark:text-[#171719]">Terapkan Filter</button>
            </div>
        </form>

        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            <div class="rounded-2xl border bg-white p-5 dark:border-white/10 dark:bg-gray-800"><p class="text-sm text-black/45 dark:text-white/45">Semua Transaksi</p><p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $summary['all'] }}</p></div>
            <div class="rounded-2xl border bg-white p-5 dark:border-white/10 dark:bg-gray-800"><p class="text-sm text-black/45 dark:text-white/45">Paid</p><p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $summary['paid'] }}</p></div>
            <div class="rounded-2xl border bg-white p-5 dark:border-white/10 dark:bg-gray-800"><p class="text-sm text-black/45 dark:text-white/45">Cancelled</p><p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">{{ $summary['cancelled'] }}</p></div>
            <div class="rounded-2xl border bg-white p-5 dark:border-white/10 dark:bg-gray-800"><p class="text-sm text-black/45 dark:text-white/45">Omzet Paid</p><p class="mt-2 text-xl font-semibold text-gray-900 dark:text-white">Rp {{ number_format($summary['gross'], 0, ',', '.') }}</p></div>
        </div>

        <div class="overflow-hidden rounded-2xl border bg-white dark:border-white/10 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="border-b text-xs uppercase text-black/45 dark:border-white/10 dark:text-white/45">
                        <tr>
                            <th class="px-5 py-4">Invoice</th>
                            <th class="px-5 py-4">Tanggal</th>
                            <th class="px-5 py-4">Customer</th>
                            <th class="px-5 py-4">Kasir</th>
                            <th class="px-5 py-4">Pembayaran</th>
                            <th class="px-5 py-4">Status</th>
                            <th class="px-5 py-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-white/10">
                        @forelse ($transactions as $transaction)
                            <tr class="transition hover:bg-black/[0.02] dark:hover:bg-white/[0.03]">
                                <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">{{ $transaction->invoice }}</td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $transaction->sale_date->format('d/m/Y H:i') }}</td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $transaction->customer?->name ?? 'Walk-in' }}</td>
                                <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $transaction->user?->name ?? '-' }}</td>
                                <td class="px-5 py-4 uppercase text-gray-600 dark:text-gray-300">{{ $transaction->payment_method }}</td>
                                <td class="px-5 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $transaction->status === 'paid' ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">Rp {{ number_format((float) $transaction->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-5 py-12 text-center text-black/40 dark:text-white/40">Tidak ada transaksi pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($transactions->hasPages())
                <div class="border-t px-5 py-4 dark:border-white/10">{{ $transactions->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
