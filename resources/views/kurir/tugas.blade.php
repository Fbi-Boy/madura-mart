<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.16em] text-gray-400">Logistics Operations</p>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Tugas Pengiriman</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola tugas yang benar-benar ditugaskan ke akun kurir ini.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">Dashboard</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-[#A8F23A]/40 bg-[#A8F23A]/10 p-5 dark:bg-[#A8F23A]/5">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-gray-500 dark:text-gray-400">Courier Workspace</p>
                        <h1 class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $courier->name }}</h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Pilih tugas untuk melihat penerima, alamat, item, nilai pesanan, dan aksi status berikutnya.</p>
                    </div>
                    <a href="{{ route('kurir.pengiriman.riwayat') }}" class="w-fit rounded-xl bg-[#A8F23A] px-4 py-2 text-sm font-semibold text-gray-900 transition hover:brightness-95">Riwayat</a>
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (['all' => 'Semua Aktif', 'pending' => 'Menunggu', 'processing' => 'Diproses', 'shipped' => 'Dalam Pengiriman'] as $key => $label)
                    <a href="{{ $key === 'all' ? route('kurir.pengiriman.index') : route('kurir.pengiriman.index', ['status' => $key]) }}"
                       class="rounded-2xl border p-4 transition {{ ($status ?: 'all') === $key ? 'border-[#A8F23A] bg-[#A8F23A]/10' : 'border-gray-200 bg-white hover:border-[#A8F23A] dark:border-gray-700 dark:bg-gray-800' }}">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $label }}</span>
                            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-bold text-gray-800 dark:bg-gray-700 dark:text-gray-200">{{ $summary[$key] }}</span>
                        </div>
                    </a>
                @endforeach
            </div>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Daftar Tugas</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Pesanan aktif yang dapat diproses oleh kurir.</p>
                    </div>
                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">{{ $orders->total() }} tugas</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[820px] text-left text-sm">
                        <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Pesanan</th>
                                <th class="px-5 py-3">Penerima</th>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Total</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($orders as $order)
                                @php
                                    $nextStatus = ['pending' => 'processing', 'processing' => 'shipped', 'shipped' => 'delivered'][$order->status] ?? null;
                                    $nextLabel = ['processing' => 'Proses', 'shipped' => 'Kirim', 'delivered' => 'Selesai'][$nextStatus] ?? null;
                                @endphp
                                <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="px-5 py-4">
                                        <a href="{{ route('kurir.pengiriman.show', $order) }}" class="font-semibold text-gray-900 hover:underline dark:text-white">{{ $order->order_number }}</a>
                                        <p class="mt-1 max-w-[280px] truncate text-xs text-gray-400 dark:text-gray-500">{{ $order->delivery_address }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="font-medium text-gray-700 dark:text-gray-200">{{ $order->customer?->name ?? '-' }}</p>
                                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $order->customer?->phone ?? 'No. telepon tidak tersedia' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $order->order_date?->format('d/m/Y H:i') }}</td>
                                    <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-medium @if ($order->status === 'shipped') bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-300 @elseif ($order->status === 'processing') bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-300 @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 @endif">{{ ucfirst($order->status) }}</span></td>
                                    <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('kurir.pengiriman.show', $order) }}" class="mr-2 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200">Detail</a>
                                        @if ($nextStatus)
                                            <form method="POST" action="{{ route('kurir.pengiriman.status', $order) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="{{ $nextStatus }}">
                                                <button type="submit" class="rounded-lg bg-[#A8F23A] px-3 py-1.5 text-xs font-semibold text-gray-900 hover:brightness-95">{{ $nextLabel }}</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">Tidak ada tugas pada filter ini.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($orders->hasPages())
                    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700">{{ $orders->links() }}</div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
