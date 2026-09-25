<x-app-layout>
<div class="space-y-5">
    <div>
        <p class="text-sm text-black/45 dark:text-white/45">Monitoring</p>
        <h2 class="mt-1 text-2xl font-semibold text-[#171719] dark:text-white">Monitoring Supplier</h2>
        <p class="mt-1 text-sm text-black/40 dark:text-white/40">Pantau pemasok, kontak, wilayah, dan status supplier.</p>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div class="rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <p class="text-sm text-black/45 dark:text-white/45">Total Supplier</p>
            <p class="mt-2 text-2xl font-semibold">{{ $totalSuppliers }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <p class="text-sm text-black/45 dark:text-white/45">Supplier Aktif</p>
            <p class="mt-2 text-2xl font-semibold">{{ $activeSuppliers }}</p>
        </div>
        <div class="rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
            <p class="text-sm text-black/45 dark:text-white/45">Supplier Nonaktif</p>
            <p class="mt-2 text-2xl font-semibold">{{ $inactiveSuppliers }}</p>
        </div>
    </div>

    <form method="GET" class="flex gap-3 rounded-2xl border border-black/5 bg-white p-5 dark:border-white/10 dark:bg-white/5">
        <input name="search" value="{{ $search }}" placeholder="Cari kode, supplier, kontak, atau kota..."
               class="w-full rounded-xl border border-black/10 bg-transparent px-3 py-2.5 text-sm outline-none focus:border-black/30 dark:border-white/10">
        <button class="rounded-xl bg-[#171719] px-5 py-2.5 text-sm font-semibold text-white">Cari</button>
    </form>

    <div class="overflow-hidden rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-black/5 text-xs uppercase text-black/45 dark:border-white/10 dark:text-white/45">
                    <tr>
                        <th class="px-5 py-4">Kode</th>
                        <th class="px-5 py-4">Supplier</th>
                        <th class="px-5 py-4">Kontak</th>
                        <th class="px-5 py-4">Kota</th>
                        <th class="px-5 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black/5 dark:divide-white/10">
                @forelse($suppliers as $supplier)
                    <tr>
                        <td class="px-5 py-4 font-medium">{{ $supplier->code }}</td>
                        <td class="px-5 py-4">
                            <div class="font-medium">{{ $supplier->name }}</div>
                            <div class="text-xs text-black/45 dark:text-white/45">{{ $supplier->email ?: '-' }}</div>
                        </td>
                        <td class="px-5 py-4 text-black/60 dark:text-white/60">
                            {{ $supplier->contact_person ?: '-' }}
                            <div class="text-xs">{{ $supplier->phone ?: '-' }}</div>
                        </td>
                        <td class="px-5 py-4">{{ $supplier->city ?: '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $supplier->is_active ? 'bg-green-50 text-green-700 dark:bg-green-400/10 dark:text-green-300' : 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-white/60' }}">
                                {{ $supplier->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-black/40 dark:text-white/40">Belum ada supplier.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($suppliers->hasPages())
            <div class="border-t border-black/5 px-5 py-4 dark:border-white/10">{{ $suppliers->links() }}</div>
        @endif
    </div>
</div>
</x-app-layout>