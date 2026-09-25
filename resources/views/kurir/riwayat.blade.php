<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Riwayat Pengiriman</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Logistics Operations · Riwayat tugas kurir yang telah selesai atau dibatalkan.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-xl bg-[#A8F23A] px-4 py-2 text-sm font-semibold text-gray-900 transition hover:brightness-95">
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-gray-400">Courier History</p>
                        <h1 class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $courier?->name ?? 'Akun Kurir' }}</h1>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Data hanya menampilkan pesanan yang ditugaskan kepada akun kurir ini.</p>
                    </div>
                    <span class="rounded-full bg-gray-100 px-3 py-1.5 text-xs font-medium text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                        {{ $orders->total() }} riwayat
                    </span>
                </div>
            </div>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Pesanan</th>
                                <th class="px-5 py-3">Customer</th>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($orders as $order)
                                <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="px-5 py-4">
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</p>
                                        <p class="mt-1 max-w-[280px] truncate text-xs text-gray-400 dark:text-gray-500">{{ $order->delivery_address }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $order->customer?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $order->order_date?->format('d/m/Y H:i') }}</td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $order->status === 'delivered' ? 'bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300' : 'bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300' }}">
                                            {{ $order->status === 'delivered' ? 'Selesai' : 'Dibatalkan' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada riwayat pengiriman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($orders->hasPages())
                    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                        {{ $orders->links() }}
                    </div>
                @endif
            </section>
        </div>
    </div>

</x-app-layout>
