<x-app-layout>
<div class="space-y-5">
    <div class="flex items-end justify-between gap-3">
        <div><p class="text-xs font-semibold uppercase tracking-wide text-black/40">Transaksi</p><h1 class="mt-1 text-2xl font-bold">Pembelian</h1><p class="mt-1 text-sm text-black/50">Catat barang masuk dari supplier dan pembaruan stok.</p></div>
        <a href="{{ route('admin.purchases.create') }}" class="rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white">+ Pembelian Baru</a>
    </div>
    @if(session('success'))<div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>@endif
    <div class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm">
        <div class="overflow-x-auto"><table class="min-w-full text-left text-sm"><thead class="border-b border-black/5 bg-black/[0.02]"><tr><th class="px-5 py-3">Invoice</th><th class="px-5 py-3">Supplier</th><th class="px-5 py-3">Tanggal</th><th class="px-5 py-3">Input Oleh</th><th class="px-5 py-3">Total</th><th class="px-5 py-3">Status</th></tr></thead>
        <tbody class="divide-y divide-black/5">@forelse($purchases as $purchase)<tr><td class="px-5 py-4 font-semibold">{{ $purchase->invoice }}</td><td class="px-5 py-4">{{ $purchase->supplier->name }}</td><td class="px-5 py-4">{{ $purchase->purchase_date->format('d/m/Y') }}</td><td class="px-5 py-4">{{ $purchase->user->name }}</td><td class="px-5 py-4">Rp {{ number_format($purchase->total,0,',','.') }}</td><td class="px-5 py-4"><span class="rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">{{ ucfirst($purchase->status) }}</span></td></tr>@empty<tr><td colspan="6" class="px-5 py-10 text-center text-black/45">Belum ada transaksi pembelian.</td></tr>@endforelse</tbody></table></div>
        @if($purchases->hasPages())<div class="border-t border-black/5 px-5 py-4">{{ $purchases->links() }}</div>@endif
    </div>
</div>
</x-app-layout>