<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PALECO CRM-CWD - Sign in</title>
    
    <!-- This compiles your node_modules Tailwind configurations locally -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center p-4 md:p-6 select-none">

    <div class="w-full max-w-5xl bg-white rounded-xl shadow-2xl flex flex-col md:flex-row overflow-hidden border border-slate-200" style="min-height: 580px;">
        
        <!-- LEFT SIDEBAR: Branding -->
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
                <img src="https://imgur.com" alt="PALECO Logo" class="w-56 h-auto drop-shadow-xl transform hover:scale-105 transition duration-300">
            </div>

            <div class="text-xs text-slate-400 font-light tracking-wide mt-auto">
                Hotlines: Globe & Smart
            </div>
        </div>

        <!-- RIGHT SIDEBAR: Authentication Form -->
        <div class="w-full md:w-[55%] p-8 md:p-12 flex flex-col justify-center">
            
            <header class="mb-6">
                <h3 class="text-2xl font-bold text-slate-800">Sign in</h3>
                <p class="text-sm text-slate-500 mt-1">Select your role to continue</p>
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

                <input type="hidden" id="role-input" name="role" value="{{ old('role', 'admin') }}">

                <!-- ROLE SELECTION CARDS -->
                <div class="grid grid-cols-2 gap-4">
                    
                    <!-- CWD Officer Card -->
                    <div id="card-cwd" onclick="selectRole('cwd')" class="role-card p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 flex flex-col justify-between h-24 border-slate-200 hover:border-slate-300 bg-white group">
                        <div id="icon-cwd" class="w-7 h-7 rounded-lg flex items-center justify-start text-slate-400 group-hover:text-slate-600 transition-colors">
                            <svg xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">CWD Officer</p>
                            <p class="text-[11px] text-slate-400 font-light mt-0.5">Web ticket intake</p>
                        </div>
                    </div>

                    <!-- Admin Card -->
                    <div id="card-admin" onclick="selectRole('admin')" class="role-card p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 flex flex-col justify-between h-24 border-slate-200 hover:border-slate-300 bg-white group">
                        <div id="icon-admin" class="w-7 h-7 rounded-lg flex items-center justify-start text-slate-400 group-hover:text-slate-600 transition-colors">
                            <svg xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Admin</p>
                            <p class="text-[11px] text-slate-400 font-light mt-0.5">IT Division</p>
                        </div>
                    </div>

                </div>

                <!-- USERNAME INPUT -->
                <div class="space-y-1.5">
                    <label for="username" class="text-xs font-semibold text-slate-700 tracking-wide">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="name@email.com" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder-slate-300 transition-all">
                </div>

                <!-- PASSWORD INPUT -->
                <div class="space-y-1.5">
                    <label for="password" class="text-xs font-semibold text-slate-700 tracking-wide">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 placeholder-slate-300 transition-all">
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" class="w-full bg-[#10a352] hover:bg-[#0e8f47] text-white font-medium py-3 rounded-lg text-sm transition duration-150 flex items-center justify-center gap-2 shadow-lg shadow-emerald-700/10 hover:shadow-emerald-700/20 active:scale-[0.99] transform">
                    <svg xmlns="http://w3.org" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                    </svg>
                    Sign in
                </button>

            </form>
        </div>

    </div>

    <!-- VANILLA JAVASCRIPT STATE CONTROLLER -->
    <script>
    function selectRole(role) {
        document.getElementById('role-input').value = role;

        document.querySelectorAll('.role-card').forEach(card => {
            card.classList.remove('border-[#10a352]', 'ring-2', 'ring-emerald-100', 'bg-emerald-50/20');
            card.classList.add('border-slate-200', 'bg-white');
        });

        document.getElementById('icon-cwd').classList.replace('text-[#10a352]', 'text-slate-400');
        document.getElementById('icon-admin').classList.replace('text-[#10a352]', 'text-slate-400');

        const activeCard = document.getElementById(`card-${role}`);
        if(activeCard) {
            activeCard.classList.remove('border-slate-200', 'bg-white');
            activeCard.classList.add('border-[#10a352]', 'ring-2', 'ring-emerald-100', 'bg-emerald-50/20');
        }

        const activeIcon = document.getElementById(`icon-${role}`);
        if(activeIcon) {
            activeIcon.classList.replace('text-slate-400', 'text-[#10a352]');
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const currentRole = document.getElementById('role-input').value;
        selectRole(currentRole);
    });
    </script>
</body>
</html>
