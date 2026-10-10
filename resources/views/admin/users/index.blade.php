<x-app-layout>

    <div class="space-y-6">

        {{-- ============================================================
             PAGE HEADER
        ============================================================= --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">
                    User & Staff
                </h1>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Kelola pengguna, role, status akun, dan akses sistem.
                </p>
            </div>


            {{-- TAMBAH USER --}}
            <a
                href="{{ route('admin.users.create') }}"
                class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-600 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-950"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>
                </svg>

                Tambah User

            </a>

        </div>


        {{-- ============================================================
             SUCCESS MESSAGE
        ============================================================= --}}
        @if (session('success'))

            <div
                class="flex items-start gap-3 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300"
            >

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mt-0.5 h-5 w-5 shrink-0"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M20 6 9 17l-5-5"/>
                </svg>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        @endif


        {{-- ============================================================
             ERROR MESSAGE
        ============================================================= --}}
        @if ($errors->any())

            <div
                class="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-950/30 dark:text-red-300"
            >

                <div class="flex items-start gap-3">

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="mt-0.5 h-5 w-5 shrink-0"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" x2="12" y1="8" y2="12"/>
                        <line x1="12" x2="12.01" y1="16" y2="16"/>
                    </svg>

                    <div class="space-y-1">

                        @foreach ($errors->all() as $error)

                            <p>
                                {{ $error }}
                            </p>

                        @endforeach

                    </div>

                </div>

            </div>

        @endif


        {{-- ============================================================
             FILTER
        ============================================================= --}}
        <div
            class="rounded-2xl bg-white p-4 shadow-sm dark:bg-gray-900"
        >

            <form
                method="GET"
                action="{{ route('admin.users.index') }}"
                class="grid grid-cols-1 gap-3 md:grid-cols-4"
            >

                {{-- SEARCH --}}
                <div class="md:col-span-2">

                    <label
                        for="search"
                        class="sr-only"
                    >
                        Cari user
                    </label>

                    <div class="relative">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>

                        <input
                            id="search"
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari nama atau email..."
                            class="w-full rounded-xl border-0 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none ring-1 ring-gray-200 transition placeholder:text-gray-400 focus:bg-white focus:ring-2 focus:ring-emerald-500 dark:bg-gray-800 dark:text-white dark:ring-gray-700 dark:placeholder:text-gray-500 dark:focus:bg-gray-800"
                        >

                    </div>

                </div>


                {{-- ROLE --}}
                <div>

                    <label
                        for="role"
                        class="sr-only"
                    >
                        Role
                    </label>

                    <select
                        id="role"
                        name="role"
                        class="w-full rounded-xl border-0 bg-gray-50 px-3 py-2.5 text-sm text-gray-900 outline-none ring-1 ring-gray-200 transition focus:bg-white focus:ring-2 focus:ring-emerald-500 dark:bg-gray-800 dark:text-white dark:ring-gray-700 dark:focus:bg-gray-800"
                    >

                        <option value="">
                            Semua Role
                        </option>

                        @foreach ($roles as $roleOption)

                            <option
                                value="{{ $roleOption }}"
                                @selected($role === $roleOption)
                            >
                                {{ config(
                                    'permissions.role_labels.' . $roleOption,
                                    ucfirst($roleOption)
                                ) }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- STATUS --}}
                <div>

                    <label
                        for="status"
                        class="sr-only"
                    >
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="w-full rounded-xl border-0 bg-gray-50 px-3 py-2.5 text-sm text-gray-900 outline-none ring-1 ring-gray-200 transition focus:bg-white focus:ring-2 focus:ring-emerald-500 dark:bg-gray-800 dark:text-white dark:ring-gray-700 dark:focus:bg-gray-800"
                    >

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="active"
                            @selected($status === 'active')
                        >
                            Aktif
                        </option>

                        <option
                            value="inactive"
                            @selected($status === 'inactive')
                        >
                            Nonaktif
                        </option>

                    </select>

                </div>


                {{-- FILTER BUTTON --}}
                <div class="flex gap-2 md:col-span-4">

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M3 6h18"/>
                            <path d="M7 12h10"/>
                            <path d="M10 18h4"/>
                        </svg>

                        Filter

                    </button>


                    <a
                        href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center justify-center rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 ring-1 ring-gray-200 transition hover:bg-gray-50 dark:text-gray-300 dark:ring-gray-700 dark:hover:bg-gray-800"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>


        {{-- ============================================================
             USER TABLE
        ============================================================= --}}
        <div
            class="overflow-hidden rounded-2xl bg-white shadow-sm dark:bg-gray-900"
        >

            <div class="overflow-x-auto">

                <table class="min-w-full">

                    {{-- HEADER --}}
                    <thead>

                        <tr class="border-b border-gray-100 dark:border-gray-800">

                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                            >
                                User
                            </th>

                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                            >
                                Role
                            </th>

                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                            >
                                Status
                            </th>

                            <th
                                class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                            >
                                Bergabung
                            </th>

                            {{-- AKSI CENTER --}}
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400"
                            >
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    {{-- BODY --}}
                    <tbody
                        class="divide-y divide-gray-100 dark:divide-gray-800"
                    >

                        @forelse ($users as $user)

                            <tr
                                class="transition hover:bg-gray-50/70 dark:hover:bg-gray-800/40"
                            >

                                {{-- USER --}}
                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        {{-- AVATAR --}}
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-semibold text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300"
                                        >
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>


                                        {{-- INFO --}}
                                        <div class="min-w-0">

                                            <p
                                                class="truncate text-sm font-semibold text-gray-900 dark:text-white"
                                            >
                                                {{ $user->name }}
                                            </p>

                                            <p
                                                class="truncate text-xs text-gray-500 dark:text-gray-400"
                                            >
                                                {{ $user->email }}
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                {{-- ROLE --}}
                                <td class="px-6 py-4">

                                    @php
                                        $roleLabel = config(
                                            'permissions.role_labels.' . $user->role,
                                            ucfirst($user->role)
                                        );
                                    @endphp

                                    <span
                                        class="inline-flex rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300"
                                    >
                                        {{ $roleLabel }}
                                    </span>

                                </td>


                                {{-- STATUS --}}
                                <td class="px-6 py-4">

                                    @if ($user->is_active)

                                        {{-- AKTIF = HIJAU --}}
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300"
                                        >

                                            <span
                                                class="h-1.5 w-1.5 rounded-full bg-emerald-500"
                                            ></span>

                                            Aktif

                                        </span>

                                    @else

                                        {{-- NONAKTIF = MERAH --}}
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full bg-red-50 px-3 py-1 text-xs font-medium text-red-700 dark:bg-red-950/40 dark:text-red-300"
                                        >

                                            <span
                                                class="h-1.5 w-1.5 rounded-full bg-red-500"
                                            ></span>

                                            Nonaktif

                                        </span>

                                    @endif

                                </td>


                                {{-- BERGABUNG --}}
                                <td
                                    class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ $user->created_at?->format('d M Y') ?? '-' }}
                                </td>


                                {{-- ========================================================
                                     ACTION
                                ========================================================= --}}
                                <td class="px-6 py-4">

                                    <div
                                        class="flex items-center justify-center gap-2"
                                    >

                                        {{-- =================================================
                                             EDIT
                                        ================================================== --}}
                                        <a
                                            href="{{ route('admin.users.edit', $user) }}"
                                            title="Edit user"
                                            aria-label="Edit user {{ $user->name }}"
                                            class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-50 text-gray-500 ring-1 ring-gray-200 transition hover:bg-emerald-50 hover:text-emerald-600 hover:ring-emerald-200 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-emerald-950/40 dark:hover:text-emerald-400 dark:hover:ring-emerald-800"
                                        >

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path d="M12 20h9"/>
                                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4Z"/>
                                            </svg>

                                        </a>


                                        {{-- =================================================
                                             RESET PASSWORD
                                        ================================================== --}}
                                        <button
                                            type="button"
                                            title="Reset password"
                                            aria-label="Reset password {{ $user->name }}"
                                            onclick="openResetPasswordModal({{ $user->id }}, @js($user->name))"
                                            class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-50 text-gray-500 ring-1 ring-gray-200 transition hover:bg-blue-50 hover:text-blue-600 hover:ring-blue-200 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-blue-950/40 dark:hover:text-blue-400 dark:hover:ring-blue-800"
                                        >

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-4 w-4"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path d="M3 12a9 9 0 0 1 15.5-6.2"/>
                                                <path d="M21 12a9 9 0 0 1-15.5 6.2"/>
                                                <path d="M18 3v4h-4"/>
                                                <path d="M6 21v-4h4"/>
                                            </svg>

                                        </button>


                                        {{-- =================================================
                                             TOGGLE STATUS
                                        ================================================== --}}
                                        @if (!auth()->user()->is($user))

                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.toggle-status', $user) }}"
                                                class="inline"
                                            >

                                                @csrf

                                                @method('PATCH')


                                                @if ($user->is_active)

                                                    {{-- AKTIF
                                                         ACTION = NONAKTIFKAN
                                                         WARNA = MERAH --}}
                                                    <button
                                                        type="submit"
                                                        title="Nonaktifkan user"
                                                        aria-label="Nonaktifkan user {{ $user->name }}"
                                                        onclick="return confirm('Nonaktifkan user ini?')"
                                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-red-50 text-red-500 ring-1 ring-red-100 transition hover:bg-red-100 hover:text-red-600 hover:ring-red-200 dark:bg-red-950/30 dark:text-red-400 dark:ring-red-900/50 dark:hover:bg-red-950/50 dark:hover:text-red-300"
                                                    >

                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        >
                                                            <circle
                                                                cx="12"
                                                                cy="12"
                                                                r="9"
                                                            />

                                                            <path d="m15 9-6 6"/>
                                                            <path d="m9 9 6 6"/>
                                                        </svg>

                                                    </button>

                                                @else

                                                    {{-- NONAKTIF
                                                         ACTION = AKTIFKAN
                                                         WARNA = HIJAU --}}
                                                    <button
                                                        type="submit"
                                                        title="Aktifkan user"
                                                        aria-label="Aktifkan user {{ $user->name }}"
                                                        onclick="return confirm('Aktifkan user ini?')"
                                                        class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-50 text-emerald-500 ring-1 ring-emerald-100 transition hover:bg-emerald-100 hover:text-emerald-600 hover:ring-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-400 dark:ring-emerald-900/50 dark:hover:bg-emerald-950/50 dark:hover:text-emerald-300"
                                                    >

                                                        <svg
                                                            xmlns="http://www.w3.org/2000/svg"
                                                            class="h-4 w-4"
                                                            viewBox="0 0 24 24"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            stroke-width="2"
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                        >
                                                            <circle
                                                                cx="12"
                                                                cy="12"
                                                                r="9"
                                                            />

                                                            <path d="m9 12 2 2 4-4"/>
                                                        </svg>

                                                    </button>

                                                @endif

                                            </form>

                                        @endif


                                        {{-- =================================================
                                             DELETE
                                        ================================================== --}}
                                        @if (!auth()->user()->is($user))

                                            <form
                                                method="POST"
                                                action="{{ route('admin.users.destroy', $user) }}"
                                                class="inline"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    title="Hapus user"
                                                    aria-label="Hapus user {{ $user->name }}"
                                                    onclick="return confirm('Yakin ingin menghapus user ini?')"
                                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-50 text-gray-500 ring-1 ring-gray-200 transition hover:bg-red-50 hover:text-red-600 hover:ring-red-200 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-red-950/40 dark:hover:text-red-400 dark:hover:ring-red-800"
                                                >

                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="h-4 w-4"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path d="M3 6h18"/>
                                                        <path d="M8 6V4h8v2"/>
                                                        <path d="M19 6v14H5V6"/>
                                                        <path d="M10 11v5"/>
                                                        <path d="M14 11v5"/>
                                                    </svg>

                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            {{-- EMPTY STATE --}}
                            <tr>

                                <td
                                    colspan="5"
                                    class="px-6 py-16 text-center"
                                >

                                    <div class="flex flex-col items-center">

                                        <div
                                            class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-800"
                                        >

                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                                                <circle cx="9" cy="7" r="4"/>
                                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                            </svg>

                                        </div>


                                        <p
                                            class="mt-4 text-sm font-semibold text-gray-900 dark:text-white"
                                        >
                                            Belum ada user
                                        </p>

                                        <p
                                            class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                                        >
                                            Tidak ada data user yang sesuai dengan filter.
                                        </p>

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- ========================================================
                 PAGINATION
            ========================================================= --}}
            @if ($users->hasPages())

                <div
                    class="border-t border-gray-100 px-6 py-4 dark:border-gray-800"
                >
                    {{ $users->links() }}
                </div>

            @endif

        </div>

    </div>


    {{-- ================================================================
         RESET PASSWORD MODAL
    ================================================================= --}}
    <div
        id="resetPasswordModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4 backdrop-blur-sm"
    >

        <div
            class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl dark:bg-gray-900"
        >

            {{-- MODAL HEADER --}}
            <div class="flex items-start justify-between gap-4">

                <div>

                    <h2
                        class="text-lg font-semibold text-gray-900 dark:text-white"
                    >
                        Reset Password
                    </h2>

                    <p
                        class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                    >
                        Atur password baru untuk

                        <span
                            id="resetPasswordUserName"
                            class="font-medium text-gray-700 dark:text-gray-300"
                        ></span>
                    </p>

                </div>


                {{-- CLOSE --}}
                <button
                    type="button"
                    onclick="closeResetPasswordModal()"
                    class="flex h-8 w-8 items-center justify-center rounded-full text-gray-400 transition hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-gray-800 dark:hover:text-white"
                    aria-label="Tutup"
                >

                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M18 6 6 18"/>
                        <path d="m6 6 12 12"/>
                    </svg>

                </button>

            </div>


            {{-- FORM --}}
            <form
                id="resetPasswordForm"
                method="POST"
                class="mt-6 space-y-4"
            >

                @csrf

                @method('PATCH')


                {{-- PASSWORD --}}
                <div>

                    <label
                        for="password"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Password Baru
                    </label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                        class="w-full rounded-xl border-0 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none ring-1 ring-gray-200 transition focus:bg-white focus:ring-2 focus:ring-emerald-500 dark:bg-gray-800 dark:text-white dark:ring-gray-700 dark:focus:bg-gray-800"
                    >

                </div>


                {{-- CONFIRM PASSWORD --}}
                <div>

                    <label
                        for="password_confirmation"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Konfirmasi Password
                    </label>

                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        minlength="8"
                        autocomplete="new-password"
                        placeholder="Ulangi password baru"
                        class="w-full rounded-xl border-0 bg-gray-50 px-4 py-2.5 text-sm text-gray-900 outline-none ring-1 ring-gray-200 transition focus:bg-white focus:ring-2 focus:ring-emerald-500 dark:bg-gray-800 dark:text-white dark:ring-gray-700 dark:focus:bg-gray-800"
                    >

                </div>


                {{-- MODAL ACTION --}}
                <div class="flex justify-end gap-2 pt-2">

                    <button
                        type="button"
                        onclick="closeResetPasswordModal()"
                        class="rounded-xl px-4 py-2.5 text-sm font-medium text-gray-600 ring-1 ring-gray-200 transition hover:bg-gray-50 dark:text-gray-300 dark:ring-gray-700 dark:hover:bg-gray-800"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="rounded-xl bg-emerald-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-600"
                    >
                        Simpan Password
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- ================================================================
         JAVASCRIPT
    ================================================================= --}}
    <script>

        function openResetPasswordModal(userId, userName) {

            const modal = document.getElementById('resetPasswordModal');
            const form = document.getElementById('resetPasswordForm');
            const name = document.getElementById('resetPasswordUserName');

            if (!modal || !form || !name) {
                return;
            }

            form.action = `/admin/users/${userId}/reset-password`;

            name.textContent = userName;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(function () {
                document.getElementById('password')?.focus();
            }, 100);
        }


        function closeResetPasswordModal() {

            const modal = document.getElementById('resetPasswordModal');
            const form = document.getElementById('resetPasswordForm');

            if (!modal) {
                return;
            }

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            if (form) {
                form.reset();
            }
        }


        {{-- Klik backdrop --}}
        document
            .getElementById('resetPasswordModal')
            ?.addEventListener('click', function (event) {

                if (event.target === this) {
                    closeResetPasswordModal();
                }

            });


        {{-- Tombol ESC --}}
        document.addEventListener('keydown', function (event) {

            if (event.key === 'Escape') {
                closeResetPasswordModal();
            }

        });

    </script>

</x-app-layout>
