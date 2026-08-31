<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Create your account</h1>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div class="group stagger-1">
            <label for="name" class="block text-xs font-bold text-slate-700 mb-2 transition-colors group-focus-within:text-emerald-600">Full name</label>
            <div class="relative">
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                    class="block w-full px-5 py-4 bg-[#F4F7FB] border-transparent {{ $errors->has('name') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'focus:bg-white focus:border-emerald-500 focus:ring-emerald-500/20' }} rounded-2xl text-slate-900 text-sm font-semibold focus:ring-4 transition-all duration-300 ease-out hover:bg-[#EDF2F7] outline-none placeholder:text-slate-400 placeholder:font-medium" 
                    placeholder="Enter Full name">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 font-bold text-xs" />
        </div>

        <!-- Email Address -->
        <div class="group stagger-2">
            <label for="email" class="block text-xs font-bold text-slate-700 mb-2 transition-colors group-focus-within:text-emerald-600">Email</label>
            <div class="relative">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                    class="block w-full px-5 py-4 bg-[#F4F7FB] border-transparent {{ $errors->has('email') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'focus:bg-white focus:border-emerald-500 focus:ring-emerald-500/20' }} rounded-2xl text-slate-900 text-sm font-semibold focus:ring-4 transition-all duration-300 ease-out hover:bg-[#EDF2F7] outline-none placeholder:text-slate-400 placeholder:font-medium" 
                    placeholder="Enter email">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 font-bold text-xs" />
        </div>

        <!-- Password -->
        <div class="group stagger-3">
            <label for="password" class="block text-xs font-bold text-slate-700 mb-2 transition-colors group-focus-within:text-emerald-600">Password</label>
            <div class="relative">
                <input id="password" type="password" name="password" required autocomplete="new-password" 
                    class="block w-full px-5 py-4 bg-[#F4F7FB] border-transparent {{ $errors->has('password') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'focus:bg-white focus:border-emerald-500 focus:ring-emerald-500/20' }} rounded-2xl text-slate-900 text-sm font-semibold focus:ring-4 transition-all duration-300 ease-out hover:bg-[#EDF2F7] outline-none placeholder:text-slate-400 placeholder:font-medium" 
                    placeholder="Enter Password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 font-bold text-xs" />
        </div>

        <!-- Confirm Password -->
        <div class="group stagger-3">
            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-2 transition-colors group-focus-within:text-emerald-600">Confirm Password</label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                    class="block w-full px-5 py-4 bg-[#F4F7FB] border-transparent {{ $errors->has('password_confirmation') ? 'border-red-400 focus:border-red-500 focus:ring-red-500/20' : 'focus:bg-white focus:border-emerald-500 focus:ring-emerald-500/20' }} rounded-2xl text-slate-900 text-sm font-semibold focus:ring-4 transition-all duration-300 ease-out hover:bg-[#EDF2F7] outline-none placeholder:text-slate-400 placeholder:font-medium" 
                    placeholder="Confirm Password">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500 font-bold text-xs" />
        </div>

        <!-- Terms -->
        <div class="pt-1 pb-1 stagger-4">
            <label for="terms" class="inline-flex items-center group cursor-pointer">
                <div class="relative flex items-center justify-center">
                    <input id="terms" type="checkbox" name="terms" required class="peer h-5 w-5 cursor-pointer appearance-none rounded-md border-2 border-slate-300 bg-white hover:border-emerald-500 checked:border-emerald-500 checked:bg-emerald-500 focus:outline-none focus:ring-4 focus:ring-offset-0 focus:ring-emerald-500/20 transition-all">
                    <svg class="absolute w-3 h-3 opacity-0 peer-checked:opacity-100 text-white pointer-events-none transition-opacity" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <span class="ms-3 text-xs font-semibold text-slate-500 group-hover:text-slate-900 transition-colors select-none">I agree to the Terms and Conditions</span>
            </label>
        </div>

        <!-- Submit Button -->
        <div class="pt-2 stagger-4">
            <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold py-4 px-4 rounded-2xl shadow-[0_8px_20px_rgba(16,185,129,0.25)] hover:shadow-[0_12px_25px_rgba(16,185,129,0.35)] hover:-translate-y-0.5 transition-all duration-300 text-sm flex justify-center items-center active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-emerald-500/30">
                Sign Up
            </button>
        </div>
    </form>

    <div class="mt-6 text-center stagger-4" style="animation-delay: 0.5s;">
        <a class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition-colors hover:underline underline-offset-4 focus:outline-none" href="{{ route('login') }}">
            Already have an account? Sign in
        </a>
    </div>
</x-guest-layout>
