<x-guest-layout>

    <div>

        <div class="text-center">

            <h1 class="text-[28px] font-semibold tracking-tight text-slate-900">
                Welcome Back
            </h1>

            <p class="mt-1.5 text-[11px] leading-5 text-slate-400">
                Masukkan email dan password untuk mengakses akun Anda.
            </p>

        </div>


        <x-auth-session-status
            class="mt-4"
            :status="session('status')"
        />


        @if ($errors->any())

            <div class="mt-4 rounded-lg bg-red-50 px-3 py-2.5 text-[11px] text-red-600">

                <ul class="space-y-0.5">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('login') }}"
            class="mt-6 space-y-4"
        >

            @csrf


            {{-- Email --}}
            <div>

                <label
                    for="email"
                    class="mb-1.5 block text-[10px] font-semibold text-slate-700"
                >
                    Email
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Masukkan email Anda"
                    class="block h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs text-slate-800 outline-none transition placeholder:text-slate-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/10"
                >

            </div>


            {{-- Password --}}
            <div>

                <label
                    for="password"
                    class="mb-1.5 block text-[10px] font-semibold text-slate-700"
                >
                    Password
                </label>

                <div class="relative">

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                        class="block h-10 w-full rounded-lg border border-slate-200 bg-white px-3 pr-10 text-xs text-slate-800 outline-none transition placeholder:text-slate-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/10"
                    >

                    <button
                        type="button"
                        onclick="togglePassword('password', this)"
                        class="absolute right-0 top-0 flex h-10 w-10 items-center justify-center text-slate-400 transition hover:text-green-600"
                    >

                        <svg
                            class="eye-open h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"/>
                            <circle cx="12" cy="12" r="2.5"/>
                        </svg>

                        <svg
                            class="eye-closed hidden h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="m3 3 18 18"/>
                            <path d="M10.6 6.2A10.7 10.7 0 0 1 12 6c6.5 0 10 6 10 6a18 18 0 0 1-3.1 3.6"/>
                            <path d="M6.6 6.6C3.5 8.6 2 12 2 12s3.5 6 10 6c1.7 0 3.2-.4 4.5-1"/>
                        </svg>

                    </button>

                </div>

            </div>


            {{-- Remember --}}
            <div class="flex items-center justify-between">

                <label class="flex cursor-pointer items-center gap-1.5">

                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="h-3 w-3 rounded border-slate-300 text-green-600 focus:ring-green-500"
                    >

                    <span class="text-[9px] text-slate-500">
                        Remember Me
                    </span>

                </label>


                @if (Route::has('password.request'))

                    <a
                        href="{{ route('password.request') }}"
                        class="text-[9px] font-medium text-green-600 hover:text-green-700"
                    >
                        Forgot Your Password?
                    </a>

                @endif

            </div>


            {{-- Login --}}
            <button
                type="submit"
                class="flex h-10 w-full items-center justify-center rounded-lg bg-green-600 text-[11px] font-semibold text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/30 active:scale-[0.99]"
            >
                Log In
            </button>

        </form>


        {{-- Divider --}}
        <div class="my-5 flex items-center gap-3">

            <div class="h-px flex-1 bg-slate-100"></div>

            <span class="text-[9px] text-slate-400">
                Or Login With
            </span>

            <div class="h-px flex-1 bg-slate-100"></div>

        </div>


        {{-- Social --}}
        <div class="grid grid-cols-2 gap-2.5">

            {{-- Google --}}
            <button
                type="button"
                class="flex h-9 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white text-[10px] font-medium text-slate-600 transition hover:bg-slate-50 hover:border-slate-300"
            >

                <svg
                    class="h-[16px] w-[16px] shrink-0"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <defs>
                        <linearGradient
                            id="googleGradient"
                            x1="3"
                            y1="3"
                            x2="21"
                            y2="21"
                            gradientUnits="userSpaceOnUse"
                        >
                            <stop offset="0" stop-color="#4285F4"/>
                            <stop offset="0.35" stop-color="#34A853"/>
                            <stop offset="0.68" stop-color="#FBBC05"/>
                            <stop offset="1" stop-color="#EA4335"/>
                        </linearGradient>
                    </defs>

                    <path
                        d="M21.35 12.27c0-.71-.06-1.4-.18-2.05H12v3.88h5.23a4.47 4.47 0 0 1-1.94 2.93v2.43h3.14c1.84-1.69 2.92-4.18 2.92-7.19Z"
                        fill="#4285F4"
                    />

                    <path
                        d="M12 21.6c2.63 0 4.84-.87 6.45-2.36l-3.14-2.43c-.87.58-1.98.92-3.31.92-2.54 0-4.7-1.72-5.47-4.03H3.28v2.51A9.75 9.75 0 0 0 12 21.6Z"
                        fill="#34A853"
                    />

                    <path
                        d="M6.53 13.7a5.87 5.87 0 0 1 0-3.4V7.79H3.28a9.75 9.75 0 0 0 0 8.42l3.25-2.51Z"
                        fill="#FBBC05"
                    />

                    <path
                        d="M12 6.28c1.43 0 2.71.49 3.72 1.46l2.79-2.79C16.83 3.4 14.63 2.5 12 2.5a9.75 9.75 0 0 0-8.72 5.29l3.25 2.51C7.3 8 9.46 6.28 12 6.28Z"
                        fill="#EA4335"
                    />
                </svg>

                <span>Google</span>

            </button>


            {{-- Apple --}}
            <button
                type="button"
                class="flex h-9 items-center justify-center gap-2 rounded-lg border border-slate-200 bg-white text-[10px] font-medium text-slate-600 transition hover:bg-slate-50 hover:border-slate-300"
            >

                <svg
                    class="h-[16px] w-[16px] shrink-0 text-slate-900"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M17.05 12.54c-.02-2.29 1.87-3.39 1.96-3.44a4.2 4.2 0 0 0-3.32-1.8c-1.4-.15-2.76.83-3.47.83-.72 0-1.83-.81-3.01-.79-1.54.03-2.96.9-3.75 2.29-1.61 2.8-.41 6.94 1.15 9.22.77 1.12 1.67 2.36 2.87 2.31 1.15-.05 1.58-.75 2.97-.75 1.38 0 1.78.75 2.98.72 1.24-.02 2.03-1.13 2.78-2.25.87-1.27 1.23-2.5 1.25-2.57-.03-.01-2.39-.92-2.41-3.77Z"
                    />

                    <path
                        d="M14.76 5.81c.63-.77 1.05-1.85.93-2.92-.91.04-2 .61-2.65 1.38-.58.67-1.09 1.76-.95 2.8 1.01.08 2.04-.51 2.67-1.26Z"
                    />
                </svg>

                <span>Apple</span>

            </button>

        </div>


        @if (Route::has('register'))

            <p class="mt-5 text-center text-[9px] text-slate-400">

                Don't Have An Account?

                <a
                    href="{{ route('register') }}"
                    class="font-semibold text-green-600 hover:text-green-700"
                >
                    Register Now.
                </a>

            </p>

        @endif

    </div>


    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeOpen = button.querySelector('.eye-open');
            const eyeClosed = button.querySelector('.eye-closed');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>

</x-guest-layout>
