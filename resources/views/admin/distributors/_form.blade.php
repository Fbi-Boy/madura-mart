<div class="space-y-5">

    {{-- ROW 1: KODE + NAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Kode Distributor <span class="text-red-500">*</span>
            </label>
            <input name="code" value="{{ old('code', $distributor->code ?? '') }}"
                placeholder="Contoh: DIST-001"
                required maxlength="30"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm font-mono text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
            @error('code')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Nama Distributor <span class="text-red-500">*</span>
            </label>
            <input name="name" value="{{ old('name', $distributor->name ?? '') }}"
                placeholder="Nama lengkap distributor"
                required maxlength="150"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
            @error('name')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- ROW 2: CONTACT + PHONE --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Contact Person
            </label>
            <input name="contact_person" value="{{ old('contact_person', $distributor->contact_person ?? '') }}"
                placeholder="Nama penanggung jawab"
                maxlength="100"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
        </div>

        <div>
            <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Telepon
            </label>
            <input type="tel" name="phone" value="{{ old('phone', $distributor->phone ?? '') }}"
                placeholder="08xxxxxxxxxx"
                maxlength="30"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm font-mono text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
        </div>
    </div>

    {{-- ROW 3: EMAIL + KOTA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Email
            </label>
            <input type="email" name="email" value="{{ old('email', $distributor->email ?? '') }}"
                placeholder="email@distributor.com"
                maxlength="150"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
            @error('email')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Kota / Kabupaten
            </label>
            <input name="city" value="{{ old('city', $distributor->city ?? '') }}"
                placeholder="Contoh: Jakarta Selatan"
                maxlength="100"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
        </div>
    </div>

    {{-- ALAMAT --}}
    <div>
        <label class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
            Alamat Lengkap
        </label>
        <textarea name="address" rows="4"
            placeholder="Alamat kantor/gudang distributor"
            maxlength="1000"
            class="mt-2 w-full rounded-xl border border-black/10 dark:border-white/10 bg-transparent px-4 py-3 text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 resize-none">{{ old('address', $distributor->address ?? '') }}</textarea>
    </div>

    {{-- STATUS AKTIF --}}
    <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl border-2 transition
        {{ old('is_active', $distributor->is_active ?? true) ? 'border-lime-400 bg-lime-50 dark:bg-lime-900/20' : 'border-black/5 dark:border-white/10 hover:border-lime-300' }}">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1"
            @checked(old('is_active', $distributor->is_active ?? true))
            class="w-4 h-4 rounded border-gray-300 text-lime-500 focus:ring-lime-400">
        <div class="flex-1">
            <p class="text-xs font-bold text-[#171719] dark:text-white">Distributor Aktif</p>
            <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">
                Distributor aktif dapat melakukan transaksi
            </p>
        </div>
    </label>

</div>

{{-- FOOTER ACTIONS --}}
<div class="mt-5 pt-5 border-t border-black/5 dark:border-white/10 flex flex-wrap items-center justify-end gap-2">
    <a href="{{ route('admin.distributors.index') }}"
       class="h-11 px-5 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
        Batal
    </a>

    <button type="submit"
        class="h-11 px-6 rounded-xl bg-gradient-to-r from-lime-400 to-lime-500 hover:from-lime-500 hover:to-lime-600 text-[#171719] text-sm font-black flex items-center gap-2 transition shadow-md hover:shadow-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M20 6 9 17l-5-5"/>
        </svg>
        Simpan Distributor
    </button>
</div>
