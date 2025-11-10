# OneHelp - Volunteer Management System

A comprehensive volunteer management platform built with Laravel 12, connecting volunteers with organizations to make a positive impact in their communities.

## Features

- **User Management**: Support for volunteers, organizations, and administrators
- **Event Management**: Organizations can create and manage volunteer events
- **Registration System**: Volunteers can browse and register for events
- **Attendance Tracking**: Track volunteer hours and attendance
- **Feedback System**: Collect feedback from volunteers
- **Organization Verification**: Admin approval workflow for organizations
- **Notifications**: Keep users informed of important updates
- **Skills Tracking**: Match volunteers with events based on their skills

## Security Features

✅ **OWASP Compliant** - Implements OWASP Top 10 security best practices
- Role-Based Access Control (RBAC)
- Input validation and sanitization
- XSS and SQL injection protection
- CSRF protection
- Security headers (CSP, X-Frame-Options, etc.)
- Rate limiting
- Comprehensive security logging
- Session management
- Password hashing (bcrypt)

See [SECURITY.md](SECURITY.md) for detailed security documentation.

## Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite (for development) or MariaDB/MySQL (for production)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Onehelp-Web-App
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   # For SQLite (development)
   touch database/database.sqlite
   
   # Update .env
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   
   # Run migrations
   php artisan migrate
   ```

5. **Seed demo data** (optional)
   ```bash
   php artisan db:seed --class=DemoDataSeeder
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` in your browser.

### Quick Setup for Demo/Video Presentation

For a streamlined setup (especially useful for video presentations), use the automated setup scripts:

**Linux/Mac:**
```bash
./setup-demo.sh
```

**Windows:**
```bash
setup-demo.bat
```

These scripts will automatically install dependencies, setup the database, seed demo data, and build assets.

### Demo Credentials

After seeding demo data:
- **Admin**: admin@onehelp.com / password123
- **Volunteer**: john.volunteer@example.com / password123
- **Organization**: contact@helpinghands.org / password123

## Development

### Running Tests
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --filter Security

# Run with coverage
php artisan test --coverage
```

### Development Server with Hot Reload
```bash
# Runs server, queue worker, logs, and vite dev server
composer run dev
```

### Code Style
```bash
# Format code with Laravel Pint
./vendor/bin/pint
```

## Documentation

- **[API Documentation](API_DOCUMENTATION.md)** - Complete API reference
- **[Security Documentation](SECURITY.md)** - Security implementation details
- **[Video Presentation Guide](VIDEO_PRESENTATION_GUIDE.md)** - Comprehensive guide for creating demo videos
- **[Quick Reference Card](VIDEO_QUICK_REFERENCE.md)** - Printable quick reference for presenters
- **[App Flow Diagrams](APP_FLOW_DIAGRAMS.md)** - Visual flow diagrams and user journey maps
- **[Copilot Instructions](.github/copilot-instructions.md)** - AI assistant guidelines

## Technology Stack

- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: Blade Templates, Tailwind CSS, Vite
- **Database**: SQLite (dev), MariaDB (production)
- **Testing**: PHPUnit
- **Authentication**: Laravel's built-in auth

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/    # API and web controllers
│   │   ├── Middleware/     # Security and custom middleware
│   │   └── Requests/       # Form request validation
│   ├── Models/             # Eloquent models
│   └── Exceptions/         # Custom exceptions
├── database/
│   ├── migrations/         # Database migrations
│   ├── factories/          # Model factories for testing
│   └── seeders/            # Database seeders
├── routes/
│   ├── web.php            # Web routes
│   └── api.php            # API routes
├── tests/
│   ├── Feature/           # Feature tests
│   │   └── Security/      # Security tests
│   └── Unit/              # Unit tests
└── resources/
    ├── views/             # Blade templates
    ├── css/               # Stylesheets
    └── js/                # JavaScript
```

## API Endpoints

All API endpoints are prefixed with `/api`. See [API_DOCUMENTATION.md](API_DOCUMENTATION.md) for complete details.

**Public Endpoints:**
- `GET /api/events` - List all events
- `GET /api/events/{id}` - Get event details
- `GET /api/skills` - List all skills

**Protected Endpoints:** (require authentication)
- `/api/users` - User management (admin only)
- `/api/events` - Event CRUD (organizations)
- `/api/registrations` - Event registrations (volunteers)
- `/api/attendances` - Attendance tracking (organizations)
- `/api/feedbacks` - Feedback system (volunteers)
- `/api/notifications` - User notifications
- `/api/verifications` - Organization verification (admin)

## Testing

The application includes comprehensive test coverage:

- **29 tests** with 63 assertions
- Authentication & authorization tests
- Input validation tests
- XSS & SQL injection prevention tests
- RBAC tests

Run tests with:
```bash
php artisan test
```

## Security

This application implements OWASP Top 10 security best practices. See [SECURITY.md](SECURITY.md) for:
- Security features overview
- OWASP compliance details
- Security testing procedures
- Vulnerability disclosure process
- Production security recommendations

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add/update tests
5. Run security checks
6. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues, questions, or contributions, please create an issue in the GitHub repository.

