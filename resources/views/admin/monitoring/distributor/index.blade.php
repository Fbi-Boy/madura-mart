<x-app-layout>
    <div class="h-full flex flex-col gap-5">
        <div>
            <p class="text-sm text-black/45 dark:text-white/45">Monitoring</p>
            <h2 class="mt-1 text-2xl font-semibold text-[#171719] dark:text-white">Distributor</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
                <p class="text-sm text-black/45 dark:text-white/45">Total Data</p>
                <p class="mt-2 text-2xl font-semibold text-[#171719] dark:text-white">0</p>
            </div>
            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
                <p class="text-sm text-black/45 dark:text-white/45">Status</p>
                <p class="mt-2 text-sm font-medium text-[#171719] dark:text-white">Belum ada data</p>
            </div>
            <div class="rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 p-5">
                <p class="text-sm text-black/45 dark:text-white/45">Informasi</p>
                <p class="mt-2 text-sm font-medium text-[#171719] dark:text-white">Halaman siap digunakan</p>
            </div>
        </div>

        <div class="flex-1 rounded-2xl border border-black/5 dark:border-white/10 bg-white dark:bg-white/5 overflow-hidden">
            <div class="px-5 py-4 border-b border-black/5 dark:border-white/10">
                <h3 class="text-sm font-semibold text-[#171719] dark:text-white">Data Distributor</h3>
            </div>
            <div class="h-full flex items-center justify-center px-5 pb-16">
                <div class="text-center">
                    <div class="mx-auto w-12 h-12 rounded-2xl bg-[#A8F23A]/20 flex items-center justify-center">
                        <svg class="w-6 h-6 text-[#171719] dark:text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="4" width="16" height="16" rx="2"></rect>
                            <path d="M8 9h8M8 13h8M8 17h4"></path>
                        </svg>
                    </div>
                    <p class="mt-4 text-sm font-medium text-[#171719] dark:text-white">Belum ada data</p>
                    <p class="mt-1 text-xs text-black/40 dark:text-white/40">Data distributor akan tampil di halaman ini.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>