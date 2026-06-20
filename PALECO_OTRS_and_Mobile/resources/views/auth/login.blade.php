<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PALECO CRM-CWD - Sign in</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4 md:p-6 select-none">

    <div class="w-full max-w-5xl bg-white rounded-xl shadow-2xl flex flex-col md:flex-row overflow-hidden border border-slate-200" style="min-height: 580px;">
        
        <div class="w-full md:w-[45%] bg-[#063321] text-white p-8 flex flex-col justify-between relative overflow-hidden">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-lg font-bold tracking-wide">PALECO CRM-CWD</h1>
                </div>
                <p class="text-xs text-emerald-300 font-light mt-0.5">Consumer Welfare Desk Portal</p>

                <div class="mt-12 space-y-3">
                    <h2 class="text-2xl md:text-3xl font-semibold tracking-tight leading-snug">Ticketing & Dispatch System</h2>
                    <p class="text-sm text-slate-300 font-light leading-relaxed max-w-sm">
                        24/7 complaint intake, team assignment, and cooperative-level reporting for PALECO's Member Services Division.
                    </p>
                </div>
            </div>

            <div class="my-10 md:my-0 flex justify-center items-center">
                <img src="{{ asset('images/paleco-logo.png') }}" alt="PALECO Logo" class="w-56 h-auto drop-shadow-xl transform hover:scale-105 transition duration-300">
            </div>

            <div class="text-xs text-slate-400 font-light tracking-wide mt-auto">
                Hotlines: Globe & Smart
            </div>
        </div>

        <div class="w-full md:w-[55%] p-8 md:p-12 flex flex-col justify-center">
            
            <header class="mb-6">
                <h3 class="text-2xl font-bold text-slate-800">Sign in</h3>
                <p class="text-sm text-slate-500 mt-1">Provide your credentials to continue</p>
            </header>

            @if ($errors->any())
                <div class="mb-5 p-3.5 bg-rose-50 border-l-4 border-rose-500 rounded text-xs text-rose-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <span>{{ $error }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-1.5">
                    <label for="role" class="text-xs font-semibold text-slate-700 tracking-wide">
                        Role <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="role" name="role" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 appearance-none bg-white transition-all cursor-pointer">
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select your role...</option>
                            <option value="cwd" {{ old('role') == 'cwd' ? 'selected' : '' }}>CWD Officer</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between items-end">
                        <label for="username" class="text-xs font-semibold text-slate-700 tracking-wide">
                            Username <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] text-slate-400">Required</span>
                    </div>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Enter your username" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder-slate-300 transition-all">
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between items-end">
                        <label for="password" class="text-xs font-semibold text-slate-700 tracking-wide">
                            Password <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] text-slate-400">Required</span>
                    </div>
                    <input type="password" id="password" name="password" placeholder="••••••••" required minlength="8" class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder-slate-300 transition-all">
                </div>

                <button type="submit" class="w-full bg-[#10a352] hover:bg-[#0e8f47] text-white font-medium py-3 rounded-lg text-sm transition duration-150 flex items-center justify-center gap-2 shadow-lg shadow-emerald-700/10 hover:shadow-emerald-700/20 active:scale-[0.99] transform mt-2">
                    <svg xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                    </svg>
                    Sign in
                </button>

            </form>
        </div>

    </div>
</body>
</html>