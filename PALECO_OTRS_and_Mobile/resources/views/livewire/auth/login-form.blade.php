{{-- resources/views/livewire/auth/login-form.blade.php --}}
{{-- This is the Livewire component view.                                              --}}
{{-- The vanilla JS selectRole() function and <script> block have been removed.       --}}
{{-- Role card active state is now controlled by $role property via @if conditions.   --}}
{{-- Form submission is handled by wire:submit="login" instead of POST to controller. --}}

<div>

    {{-- ERROR MESSAGES — same design as Allen's original --}}
    @if ($errors->any())
        <div class="mb-5 p-3.5 bg-rose-50 border-l-4 border-rose-500 rounded text-xs text-rose-700 space-y-1">
            @foreach ($errors->all() as $error)
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ $error }}</span>
                </div>
            @endforeach
        </div>
    @endif

    {{-- FORM — wire:submit replaces action="{{ route('login') }}" method="POST"  --}}
    {{-- No @csrf needed — Livewire handles CSRF protection automatically         --}}
    <form wire:submit="login" class="space-y-5">

        {{-- ROLE SELECTION CARDS                                                      --}}
        {{-- wire:click="selectRole('cwd_officer')" replaces onclick="selectRole()"   --}}
        {{-- Active card styling is controlled by @if($role === '...') instead of JS  --}}
        <div class="grid grid-cols-2 gap-4">

            {{-- CWD Officer Card --}}
            <div
                wire:click="selectRole('cwd_officer')"
                class="role-card p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 flex flex-col justify-between h-24
                    {{ $role === 'cwd_officer'
                        ? 'border-[#10a352] ring-2 ring-emerald-100 bg-emerald-50/20'
                        : 'border-slate-200 bg-white hover:border-slate-300' }}"
            >
                <div class="w-7 h-7 rounded-lg flex items-center justify-start transition-colors
                    {{ $role === 'cwd_officer' ? 'text-[#10a352]' : 'text-slate-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">CWD Officer</p>
                    <p class="text-[11px] text-slate-400 font-light mt-0.5">Web ticket intake</p>
                </div>
            </div>

            {{-- Admin Card --}}
            <div
                wire:click="selectRole('admin')"
                class="role-card p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 flex flex-col justify-between h-24
                    {{ $role === 'admin'
                        ? 'border-[#10a352] ring-2 ring-emerald-100 bg-emerald-50/20'
                        : 'border-slate-200 bg-white hover:border-slate-300' }}"
            >
                <div class="w-7 h-7 rounded-lg flex items-center justify-start transition-colors
                    {{ $role === 'admin' ? 'text-[#10a352]' : 'text-slate-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800">Admin</p>
                    <p class="text-[11px] text-slate-400 font-light mt-0.5">IT Division</p>
                </div>
            </div>

        </div>

        {{-- USERNAME INPUT — wire:model binds to $username property in LoginForm.php --}}
        <div class="space-y-1.5">
            <label for="username" class="text-xs font-semibold text-slate-700 tracking-wide">Username</label>
            <input
                type="text"
                id="username"
                wire:model="username"
                autocomplete="username"
                placeholder="Enter your username"
                class="w-full px-3.5 py-2.5 border rounded-lg text-sm text-slate-800 placeholder-slate-300 transition-all focus:outline-none focus:ring-4
                    {{ $errors->has('username')
                        ? 'border-rose-400 focus:border-rose-400 focus:ring-rose-500/10'
                        : 'border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/10' }}"
            />
        </div>

        {{-- PASSWORD INPUT — wire:model binds to $password property in LoginForm.php --}}
        <div class="space-y-1.5">
            <label for="password" class="text-xs font-semibold text-slate-700 tracking-wide">Password</label>
            <input
                type="password"
                id="password"
                wire:model="password"
                autocomplete="current-password"
                placeholder="••••••••"
                class="w-full px-3.5 py-2.5 border rounded-lg text-sm text-slate-800 placeholder-slate-300 transition-all focus:outline-none focus:ring-4
                    {{ $errors->has('password')
                        ? 'border-rose-400 focus:border-rose-400 focus:ring-rose-500/10'
                        : 'border-slate-200 focus:border-emerald-500 focus:ring-emerald-500/10' }}"
            />
        </div>

        {{-- SUBMIT BUTTON                                                                    --}}
        {{-- wire:loading.attr="disabled" disables the button while Livewire is processing   --}}
        {{-- wire:loading / wire:loading.remove swaps button content during loading          --}}
        <button
            type="submit"
            wire:loading.attr="disabled"
            class="w-full bg-[#10a352] hover:bg-[#0e8f47] text-white font-medium py-3 rounded-lg text-sm transition duration-150
                   flex items-center justify-center gap-2 shadow-lg shadow-emerald-700/10 hover:shadow-emerald-700/20
                   active:scale-[0.99] transform disabled:opacity-70 disabled:cursor-not-allowed"
        >
            {{-- Default state --}}
            <span wire:loading.remove class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                </svg>
                Sign in
            </span>

            {{-- Loading state — shown while Livewire awaits server response --}}
            <span wire:loading class="flex items-center gap-2">
                <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Signing in...
            </span>
        </button>

    </form>
</div>
