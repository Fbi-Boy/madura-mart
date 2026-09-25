<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Supplier</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Directory supplier aktif untuk kebutuhan procurement.</p>
            </div>
            <a href="{{ route('purchasing.purchases.create') }}" class="rounded-xl bg-[#A8F23A] px-4 py-2.5 text-xs font-semibold text-gray-900 transition hover:brightness-95">
                Buat PO
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-5 px-4 sm:px-6 lg:px-8">
            <form method="GET" class="flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row">
                <input name="search" value="{{ $search }}" placeholder="Cari kode, nama, atau contact person..." class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <button class="rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white dark:bg-white dark:text-gray-900">Cari</button>
                @if ($search !== '')
                    <a href="{{ route('purchasing.suppliers.index') }}" class="rounded-xl border border-gray-200 px-5 py-2.5 text-center text-sm font-semibold text-gray-700 dark:border-gray-600 dark:text-gray-200">Reset</a>
                @endif
            </form>

            <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-gray-700 dark:text-gray-500">
                            <tr>
                                <th class="px-5 py-4">Kode</th>
                                <th class="px-5 py-4">Supplier</th>
                                <th class="px-5 py-4">Contact</th>
                                <th class="px-5 py-4">Telepon</th>
                                <th class="px-5 py-4">Email</th>
                                <th class="px-5 py-4">Lokasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($suppliers as $supplier)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <td class="px-5 py-4 font-medium text-gray-700 dark:text-gray-200">{{ $supplier->code }}</td>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $supplier->name }}</p>
                                        <p class="mt-1 max-w-xs truncate text-xs text-gray-400">{{ $supplier->address ?: 'Alamat belum diisi' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $supplier->contact_person ?: '-' }}</td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $supplier->phone ?: '-' }}</td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ $supplier->email ?: '-' }}</td>
                                    <td class="px-5 py-4 text-gray-600 dark:text-gray-300">{{ collect([$supplier->city])->filter()->implode(', ') ?: '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">Supplier aktif tidak ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($suppliers->hasPages())
                    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700">{{ $suppliers->links() }}</div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>
