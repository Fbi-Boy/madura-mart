<x-app-layout>
    <x-slot name="header">
        <div><h2 class="text-xl font-semibold text-gray-900 dark:text-white">Edit User</h2><p class="text-sm text-gray-500 dark:text-gray-400">Perbarui identitas dan role akun.</p></div>
    </x-slot>
    <div class="py-6"><div class="mx-auto max-w-3xl space-y-5 px-4 sm:px-6 lg:px-8">
        @if(session('success'))<div class="rounded-2xl border border-[#A8F23A]/50 bg-[#A8F23A]/10 px-4 py-3 text-sm">{{ session('success') }}</div>@endif
        <form method="POST" action="{{ route('admin.users.update',$user) }}" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">@csrf @method('PUT') @include('admin.users._form')</form>
        <form method="POST" action="{{ route('admin.users.reset-password',$user) }}" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            @csrf @method('PATCH')
            <h3 class="font-semibold text-gray-900 dark:text-white">Reset Password</h3>
            <p class="mt-1 text-xs text-gray-500">Password baru tidak ditampilkan kembali setelah disimpan.</p>
            <div class="mt-4 grid gap-4 md:grid-cols-2">
                <input type="password" name="password" placeholder="Password baru" class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white" required>
                <input type="password" name="password_confirmation" placeholder="Konfirmasi password" class="rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white" required>
            </div>
            <button class="mt-4 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white dark:bg-[#A8F23A] dark:text-gray-900">Reset Password</button>
        </form>
    </div></div>
</x-app-layout>