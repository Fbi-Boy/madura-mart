<x-app-layout>
<div class="space-y-5">
    <div class="flex items-end justify-between gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-black/40">Transaksi</p>
            <h1 class="mt-1 text-2xl font-bold">Pembelian</h1>
            <p class="mt-1 text-sm text-black/50">{{ auth()->user()->role === 'purchasing' ? 'Kelola purchase order dan siapkan pengadaan supplier.' : 'Catat barang masuk dari supplier dan pembaruan stok.' }}</p>
        </div>
        <a href="{{ route(auth()->user()->role === 'purchasing' ? 'purchasing.purchases.create' : 'admin.purchases.create') }}" class="rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white">+ {{ auth()->user()->role === 'purchasing' ? 'Purchase Order' : 'Pembelian Baru' }}</a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    <form method="GET" class="grid gap-3 rounded-2xl border border-black/5 bg-white p-4 shadow-sm md:grid-cols-2 xl:grid-cols-5">
        <div class="xl:col-span-2">
            <label for="purchase-search" class="text-xs font-semibold uppercase tracking-wide text-black/40">Cari</label>
            <input id="purchase-search" name="q" value="{{ request('q') }}" placeholder="Invoice atau nama supplier" class="mt-1.5 w-full rounded-xl border-black/10 text-sm">
        </div>
        <div>
            <label for="purchase-status" class="text-xs font-semibold uppercase tracking-wide text-black/40">Status</label>
            <select id="purchase-status" name="status" class="mt-1.5 w-full rounded-xl border-black/10 text-sm">
                <option value="">Semua status</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                <option value="submitted" @selected(request('status') === 'submitted')>Submitted</option>
                <option value="received" @selected(request('status') === 'received')>Received</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
            </select>
        </div>
        <div>
            <label for="purchase-date-from" class="text-xs font-semibold uppercase tracking-wide text-black/40">Dari</label>
            <input id="purchase-date-from" type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1.5 w-full rounded-xl border-black/10 text-sm">
        </div>
        <div>
            <label for="purchase-date-to" class="text-xs font-semibold uppercase tracking-wide text-black/40">Sampai</label>
            <input id="purchase-date-to" type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1.5 w-full rounded-xl border-black/10 text-sm">
        </div>
        <div class="flex items-end gap-2 md:col-span-2 xl:col-span-5">
            <button class="rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white">Terapkan Filter</button>
            <a href="{{ route(auth()->user()->role === 'purchasing' ? 'purchasing.purchases.index' : 'admin.purchases.index') }}" class="rounded-xl border border-black/10 px-4 py-2.5 text-sm font-semibold">Reset</a>
            <span class="ml-auto self-center text-xs text-black/40">{{ $purchases->total() }} hasil</span>
        </div>
    </form>

    <div class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="border-b border-black/5 bg-black/[0.02]">
                    <tr>
                        <th class="px-5 py-3">Invoice</th>
                        <th class="px-5 py-3">Supplier</th>
                        <th class="px-5 py-3">Tanggal</th>
                        <th class="px-5 py-3">Input Oleh</th>
                        <th class="px-5 py-3">Total</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5">
                    @forelse($purchases as $purchase)
                        @php($isSubmitted = $purchase->submitted_at !== null)
                        <tr>
                            <td class="px-5 py-4"><a href="{{ route(auth()->user()->role === 'purchasing' ? 'purchasing.purchases.show' : 'admin.purchases.show', $purchase) }}" class="font-semibold hover:underline">{{ $purchase->invoice }}</a></td>
                            <td class="px-5 py-4">{{ $purchase->supplier->name }}</td>
                            <td class="px-5 py-4">{{ $purchase->purchase_date->format('d/m/Y') }}</td>
                            <td class="px-5 py-4">{{ $purchase->user->name }}</td>
                            <td class="px-5 py-4">Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold @if($purchase->status === 'received') bg-green-100 text-green-700 @elseif($purchase->status === 'cancelled') bg-red-100 text-red-700 @elseif($isSubmitted) bg-blue-100 text-blue-700 @else bg-yellow-100 text-yellow-700 @endif">
                                        {{ $isSubmitted ? 'Submitted' : ucfirst($purchase->status) }}
                                    </span>

                                    @if(auth()->user()->role === 'purchasing' && $purchase->status === 'draft' && !$isSubmitted)
                                        <form method="POST" action="{{ route('purchasing.purchases.submit', $purchase) }}" onsubmit="return confirm('Kirim purchase order ini ke gudang?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-lg bg-[#A8F23A] px-2.5 py-1 text-xs font-semibold text-gray-900 transition hover:opacity-80">Kirim</button>
                                        </form>
                                        <form method="POST" action="{{ route('purchasing.purchases.cancel', $purchase) }}" onsubmit="return confirm('Batalkan purchase order ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-lg border border-red-200 px-2.5 py-1 text-xs font-semibold text-red-600 transition hover:bg-red-50">Batalkan</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-10 text-center text-black/45">Belum ada transaksi pembelian.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($purchases->hasPages())
            <div class="border-t border-black/5 px-5 py-4">{{ $purchases->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>
