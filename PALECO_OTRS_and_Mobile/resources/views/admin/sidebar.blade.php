<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PALECO CRM-CWD')</title>
</head>
<body style="display: flex; margin: 0; font-family: sans-serif;">

    <!-- Sidebar Section -->
    <aside style="width: 250px; background: #f4f4f4; height: 100vh; padding: 20px; box-sizing: border-box; display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <h2>PALECO CRM</h2>
            <nav>
                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                </ul>

                <ul style="list-style: none; padding: 0;">
                    <li style="margin-bottom: 10px;"><a href="{{ route('admin.userManagement') }}">User Management</a></li>
                </ul>
            </nav>
        </div>

        <!-- Centralized Logout Form -->
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" style="width: 100%; padding: 10px; background: #ff4d4d; color: white; border: none; cursor: pointer; border-radius: 4px;">
                Log out
            </button>
        </form>
    </aside>

    <!-- Main Content Area -->
    <main style="flex: 1; padding: 20px;">
        @yield('content')
    </main>

</body>
</html>
