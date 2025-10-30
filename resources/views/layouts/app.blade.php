<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OneHelp - Connecting Hearts to Communities')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-teal: #90C7D0;
            --secondary-teal: #5FB9A8;
            --dark-teal: #2C7A6E;
            --accent-yellow: #F4D58D;
            --light-yellow: #FFF4D6;
            --cream: #FFF9E6;
            --navy: #1A4D5E;
            --text-dark: #2C3E50;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Navbar */
        .navbar {
            background: #1B3C53;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 1.5rem;
            letter-spacing: 0.5px;
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
        }

        .navbar-logo {
            height: 40px;
            width: auto;
            max-width: 150px;
            object-fit: contain;
            display: block;
        }

        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1.2rem !important;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .nav-link:hover {
            color: var(--accent-yellow) !important;
        }

        .btn-login {
            background: transparent;
            color: white !important;
            border: 2px solid white;
            padding: 0.5rem 1.5rem !important;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.85rem;
            margin-left: 0.5rem !important;
        }

        .btn-login:hover {
            background: white;
            color: var(--dark-teal) !important;
        }

        .btn-signup {
            background: var(--accent-yellow);
            color: var(--navy) !important;
            border: 2px solid var(--accent-yellow);
            padding: 0.5rem 1.5rem !important;
            border-radius: 25px;
            font-weight: 700;
            transition: all 0.3s ease;
            text-transform: uppercase;
            font-size: 0.85rem;
            margin-left: 0.5rem !important;
        }

        .btn-signup:hover {
            background: transparent;
            color: var(--accent-yellow) !important;
            border-color: var(--accent-yellow);
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            min-height: 600px;
            background: linear-gradient(rgba(28, 77, 94, 0.6), rgba(28, 77, 94, 0.6)), 
                        url('https://via.placeholder.com/1920x800/7DD3C0/ffffff?text=Upload+Your+Hero+Image+Here') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 4rem 2rem;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        .btn-browse-events {
            background: var(--accent-yellow);
            color: var(--navy);
            padding: 1rem 3rem;
            border-radius: 50px;
            border: none;
            font-size: 1.1rem;
            font-weight: 700;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 5px 20px rgba(244, 213, 141, 0.4);
        }

        .btn-browse-events:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(244, 213, 141, 0.6);
        }

        .hero-stats {
            display: flex;
            gap: 3rem;
            justify-content: center;
            margin-top: 3rem;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            display: block;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* How It Works Section */
        .how-it-works {
            background: var(--primary-teal);
            padding: 5rem 0;
        }

        .how-it-works h2 {
            color: var(--navy);
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 4rem;
        }

        .how-card {
            background: white;
            border: 3px solid var(--navy);
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
        }

        .how-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .how-icon {
            width: 100px;
            height: 100px;
            background: var(--primary-teal);
            border: 3px solid var(--navy);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
        }

        .how-card h3 {
            color: var(--navy);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .how-card p {
            color: var(--text-dark);
            line-height: 1.6;
            font-size: 0.95rem;
        }

        /* Featured Events Section */
        .featured-events {
            background: var(--accent-yellow);
            padding: 5rem 0;
        }

        .featured-events h2 {
            color: var(--navy);
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 4rem;
            text-transform: uppercase;
        }

        .event-card {
            background: var(--primary-teal);
            border: 3px solid var(--navy);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .event-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .event-image {
            width: calc(100% - 2rem);
            height: 200px;
            background: white;
            border: 3px solid var(--navy);
            margin: 1rem auto 0;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }

        .event-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .event-title {
            color: var(--navy);
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .event-org {
            color: var(--navy);
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .event-description {
            color: var(--navy);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1rem;
            flex-grow: 1;
        }

        .event-meta {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1rem;
            font-size: 0.85rem;
            color: var(--navy);
        }

        .event-meta i {
            margin-right: 0.3rem;
        }

        .event-tags {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .event-tag {
            background: white;
            color: var(--navy);
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            border: 2px solid var(--navy);
        }

        .btn-view-details {
            background: var(--accent-yellow);
            color: var(--navy);
            padding: 0.8rem 2rem;
            border-radius: 25px;
            border: 2px solid var(--navy);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            text-align: center;
            display: inline-block;
            text-decoration: none;
        }

        .btn-view-details:hover {
            background: white;
            color: var(--navy);
            transform: translateY(-2px);
        }

        /* Sustainable Future Section */
        .sustainable-section {
            background: var(--primary-teal);
            padding: 5rem 0;
        }

        .sustainable-section h2 {
            color: var(--navy);
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
        }

        .sustainable-section p {
            color: var(--navy);
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 3rem;
        }

        .sdg-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            max-width: 500px;
        }

        .sdg-item {
            width: 100%;
            aspect-ratio: 1;
            background: white;
            border: 3px solid var(--navy);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .sdg-item:hover {
            transform: scale(1.05);
        }

        .sdg-logo {
            width: 100%;
            margin-top: 2rem;
        }

        /* Footer */
        footer {
            background: var(--accent-yellow);
            padding: 3rem 0 1.5rem;
        }

        .footer-brand {
            color: var(--navy);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .footer-tagline {
            color: var(--navy);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .footer-social {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            background: white;
            border: 2px solid var(--navy);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--navy);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-icon:hover {
            background: var(--navy);
            color: white;
            transform: translateY(-3px);
        }

        .footer-links {
            display: flex;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .footer-column h4 {
            color: var(--navy);
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .footer-column ul {
            list-style: none;
            padding: 0;
        }

        .footer-column ul li {
            margin-bottom: 0.5rem;
        }

        .footer-column ul li a {
            color: var(--navy);
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .footer-column ul li a:hover {
            color: var(--dark-teal);
            padding-left: 5px;
        }

        .footer-bottom {
            border-top: 2px solid var(--navy);
            padding-top: 1.5rem;
            text-align: center;
            color: var(--navy);
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }

            .hero-stats {
                flex-direction: column;
                gap: 1.5rem;
            }

            .how-it-works h2,
            .featured-events h2,
            .sustainable-section h2 {
                font-size: 2rem;
            }

            .sdg-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-links {
                flex-direction: column;
                gap: 2rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>