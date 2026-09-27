<x-app-layout>
<div class="space-y-5">
    <div>
        <p class="text-sm text-black/45 dark:text-white/45">Report</p>
        <h2 class="mt-1 text-2xl font-semibold text-[#171719] dark:text-white">Laporan Pelanggan</h2>
        <p class="mt-1 text-sm text-black/45 dark:text-white/45">Aktivitas dan nilai belanja customer pada periode terpilih.</p>
    </div>

    <form method="GET" class="grid gap-3 rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5 sm:grid-cols-3">
        <div><label class="text-sm font-medium">Dari</label><input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="mt-2 w-full rounded-xl border px-3 py-2.5"></div>
        <div><label class="text-sm font-medium">Sampai</label><input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="mt-2 w-full rounded-xl border px-3 py-2.5"></div>
        <div class="flex items-end"><button class="w-full rounded-xl bg-[#171719] px-4 py-2.5 font-semibold text-white">Filter Periode</button></div>
    </form>

    <div class="grid gap-3 sm:grid-cols-3">
        <div class="rounded-2xl border bg-white p-5 dark:border-white/10 dark:bg-white/5"><p class="text-sm text-black/45">Customer Terdaftar</p><p class="mt-2 text-2xl font-semibold">{{ number_format($totalCustomers,0,',','.') }}</p></div>
        <div class="rounded-2xl border bg-white p-5 dark:border-white/10 dark:bg-white/5"><p class="text-sm text-black/45">Customer Baru</p><p class="mt-2 text-2xl font-semibold">{{ number_format($periodNewCustomers,0,',','.') }}</p></div>
        <div class="rounded-2xl border border-[#A8F23A]/30 bg-[#A8F23A]/10 p-5"><p class="text-sm text-black/45">Belanja Customer</p><p class="mt-2 text-2xl font-semibold">Rp {{ number_format($periodRevenue,0,',','.') }}</p></div>
    </div>

    <section class="overflow-hidden rounded-2xl border bg-white dark:border-white/10 dark:bg-white/5">
        <div class="border-b px-5 py-4 dark:border-white/10">
            <h3 class="font-semibold">Aktivitas Customer</h3>
            <p class="mt-1 text-xs text-black/45">{{ $from->format('d/m/Y') }} — {{ $to->format('d/m/Y') }}</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full min-w-[680px] text-left text-sm">
                <thead class="border-b text-xs uppercase text-black/40 dark:border-white/10"><tr><th class="px-5 py-4">Customer</th><th class="px-5 py-4">Kontak</th><th class="px-5 py-4 text-right">Transaksi</th><th class="px-5 py-4 text-right">Total Belanja</th><th class="px-5 py-4">Status</th></tr></thead>
                <tbody class="divide-y dark:divide-white/10">
                @forelse($customers as $customer)
                    <tr>
                        <td class="px-5 py-4 font-medium">{{ $customer->name }}<span class="mt-1 block text-xs text-black/40">{{ $customer->code }}</span></td>
                        <td class="px-5 py-4">{{ $customer->phone ?: ($customer->email ?: '-') }}</td>
                        <td class="px-5 py-4 text-right">{{ number_format((int)$customer->transaction_count,0,',','.') }}</td>
                        <td class="px-5 py-4 text-right font-semibold">Rp {{ number_format((float)$customer->total_spent,0,',','.') }}</td>
                        <td class="px-5 py-4">{{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-black/40">Belum ada customer.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($customers->hasPages())<div class="border-t px-5 py-4 dark:border-white/10">{{ $customers->links() }}</div>@endif
    </section>
</div>
</x-app-layout>
