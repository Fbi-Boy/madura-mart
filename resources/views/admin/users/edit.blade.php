<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center gap-3">

            <a
                href="{{ route('admin.users.index') }}"
                class="flex h-9 w-9 items-center justify-center rounded-xl border border-gray-200 text-gray-500 transition hover:bg-gray-50 hover:text-gray-900 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white"
            >

                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M15 19l-7-7 7-7"/>
                </svg>

            </a>


            <div>

                <p class="text-xs font-medium text-gray-400 dark:text-gray-500">
                    User & Staff
                </p>

                <h2 class="mt-1 text-xl font-semibold tracking-tight text-gray-900 dark:text-white">
                    Edit User
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Perbarui informasi dan akses akun pengguna.
                </p>

            </div>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="mx-auto max-w-3xl space-y-5 px-4 sm:px-6 lg:px-8">

            @if(session('success'))

                <div class="flex items-center gap-3 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 dark:border-green-900/40 dark:bg-green-950/30">

                    <div class="flex h-7 w-7 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/50 dark:text-green-400">

                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7"/>
                        </svg>

                    </div>

                    <p class="text-sm font-medium text-green-700 dark:text-green-300">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


            @if($errors->any())

                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 dark:border-red-900/40 dark:bg-red-950/30">

                    <p class="text-sm font-semibold text-red-700 dark:text-red-300">
                        Terdapat kesalahan
                    </p>

                    <ul class="mt-2 space-y-1 text-xs text-red-600 dark:text-red-400">

                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- Profile summary --}}
            <div class="rounded-2xl bg-white p-5 ring-1 ring-gray-200/70 dark:bg-gray-900 dark:ring-gray-800">

                <div class="flex items-center gap-4">

                    @php
                        $initials = collect(explode(' ', trim($user->name)))
                            ->filter()
                            ->take(2)
                            ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
                            ->implode('');
                    @endphp

                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-green-50 text-lg font-bold text-green-700 dark:bg-green-950/50 dark:text-green-400">
                        {{ $initials ?: 'U' }}
                    </div>

                    <div class="min-w-0">

                        <h3 class="truncate text-base font-semibold text-gray-900 dark:text-white">
                            {{ $user->name }}
                        </h3>

                        <p class="truncate text-xs text-gray-500 dark:text-gray-400">
                            {{ $user->email }}
                        </p>

                        <div class="mt-2 flex items-center gap-2">

                            <span class="rounded-lg bg-gray-100 px-2 py-1 text-[10px] font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                {{ ucfirst(str_replace('-', ' ', $user->role)) }}
                            </span>

                            @if($user->is_active)

                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-green-600 dark:text-green-400">
                                    <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                    Aktif
                                </span>

                            @else

                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-red-500">
                                    <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                    Nonaktif
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

            </div>


            {{-- Main form --}}
            <div class="rounded-2xl bg-white p-5 ring-1 ring-gray-200/70 sm:p-7 dark:bg-gray-900 dark:ring-gray-800">

                <form
                    method="POST"
                    action="{{ route('admin.users.update', $user) }}"
                >

                    @csrf
                    @method('PUT')

                    @include('admin.users._form')

                </form>

            </div>


            {{-- Reset password --}}
            <div class="rounded-2xl bg-white p-5 ring-1 ring-red-100 sm:p-6 dark:bg-gray-900 dark:ring-red-950/50">

                <div class="flex items-start gap-3">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-500 dark:bg-red-950/40">

                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 15v2m-6 3h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm3-9V7a3 3 0 016 0v3"/>
                        </svg>

                    </div>

                    <div>

                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                            Reset Password
                        </h3>

                        <p class="mt-1 text-xs leading-5 text-gray-500 dark:text-gray-400">
                            Gunakan fitur ini jika pengguna membutuhkan password baru.
                        </p>

                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route('admin.users.reset-password', $user) }}"
                    class="mt-5"
                >

                    @csrf
                    @method('PATCH')

                    <div class="grid gap-4 md:grid-cols-2">

                        <div>

                            <label
                                for="reset_password"
                                class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                            >
                                Password Baru
                            </label>

                            <input
                                id="reset_password"
                                type="password"
                                name="password"
                                required
                                placeholder="Minimal 8 karakter"
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm outline-none transition focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:bg-gray-900"
                            >

                        </div>


                        <div>

                            <label
                                for="reset_password_confirmation"
                                class="mb-1.5 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                            >
                                Konfirmasi
                            </label>

                            <input
                                id="reset_password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                placeholder="Ulangi password"
                                class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50 px-3 text-sm outline-none transition focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:bg-gray-900"
                            >

                        </div>

                    </div>


                    <button
                        type="submit"
                        class="mt-4 inline-flex h-10 items-center gap-2 rounded-xl bg-gray-900 px-4 text-xs font-semibold text-white transition hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-100"
                    >

                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M15 7a3 3 0 11-6 0 3 3 0 016 0zM4 21a8 8 0 0116 0"/>
                        </svg>

                        Reset Password

                    </button>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
