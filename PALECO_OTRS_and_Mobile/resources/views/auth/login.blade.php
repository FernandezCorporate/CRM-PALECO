{{-- resources/views/auth/login.blade.php --}}
{{-- Allen's original design is fully preserved here.                         --}}
{{-- The only changes from the original:                                       --}}
{{--   1. Added @livewireStyles in <head>                                      --}}
{{--   2. Replaced the entire <form>...</form> block and <script> block        --}}
{{--      with a single <livewire:auth.login-form /> tag                       --}}
{{--   3. Added @livewireScripts before </body>                                --}}

<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PALECO CRM-CWD - Sign in</title>

    {{-- Vite compiles Tailwind CSS and JS (run: composer run dev) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Livewire styles — required in <head> --}}
    @livewireStyles
</head>
<body class="h-full flex items-center justify-center p-4 md:p-6 select-none">

    <div class="w-full max-w-5xl bg-white rounded-xl shadow-2xl flex flex-col md:flex-row overflow-hidden border border-slate-200" style="min-height: 580px;">

        {{-- LEFT PANEL: Branding — unchanged from Allen's original --}}
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

        {{-- RIGHT PANEL: Authentication Form --}}
        <div class="w-full md:w-[55%] p-8 md:p-12 flex flex-col justify-center">

            <header class="mb-6">
                <h3 class="text-2xl font-bold text-slate-800">Sign in</h3>
                <p class="text-sm text-slate-500 mt-1">Select your role to continue</p>
            </header>

            {{-- ================================================================
                 LIVEWIRE COMPONENT
                 Replaces the entire <form>...</form> block and <script> block.
                 All logic (role selection, validation, Auth::attempt) now lives
                 in app/Livewire/Auth/LoginForm.php instead of AuthController.
                 ================================================================ --}}
            <livewire:auth.login-form />

        </div>

    </div>

    {{-- Livewire scripts — required before </body> --}}
    @livewireScripts

</body>
</html>
