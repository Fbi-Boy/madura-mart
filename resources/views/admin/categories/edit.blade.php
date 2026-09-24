<x-app-layout>
    <div class="mx-auto max-w-2xl space-y-5">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-black/40 dark:text-white/40">Master Data</p>
            <h1 class="mt-1 text-2xl font-bold">Edit Kategori</h1>
        </div>

        <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="space-y-5 rounded-2xl border border-black/5 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/[0.04]">
            @csrf
            @method('PUT')
            @include('admin.categories._form', ['category' => $category, 'submitLabel' => 'Perbarui'])
        </form>
    </div>
</x-app-layout>
