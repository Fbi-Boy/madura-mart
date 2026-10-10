<div class="space-y-5">

    {{-- ROW 1: KATEGORI + SKU --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="category_id" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Kategori <span class="text-red-500">*</span>
            </label>
            <select id="category_id" name="category_id" required
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="sku" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                SKU <span class="text-red-500">*</span>
            </label>
            <input id="sku" name="sku" value="{{ old('sku', $product?->sku) }}"
                required maxlength="50"
                placeholder="Contoh: PRD-001"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm font-mono text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
            @error('sku')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- NAMA PRODUK --}}
    <div>
        <label for="name" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
            Nama Produk <span class="text-red-500">*</span>
        </label>
        <input id="name" name="name" value="{{ old('name', $product?->name) }}"
            required maxlength="150"
            placeholder="Nama lengkap produk"
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
        <textarea id="description" name="description" rows="4" maxlength="2000"
            placeholder="Deskripsi produk (opsional)"
            class="mt-2 w-full rounded-xl border border-black/10 dark:border-white/10 bg-transparent px-4 py-3 text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 resize-none">{{ old('description', $product?->description) }}</textarea>
        @error('description')
            <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- ROW 2: HARGA + STOK + SATUAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="price" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Harga <span class="text-red-500">*</span>
            </label>
            <div class="relative mt-2">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xs font-medium text-black/45 dark:text-white/45">Rp</span>
                <input id="price" type="number" min="0" step="100" name="price"
                    value="{{ old('price', $product?->price) }}"
                    required
                    class="no-spinner w-full h-11 pl-11 pr-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm font-bold text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
            </div>
            @error('price')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="stock" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Stok <span class="text-red-500">*</span>
            </label>
            <input id="stock" type="number" min="0" name="stock"
                value="{{ old('stock', $product?->stock ?? 0) }}"
                required
                class="no-spinner mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm font-bold text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
            @error('stock')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="unit" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Satuan <span class="text-red-500">*</span>
            </label>
            <input id="unit" name="unit"
                value="{{ old('unit', $product?->unit ?? 'pcs') }}"
                required maxlength="30"
                placeholder="pcs / box / kg"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
            @error('unit')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- STATUS AKTIF --}}
    <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl border-2 transition
        {{ old('is_active', $product?->is_active ?? true) ? 'border-lime-400 bg-lime-50 dark:bg-lime-900/20' : 'border-black/5 dark:border-white/10 hover:border-lime-300' }}">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1"
            @checked(old('is_active', $product?->is_active ?? true))
            class="w-4 h-4 rounded border-gray-300 text-lime-500 focus:ring-lime-400">
        <div class="flex-1">
            <p class="text-xs font-bold text-[#171719] dark:text-white">Produk Aktif</p>
            <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">
                Produk aktif akan tampil di katalog customer
            </p>
        </div>
    </label>

</div>

{{-- FOOTER ACTIONS --}}
<div class="mt-5 pt-5 border-t border-black/5 dark:border-white/10 flex flex-wrap items-center justify-end gap-2">
    <a href="{{ route('admin.products.index') }}"
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

<style>
    .no-spinner::-webkit-outer-spin-button,
    .no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .no-spinner { -moz-appearance: textfield; appearance: textfield; }
</style>
