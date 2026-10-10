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
                    Tambah User
                </h2>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Buat akun baru untuk pengguna War-MART.
                </p>

            </div>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">

            @if($errors->any())

                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 dark:border-red-900/40 dark:bg-red-950/30">

                    <p class="text-sm font-semibold text-red-700 dark:text-red-300">
                        Periksa kembali data yang dimasukkan.
                    </p>

                    <ul class="mt-2 space-y-1 text-xs text-red-600 dark:text-red-400">

                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            <div class="rounded-2xl bg-white p-5 ring-1 ring-gray-200/70 sm:p-7 dark:bg-gray-900 dark:ring-gray-800">

                <form
                    method="POST"
                    action="{{ route('admin.users.store') }}"
                >

                    @csrf

                    @include('admin.users._form')

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
