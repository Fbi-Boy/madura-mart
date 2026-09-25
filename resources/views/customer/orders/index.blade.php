<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Pesanan Saya</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Riwayat seluruh pesanan yang terhubung dengan akun Anda.</p>
            </div>
            <a href="{{ route('customer.catalog.index') }}" class="inline-flex w-fit rounded-xl bg-[#A8F23A] px-4 py-2 text-xs font-semibold text-gray-900 transition hover:brightness-95">Belanja Lagi</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (!$customer)
                <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-5 text-sm text-yellow-800 dark:border-yellow-900/40 dark:bg-yellow-900/10 dark:text-yellow-300">
                    Profil customer belum terhubung dengan akun ini.
                </div>
            @endif

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Riwayat Pesanan</h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $orders->total() }} pesanan tercatat</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                            <tr>
                                <th class="px-5 py-3">Order</th>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3">Item</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Total</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($orders as $order)
                                <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="px-5 py-4 font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</td>
                                    <td class="px-5 py-4 text-gray-500 dark:text-gray-400">{{ $order->order_date?->format('d/m/Y H:i') }}</td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $order->items_count }} item</td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-full px-2.5 py-1 text-xs font-medium @if ($order->status === 'delivered') bg-green-50 text-green-700 dark:bg-green-900/20 dark:text-green-300 @elseif ($order->status === 'cancelled') bg-red-50 text-red-700 dark:bg-red-900/20 dark:text-red-300 @else bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-300 @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-right font-semibold text-gray-900 dark:text-white">Rp {{ number_format((float) $order->total, 0, ',', '.') }}</td>
                                    <td class="px-5 py-4 text-right">
                                        <a href="{{ route('customer.orders.show', $order) }}" class="font-semibold text-gray-700 hover:underline dark:text-[#A8F23A]">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">Belum ada pesanan.</td></tr>
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
