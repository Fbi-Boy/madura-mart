<x-app-layout>

    <div class="min-h-full">

        {{-- HEADER --}}
        <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <p class="text-xs font-medium uppercase tracking-[0.18em] text-gray-400">
                    Monitoring
                </p>

                <h1 class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                    Verifikasi Pembayaran
                </h1>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Periksa dan verifikasi pembayaran pesanan pelanggan.
                </p>
            </div>

            <button
                type="button"
                onclick="window.location.reload()"
                class="flex h-10 w-10 items-center justify-center rounded-xl
                       border border-gray-200 bg-white text-gray-500
                       transition hover:bg-gray-50 hover:text-gray-900
                       dark:border-white/10 dark:bg-white/5 dark:text-gray-400
                       dark:hover:bg-white/10 dark:hover:text-white"
                title="Refresh"
            >
                <svg class="h-4 w-4" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M20 11a8 8 0 1 0 1 4"/>
                    <path d="M20 4v7h-7"/>
                </svg>
            </button>

        </div>


        {{-- SUMMARY --}}
        @php
            $paymentCollection = isset($orders)
                ? collect(
                    $orders instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
                        ? $orders->items()
                        : $orders
                )
                : collect();

            $paymentCount = isset($orders)
                && $orders instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator
                    ? $orders->total()
                    : $paymentCollection->count();

            $pendingPayments = $paymentCollection
                ->where('payment_status', 'pending')
                ->count();

            $paidPayments = $paymentCollection
                ->where('payment_status', 'paid')
                ->count();

            $rejectedPayments = $paymentCollection
                ->where('payment_status', 'rejected')
                ->count();
        @endphp


        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-2xl border border-gray-200 bg-white p-5
                        dark:border-white/10 dark:bg-white/[0.03]">

                <p class="text-xs font-medium text-gray-400">
                    Total Pembayaran
                </p>

                <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">
                    {{ number_format($paymentCount, 0, ',', '.') }}
                </p>

            </div>


            <div class="rounded-2xl border border-yellow-200 bg-yellow-50/50 p-5
                        dark:border-yellow-500/10 dark:bg-yellow-500/5">

                <p class="text-xs font-medium text-yellow-600 dark:text-yellow-400">
                    Menunggu
                </p>

                <p class="mt-2 text-2xl font-semibold text-yellow-600 dark:text-yellow-400">
                    {{ number_format($pendingPayments, 0, ',', '.') }}
                </p>

            </div>


            <div class="rounded-2xl border border-green-200 bg-green-50/50 p-5
                        dark:border-green-500/10 dark:bg-green-500/5">

                <p class="text-xs font-medium text-green-600 dark:text-green-400">
                    Terverifikasi
                </p>

                <p class="mt-2 text-2xl font-semibold text-green-600 dark:text-green-400">
                    {{ number_format($paidPayments, 0, ',', '.') }}
                </p>

            </div>


            <div class="rounded-2xl border border-red-200 bg-red-50/50 p-5
                        dark:border-red-500/10 dark:bg-red-500/5">

                <p class="text-xs font-medium text-red-600 dark:text-red-400">
                    Ditolak
                </p>

                <p class="mt-2 text-2xl font-semibold text-red-600 dark:text-red-400">
                    {{ number_format($rejectedPayments, 0, ',', '.') }}
                </p>

            </div>

        </div>


        {{-- FILTER --}}
        <div class="mb-4 rounded-2xl border border-gray-200 bg-white p-4
                    dark:border-white/10 dark:bg-white/[0.03]">

            <form method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-4">

                <div class="relative md:col-span-2">

                    <svg
                        class="pointer-events-none absolute left-3 top-1/2 h-4 w-4
                               -translate-y-1/2 text-gray-400"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-4-4"/>
                    </svg>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nomor pesanan atau customer..."
                        class="h-10 w-full rounded-xl border border-gray-200
                               bg-gray-50 pl-10 pr-4 text-sm outline-none
                               focus:border-green-500 focus:ring-2
                               focus:ring-green-500/10
                               dark:border-white/10 dark:bg-white/5
                               dark:text-white dark:placeholder:text-gray-500"
                    >

                </div>


                <select
                    name="payment_status"
                    class="h-10 rounded-xl border border-gray-200
                           bg-gray-50 px-3 text-sm outline-none
                           focus:border-green-500
                           dark:border-white/10 dark:bg-white/5
                           dark:text-gray-300"
                >

                    <option value="">
                        Semua Status
                    </option>

                    <option value="pending" @selected(request('payment_status') === 'pending')>
                        Menunggu
                    </option>

                    <option value="paid" @selected(request('payment_status') === 'paid')>
                        Terverifikasi
                    </option>

                    <option value="rejected" @selected(request('payment_status') === 'rejected')>
                        Ditolak
                    </option>

                </select>


                <div class="flex gap-2">

                    <button
                        type="submit"
                        class="h-10 flex-1 rounded-xl bg-[#A8F23A]
                               px-4 text-sm font-semibold text-[#171719]
                               hover:bg-[#96df2d]"
                    >
                        Filter
                    </button>

                    <a
                        href="{{ url()->current() }}"
                        class="flex h-10 w-10 items-center justify-center
                               rounded-xl border border-gray-200 text-gray-500
                               dark:border-white/10 dark:bg-white/5"
                        title="Reset"
                    >
                        ↻
                    </a>

                </div>

            </form>

        </div>


        {{-- TABLE --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200
                    bg-white dark:border-white/10 dark:bg-white/[0.03]">

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    <thead class="border-b border-gray-200 bg-gray-50/80
                                  dark:border-white/10 dark:bg-white/[0.02]">

                        <tr>

                            <th class="px-5 py-4 text-left text-[11px]
                                       font-semibold uppercase tracking-wider text-gray-400">
                                Pesanan
                            </th>

                            <th class="px-5 py-4 text-left text-[11px]
                                       font-semibold uppercase tracking-wider text-gray-400">
                                Customer
                            </th>

                            <th class="px-5 py-4 text-right text-[11px]
                                       font-semibold uppercase tracking-wider text-gray-400">
                                Total
                            </th>

                            <th class="px-5 py-4 text-center text-[11px]
                                       font-semibold uppercase tracking-wider text-gray-400">
                                Metode
                            </th>

                            <th class="px-5 py-4 text-center text-[11px]
                                       font-semibold uppercase tracking-wider text-gray-400">
                                Status
                            </th>

                            <th class="px-5 py-4 text-center text-[11px]
                                       font-semibold uppercase tracking-wider text-gray-400">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100 dark:divide-white/5">

                        @forelse($orders ?? [] as $order)

                            <tr class="transition hover:bg-gray-50/70
                                       dark:hover:bg-white/[0.025]">

                                <td class="px-5 py-4">

                                    <div class="text-sm font-semibold text-gray-800 dark:text-white">
                                        {{ $order->order_number ?? '-' }}
                                    </div>

                                    <div class="mt-0.5 text-[11px] text-gray-400">
                                        {{ $order->order_date ?? '-' }}
                                    </div>

                                </td>


                                <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ optional($order->customer)->name ?? 'Umum' }}
                                </td>


                                <td class="px-5 py-4 text-right text-sm font-semibold
                                           text-gray-900 dark:text-white">
                                    Rp {{ number_format($order->total ?? 0, 0, ',', '.') }}
                                </td>


                                <td class="px-5 py-4 text-center text-sm text-gray-600 dark:text-gray-300">
                                    {{ strtoupper(str_replace('_', ' ', $order->payment_method ?? '-')) }}
                                </td>


                                <td class="px-5 py-4 text-center">

                                    @php
                                        $paymentStatus = $order->payment_status ?? 'pending';

                                        $paymentClass = match ($paymentStatus) {
                                            'paid' => 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-400',
                                            'rejected' => 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400',
                                            default => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-400',
                                        };
                                    @endphp

                                    <span class="inline-flex rounded-full px-3 py-1
                                                 text-[11px] font-semibold {{ $paymentClass }}">
                                        {{ ucfirst($paymentStatus) }}
                                    </span>

                                </td>


                                <td class="px-5 py-4">

                                    <div class="flex justify-center gap-2">

                                        {{-- DETAIL --}}
                                        <a
                                            href="{{ url('/admin/orders/'.$order->id) }}"
                                            class="flex h-9 w-9 items-center justify-center
                                                   rounded-full bg-gray-100 text-gray-600
                                                   hover:bg-gray-200
                                                   dark:bg-white/10 dark:text-gray-300"
                                            title="Detail"
                                        >
                                            <svg class="h-4 w-4" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor"
                                                 stroke-width="1.8">
                                                <circle cx="12" cy="12" r="9"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </a>


                                        {{-- VERIFIKASI --}}
                                        @if(($order->payment_status ?? '') === 'pending')

                                            <a
                                                href="{{ url('/admin/orders/'.$order->id.'/payment') }}"
                                                class="flex h-9 w-9 items-center justify-center
                                                       rounded-full bg-green-50 text-green-600
                                                       hover:bg-green-100
                                                       dark:bg-green-500/10 dark:text-green-400"
                                                title="Verifikasi"
                                            >
                                                <svg class="h-4 w-4" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor"
                                                     stroke-width="1.8">
                                                    <path d="m5 12 4 4L19 6"/>
                                                </svg>
                                            </a>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6"
                                    class="px-5 py-16 text-center text-sm text-gray-400">
                                    Tidak ada pembayaran yang perlu ditampilkan.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            @if(isset($orders) &&
                $orders instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)

                <div class="border-t border-gray-100 px-5 py-4 dark:border-white/5">
                    {{ $orders->withQueryString()->links() }}
                </div>

            @endif

        </div>

    </div>

</x-app-layout>
