<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="category_id" class="text-sm font-semibold">Kategori</label>
        <select id="category_id" name="category_id" required class="mt-2 w-full rounded-xl border-black/10 bg-transparent text-sm focus:border-black focus:ring-black dark:border-white/10">
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="sku" class="text-sm font-semibold">SKU</label>
        <input id="sku" name="sku" value="{{ old('sku', $product?->sku) }}" required maxlength="50" class="mt-2 w-full rounded-xl border-black/10 bg-transparent text-sm focus:border-black focus:ring-black dark:border-white/10">
        @error('sku') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label for="name" class="text-sm font-semibold">Nama Produk</label>
    <input id="name" name="name" value="{{ old('name', $product?->name) }}" required maxlength="150" class="mt-2 w-full rounded-xl border-black/10 bg-transparent text-sm focus:border-black focus:ring-black dark:border-white/10">
    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="description" class="text-sm font-semibold">Deskripsi</label>
    <textarea id="description" name="description" rows="4" maxlength="2000" class="mt-2 w-full rounded-xl border-black/10 bg-transparent text-sm focus:border-black focus:ring-black dark:border-white/10">{{ old('description', $product?->description) }}</textarea>
    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid gap-5 md:grid-cols-3">
    <div>
        <label for="price" class="text-sm font-semibold">Harga</label>
        <input id="price" type="number" min="0" step="0.01" name="price" value="{{ old('price', $product?->price) }}" required class="mt-2 w-full rounded-xl border-black/10 bg-transparent text-sm">
    </div>
    <div>
        <label for="stock" class="text-sm font-semibold">Stok</label>
        <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', $product?->stock ?? 0) }}" required class="mt-2 w-full rounded-xl border-black/10 bg-transparent text-sm">
    </div>
    <div>
        <label for="unit" class="text-sm font-semibold">Satuan</label>
        <input id="unit" name="unit" value="{{ old('unit', $product?->unit ?? 'pcs') }}" required maxlength="30" class="mt-2 w-full rounded-xl border-black/10 bg-transparent text-sm">
    </div>
</div>

<label class="flex items-center gap-2 text-sm">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product?->is_active ?? true)) class="rounded border-black/20">
    <span>Aktif</span>
</label>

<div class="flex justify-end gap-2">
    <a href="{{ route('admin.products.index') }}" class="rounded-xl border border-black/10 px-4 py-2.5 text-sm font-semibold dark:border-white/10">Batal</a>
    <button class="rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white dark:bg-white dark:text-[#171719]">{{ $submitLabel }}</button>
</div>
