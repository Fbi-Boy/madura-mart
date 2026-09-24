<x-app-layout>
<div class="space-y-5">
    <div><p class="text-sm text-black/45 dark:text-white/45">Report</p><h2 class="mt-1 text-2xl font-semibold">Laporan Penjualan</h2></div>
    <form method="GET" class="grid grid-cols-1 gap-3 rounded-2xl border border-black/5 bg-white p-5 sm:grid-cols-3">
        <div><label class="text-sm font-medium">Dari</label><input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="mt-2 w-full rounded-xl border px-3 py-2.5"></div>
        <div><label class="text-sm font-medium">Sampai</label><input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="mt-2 w-full rounded-xl border px-3 py-2.5"></div>
        <div class="flex items-end"><button class="w-full rounded-xl bg-[#171719] px-4 py-2.5 font-semibold text-white">Filter</button></div>
    </form>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Total Transaksi</p><p class="mt-2 text-2xl font-semibold">{{ $totalTransactions }}</p></div>
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Total Penjualan</p><p class="mt-2 text-2xl font-semibold">Rp {{ number_format($totalSales,0,',','.') }}</p></div>
    </div>
    <div class="overflow-hidden rounded-2xl border bg-white">
        <div class="overflow-x-auto"><table class="w-full text-left text-sm"><thead class="border-b text-xs uppercase text-black/45"><tr><th class="px-5 py-4">Invoice</th><th class="px-5 py-4">Tanggal</th><th class="px-5 py-4">Kasir</th><th class="px-5 py-4">Pembayaran</th><th class="px-5 py-4 text-right">Total</th></tr></thead>
        <tbody class="divide-y">@forelse($sales as $sale)<tr><td class="px-5 py-4 font-medium">{{ $sale->invoice }}</td><td class="px-5 py-4">{{ $sale->sale_date->format('d/m/Y H:i') }}</td><td class="px-5 py-4">{{ $sale->user->name }}</td><td class="px-5 py-4 uppercase">{{ $sale->payment_method }}</td><td class="px-5 py-4 text-right font-semibold">Rp {{ number_format((float)$sale->total,0,',','.') }}</td></tr>@empty<tr><td colspan="5" class="px-5 py-12 text-center text-black/40">Tidak ada transaksi pada periode ini.</td></tr>@endforelse</tbody></table></div>
        @if($sales->hasPages())<div class="border-t px-5 py-4">{{ $sales->links() }}</div>@endif
    </div>
</div>
</x-app-layout>