<x-app-layout>
<div class="space-y-5">
    <div>
        <p class="text-sm text-black/45">Monitoring</p>
        <h2 class="mt-1 text-2xl font-semibold">Monitoring Pesanan</h2>
        <p class="mt-1 text-sm text-black/40">Pantau pesanan, pelanggan, kurir, dan progres pengiriman.</p>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Total Pesanan</p><p class="mt-2 text-2xl font-semibold">{{ $totalOrders }}</p></div>
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Menunggu</p><p class="mt-2 text-2xl font-semibold">{{ $pending }}</p></div>
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Diproses / Dikirim</p><p class="mt-2 text-2xl font-semibold">{{ $processing + $shipping }}</p></div>
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Nilai Hari Ini</p><p class="mt-2 text-2xl font-semibold">Rp {{ number_format($todayTotal, 0, ',', '.') }}</p></div>
    </div>

    <form method="GET" class="flex gap-3 rounded-2xl border bg-white p-5">
        <input name="search" value="{{ $search }}" placeholder="Cari nomor pesanan atau pelanggan..." class="w-full rounded-xl border px-3 py-2.5">
        <button class="rounded-xl bg-[#171719] px-5 py-2.5 font-semibold text-white">Cari</button>
    </form>

    <div class="overflow-hidden rounded-2xl border bg-white">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b text-xs uppercase text-black/45">
                    <tr>
                        <th class="px-5 py-4">Pesanan</th>
                        <th class="px-5 py-4">Tanggal</th>
                        <th class="px-5 py-4">Pelanggan</th>
                        <th class="px-5 py-4">Kurir</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-5 py-4 font-medium">{{ $order->order_number }}</td>
                        <td class="px-5 py-4">{{ $order->order_date->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-4">{{ $order->customer?->name ?? 'Umum' }}</td>
                        <td class="px-5 py-4">{{ $order->courier?->name ?? 'Belum ditugaskan' }}</td>
                        <td class="px-5 py-4">
                            <span class="rounded-full px-2.5 py-1 text-xs font-medium
                                @if($order->status === 'delivered') bg-green-50 text-green-700
                                @elseif($order->status === 'cancelled') bg-red-50 text-red-600
                                @elseif($order->status === 'shipped') bg-blue-50 text-blue-700
                                @elseif($order->status === 'processing') bg-yellow-50 text-yellow-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right font-semibold">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-12 text-center text-black/40">Belum ada pesanan.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
            <div class="border-t px-5 py-4">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>