<x-guest-layout>

    <div>

        <div class="text-center">

            <h1 class="text-[28px] font-semibold tracking-tight text-slate-900">
                Create Account
            </h1>

            <p class="mt-1.5 text-[11px] leading-5 text-slate-400">
                Buat akun War-MART untuk mulai menggunakan sistem.
            </p>

        </div>


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
            action="{{ route('register') }}"
            class="mt-5 space-y-3"
        >

            @csrf


            {{-- Name --}}
            <div>

                <label
                    for="name"
                    class="mb-1.5 block text-[10px] font-semibold text-slate-700"
                >
                    Full Name
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Masukkan nama lengkap"
                    class="block h-10 w-full rounded-lg border border-slate-200 bg-white px-3 text-xs text-slate-800 outline-none transition placeholder:text-slate-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/10"
                >

            </div>


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
                    autocomplete="username"
                    placeholder="Masukkan email"
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
                        autocomplete="new-password"
                        placeholder="Buat password"
                        class="block h-10 w-full rounded-lg border border-slate-200 bg-white px-3 pr-10 text-xs text-slate-800 outline-none transition placeholder:text-slate-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/10"
                    >

                    <button
                        type="button"
                        onclick="togglePassword('password', this)"
                        class="absolute right-0 top-0 flex h-10 w-10 items-center justify-center text-slate-400 hover:text-green-600"
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


            {{-- Confirm Password --}}
            <div>

                <label
                    for="password_confirmation"
                    class="mb-1.5 block text-[10px] font-semibold text-slate-700"
                >
                    Confirm Password
                </label>

                <div class="relative">

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi password"
                        class="block h-10 w-full rounded-lg border border-slate-200 bg-white px-3 pr-10 text-xs text-slate-800 outline-none transition placeholder:text-slate-300 focus:border-green-500 focus:ring-2 focus:ring-green-500/10"
                    >

                    <button
                        type="button"
                        onclick="togglePassword('password_confirmation', this)"
                        class="absolute right-0 top-0 flex h-10 w-10 items-center justify-center text-slate-400 hover:text-green-600"
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


            <button
                type="submit"
                class="mt-2 flex h-10 w-full items-center justify-center rounded-lg bg-green-600 text-[11px] font-semibold text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/30 active:scale-[0.99]"
            >
                Create Account
            </button>

        </form>


        @if (Route::has('login'))

            <p class="mt-5 text-center text-[9px] text-slate-400">

                Already Have An Account?

                <a
                    href="{{ route('login') }}"
                    class="font-semibold text-green-600 hover:text-green-700"
                >
                    Login Now.
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
