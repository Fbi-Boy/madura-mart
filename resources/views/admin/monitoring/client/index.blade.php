<x-app-layout>
<div class="space-y-5">
    <div><p class="text-sm text-black/45">Monitoring</p><h2 class="mt-1 text-2xl font-semibold">Monitoring Client</h2><p class="mt-1 text-sm text-black/40">Pantau data pelanggan dan status akun client.</p></div>
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Total Client</p><p class="mt-2 text-2xl font-semibold">{{ $totalClients }}</p></div>
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Client Aktif</p><p class="mt-2 text-2xl font-semibold">{{ $activeClients }}</p></div>
        <div class="rounded-2xl border bg-white p-5"><p class="text-sm text-black/45">Client Nonaktif</p><p class="mt-2 text-2xl font-semibold">{{ $inactiveClients }}</p></div>
    </div>
    <form method="GET" class="flex gap-3 rounded-2xl border bg-white p-5">
        <input name="search" value="{{ $search }}" placeholder="Cari kode, nama, atau nomor HP..." class="w-full rounded-xl border px-3 py-2.5">
        <button class="rounded-xl bg-[#171719] px-5 py-2.5 font-semibold text-white">Cari</button>
    </form>
    <div class="overflow-hidden rounded-2xl border bg-white">
        <div class="overflow-x-auto"><table class="w-full text-left text-sm">
            <thead class="border-b text-xs uppercase text-black/45"><tr>
                <th class="px-5 py-4">Kode</th><th class="px-5 py-4">Nama</th><th class="px-5 py-4">Telepon</th><th class="px-5 py-4">Kota</th><th class="px-5 py-4">Status</th>
            </tr></thead>
            <tbody class="divide-y">
            @forelse($clients as $client)
                <tr>
                    <td class="px-5 py-4 font-medium">{{ $client->code }}</td>
                    <td class="px-5 py-4">{{ $client->name }}</td>
                    <td class="px-5 py-4">{{ $client->phone ?: '-' }}</td>
                    <td class="px-5 py-4">{{ $client->city ?: '-' }}</td>
                    <td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $client->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $client->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-12 text-center text-black/40">Belum ada client.</td></tr>
            @endforelse
            </tbody>
        </table></div>
        @if($clients->hasPages())<div class="border-t px-5 py-4">{{ $clients->links() }}</div>@endif
    </div>
</div>
</x-app-layout>