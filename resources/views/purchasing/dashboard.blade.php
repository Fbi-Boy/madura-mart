<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Procurement Operations</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Dashboard Purchasing</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pantau pembelian dan penerimaan barang</p>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-3 py-1.5 rounded-xl font-medium">
                {{ now()->translatedFormat('d M Y') }}
            </span>
            <a href="{{ route('purchasing.purchases.create') }}"
               class="h-10 px-4 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-sm font-medium flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 5v14"/><path d="M5 12h14"/>
                </svg>
                Buat PO
            </a>
        </div>
    </div>

    {{-- ================= KPI CARDS (6) ================= --}}
    @php
        $kpis = [
            ['label' => 'Supplier Aktif', 'value' => number_format($activeSuppliers, 0, ',', '.'), 'hint' => 'supplier tersedia', 'icon' => 'users', 'color' => 'blue'],
            ['label' => 'Pembelian Hari Ini', 'value' => 'Rp ' . number_format($todayPurchases, 0, ',', '.'), 'hint' => $todayTransactions . ' transaksi', 'icon' => 'money', 'color' => 'lime'],
            ['label' => 'Draft', 'value' => number_format($draftPurchases, 0, ',', '.'), 'hint' => 'belum dikirim', 'icon' => 'draft', 'color' => 'gray'],
            ['label' => 'Menunggu Terima', 'value' => number_format($submittedPurchases, 0, ',', '.'), 'hint' => 'sudah dikirim gudang', 'icon' => 'clock', 'color' => 'amber'],
            ['label' => 'Diterima Hari Ini', 'value' => number_format($receivedToday, 0, ',', '.'), 'hint' => 'status received', 'icon' => 'check', 'color' => 'emerald'],
            ['label' => 'Receipt Terlambat', 'value' => number_format($overdueReceiving, 0, ',', '.'), 'hint' => '> 3 hari', 'icon' => 'alert', 'color' => 'red'],
        ];
    @endphp

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 xl:grid-cols-6">
        @foreach ($kpis as $kpi)
            <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">{{ $kpi['label'] }}</p>
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center
                        {{ $kpi['color'] === 'blue' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : '' }}
                        {{ $kpi['color'] === 'lime' ? 'bg-lime-100 dark:bg-lime-900/30 text-lime-600 dark:text-lime-400' : '' }}
                        {{ $kpi['color'] === 'gray' ? 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-400' : '' }}
                        {{ $kpi['color'] === 'amber' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : '' }}
                        {{ $kpi['color'] === 'emerald' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : '' }}
                        {{ $kpi['color'] === 'red' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : '' }}">
                        @if($kpi['icon'] === 'users')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                        @elseif($kpi['icon'] === 'money')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                        @elseif($kpi['icon'] === 'draft')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        @elseif($kpi['icon'] === 'clock')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>
                            </svg>
                        @elseif($kpi['icon'] === 'check')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                        @elseif($kpi['icon'] === 'alert')
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                            </svg>
                        @endif
                    </div>
                </div>
                <p class="text-lg font-bold text-[#171719] dark:text-white truncate">{{ $kpi['value'] }}</p>
                <p class="mt-1 text-[10px] text-black/45 dark:text-white/45">{{ $kpi['hint'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- ================= WARNING OVERDUE ================= --}}
    @if ($overdueReceiving > 0)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10 px-4 py-3 flex items-start justify-between gap-3 flex-wrap">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 9v4"/><path d="M12 17h.01"/>
                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">Receiving Perlu Perhatian</p>
                    <p class="mt-0.5 text-[11px] text-amber-700 dark:text-amber-200/80">
                        {{ $overdueReceiving }} pembelian sudah disubmit > 3 hari & belum received.
                    </p>
                </div>
            </div>
            <a href="{{ route('purchasing.purchases.index') }}"
               class="h-9 px-4 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold flex items-center gap-1.5 transition">
                Tinjau PO
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                </svg>
            </a>
        </div>
    @endif

    {{-- ================= MAIN GRID ================= --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- PEMBELIAN TERBARU (2/3) --}}
        <section class="xl:col-span-2 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">

            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Pembelian Terbaru</h2>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Aktivitas procurement terbaru</p>
                </div>
                <span class="text-xs bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-2.5 py-1 rounded-lg font-medium">
                    {{ $recentPurchases->count() }} data
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[680px] text-left">
                    <thead class="border-b border-black/5 dark:border-white/10">
                        <tr class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">
                            <th class="px-5 py-3.5 font-medium">Invoice</th>
                            <th class="px-5 py-3.5 font-medium">Supplier</th>
                            <th class="px-5 py-3.5 font-medium">Tanggal</th>
                            <th class="px-5 py-3.5 text-center font-medium">Item</th>
                            <th class="px-5 py-3.5 font-medium">Status</th>
                            <th class="px-5 py-3.5 text-right font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/10">
                        @forelse ($recentPurchases as $purchase)
                            @php
                                $statusColor = match($purchase->status) {
                                    'received'  => 'bg-lime-100 text-lime-700 dark:bg-lime-900/30 dark:text-lime-400',
                                    'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                    'submitted' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    default     => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
                                <td class="px-5 py-4">
                                    <p class="text-xs font-semibold text-[#171719] dark:text-white font-mono">{{ $purchase->invoice }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-xs text-black/70 dark:text-white/70 truncate max-w-[160px]">
                                        {{ $purchase->supplier?->name ?? '-' }}
                                    </p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-xs text-black/70 dark:text-white/70 font-mono">
                                        {{ $purchase->purchase_date?->format('d/m/Y') }}
                                    </p>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-white/10 text-[11px] font-bold text-[#171719] dark:text-white">
                                        {{ $purchase->items_count }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}">
                                        {{ $purchase->status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <p class="text-xs font-bold text-[#171719] dark:text-white">
                                        Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}
                                    </p>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-black/20 dark:text-white/20" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                                        <path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>
                                    </svg>
                                    <p class="text-sm font-medium text-black/50 dark:text-white/50">Belum ada transaksi pembelian</p>
                                    <p class="mt-1 text-xs text-black/40 dark:text-white/40">PO akan muncul di sini setelah dibuat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- SIDEBAR KANAN (1/3) --}}
        <div class="space-y-5">

            {{-- STATUS PEMBELIAN --}}
            <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Status Pembelian</h3>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Ringkasan seluruh transaksi</p>

                <div class="mt-4 space-y-2">
                    @php
                        $statusList = [
                            'draft'     => ['label' => 'Draft', 'color' => 'gray'],
                            'submitted' => ['label' => 'Menunggu Penerimaan', 'color' => 'amber'],
                            'received'  => ['label' => 'Received', 'color' => 'lime'],
                            'cancelled' => ['label' => 'Cancelled', 'color' => 'red'],
                        ];
                    @endphp

                    @foreach ($statusList as $key => $data)
                        <div class="flex items-center justify-between gap-3 rounded-xl bg-gray-50 dark:bg-white/5 px-3 py-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-lg flex items-center justify-center
                                    {{ $data['color'] === 'gray' ? 'bg-gray-100 dark:bg-white/10 text-gray-600 dark:text-gray-400' : '' }}
                                    {{ $data['color'] === 'amber' ? 'bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400' : '' }}
                                    {{ $data['color'] === 'lime' ? 'bg-lime-100 dark:bg-lime-900/30 text-lime-600 dark:text-lime-400' : '' }}
                                    {{ $data['color'] === 'red' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : '' }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10"/>
                                    </svg>
                                </div>
                                <span class="text-xs text-black/70 dark:text-white/70">{{ $data['label'] }}</span>
                            </div>
                            <span class="text-sm font-bold text-[#171719] dark:text-white">
                                {{ number_format($statusSummary[$key] ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </section>

            {{-- SUPPLIER UTAMA --}}
            <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Supplier Utama</h3>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Berdasarkan nilai pembelian</p>

                <div class="mt-4 space-y-3">
                    @forelse ($supplierPurchases as $item)
                        <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-3">
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-xs font-semibold text-[#171719] dark:text-white truncate">
                                    {{ $item->supplier?->name ?? 'Supplier tidak tersedia' }}
                                </p>
                                <span class="shrink-0 text-[10px] font-medium text-black/45 dark:text-white/45">
                                    {{ $item->transaction_count }} trx
                                </span>
                            </div>
                            <p class="mt-1 text-[11px] font-bold text-lime-600 dark:text-lime-400">
                                Rp {{ number_format((float) $item->total_value, 0, ',', '.') }}
                            </p>
                        </div>
                    @empty
                        <div class="rounded-xl bg-lime-50 dark:bg-lime-900/20 p-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-lime-600 dark:text-lime-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                            </svg>
                            <span class="text-xs font-medium text-lime-700 dark:text-lime-400">Belum ada data supplier</span>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>

    {{-- ================= AGING RECEIVING ================= --}}
    <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
        <div class="flex items-center justify-between gap-3 mb-5">
            <div>
                <h2 class="text-sm font-semibold text-[#171719] dark:text-white">Usia Receiving</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Distribusi PO yang sudah disubmit & menunggu penerimaan</p>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-400 px-2.5 py-1 rounded-lg">
                Aging
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            @foreach ([
                ['label' => 'Hari Ini', 'value' => $receivingAging['today'], 'color' => 'lime'],
                ['label' => '1–3 Hari', 'value' => $receivingAging['1_3_days'], 'color' => 'amber'],
                ['label' => '> 3 Hari', 'value' => $receivingAging['over_3_days'], 'color' => 'red'],
            ] as $age)
                @php
                    $width = $submittedPurchases > 0 ? min(100, ($age['value'] / $submittedPurchases) * 100) : 0;
                @endphp
                <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-4">
                    <div class="flex items-center justify-between gap-3 mb-2">
                        <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">{{ $age['label'] }}</p>
                        <span class="text-lg font-bold text-[#171719] dark:text-white">{{ number_format($age['value'], 0, ',', '.') }}</span>
                    </div>
                    <div class="h-1.5 rounded-full overflow-hidden bg-black/5 dark:bg-white/10">
                        <div class="h-full rounded-full transition-all duration-500
                            {{ $age['color'] === 'lime' ? 'bg-lime-400' : '' }}
                            {{ $age['color'] === 'amber' ? 'bg-amber-400' : '' }}
                            {{ $age['color'] === 'red' ? 'bg-red-400' : '' }}"
                            style="width: {{ $width }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ================= NILAI PROCUREMENT + ALUR ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- NILAI PROCUREMENT --}}
        <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Nilai Procurement</h3>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Pisahkan PO berdasarkan status</p>
                </div>
                <span class="rounded-lg bg-lime-100 dark:bg-lime-900/30 px-2.5 py-1 text-[10px] font-bold text-lime-700 dark:text-lime-400">
                    LIVE
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="rounded-xl bg-gray-50 dark:bg-white/5 p-3">
                    <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Draft</p>
                    <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white truncate">
                        Rp {{ number_format($draftPurchaseValue, 0, ',', '.') }}
                    </p>
                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">{{ $draftPurchases }} trx</p>
                </div>

                <div class="rounded-xl bg-amber-50 dark:bg-amber-900/20 p-3">
                    <p class="text-[10px] font-medium uppercase tracking-wider text-amber-700 dark:text-amber-400">Menunggu</p>
                    <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white truncate">
                        Rp {{ number_format($submittedPurchaseValue, 0, ',', '.') }}
                    </p>
                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">{{ $submittedPurchases }} trx</p>
                </div>

                <div class="rounded-xl bg-lime-50 dark:bg-lime-900/20 p-3">
                    <p class="text-[10px] font-medium uppercase tracking-wider text-lime-700 dark:text-lime-400">Received</p>
                    <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white truncate">
                        Rp {{ number_format($receivedValueToday, 0, ',', '.') }}
                    </p>
                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">{{ $receivedToday }} trx</p>
                </div>
            </div>
        </section>

        {{-- ALUR PROCUREMENT --}}
        <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Alur Procurement</h3>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Prioritas kerja berdasarkan status</p>

            <div class="mt-4 space-y-2">
                @foreach ([
                    ['label' => 'Draft', 'value' => $statusSummary['draft'], 'color' => 'gray'],
                    ['label' => 'Menunggu Penerimaan', 'value' => $statusSummary['submitted'], 'color' => 'amber'],
                    ['label' => 'Received', 'value' => $statusSummary['received'], 'color' => 'lime'],
                    ['label' => 'Cancelled', 'value' => $statusSummary['cancelled'], 'color' => 'red'],
                ] as $step)
                    <div class="flex items-center justify-between gap-3 rounded-xl bg-gray-50 dark:bg-white/5 px-3 py-2.5">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full
                                {{ $step['color'] === 'gray' ? 'bg-gray-400' : '' }}
                                {{ $step['color'] === 'amber' ? 'bg-amber-500' : '' }}
                                {{ $step['color'] === 'lime' ? 'bg-lime-500' : '' }}
                                {{ $step['color'] === 'red' ? 'bg-red-500' : '' }}"></div>
                            <span class="text-xs text-black/70 dark:text-white/70">{{ $step['label'] }}</span>
                        </div>
                        <span class="text-sm font-bold text-[#171719] dark:text-white">
                            {{ number_format($step['value'], 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    {{-- ================= TREN + AKSES CEPAT ================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- TREN PROCUREMENT --}}
        <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <div class="flex items-start justify-between gap-3 mb-4">
                <div>
                    <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Tren Procurement</h3>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Nilai pembelian received 6 bulan terakhir</p>
                </div>
                <span class="text-[10px] font-medium text-black/45 dark:text-white/45">6 bulan</span>
            </div>

            @php($maxTrend = max((float) $procurementTrend->max('value'), 1))
            <div class="flex h-40 items-end gap-2">
                @foreach ($procurementTrend as $point)
                    @php($height = max(8, (int) (($point['value'] / $maxTrend) * 100)))
                    <div class="flex min-w-0 flex-1 flex-col items-center justify-end gap-1.5 group">
                        <span class="text-[9px] font-medium text-black/45 dark:text-white/45 opacity-0 group-hover:opacity-100 transition">
                            Rp {{ number_format($point['value'] / 1000, 0, ',', '.') }}k
                        </span>
                        <div class="w-full rounded-t-lg bg-lime-400 hover:bg-lime-500 transition-all duration-300" style="height: {{ $height }}%"></div>
                        <span class="text-[9px] font-semibold text-black/45 dark:text-white/45">{{ $point['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- AKSES CEPAT --}}
        <section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <div class="mb-4">
                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Akses Cepat</h3>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Jalur kerja utama purchasing</p>
            </div>

            <div class="grid grid-cols-2 gap-3">

                <a href="{{ route('purchasing.purchases.create') }}"
                   class="rounded-xl border border-black/5 dark:border-white/10 p-4 hover:border-lime-400 hover:shadow-md transition group">
                    <div class="w-9 h-9 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path d="M12 5v14"/><path d="M5 12h14"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-[#171719] dark:text-white">Buat PO</p>
                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Transaksi baru</p>
                </a>

                <a href="{{ route('purchasing.purchases.index') }}"
                   class="rounded-xl border border-black/5 dark:border-white/10 p-4 hover:border-lime-400 hover:shadow-md transition group">
                    <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <path d="M14 2v6h6"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-[#171719] dark:text-white">Daftar PO</p>
                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Kelola transaksi</p>
                </a>

                <a href="{{ route('purchasing.suppliers.index') }}"
                   class="rounded-xl border border-black/5 dark:border-white/10 p-4 hover:border-lime-400 hover:shadow-md transition group">
                    <div class="w-9 h-9 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-[#171719] dark:text-white">Supplier</p>
                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Kelola supplier</p>
                </a>

                <a href="{{ route('admin.monitoring.pembelian') }}"
                   class="rounded-xl border border-black/5 dark:border-white/10 p-4 hover:border-lime-400 hover:shadow-md transition group">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M18 20V10"/><path d="M12 20V4"/><path d="M6 20v-6"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold text-[#171719] dark:text-white">Monitoring</p>
                    <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">Pantau status</p>
                </a>
            </div>
        </section>
    </div>

    {{-- ================= FOKUS OPERASIONAL ================= --}}
    <div class="rounded-2xl border-2 border-lime-400 bg-lime-50 dark:bg-lime-900/20 p-5">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-lime-400 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-[#171719]" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-medium uppercase tracking-wider text-lime-700 dark:text-lime-400">Fokus Operasional</p>
                    <h3 class="mt-0.5 text-sm font-bold text-[#171719] dark:text-white">Prioritas Hari Ini</h3>
                    <p class="mt-0.5 text-[11px] text-black/60 dark:text-white/60">
                        Prioritaskan <strong>{{ $submittedPurchases }} PO</strong> sudah dikirim ke gudang & <strong>{{ $draftPurchases }} draft</strong> belum disubmit.
                    </p>
                </div>
            </div>

            <a href="{{ route('purchasing.purchases.index') }}"
               class="h-10 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-bold flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                </svg>
                Buka Purchase Order
            </a>
        </div>
    </div>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
