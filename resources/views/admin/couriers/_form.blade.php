<div class="space-y-5">

    {{-- ROW 1: KODE + NAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="code" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Kode Kurir <span class="text-red-500">*</span>
            </label>
            <input id="code" name="code" value="{{ old('code', $courier->code ?? '') }}"
                required maxlength="30"
                placeholder="Contoh: KR-001"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm font-mono text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
            @error('code')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="name" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Nama Kurir <span class="text-red-500">*</span>
            </label>
            <input id="name" name="name" value="{{ old('name', $courier->name ?? '') }}"
                required maxlength="150"
                placeholder="Nama lengkap kurir"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
            @error('name')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- ROW 2: PHONE + EMAIL --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="phone" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                No. Telepon
            </label>
            <input id="phone" name="phone" type="tel"
                value="{{ old('phone', $courier->phone ?? '') }}"
                maxlength="30"
                placeholder="08xxxxxxxxxx"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm font-mono text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
            @error('phone')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Email
            </label>
            <input id="email" type="email" name="email"
                value="{{ old('email', $courier->email ?? '') }}"
                maxlength="150"
                placeholder="email@kurir.com"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
            @error('email')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- ROW 3: JENIS KENDARAAN + NOMOR --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="vehicle_type" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Jenis Kendaraan
            </label>
            <select id="vehicle_type" name="vehicle_type"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
                <option value="">Pilih Kendaraan</option>
                @foreach(['Motor','Mobil'] as $type)
                    <option value="{{ $type }}" @selected(old('vehicle_type', $courier->vehicle_type ?? '') === $type)>
                        {{ $type }}
                    </option>
                @endforeach
            </select>
            @error('vehicle_type')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="vehicle_number" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                Nomor Kendaraan
            </label>
            <input id="vehicle_number" name="vehicle_number"
                value="{{ old('vehicle_number', $courier->vehicle_number ?? '') }}"
                maxlength="30"
                placeholder="Contoh: B 1234 XYZ"
                class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm font-mono text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
            @error('vehicle_number')
                <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- ALAMAT --}}
    <div>
        <label for="address" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
            Alamat Lengkap
        </label>
        <textarea id="address" name="address" rows="4" maxlength="2000"
            placeholder="Alamat lengkap kurir"
            class="mt-2 w-full rounded-xl border border-black/10 dark:border-white/10 bg-transparent px-4 py-3 text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 resize-none">{{ old('address', $courier->address ?? '') }}</textarea>
        @error('address')
            <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- STATUS AKTIF --}}
    <label class="flex items-center gap-3 cursor-pointer p-4 rounded-xl border-2 transition
        {{ old('is_active', $courier->is_active ?? true) ? 'border-lime-400 bg-lime-50 dark:bg-lime-900/20' : 'border-black/5 dark:border-white/10 hover:border-lime-300' }}">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1"
            @checked(old('is_active', $courier->is_active ?? true))
            class="w-4 h-4 rounded border-gray-300 text-lime-500 focus:ring-lime-400">
        <div class="flex-1">
            <p class="text-xs font-bold text-[#171719] dark:text-white">Kurir Aktif</p>
            <p class="mt-0.5 text-[10px] text-black/45 dark:text-white/45">
                Kurir aktif dapat ditugaskan mengantar pesanan
            </p>
        </div>
    </label>

</div>

{{-- FOOTER ACTIONS --}}
<div class="mt-5 pt-5 border-t border-black/5 dark:border-white/10 flex flex-wrap items-center justify-end gap-2">
    <a href="{{ route('admin.couriers.index') }}"
       class="h-11 px-5 rounded-xl bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-[#171719] dark:text-white text-sm font-medium flex items-center gap-2 transition">
        Batal
    </a>

    <button type="submit"
        class="h-11 px-6 rounded-xl bg-gradient-to-r from-lime-400 to-lime-500 hover:from-lime-500 hover:to-lime-600 text-[#171719] text-sm font-black flex items-center gap-2 transition shadow-md hover:shadow-lg">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M20 6 9 17l-5-5"/>
        </svg>
        {{ $submitLabel ?? 'Simpan' }}
    </button>
</div>
