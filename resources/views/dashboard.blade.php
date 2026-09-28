<x-app-layout>
    <div class="space-y-5">
        <div>
            <p class="text-xs font-medium text-black/40 dark:text-white/40">Ringkasan aktivitas</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#171719] dark:text-white">dashboard</h1>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[18px] bg-white dark:bg-white/[0.05] p-5">
                <p class="text-xs text-black/45 dark:text-white/45">Status akun</p>
                <p class="mt-2 text-lg font-semibold">Aktif</p>
            </div>
            <div class="rounded-[18px] bg-white dark:bg-white/[0.05] p-5">
                <p class="text-xs text-black/45 dark:text-white/45">Role</p>
                <p class="mt-2 text-lg font-semibold capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
            </div>
            <div class="rounded-[18px] bg-white dark:bg-white/[0.05] p-5">
                <p class="text-xs text-black/45 dark:text-white/45">Login sebagai</p>
                <p class="mt-2 truncate text-lg font-semibold">{{ auth()->user()->name }}</p>
            </div>
            <div class="rounded-[18px] bg-[#A8F23A] p-5 text-[#171719]">
                <p class="text-xs opacity-60">Sistem</p>
                <p class="mt-2 text-lg font-semibold">Madura Mart</p>
            </div>
        </div>
    </div>
</x-app-layout>
