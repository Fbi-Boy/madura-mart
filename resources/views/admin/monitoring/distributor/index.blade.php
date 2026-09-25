<x-app-layout>
<div class="space-y-5">
    <div><p class="text-sm text-black/45">Monitoring</p><h2 class="mt-1 text-2xl font-semibold">Monitoring Distributor</h2><p class="mt-1 text-sm text-black/40">Pantau data distributor dan status kerja sama.</p></div>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Total Distributor</p><p class="mt-2 text-2xl font-semibold">{{ $totalDistributors }}</p></div>
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Distributor Aktif</p><p class="mt-2 text-2xl font-semibold">{{ $activeDistributors }}</p></div>
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Distributor Nonaktif</p><p class="mt-2 text-2xl font-semibold">{{ $inactiveDistributors }}</p></div>
    </div>
    <form method="GET" class="flex gap-3 rounded-2xl border bg-white p-5">
        <input name="search" value="{{ $search }}" placeholder="Cari kode, distributor, atau contact person..." class="w-full rounded-xl border px-3 py-2.5">
        <button class="rounded-xl bg-[#171719] px-5 py-2.5 font-semibold text-white">Cari</button>
    </form>
    <div class="overflow-hidden rounded-2xl border bg-white">
        <div class="overflow-x-auto"><table class="w-full text-left text-sm">
            <thead class="border-b text-xs uppercase text-black/45"><tr>
                <th class="px-5 py-4">Kode</th><th class="px-5 py-4">Distributor</th><th class="px-5 py-4">Contact Person</th><th class="px-5 py-4">Kota</th><th class="px-5 py-4">Status</th>
            </tr></thead>
            <tbody class="divide-y">
            @forelse($distributors as $distributor)
                <tr>
                    <td class="px-5 py-4 font-medium">{{ $distributor->code }}</td>
                    <td class="px-5 py-4">{{ $distributor->name }}</td>
                    <td class="px-5 py-4">{{ $distributor->contact_person ?: '-' }}</td>
                    <td class="px-5 py-4">{{ $distributor->city ?: '-' }}</td>
                    <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $distributor->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $distributor->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-12 text-center text-black/40">Belum ada distributor.</td></tr>
            @endforelse
            </tbody>
        </table></div>
        @if($distributors->hasPages())<div class="border-t px-5 py-4">{{ $distributors->links() }}</div>@endif
    </div>
</div>
</x-app-layout>