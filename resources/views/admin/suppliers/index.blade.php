<x-app-layout>
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-black/40 dark:text-white/40">Master Data</p>
                <h1 class="mt-1 text-2xl font-bold">Supplier</h1>
                <p class="mt-1 text-sm text-black/50 dark:text-white/50">Kelola data pemasok yang digunakan pada proses pembelian Madura Mart.</p>
            </div>

            <a href="{{ route('admin.suppliers.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90 dark:bg-white dark:text-[#171719]">
                Tambah Supplier
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-black/5 bg-white shadow-sm dark:border-white/10 dark:bg-white/[0.04]">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-black/5 bg-black/[0.02] dark:border-white/10 dark:bg-white/[0.03]">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Kode</th>
                            <th class="px-5 py-3 font-semibold">Supplier</th>
                            <th class="px-5 py-3 font-semibold">Kontak</th>
                            <th class="px-5 py-3 font-semibold">Kota</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/5 dark:divide-white/10">
                        @forelse ($suppliers as $supplier)
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
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $supplier->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $supplier->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="rounded-lg border border-black/10 px-3 py-1.5 text-xs font-semibold hover:bg-black/[0.03] dark:border-white/10 dark:hover:bg-white/[0.05]">Edit</a>
                                        <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}" onsubmit="return confirm('Hapus supplier ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-10 text-center text-sm text-black/45">Belum ada supplier.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($suppliers->hasPages())
                <div class="border-t border-black/5 px-5 py-4 dark:border-white/10">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
