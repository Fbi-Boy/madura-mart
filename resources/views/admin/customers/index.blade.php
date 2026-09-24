<x-app-layout>
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div><p class="text-xs font-semibold uppercase tracking-wide text-black/40">Master Data</p><h1 class="mt-1 text-2xl font-bold">Customer</h1><p class="mt-1 text-sm text-black/50">Kelola data customer Madura Mart.</p></div>
            <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center justify-center rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white">Tambah Customer</a>
        </div>
        @if(session('success')) <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div> @endif
        <div class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm">
            <div class="overflow-x-auto"><table class="min-w-full text-left text-sm">
                <thead class="border-b border-black/5 bg-black/[0.02]"><tr><th class="px-5 py-3">Kode</th><th class="px-5 py-3">Customer</th><th class="px-5 py-3">Kontak</th><th class="px-5 py-3">Kota</th><th class="px-5 py-3">Status</th><th class="px-5 py-3 text-right">Aksi</th></tr></thead>
                <tbody class="divide-y divide-black/5">
                @forelse($customers as $customer)
                    <tr><td class="px-5 py-4 font-medium">{{ $customer->code }}</td><td class="px-5 py-4"><div class="font-medium">{{ $customer->name }}</div><div class="text-xs text-black/45">{{ $customer->email ?: '-' }}</div></td><td class="px-5 py-4">{{ $customer->phone ?: '-' }}</td><td class="px-5 py-4">{{ $customer->city ?: '-' }}</td><td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $customer->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $customer->is_active ? 'Aktif' : 'Nonaktif' }}</span></td><td class="px-5 py-4 text-right"><div class="flex justify-end gap-2"><a href="{{ route('admin.customers.edit', $customer) }}" class="rounded-lg border border-black/10 px-3 py-1.5 text-xs font-semibold">Edit</a><form method="POST" action="{{ route('admin.customers.destroy', $customer) }}" onsubmit="return confirm('Hapus customer ini?')">@csrf @method('DELETE')<button class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600">Hapus</button></form></div></td></tr>
                @empty <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-black/45">Belum ada customer.</td></tr> @endforelse
                </tbody>
            </table></div>
            @if($customers->hasPages()) <div class="border-t border-black/5 px-5 py-4">{{ $customers->links() }}</div> @endif
        </div>
    </div>
</x-app-layout>