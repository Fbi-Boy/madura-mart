<div class="space-y-6">

    {{-- =====================================================
        IDENTITY
    ====================================================== --}}

    <div>

        <div class="mb-4">

            <p class="text-xs font-semibold uppercase tracking-wider text-green-600">
                Informasi Akun
            </p>

            <h3 class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                Identitas pengguna
            </h3>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Masukkan informasi dasar akun yang akan digunakan.
            </p>

        </div>


        <div class="grid gap-5 md:grid-cols-2">

            {{-- Name --}}
            <div>

                <label
                    for="name"
                    class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                >
                    Nama Lengkap
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name ?? '') }}"
                    placeholder="Contoh: Fabi Fahruddin"
                    required
                    class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:bg-gray-900"
                >

                @error('name')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror

            </div>


            {{-- Email --}}
            <div>

                <label
                    for="email"
                    class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                >
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $user->email ?? '') }}"
                    placeholder="nama@example.com"
                    required
                    class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:bg-gray-900"
                >

                @error('email')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror

            </div>

        </div>

    </div>


    {{-- =====================================================
        ACCESS
    ====================================================== --}}

    <div class="border-t border-gray-100 pt-6 dark:border-gray-800">

        <div class="mb-4">

            <p class="text-xs font-semibold uppercase tracking-wider text-green-600">
                Access Control
            </p>

            <h3 class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                Role & akses
            </h3>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Role menentukan workspace dan hak akses pengguna.
            </p>

        </div>


        <div>

            <label
                for="role"
                class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300"
            >
                Role
            </label>

            <select
                id="role"
                name="role"
                required
                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm text-gray-800 outline-none transition focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:bg-gray-900"
            >

                <option value="">
                    Pilih role
                </option>

                @foreach([
                    'admin',
                    'super-admin',
                    'gudang',
                    'kasir',
                    'purchasing',
                    'kurir',
                    'customer'
                ] as $roleOption)

                    <option
                        value="{{ $roleOption }}"
                        @selected(old('role', $user->role ?? '') === $roleOption)
                    >
                        {{ ucfirst(str_replace('-', ' ', $roleOption)) }}
                    </option>

                @endforeach

            </select>

            @error('role')
                <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
            @enderror

        </div>

    </div>


    {{-- =====================================================
        PASSWORD
    ====================================================== --}}

    <div class="border-t border-gray-100 pt-6 dark:border-gray-800">

        <div class="mb-4">

            <p class="text-xs font-semibold uppercase tracking-wider text-green-600">
                Security
            </p>

            <h3 class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                Kredensial login
            </h3>

            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Gunakan password yang kuat dan tidak mudah ditebak.
            </p>

        </div>


        <div class="grid gap-5 md:grid-cols-2">

            {{-- Password --}}
            <div>

                <label
                    for="password"
                    class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                >
                    Password
                    @if(isset($user))
                        <span class="font-normal text-gray-400">
                            (opsional)
                        </span>
                    @endif
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    placeholder="Minimal 8 karakter"
                    {{ isset($user) ? '' : 'required' }}
                    class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:bg-gray-900"
                >

                @error('password')
                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                @enderror

            </div>


            {{-- Confirm --}}
            <div>

                <label
                    for="password_confirmation"
                    class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                >
                    Konfirmasi Password
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    placeholder="Ulangi password"
                    class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:bg-gray-900"
                >

            </div>

        </div>

    </div>


    {{-- =====================================================
        ACTIONS
    ====================================================== --}}

    <div class="flex flex-col-reverse gap-3 border-t border-gray-100 pt-6 sm:flex-row sm:justify-end dark:border-gray-800">

        <a
            href="{{ route('admin.users.index') }}"
            class="inline-flex h-11 items-center justify-center rounded-xl border border-gray-200 px-5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800"
        >
            Batal
        </a>

        <button
            type="submit"
            class="inline-flex h-11 items-center justify-center gap-2 rounded-xl bg-green-600 px-5 text-sm font-semibold text-white shadow-sm shadow-green-600/20 transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/30"
        >

            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M5 13l4 4L19 7"/>
            </svg>

            {{ isset($user) ? 'Simpan Perubahan' : 'Buat User' }}

        </button>

    </div>

</div>
