<section class="rounded-2xl border border-black/5 bg-white dark:border-white/10 dark:bg-white/5 font-poppins">

    {{-- ================= HEADER ================= --}}
    <div class="px-5 py-4 border-b border-black/5 dark:border-white/10 flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl bg-lime-100 dark:bg-lime-900/30 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
        </div>
        <div>
            <h2 class="text-sm font-semibold text-[#171719] dark:text-white">
                {{ __('Informasi Profil') }}
            </h2>
            <p class="mt-0.5 text-[11px] text-black/45 dark:text-white/45">
                {{ __("Perbarui informasi akun dan alamat email Anda.") }}
            </p>
        </div>
    </div>

    {{-- ================= FORM SEND VERIFICATION (hidden) ================= --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- ================= FORM UPDATE PROFILE ================= --}}
    <form method="post" action="{{ route('profile.update') }}" class="p-5 space-y-5">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- NAMA --}}
            <div>
                <label for="name" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                    {{ __('Nama Lengkap') }}
                </label>
                <input id="name" name="name" type="text"
                    value="{{ old('name', $user->name) }}"
                    required autofocus autocomplete="name"
                    placeholder="Masukkan nama lengkap"
                    class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
                @error('name')
                    <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label for="email" class="text-[11px] font-medium uppercase tracking-wider text-black/45 dark:text-white/45">
                    {{ __('Email') }}
                </label>
                <input id="email" name="email" type="email"
                    value="{{ old('email', $user->email) }}"
                    required autocomplete="username"
                    placeholder="email@example.com"
                    class="mt-2 w-full h-11 px-4 rounded-xl border border-black/10 dark:border-white/10 bg-transparent text-sm text-[#171719] dark:text-white focus:outline-none focus:border-lime-400 transition">
                @error('email')
                    <p class="mt-1.5 text-[10px] font-medium text-red-500">{{ $message }}</p>
                @enderror

                {{-- VERIFIKASI EMAIL BELUM --}}
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10 p-3">
                        <div class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-[11px] text-amber-800 dark:text-amber-200">
                                    {{ __('Alamat email Anda belum diverifikasi.') }}
                                </p>
                                <button form="send-verification" type="submit"
                                    class="mt-1 text-[11px] font-semibold text-amber-700 dark:text-amber-300 hover:underline focus:outline-none">
                                    {{ __('Klik di sini untuk kirim ulang email verifikasi.') }}
                                </button>
                            </div>
                        </div>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 text-[11px] font-medium text-lime-700 dark:text-lime-400 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path d="M20 6 9 17l-5-5"/>
                                </svg>
                                {{ __('Link verifikasi baru sudah dikirim ke email Anda.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

        </div>

        {{-- ACTIONS --}}
        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="h-10 px-5 rounded-xl bg-lime-400 hover:bg-lime-500 text-[#171719] text-xs font-bold flex items-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M20 6 9 17l-5-5"/>
                </svg>
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 2000)"
                   class="text-[11px] font-medium text-lime-600 dark:text-lime-400 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M20 6 9 17l-5-5"/>
                    </svg>
                    {{ __('Tersimpan.') }}
                </p>
            @endif
        </div>

    </form>

</section>

<style>
    .font-poppins, .font-poppins * { font-family: 'Poppins', system-ui, -apple-system, sans-serif; }
</style>
