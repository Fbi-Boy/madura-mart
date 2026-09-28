<x-app-layout>
<div class="mx-auto max-w-4xl space-y-5">
    <div>
        <p class="text-xs font-semibold uppercase tracking-wide text-black/40 dark:text-white/40">Customer</p>
        <h1 class="mt-1 text-2xl font-bold text-black dark:text-white">Alamat Pengiriman</h1>
        <p class="mt-1 text-sm text-black/50 dark:text-white/50">Kelola beberapa alamat dan pilih alamat utama untuk checkout.</p>
    </div>

    @if(in_array(session('status'), ['address-created', 'address-default-updated', 'address-deleted']))
        <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-900/40 dark:bg-green-950/30 dark:text-green-300">
            Perubahan alamat berhasil disimpan.
        </div>
    @endif

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/40 dark:bg-red-950/30 dark:text-red-300">
            <ul class="list-disc space-y-1 pl-5">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <section class="rounded-2xl border border-black/5 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
        <h2 class="font-semibold text-black dark:text-white">Alamat tersimpan</h2>
        <div class="mt-4 space-y-3">
            @forelse($addresses as $address)
                <div class="rounded-xl border {{ $address->is_default ? 'border-lime-400' : 'border-black/10 dark:border-white/10' }} p-4">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-black dark:text-white">{{ $address->label }}</p>
                                @if($address->is_default)
                                    <span class="rounded-full bg-lime-100 px-2 py-0.5 text-[11px] font-semibold text-lime-800">Utama</span>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-black/70 dark:text-white/70">{{ $address->recipient_name }} · {{ $address->phone }}</p>
                            <p class="mt-1 text-sm text-black/60 dark:text-white/60">{{ $address->address }}, {{ $address->city }}</p>
                        </div>
                        <div class="flex gap-2">
                            @if(!$address->is_default)
                                <form method="POST" action="{{ route('customer.address.update') }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="mode" value="default">
                                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                                    <button class="rounded-lg border border-black/10 px-3 py-2 text-xs font-semibold dark:border-white/10 dark:text-white">Jadikan utama</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('customer.address.update') }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="mode" value="delete">
                                <input type="hidden" name="address_id" value="{{ $address->id }}">
                                <button class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-600">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="rounded-xl bg-black/[.03] p-4 text-sm text-black/50 dark:bg-white/[.03] dark:text-white/50">Belum ada alamat tersimpan.</p>
            @endforelse
        </div>
    </section>

    <form method="POST" action="{{ route('customer.address.update') }}" class="space-y-5 rounded-2xl border border-black/5 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-gray-900">
        @csrf
        @method('PATCH')
        <input type="hidden" name="mode" value="add">
        <h2 class="font-semibold text-black dark:text-white">Tambah alamat</h2>
        <div class="grid gap-4 md:grid-cols-2">
            <div>
                <label for="label" class="text-sm font-semibold text-black dark:text-white">Label</label>
                <input id="label" name="label" value="{{ old('label') }}" placeholder="Rumah / Kost / Kantor" required maxlength="50" class="mt-2 w-full rounded-xl border-black/10 bg-white text-sm dark:border-white/10 dark:bg-gray-950 dark:text-white">
            </div>
            <div>
                <label for="recipient_name" class="text-sm font-semibold text-black dark:text-white">Nama Penerima</label>
                <input id="recipient_name" name="recipient_name" value="{{ old('recipient_name', $customer->name) }}" required maxlength="100" class="mt-2 w-full rounded-xl border-black/10 bg-white text-sm dark:border-white/10 dark:bg-gray-950 dark:text-white">
            </div>
            <div>
                <label for="phone" class="text-sm font-semibold text-black dark:text-white">Nomor Telepon</label>
                <input id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required maxlength="30" class="mt-2 w-full rounded-xl border-black/10 bg-white text-sm dark:border-white/10 dark:bg-gray-950 dark:text-white">
            </div>
            <div>
                <label for="city" class="text-sm font-semibold text-black dark:text-white">Kota / Kabupaten</label>
                <input id="city" name="city" value="{{ old('city', $customer->city) }}" required maxlength="100" class="mt-2 w-full rounded-xl border-black/10 bg-white text-sm dark:border-white/10 dark:bg-gray-950 dark:text-white">
            </div>
        </div>
        <div>
            <label for="address" class="text-sm font-semibold text-black dark:text-white">Alamat Lengkap</label>
            <textarea id="address" name="address" rows="4" required maxlength="1000" class="mt-2 w-full rounded-xl border-black/10 bg-white text-sm dark:border-white/10 dark:bg-gray-950 dark:text-white">{{ old('address') }}</textarea>
        </div>
        <label class="flex items-center gap-2 text-sm text-black dark:text-white">
            <input type="checkbox" name="is_default" value="1" class="rounded border-black/20" @checked(old('is_default'))>
            Jadikan alamat utama
        </label>
        <div class="flex justify-end gap-2">
            <a href="{{ route('dashboard') }}" class="rounded-xl border border-black/10 px-4 py-2.5 text-sm font-semibold text-black dark:border-white/10 dark:text-white">Kembali</a>
            <button class="rounded-xl bg-[#171719] px-4 py-2.5 text-sm font-semibold text-white">Tambah Alamat</button>
        </div>
    </form>
</div>
</x-app-layout>
