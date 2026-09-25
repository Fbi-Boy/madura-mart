<x-app-layout>
<div class="space-y-5">
    <div>
        <p class="text-sm text-black/45 dark:text-white/45">Report</p>
        <h2 class="mt-1 text-2xl font-semibold text-[#171719] dark:text-white">Laporan Pengiriman</h2>
        <p class="mt-1 text-sm text-black/50 dark:text-white/50">Ringkasan order dan status pengiriman berdasarkan periode.</p>
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
            <p class="text-sm text-black/45 dark:text-white/45">Total Pengiriman</p>
            <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">{{ number_format($totalShipments, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <p class="text-sm text-black/45 dark:text-white/45">Sudah Ditugaskan</p>
            <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">{{ number_format($assignedShipments, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <p class="text-sm text-black/45 dark:text-white/45">Selesai</p>
            <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">{{ number_format($statusSummary['delivered'], 0, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <p class="text-sm text-black/45 dark:text-white/45">Nilai Order Aktif</p>
            <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">Rp {{ number_format($shippingValue, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach (['pending'=>'Menunggu','processing'=>'Diproses','shipped'=>'Dikirim','delivered'=>'Selesai','cancelled'=>'Dibatalkan'] as $key => $label)
            <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <p class="text-xs text-black/45 dark:text-white/45">{{ $label }}</p>
                <p class="mt-2 text-xl font-semibold text-[#171719] dark:text-white">{{ number_format($statusSummary[$key], 0, ',', '.') }}</p>
            </div>
        @endforeach
    </div>

    <div class="overflow-hidden rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead class="border-b border-black/5 text-xs uppercase text-black/45 dark:border-white/10 dark:text-white/45">
                    <tr>
                        <th class="px-5 py-4">Order</th>
                        <th class="px-5 py-4">Customer</th>
                        <th class="px-5 py-4">Kurir</th>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse($orders as $order)
                        <tr class="transition hover:bg-black/[0.02] dark:hover:bg-white/[0.03]">
                            <td class="px-5 py-4 font-medium text-[#171719] dark:text-white">{{ $order->order_number }}</td>
                            <td class="px-5 py-4 text-black/65 dark:text-white/65">{{ $order->customer?->name ?? '-' }}</td>
                            <td class="px-5 py-4 text-black/65 dark:text-white/65">{{ $order->courier?->name ?? 'Belum ditugaskan' }}</td>
                            <td class="px-5 py-4 text-black/55 dark:text-white/55">{{ $order->order_date?->format('d/m/Y H:i') }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-2.5 py-1 text-xs font-medium @if($order->status === 'delivered') bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300 @elseif($order->status === 'cancelled') bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300 @elseif($order->status === 'shipped') bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300 @else bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-300 @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-semibold text-[#171719] dark:text-white">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-12 text-center text-black/40 dark:text-white/40">Tidak ada order pada periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="border-t border-black/5 px-5 py-4 dark:border-white/10">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
