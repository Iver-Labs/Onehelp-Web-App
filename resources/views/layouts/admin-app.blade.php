<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - OneHelp</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-teal: #90C7D0;
            --dark-teal: #2C7A6E;
            --accent-yellow: #F4D58D;
            --navy: #1A4D5E;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }

        .sidebar {
            background: var(--navy);
            min-height: 100vh;
            color: white;
            position: fixed;
            width: 250px;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 5px 10px;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-left: 250px;
            padding: 15px 30px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 1rem;
        }

        .stat-icon.blue { background: #E3F2FD; color: #1976D2; }
        .stat-icon.green { background: #E8F5E9; color: #388E3C; }
        .stat-icon.orange { background: #FFF3E0; color: #F57C00; }
        .stat-icon.purple { background: #F3E5F5; color: #7B1FA2; }
        .stat-icon.red { background: #FFEBEE; color: #D32F2F; }
        .stat-icon.teal { background: #E0F2F1; color: #00796B; }

        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .table-responsive {
            background: white;
            border-radius: 10px;
            padding: 20px;
        }

        .badge {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="px-3 mb-4">
            <h3 class="mb-0"><i class="fas fa-shield-alt"></i> OneHelp Admin</h3>
        </div>
        
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                <i class="fas fa-users me-2"></i> Users
            </a>
            <a class="nav-link {{ request()->routeIs('admin.organizations') ? 'active' : '' }}" href="{{ route('admin.organizations') }}">
                <i class="fas fa-building me-2"></i> Organizations
            </a>
            <a class="nav-link {{ request()->routeIs('admin.events') ? 'active' : '' }}" href="{{ route('admin.events') }}">
                <i class="fas fa-calendar-alt me-2"></i> Events
            </a>
            <a class="nav-link {{ request()->routeIs('admin.verifications') ? 'active' : '' }}" href="{{ route('admin.verifications') }}">
                <i class="fas fa-check-circle me-2"></i> Verifications
            </a>
            <a class="nav-link {{ request()->routeIs('admin.analytics') ? 'active' : '' }}" href="{{ route('admin.analytics') }}">
                <i class="fas fa-chart-line me-2"></i> Analytics
            </a>
            <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
            <a class="nav-link" href="{{ route('home') }}">
                <i class="fas fa-home me-2"></i> Back to Site
            </a>
        </nav>
    </div>

    <!-- Top Navbar -->
    <nav class="navbar">
        <div class="container-fluid">
            <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
            <div>
                <span class="me-3">Welcome, {{ auth()->user()->email }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('scripts')
</body>
</html>
