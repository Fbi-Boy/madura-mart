<div class="grid gap-5 sm:grid-cols-2">
    <div>
        <label class="text-sm font-semibold">Kode</label>
        <input name="code" value="{{ old('code', $customer->code ?? '') }}" class="mt-2 w-full rounded-xl border-black/10" required>
        @error('code') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-semibold">Nama</label>
        <input name="name" value="{{ old('name', $customer->name ?? '') }}" class="mt-2 w-full rounded-xl border-black/10" required>
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-semibold">Telepon</label>
        <input name="phone" value="{{ old('phone', $customer->phone ?? '') }}" class="mt-2 w-full rounded-xl border-black/10">
    </div>
    <div>
        <label class="text-sm font-semibold">Email</label>
        <input type="email" name="email" value="{{ old('email', $customer->email ?? '') }}" class="mt-2 w-full rounded-xl border-black/10">
        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="text-sm font-semibold">Kota</label>
        <input name="city" value="{{ old('city', $customer->city ?? '') }}" class="mt-2 w-full rounded-xl border-black/10">
    </div>
    <div class="flex items-center gap-3 pt-7">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $customer->is_active ?? true)) class="rounded">
        <label class="text-sm font-semibold">Customer aktif</label>
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold">Alamat</label>
        <textarea name="address" rows="4" class="mt-2 w-full rounded-xl border-black/10">{{ old('address', $customer->address ?? '') }}</textarea>
    </div>
</div>
<div class="mt-6 flex justify-end gap-2">
    <a href="{{ route('admin.customers.index') }}" class="rounded-xl border border-black/10 px-4 py-2.5 text-sm font-semibold">Batal</a>
    <button class="rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white">Simpan</button>
</div>