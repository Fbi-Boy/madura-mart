<div>
    <label for="name" class="text-sm font-semibold">Nama Kategori</label>
    <input id="name" name="name" value="{{ old('name', $category?->name) }}" required maxlength="100"
           class="mt-2 w-full rounded-xl border-black/10 bg-transparent text-sm focus:border-black focus:ring-black dark:border-white/10"
           placeholder="Contoh: Sembako">
    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="description" class="text-sm font-semibold">Deskripsi</label>
    <textarea id="description" name="description" rows="4" maxlength="1000"
              class="mt-2 w-full rounded-xl border-black/10 bg-transparent text-sm focus:border-black focus:ring-black dark:border-white/10"
              placeholder="Deskripsi singkat kategori">{{ old('description', $category?->description) }}</textarea>
    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

<label class="flex items-center gap-2 text-sm">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category?->is_active ?? true))
           class="rounded border-black/20">
    <span>Aktif</span>
</label>

<div class="flex items-center justify-end gap-2">
    <a href="{{ route('admin.categories.index') }}" class="rounded-xl border border-black/10 px-4 py-2.5 text-sm font-semibold dark:border-white/10">Batal</a>
    <button class="rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white dark:bg-white dark:text-[#171719]">{{ $submitLabel }}</button>
</div>
