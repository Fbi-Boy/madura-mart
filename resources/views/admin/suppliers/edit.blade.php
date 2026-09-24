<x-app-layout>
    <div class="max-w-3xl space-y-5">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-black/40 dark:text-white/40">Master Data</p>
            <h1 class="mt-1 text-2xl font-bold">Edit Supplier</h1>
            <p class="mt-1 text-sm text-black/50 dark:text-white/50">Perbarui informasi supplier yang dipilih.</p>
        </div>

        <div class="rounded-2xl border border-black/5 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/[0.04]">
            <form method="POST" action="{{ route('admin.suppliers.update', $supplier) }}" class="space-y-5">
                @csrf
                @method('PUT')
                @include('admin.suppliers._form', ['submitLabel' => 'Simpan Perubahan'])
            </form>
        </div>
    </div>
</x-app-layout>
