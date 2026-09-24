<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label for="code" class="text-sm font-semibold">Kode Supplier</label>
        <input id="code" name="code" value="{{ old('code', $supplier->code ?? '') }}" required maxlength="30"
               class="mt-2 w-full rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm dark:border-white/10 dark:bg-white/[0.04]">
        @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="name" class="text-sm font-semibold">Nama Supplier</label>
        <input id="name" name="name" value="{{ old('name', $supplier->name ?? '') }}" required maxlength="150"
               class="mt-2 w-full rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm dark:border-white/10 dark:bg-white/[0.04]">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="contact_person" class="text-sm font-semibold">PIC / Contact Person</label>
        <input id="contact_person" name="contact_person" value="{{ old('contact_person', $supplier->contact_person ?? '') }}" maxlength="100"
               class="mt-2 w-full rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm dark:border-white/10 dark:bg-white/[0.04]">
        @error('contact_person') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="phone" class="text-sm font-semibold">No. Telepon</label>
        <input id="phone" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" maxlength="30"
               class="mt-2 w-full rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm dark:border-white/10 dark:bg-white/[0.04]">
        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="email" class="text-sm font-semibold">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $supplier->email ?? '') }}" maxlength="150"
               class="mt-2 w-full rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm dark:border-black/10 dark:bg-white/[0.04]">
        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="city" class="text-sm font-semibold">Kota</label>
        <input id="city" name="city" value="{{ old('city', $supplier->city ?? '') }}" maxlength="100"
               class="mt-2 w-full rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm dark:border-white/10 dark:bg-white/[0.04]">
        @error('city') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label for="address" class="text-sm font-semibold">Alamat</label>
        <textarea id="address" name="address" rows="3" maxlength="2000"
                  class="mt-2 w-full rounded-xl border border-black/10 bg-white px-3 py-2.5 text-sm dark:border-white/10 dark:bg-white/[0.04]">{{ old('address', $supplier->address ?? '') }}</textarea>
        @error('address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <label class="sm:col-span-2 inline-flex items-center gap-2 text-sm font-medium">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $supplier->is_active ?? true))
               class="rounded border-black/20">
        Supplier aktif
    </label>
</div>

<div class="flex items-center justify-end gap-2 pt-2">
    <a href="{{ route('admin.suppliers.index') }}"
       class="rounded-xl border border-black/10 px-4 py-2.5 text-sm font-semibold dark:border-white/10">Batal</a>
    <button type="submit"
            class="rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white dark:bg-white dark:text-[#171719]">
        {{ $submitLabel }}
    </button>
</div>
