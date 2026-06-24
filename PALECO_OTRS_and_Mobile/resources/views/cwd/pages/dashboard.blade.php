<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - PALECO CRM-CWD</title>
</head>
<body>

    <h1>Dashboard</h1>
    
    <p>You have successfully logged as CWD.</p>

    <!-- Logout form must use POST to match your web.php route -->
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Log out</button>
    </form>

</body>
</html>
