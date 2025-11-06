<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'OneHelp - Volunteer Dashboard')</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
            background: #90C7D0;
            overflow-x: hidden;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 200px;
            background: #EFD191;
            padding: 20px 15px;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            padding: 8px;
        }

        .sidebar-logo-icon {
            width: 28px;
            height: 28px;
            background: #2B5F6F;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }

        .sidebar-logo-text {
            font-weight: 700;
            font-size: 16px;
            color: #2C3E50;
        }

        .sidebar-profile {
            background: white;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .sidebar-profile-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .sidebar-profile-icon {
            width: 32px;
            height: 32px;
            background: #E0E0E0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-profile-icon svg {
            width: 18px;
            height: 18px;
            color: #757575;
        }

        .sidebar-profile-info h3 {
            font-size: 13px;
            font-weight: 600;
            color: #2C3E50;
            line-height: 1.2;
        }

        .sidebar-profile-info p {
            font-size: 11px;
            color: #666;
        }

        .sidebar-logout {
            display: flex;
            align-items: center;
            gap: 6px;
            width: 100%;
            padding: 8px 12px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 12px;
            color: #2C3E50;
            cursor: pointer;
            transition: all 0.2s;
        }

        .sidebar-logout:hover {
            background: #f5f5f5;
        }

        .sidebar-logout svg {
            width: 14px;
            height: 14px;
        }

        .sidebar-nav {
            flex: 1;
            margin-top: 15px;
        }

        .sidebar-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            margin-bottom: 4px;
            border-radius: 10px;
            color: #4A5568;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
            position: relative;
        }

        .sidebar-nav-item svg {
            width: 18px;
            height: 18px;
        }

        .sidebar-nav-item:hover {
            background: rgba(255,255,255,0.5);
        }

        .sidebar-nav-item.active {
            background: #E8967C;
            color: white;
        }

        .sidebar-nav-badge {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background: #5BA8C9;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 200px;
            padding: 30px 40px;
        }

        /* Welcome Section */
        .welcome-section {
            margin-bottom: 30px;
        }

        .welcome-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }

        .welcome-avatar {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(255,255,255,0.8);
        }

        .welcome-avatar svg {
            width: 30px;
            height: 30px;
            color: #7B9FA8;
        }

        .welcome-text h1 {
            font-size: 28px;
            font-weight: 600;
            color: #2C3E50;
            margin-bottom: 2px;
        }

        .welcome-text p {
            font-size: 14px;
            color: #5A6C7D;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .stat-card-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon svg {
            width: 24px;
            height: 24px;
        }

        .stat-icon.blue {
            background: #D4E9F0;
            color: #4A90A4;
        }

        .stat-icon.teal {
            background: #C8E6E3;
            color: #4A9D8F;
        }

        .stat-icon.gray {
            background: #E8E8E8;
            color: #6B7280;
        }

        .stat-icon.beige {
            background: #F3E5D7;
            color: #B8956A;
        }

        .stat-info h3 {
            font-size: 32px;
            font-weight: 700;
            color: #2C3E50;
            line-height: 1;
            margin-bottom: 5px;
        }

        .stat-info p {
            font-size: 13px;
            color: #6B7280;
            font-weight: 500;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        /* Card Styles */
        .card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #2C3E50;
            margin-bottom: 20px;
        }

        /* This Month's Impact */
        .impact-list {
            list-style: none;
        }

        .impact-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #F0F0F0;
        }

        .impact-item:last-child {
            border-bottom: none;
        }

        .impact-label {
            font-size: 14px;
            color: #4A5568;
        }

        .impact-value {
            font-size: 16px;
            font-weight: 700;
            color: #2C3E50;
        }

        .impact-banner {
            background: #7CB5B3;
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            margin-top: 15px;
        }

        .impact-banner strong {
            display: block;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .impact-banner p {
            font-size: 12px;
            opacity: 0.95;
        }

        /* Recent Activity */
        .activity-list {
            list-style: none;
        }

        .activity-item {
            display: flex;
            gap: 10px;
            padding: 12px 0;
            border-bottom: 1px solid #F0F0F0;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-top: 6px;
            flex-shrink: 0;
        }

        .activity-dot.green {
            background: #48BB78;
        }

        .activity-dot.yellow {
            background: #ECC94B;
        }

        .activity-dot.blue {
            background: #4299E1;
        }

        .activity-content p {
            font-size: 13px;
            color: #2C3E50;
            margin-bottom: 3px;
            line-height: 1.4;
        }

        .activity-date {
            font-size: 11px;
            color: #9CA3AF;
        }

        /* My History Table */
        .history-card {
            grid-column: 1 / -1;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-table thead {
            border-bottom: 2px solid #E5E7EB;
        }

        .history-table th {
            text-align: left;
            padding: 12px 15px;
            font-size: 14px;
            font-weight: 600;
            color: #4A5568;
        }

        .history-table td {
            padding: 15px;
            font-size: 14px;
            color: #2C3E50;
            border-bottom: 1px solid #F0F0F0;
        }

        .history-table tbody tr:hover {
            background: #F9FAFB;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.completed {
            background: #D1FAE5;
            color: #065F46;
        }

        .status-badge.pending {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-badge.approved {
            background: #DBEAFE;
            color: #1E40AF;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        @include('components.volunteer-sidebar')
        
        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>