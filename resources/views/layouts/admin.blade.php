<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Your CSS -->
</head>
<body>
    <header style="background:#222; color:#fff; padding:1rem;">
        <nav>
            <a href="{{ route('admin.dashboard') }}" style="color:#fff; margin-right:1rem;">Dashboard</a>
            <a href="{{ route('admin.reports.index') }}" style="color:#fff; margin-right:1rem;">Reports</a>
            <a href="{{ route('admin.settings') }}" style="color:#fff;">Settings</a>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="background:none; border:none; color:#fff; cursor:pointer; margin-left:1rem;">Logout</button>
            </form>
        </nav>
    </header>

    <main style="padding: 2rem;">
        @yield('content')
    </main>

    <footer style="text-align:center; padding:1rem; border-top:1px solid #ddd; color:#888;">
        &copy; {{ date('Y') }} Tasty Delight Admin
    </footer>
</body>
</html>
