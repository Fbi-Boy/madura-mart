<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'War-MART') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >
</head>

<body class="h-screen overflow-hidden bg-[#f3f5f4] font-['Inter',sans-serif] antialiased">

    <div class="flex h-screen items-center justify-center p-3 sm:p-4 lg:p-5">

        <div
            class="h-full max-h-[720px] w-full max-w-6xl overflow-hidden rounded-2xl bg-white shadow-[0_20px_60px_rgba(15,23,42,0.10)] ring-1 ring-slate-200"
        >

            <div class="grid h-full lg:grid-cols-2">

                {{-- =====================================================
                    LEFT
                ====================================================== --}}
                <div class="flex h-full min-h-0 flex-col bg-white px-6 py-5 sm:px-9 sm:py-6 lg:px-12">

                    {{-- Logo --}}
                    <div class="flex shrink-0 items-center gap-2">

                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-600 text-white shadow-sm"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                class="h-5 w-5"
                            >
                                <path
                                    d="M7 3.5L12 6.5L17 3.5M7 20.5L12 17.5L17 20.5M3.5 7L8.5 10V14L3.5 17M20.5 7L15.5 10V14L20.5 17M8.5 10L12 12L15.5 10M8.5 14L12 12L15.5 14"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </div>

                        <span class="text-[15px] font-semibold tracking-tight text-slate-900">
                            War-MART
                        </span>

                    </div>


                    {{-- Authentication content --}}
                    <div class="flex min-h-0 flex-1 items-center">

                        <div class="mx-auto w-full max-w-[390px]">

                            {{ $slot }}

                        </div>

                    </div>


                    {{-- Footer --}}
                    <div class="flex shrink-0 items-center justify-between text-[9px] text-slate-400">

                        <span>
                            Copyright © {{ date('Y') }} War-MART
                        </span>

                        <span>
                            Privacy Policy
                        </span>

                    </div>

                </div>


                {{-- =====================================================
                    RIGHT
                ====================================================== --}}
                <div
                    class="relative hidden h-full overflow-hidden bg-green-600 lg:flex"
                >

                    {{-- Background shapes --}}
                    <div class="absolute inset-0 overflow-hidden">

                        <div
                            class="absolute -left-28 -top-28 h-80 w-80 rounded-full bg-green-500/50"
                        ></div>

                        <div
                            class="absolute -right-32 top-12 h-[420px] w-[420px] rounded-full bg-green-700/40"
                        ></div>

                        <div
                            class="absolute -bottom-44 left-16 h-[430px] w-[430px] rounded-full bg-green-500/30"
                        ></div>

                        <div
                            class="absolute right-24 top-0 h-full w-px bg-white/5"
                        ></div>

                        <div
                            class="absolute right-44 top-0 h-full w-px bg-white/5"
                        ></div>

                    </div>


                    <div class="relative z-10 flex h-full w-full flex-col px-9 py-9 xl:px-12">

                        {{-- Heading --}}
                        <div class="shrink-0 max-w-[430px]">

                            <h2
                                class="text-[28px] font-semibold leading-[1.18] tracking-tight text-white xl:text-[32px]"
                            >
                                Kelola toko dan
                                <br>
                                operasional dengan mudah.
                            </h2>

                            <p class="mt-3 max-w-[370px] text-xs leading-5 text-green-50/80">
                                Kelola produk, penjualan, persediaan, pembelian,
                                dan aktivitas toko dalam satu sistem.
                            </p>

                        </div>


                        {{-- Dashboard illustration --}}
                        <div class="relative min-h-0 flex-1">

                            {{-- Main dashboard --}}
                            <div
                                class="absolute left-1/2 top-7 w-[91%] -translate-x-1/2 rounded-xl bg-white p-3 shadow-2xl shadow-green-950/30"
                            >

                                {{-- Header --}}
                                <div class="flex items-center justify-between px-2 pb-2">

                                    <div class="flex items-center gap-2">

                                        <div class="h-2.5 w-2.5 rounded-full bg-green-500"></div>

                                        <div class="h-2 w-20 rounded-full bg-slate-200"></div>

                                    </div>

                                    <div class="flex gap-1.5">

                                        <div class="h-2 w-2 rounded-full bg-slate-200"></div>
                                        <div class="h-2 w-2 rounded-full bg-slate-200"></div>

                                    </div>

                                </div>


                                {{-- Stats --}}
                                <div class="grid grid-cols-3 gap-2">

                                    <div class="rounded-lg bg-green-50 p-2.5">

                                        <div class="h-1.5 w-11 rounded bg-green-200"></div>

                                        <div class="mt-1.5 text-[12px] font-bold text-green-700">
                                            Rp18.9jt
                                        </div>

                                        <div class="mt-1 h-1.5 w-14 rounded bg-green-100"></div>

                                    </div>

                                    <div class="rounded-lg bg-slate-50 p-2.5">

                                        <div class="h-1.5 w-11 rounded bg-slate-200"></div>

                                        <div class="mt-1.5 text-[12px] font-bold text-slate-700">
                                            01:30
                                        </div>

                                        <div class="mt-1 h-1.5 w-14 rounded bg-slate-200"></div>

                                    </div>

                                    <div class="rounded-lg bg-emerald-50 p-2.5">

                                        <div class="h-1.5 w-11 rounded bg-emerald-200"></div>

                                        <div class="mt-1.5 text-[12px] font-bold text-emerald-700">
                                            6,248
                                        </div>

                                        <div class="mt-1 h-1.5 w-14 rounded bg-emerald-100"></div>

                                    </div>

                                </div>


                                {{-- Charts --}}
                                <div class="mt-2 grid grid-cols-[1.5fr_1fr] gap-2">

                                    {{-- Line chart --}}
                                    <div class="rounded-lg border border-slate-100 p-2.5">

                                        <div class="flex items-center justify-between">

                                            <div>
                                                <div class="h-1.5 w-14 rounded bg-slate-200"></div>
                                                <div class="mt-1 h-1 w-9 rounded bg-slate-100"></div>
                                            </div>

                                            <div class="h-1.5 w-7 rounded bg-green-100"></div>

                                        </div>

                                        <div class="relative mt-3 h-[75px]">

                                            <div
                                                class="absolute inset-x-0 top-1/2 border-t border-dashed border-slate-100"
                                            ></div>

                                            <div
                                                class="absolute inset-x-0 top-1/4 border-t border-dashed border-slate-100"
                                            ></div>

                                            <svg
                                                viewBox="0 0 300 100"
                                                class="absolute inset-0 h-full w-full"
                                                preserveAspectRatio="none"
                                            >
                                                <path
                                                    d="M0 78 C25 65,40 72,62 57 S95 64,118 42 S148 51,170 35 S205 46,225 22 S260 35,300 10"
                                                    fill="none"
                                                    stroke="#16a34a"
                                                    stroke-width="4"
                                                    stroke-linecap="round"
                                                />
                                            </svg>

                                        </div>

                                    </div>


                                    {{-- Donut --}}
                                    <div class="rounded-lg border border-slate-100 p-2.5">

                                        <div class="h-1.5 w-14 rounded bg-slate-200"></div>

                                        <div class="flex items-center justify-center py-3">

                                            <div
                                                class="relative flex h-[82px] w-[82px] items-center justify-center rounded-full"
                                                style="background: conic-gradient(#16a34a 0deg 220deg, #4ade80 220deg 285deg, #dcfce7 285deg 360deg);"
                                            >

                                                <div
                                                    class="flex h-12 w-12 items-center justify-center rounded-full bg-white"
                                                >
                                                    <span class="text-[9px] font-bold text-slate-700">
                                                        6,248
                                                    </span>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- Table --}}
                                <div class="mt-2 rounded-lg border border-slate-100 p-2.5">

                                    <div class="mb-2 flex items-center justify-between">

                                        <div class="h-1.5 w-20 rounded bg-slate-200"></div>

                                        <div class="h-1.5 w-8 rounded bg-slate-100"></div>

                                    </div>

                                    <div class="space-y-1.5">

                                        @foreach (range(1, 4) as $item)

                                            <div class="flex items-center gap-2">

                                                <div class="h-3.5 w-3.5 rounded bg-slate-100"></div>

                                                <div class="flex-1">
                                                    <div class="h-1.5 w-20 rounded bg-slate-200"></div>
                                                </div>

                                                <div class="h-1.5 w-8 rounded bg-slate-100"></div>

                                                <div class="h-1.5 w-7 rounded bg-green-100"></div>

                                            </div>

                                        @endforeach

                                    </div>

                                </div>

                            </div>


                            {{-- Floating card --}}
                            <div
                                class="absolute bottom-7 right-0 w-32 rounded-xl bg-white p-2.5 shadow-xl shadow-green-950/30"
                            >

                                <div class="flex items-center justify-between">

                                    <div class="h-1.5 w-10 rounded bg-slate-200"></div>

                                    <div class="h-2 w-2 rounded-full bg-green-400"></div>

                                </div>

                                <div class="mt-2 text-base font-bold text-slate-800">
                                    94.8%
                                </div>

                                <div class="mt-2 flex items-end gap-1">

                                    <div class="h-3 w-2 rounded-sm bg-green-200"></div>
                                    <div class="h-5 w-2 rounded-sm bg-green-300"></div>
                                    <div class="h-7 w-2 rounded-sm bg-green-400"></div>
                                    <div class="h-4 w-2 rounded-sm bg-green-300"></div>
                                    <div class="h-9 w-2 rounded-sm bg-green-500"></div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>
