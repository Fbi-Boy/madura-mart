<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Report</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Laporan Pelanggan</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Aktivitas dan nilai belanja customer pada periode terpilih</p>
        </div>
        <button type="button" onclick="window.print()"
            class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 9V3h12v6"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v7H6z"/>
            </svg>
            Cetak
        </button>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-4">
        <div class="flex flex-wrap items-end gap-3">
            <div class="min-w-[150px]">
                <label class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Dari</label>
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}"
                    class="mt-1.5 w-full h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>
            <div class="min-w-[150px]">
                <label class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Sampai</label>
                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}"
                    class="mt-1.5 w-full h-10 px-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-black/5 dark:border-white/10 text-sm focus:outline-none focus:border-lime-400 dark:text-white">
            </div>
            <div>
                <button type="submit"
                    class="h-10 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-bold flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/>
                    </svg>
                    Filter
                </button>
            </div>
        </div>
    </form>

    {{-- SUMMARY --}}
    @php
        $kpis = [
            ['label' => 'Customer Terdaftar', 'value' => number_format($totalCustomers, 0, ',', '.'),      'hint' => 'semua customer',   'icon' => 'users', 'color' => 'blue'],
            ['label' => 'Customer Baru',      'value' => number_format($periodNewCustomers, 0, ',', '.'),  'hint' => 'pada periode ini', 'icon' => 'user',  'color' => 'lime'],
            ['label' => 'Belanja Customer',   'value' => 'Rp ' . number_format($periodRevenue, 0, ',', '.'),'hint' => 'pendapatan periode','icon' => 'money', 'color' => 'purple'],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach ($kpis as $kpi)
            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">{{ $kpi['label'] }}</p>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center
                        {{ $kpi['color'] === 'blue' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                        {{ $kpi['color'] === 'lime' ? 'bg-lime-100 dark:bg-lime-900/30 text-lime-600 dark:text-lime-400' : '' }}
                        {{ $kpi['color'] === 'purple' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400' : '' }}">
                        @if($kpi['icon'] === 'users')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        @elseif($kpi['icon'] === 'user')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        @elseif($kpi['icon'] === 'money')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        @endif
                    </div>
                </div>
                <p class="text-lg font-bold text-[#171719] dark:text-white truncate">{{ $kpi['value'] }}</p>
                <p class="mt-1 text-[10px] text-black/45 dark:text-white/45">{{ $kpi['hint'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- TABLE --}}
    <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">
        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
            <h2 class="text-sm font-bold text-[#171719] dark:text-white">Aktivitas Customer</h2>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                {{ $from->format('d/m/Y') }} — {{ $to->format('d/m/Y') }}
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left">
                <thead class="border-b border-black/5 dark:border-white/10">
                    <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                        <th class="px-5 py-3.5 font-medium">Customer</th>
                        <th class="px-5 py-3.5 font-medium">Kontak</th>
                        <th class="px-5 py-3.5 text-center font-medium">Transaksi</th>
                        <th class="px-5 py-3.5 text-right font-medium">Total Belanja</th>
                        <th class="px-5 py-3.5 text-center font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                    @forelse($customers ?? [] as $customer)
                        @php
                            $statusColor = $customer->is_active
                                ? 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400'
                                : 'bg-gray-100 text-gray-700 dark:bg-white/10 dark:text-white/60';
                            $statusLabel = $customer->is_active ? 'Aktif' : 'Nonaktif';
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-100 to-purple-50 dark:from-purple-900/30 dark:to-purple-900/10 flex items-center justify-center shrink-0">
                                        <span class="text-lg font-black text-purple-600 dark:text-purple-400">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-[#171719] dark:text-white truncate max-w-[220px]">
                                            {{ $customer->name }}
                                        </p>
                                        <p class="text-[10px] text-black/45 dark:text-white/45 mt-0.5 font-mono">
                                            {{ $customer->code }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <p class="text-xs text-black/70 dark:text-white/70 font-mono">
                                    {{ $customer->phone ?: ($customer->email ?: '-') }}
                                </p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-white/10 text-[10px] font-bold text-black/70 dark:text-white/70">
                                    {{ number_format((int) $customer->transaction_count, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <p class="text-xs font-bold text-lime-600 dark:text-lime-400">
                                    Rp {{ number_format((float) $customer->total_spent, 0, ',', '.') }}
                                </p>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-16 text-center">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada customer</p>
                                <p class="mt-1 text-xs text-black/40 dark:text-white/40">Data akan muncul pada periode ini</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($customers) && $customers instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $customers->hasPages())
            <div class="border-t border-black/5 dark:border-white/10 px-5 py-4">
                {{ $customers->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
    @media print { nav, aside, button, form { display: none !important; } body { background: white !important; } }
</style>
</x-app-layout>
