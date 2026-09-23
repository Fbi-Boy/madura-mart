<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    Selamat Datang, {{ auth()->user()->name }}
                </h1>

                <p class="text-gray-500 mt-2">
                    Anda login sebagai kasir Madura Mart.
                </p>
            </div>

        </div>
    </div>

</x-app-layout>
