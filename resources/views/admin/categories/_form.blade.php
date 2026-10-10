<div class="space-y-5">

    {{-- NAMA --}}
    <div>
        <label for="name" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
            Nama Kategori <span class="text-red-500">*</span>
        </label>
        <input id="name" name="name" value="{{ old('name', $category?->name) }}"
            required maxlength="100"
            placeholder="Contoh: Sembako"
            class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
        @error('name')
            <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- DESKRIPSI --}}
    <div>
        <label for="description" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
            Deskripsi
        </label>
        <textarea id="description" name="description" rows="4" maxlength="1000"
            placeholder="Deskripsi singkat kategori"
            class="mt-2 w-full rounded-xl border border-black/10 dark:border-white/10 bg-transparent px-4 py-3 text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 resize-none">{{ old('description', $category?->description) }}</textarea>
        @error('description')
            <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- STATUS AKTIF --}}
    <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl border-2 transition
        {{ old('is_active', $category?->is_active ?? true) ? 'border-lime-400 bg-lime-50 dark:bg-lime-900/20' : 'border-black/5 dark:border-white/10 hover:border-lime-300' }}">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1"
            @checked(old('is_active', $category?->is_active ?? true))
            class="w-4 h-4 rounded border-gray-300 text-lime-500 focus:ring-lime-400">
        <div class="flex-1">
            <p class="text-xs font-bold text-[#171719] dark:text-white">Kategori Aktif</p>
            <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">
                Kategori aktif akan tampil di katalog produk
            </p>
        </div>
    </label>

</div>

{{-- FOOTER ACTIONS --}}
<div class="mt-5 pt-5 border-t border-black/5 dark:border-white/10 flex flex-wrap items-center justify-end gap-2">
    <a href="{{ route('admin.categories.index') }}"
       class="h-11 px-5 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
        Batal
    </a>

    <button type="submit"
        class="h-11 px-6 rounded-xl bg-gradient-to-r from-lime-400 to-lime-500 hover:from-lime-500 hover:to-lime-600 text-[#171719] text-sm font-black flex items-center gap-2 transition shadow-md hover:shadow-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M20 6 9 17l-5-5"/>
        </svg>
        {{ $submitLabel }}
    </button>
</div>
