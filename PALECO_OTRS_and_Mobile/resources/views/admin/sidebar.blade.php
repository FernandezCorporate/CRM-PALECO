<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PALECO CRM-CWD')</title>
    
    <!-- 💡 CRITICAL FIX: Tells Vite to bundle and stream your Tailwind CSS into this layout -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex m-0 font-sans bg-slate-50 min-h-screen">

    <!-- Sidebar Section Layout Container -->
    <aside class="w-64 bg-white border-r border-slate-200 h-screen p-5 flex flex-col justify-between box-border sticky top-0">
        <div>
            <h2 class="text-xl font-black text-slate-800 tracking-wider mb-6">PALECO CRM</h2>
            <nav>
                <ul class="list-none p-0 m-0 space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            📊 Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.userManagement') }}" class="flex items-center px-4 py-2.5 text-sm font-semibold rounded-lg text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            👤 User Management
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Centralized Logout Form -->
        <form action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="w-full px-4 py-2.5 text-sm font-bold text-white bg-rose-500 hover:bg-rose-600 rounded-lg shadow-sm cursor-pointer border-none transition-colors">
                Log out
            </button>
        </form>
    </aside>

    <!-- Main Dynamic Content Workspace Area -->
    <main class="flex-1 p-8 overflow-y-auto">
        @yield('content')
    </main>

</body>
</html>
