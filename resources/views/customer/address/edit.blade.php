<x-app-layout>
<div class="space-y-5 font-poppins max-w-4xl mx-auto">

    {{-- HEADER --}}
    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 px-5 py-4">
        <div>
            <p class="text-[10px] font-medium uppercase tracking-[0.14em] text-black/45 dark:text-white/45">Customer</p>
            <h1 class="mt-1 text-xl font-semibold tracking-tight text-[#171719] dark:text-white">Alamat Pengiriman</h1>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Kelola alamat dan pilih alamat utama untuk checkout</p>
        </div>

        <a href="{{ route('dashboard') }}"
           class="h-10 px-4 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
            </svg>
            Dashboard
        </a>
    </div>

    {{-- WARNING: CUSTOMER BELUM TERHUBUNG --}}
    @if(!$customer)
        <div class="rounded-2xl border border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 9v4"/><path d="M12 17h.01"/>
                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-amber-800 dark:text-amber-200">Profil Customer Belum Terhubung</p>
                <p class="mt-0.5 text-[11px] text-amber-700 dark:text-amber-200/80">
                    Akun Anda belum terhubung dengan data customer. Hubungi admin untuk menghubungkan akun.
                    Fitur alamat tetap bisa dilihat, tapi tidak bisa menambah alamat baru.
                </p>
            </div>
        </div>
    @endif

    {{-- ALERT SUCCESS --}}
    @if(in_array(session('status'), ['address-created', 'address-default-updated', 'address-deleted']))
        <div class="rounded-2xl border border-lime-200 bg-lime-50 dark:border-lime-500/20 dark:bg-lime-500/10 px-4 py-3 flex items-start gap-3">
            <svg class="w-5 h-5 text-lime-600 dark:text-lime-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path d="M20 6 9 17l-5-5"/>
            </svg>
            <p class="text-sm font-medium text-lime-800 dark:text-lime-300">Perubahan alamat berhasil disimpan.</p>
        </div>
    @endif

    {{-- ALERT ERROR --}}
    @if($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 dark:border-red-500/20 dark:bg-red-500/10 px-4 py-3">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800 dark:text-red-300">Periksa input alamat</p>
                    <ul class="mt-1 list-disc space-y-0.5 pl-5 text-xs text-red-700 dark:text-red-200">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- DAFTAR ALAMAT --}}
    <section class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">

        <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-[#171719] dark:text-white">Alamat Tersimpan</h2>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">{{ $addresses->count() }} alamat terdaftar</p>
                </div>
            </div>
        </div>

        <div class="p-4 space-y-3">
            @forelse($addresses as $address)
                <div class="rounded-xl border-2 p-4 transition
                    {{ $address->is_default ? 'border-lime-400 bg-lime-50 dark:bg-lime-900/20' : 'border-black/5 dark:border-white/10 hover:border-lime-300' }}">

                    <div class="flex flex-wrap items-start justify-between gap-4">

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-xs font-bold text-[#171719] dark:text-white">{{ $address->label }}</p>
                                @if($address->is_default)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-lime-400 text-[#171719] text-[9px] font-bold uppercase tracking-wider">
                                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                            <path d="M20 6 9 17l-5-5"/>
                                        </svg>
                                        Utama
                                    </span>
                                @endif
                            </div>

                            <div class="mt-2 space-y-1">
                                <p class="text-xs text-black/70 dark:text-white/70">
                                    <span class="font-semibold">{{ $address->recipient_name }}</span> · {{ $address->phone }}
                                </p>
                                <p class="text-[11px] leading-relaxed text-black/60 dark:text-white/60">
                                    {{ $address->address }}, {{ $address->city }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">

                            @if(!$address->is_default)
                                <form method="POST" action="{{ route('customer.address.update') }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="mode" value="default">
                                    <input type="hidden" name="address_id" value="{{ $address->id }}">
                                    <button type="submit"
                                        class="h-9 px-3 rounded-lg bg-lime-100 dark:bg-lime-900/30 hover:bg-lime-200 dark:hover:bg-lime-900/50 text-lime-700 dark:text-lime-400 text-[11px] font-bold flex items-center gap-1.5 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path d="M20 6 9 17l-5-5"/>
                                        </svg>
                                        Jadikan Utama
                                    </button>
                                </form>
                            @endif

                            <form method="POST" action="{{ route('customer.address.update') }}"
                                  onsubmit="return confirm('Hapus alamat ini?')">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="mode" value="delete">
                                <input type="hidden" name="address_id" value="{{ $address->id }}">
                                <button type="submit"
                                    class="w-9 h-9 rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 flex items-center justify-center transition"
                                    title="Hapus alamat">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-dashed border-black/10 dark:border-white/10 p-8 text-center">
                    <div class="w-14 h-14 rounded-2xl bg-gray-100 dark:bg-white/5 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-black/30 dark:text-white/30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                    </div>
                    <p class="text-xs font-semibold text-[#171719] dark:text-white">Belum ada alamat tersimpan</p>
                    <p class="mt-1 text-[11px] text-black/45 dark:text-white/45">Tambahkan alamat di bawah untuk memulai</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- FORM TAMBAH ALAMAT — hanya tampil kalau customer terhubung --}}
    @if($customer)
        <form method="POST" action="{{ route('customer.address.update') }}"
              class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">
            @csrf
            @method('PATCH')
            <input type="hidden" name="mode" value="add">

            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 5v14"/><path d="M5 12h14"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-[#171719] dark:text-white">Tambah Alamat Baru</h2>
                    <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">Isi data alamat pengiriman</p>
                </div>
            </div>

            <div class="p-5 space-y-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="label" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                            Label Alamat
                        </label>
                        <input id="label" name="label" value="{{ old('label') }}"
                            placeholder="Rumah / Kost / Kantor"
                            required maxlength="50"
                            class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
                    </div>

                    <div>
                        <label for="recipient_name" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                            Nama Penerima
                        </label>
                        <input id="recipient_name" name="recipient_name"
                            value="{{ old('recipient_name', $customer?->name ?? auth()->user()->name) }}"
                            required maxlength="100"
                            class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="phone" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                            Nomor Telepon
                        </label>
                        <input id="phone" name="phone" type="tel"
                            value="{{ old('phone', $customer?->phone ?? '') }}"
                            required maxlength="30"
                            placeholder="08xxxxxxxxxx"
                            class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm font-mono text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
                    </div>

                    <div>
                        <label for="city" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                            Kota / Kabupaten
                        </label>
                        <input id="city" name="city" value="{{ old('city', $customer?->city ?? '') }}"
                            required maxlength="100"
                            placeholder="Contoh: Jakarta Selatan"
                            class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
                    </div>
                </div>

                <div>
                    <label for="address" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                        Alamat Lengkap
                    </label>
                    <textarea id="address" name="address" rows="4"
                        placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kode pos"
                        required maxlength="1000"
                        class="mt-2 w-full rounded-xl border border-black/10 dark:border-white/10 bg-transparent px-4 py-3 text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 resize-none">{{ old('address', $customer?->address ?? '') }}</textarea>
                </div>

                <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl border-2 transition
                    {{ old('is_default') ? 'border-lime-400 bg-lime-50 dark:bg-lime-900/20' : 'border-black/5 dark:border-white/10 hover:border-lime-300' }}">
                    <input type="checkbox" name="is_default" value="1"
                        @checked(old('is_default'))
                        class="w-4 h-4 rounded border-gray-300 text-lime-500 focus:ring-lime-400">
                    <div class="flex-1">
                        <p class="text-xs font-bold text-[#171719] dark:text-white">Jadikan alamat utama</p>
                        <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">
                            Alamat ini akan otomatis dipilih saat checkout
                        </p>
                    </div>
                </label>

            </div>

            <div class="px-5 py-4 border-t border-black/5 dark:border-white/10 flex flex-wrap items-center justify-end gap-2">
                <a href="{{ route('dashboard') }}"
                   class="h-11 px-5 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
                    Kembali
                </a>

                <button type="submit"
                    class="h-11 px-6 rounded-xl bg-gradient-to-r from-lime-400 to-lime-500 hover:from-lime-500 hover:to-lime-600 text-[#171719] text-sm font-black flex items-center gap-2 transition shadow-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M12 5v14"/><path d="M5 12h14"/>
                    </svg>
                    Tambah Alamat
                </button>
            </div>
        </form>
    @endif

</div>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
</x-app-layout>
