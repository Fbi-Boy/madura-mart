<x-app-layout>
<div class="space-y-5 font-poppins max-w-3xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Master Data</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Edit Supplier</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45 font-mono">{{ $supplier->code }}</p>
        </div>

        <a href="{{ route('admin.suppliers.index') }}"
           class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
            </svg>
            Kembali
        </a>
    </div>

    {{-- ALERT ERROR --}}
    @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 dark:border-red-500/20 dark:bg-red-500/10 px-4 py-3">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800 dark:text-red-300">Periksa input supplier</p>
                    <ul class="mt-1 list-disc space-y-0.5 pl-5 text-xs text-red-700 dark:text-red-200">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- FORM --}}
    <form method="POST" action="{{ route('admin.suppliers.update', $supplier) }}"
          class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">
        @csrf
        @method('PUT')

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="m4 20 4-1 10-10-3-3L5 16l-1 4Z"/>
                    <path d="m13 7 3 3"/>
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-[#171719] dark:text-white">Edit Informasi Supplier</h2>
                <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Perbarui data supplier</p>
            </div>
        </div>

        <div class="p-5">
            @include('admin.suppliers._form', ['supplier' => $supplier, 'submitLabel' => 'Perbarui Supplier'])
        </div>
    </form>

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
