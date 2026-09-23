<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                    Selamat Datang, {{ auth()->user()->name }}
                </h1>

                <p class="text-gray-500 dark:text-gray-400 mt-2">
                    Anda login sebagai Administrator Madura Mart.
                </p>
            </div>

        </div>
    </div>

</x-app-layout>
