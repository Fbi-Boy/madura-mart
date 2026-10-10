<x-app-layout>
<div class="space-y-5 font-poppins">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Kurir</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Detail Pengiriman</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Periksa detail pesanan sebelum update status</p>
        </div>

        <a href="{{ route('kurir.pengiriman.index') }}"
           class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- INFO PESANAN --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">No. Pesanan</p>
            <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white font-mono">{{ $order->order_number }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Tanggal</p>
            <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white font-mono">
                {{ $order->order_date?->format('d/m/Y H:i') }}
            </p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Total</p>
            <p class="mt-1 text-sm font-bold text-lime-600 dark:text-lime-400">
                Rp {{ number_format($order->total, 0, ',', '.') }}
            </p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-4 dark:border-white/10 dark:bg-white/5">
            <p class="text-[10px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">Status</p>
            <p class="mt-1 text-sm font-bold text-[#171719] dark:text-white capitalize">{{ $order->status }}</p>
        </div>
    </div>

    {{-- INFO CUSTOMER + ALAMAT --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <h3 class="text-sm font-semibold text-[#171719] dark:text-white mb-4">Customer</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Nama</p>
                    <p class="mt-0.5 font-semibold text-[#171719] dark:text-white">{{ $order->customer?->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Telepon</p>
                    <p class="mt-0.5 font-semibold text-[#171719] dark:text-white font-mono">{{ $order->customer?->phone ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 p-5">
            <h3 class="text-sm font-semibold text-[#171719] dark:text-white mb-4">Alamat Pengiriman</h3>
            <p class="text-sm text-black/70 dark:text-white/70">{{ $order->delivery_address }}</p>
            @if($order->notes)
                <div class="mt-3 pt-3 border-t border-dashed border-black/5 dark:border-white/10">
                    <p class="text-[10px] uppercase tracking-wider text-black/45 dark:text-white/45">Catatan</p>
                    <p class="mt-1 text-xs text-black/70 dark:text-white/70 italic">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- UPDATE STATUS --}}
    <div class="rounded-2xl border-2 border-lime-400 bg-lime-50 dark:bg-lime-900/20 p-5">
        <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Update Status Pengiriman</h3>
        <p class="mt-0.5 text-[11px] text-black/60 dark:text-white/60">Pilih status berikutnya sesuai kondisi di lapangan</p>

        <div class="mt-4 flex flex-wrap gap-2">
            @php
                $statuses = [
                    'processing' => ['label' => 'Diproses',  'color' => 'purple'],
                    'shipped'    => ['label' => 'Dikirim',   'color' => 'blue'],
                    'delivered'  => ['label' => 'Selesai',   'color' => 'lime'],
                    'failed'     => ['label' => 'Gagal',     'color' => 'red'],
                ];
            @endphp

            @foreach($statuses as $key => $data)
                <form method="POST" action="{{ route('kurir.pengiriman.status', $order) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="{{ $key }}">
                    <button type="submit"
                        class="h-10 px-4 rounded-xl text-sm font-bold flex items-center gap-2 transition
                            {{ $order->status === $key
                                ? 'bg-lime-400 text-[#171719] ring-2 ring-lime-500 ring-offset-2 dark:ring-offset-gray-900'
                                : 'bg-white dark:bg-white/10 hover:bg-gray-100 dark:hover:bg-white/20 text-[#171719] dark:text-white border border-black/10 dark:border-white/10' }}">
                        {{ $data['label'] }}
                    </button>
                </form>
            @endforeach
        </div>
    </div>

    {{-- RIWAYAT STATUS --}}
    @if($order->statusHistories && $order->statusHistories->count())
        <div class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 overflow-hidden">
            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Riwayat Status</h3>
            </div>
            <div class="divide-y divide-black/5 dark:divide-white/10">
                @foreach($order->statusHistories as $history)
                    <div class="px-5 py-3 flex items-start gap-3">
                        <div class="mt-1.5 w-2 h-2 rounded-full bg-lime-500 shrink-0"></div>
                        <div class="flex-1">
                            <p class="text-xs text-black/70 dark:text-white/70">
                                <span class="font-semibold capitalize">{{ $history->to_status }}</span>
                                @if($history->from_status)
                                    <span class="text-black/45 dark:text-white/45"> (dari {{ $history->from_status }})</span>
                                @endif
                            </p>
                            @if($history->note)
                                <p class="mt-0.5 text-[11px] text-black/50 dark:text-white/50 italic">{{ $history->note }}</p>
                            @endif
                            <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45 font-mono">
                                {{ $history->created_at?->format('d/m/Y H:i') }} · {{ $history->user?->name ?? 'Sistem' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
