<x-app-layout>
<div class="mx-auto max-w-3xl space-y-5">
    <div>
        <p class="text-xs font-semibold uppercase tracking-wide text-black/40 dark:text-white/40">Customer</p>
        <h1 class="mt-1 text-2xl font-bold text-black dark:text-white">Alamat Pengiriman</h1>
        <p class="mt-1 text-sm text-black/50 dark:text-white/50">Simpan data penerima yang digunakan saat checkout.</p>
    </div>

    @if(session('status') === 'address-updated')
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900/40 dark:bg-green-950/30 dark:text-green-300">
            Data alamat berhasil diperbarui.
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-950/30 dark:text-red-300">
            <ul class="list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('customer.address.update') }}" class="space-y-5 rounded-2xl border border-black/5 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
        @csrf
        @method('PATCH')

        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="text-sm font-semibold text-black dark:text-white">Nama Penerima</label>
                <input id="name" name="name" value="{{ old('name', $customer->name) }}" required maxlength="100"
                    class="mt-2 w-full rounded-xl border-black/10 bg-white text-sm dark:border-white/10 dark:bg-gray-950 dark:text-white">
            </div>
            <div>
                <label for="phone" class="text-sm font-semibold text-black dark:text-white">Nomor Telepon</label>
                <input id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required maxlength="30"
                    class="mt-2 w-full rounded-xl border-black/10 bg-white text-sm dark:border-white/10 dark:bg-gray-950 dark:text-white">
            </div>
        </div>

        <div>
            <label for="address" class="text-sm font-semibold text-black dark:text-white">Alamat Lengkap</label>
            <textarea id="address" name="address" rows="4" required maxlength="500"
                class="mt-2 w-full rounded-xl border-black/10 bg-white text-sm dark:border-white/10 dark:bg-gray-950 dark:text-white">{{ old('address', $customer->address) }}</textarea>
        </div>

        <div>
            <label for="city" class="text-sm font-semibold text-black dark:text-white">Kota / Kabupaten</label>
            <input id="city" name="city" value="{{ old('city', $customer->city) }}" required maxlength="100"
                class="mt-2 w-full rounded-xl border-black/10 bg-white text-sm dark:border-white/10 dark:bg-gray-950 dark:text-white">
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('dashboard') }}" class="rounded-xl border border-black/10 px-4 py-2.5 text-sm font-semibold text-black dark:border-white/10 dark:text-white">Kembali</a>
            <button class="rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white">Simpan Alamat</button>
        </div>
    </form>
</div>
</x-app-layout>
