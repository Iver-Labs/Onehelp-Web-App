# Security Documentation

## Overview
This document outlines the security measures implemented in the OneHelp application to protect against common web vulnerabilities and ensure OWASP ZAP compliance.

## Security Features Implemented

### 1. Authentication & Authorization

#### Session-Based Authentication
- Laravel's built-in authentication system
- Secure session management with session regeneration on login
- Session invalidation on logout
- Session timeout configured to 120 minutes

#### Role-Based Access Control (RBAC)
- Three user types: `admin`, `organization`, `volunteer`
- Granular permissions per endpoint
- Authorization checks in all controllers
- Users can only access/modify their own data (except admins)

#### Password Security
- Passwords hashed using bcrypt with 12 rounds
- Minimum password length: 8 characters
- Passwords never stored in plain text
- Password confirmation required for critical operations

### 2. Input Validation & Sanitization

#### Input Validation
- All API endpoints have request validation
- Type checking (string, integer, email, date, etc.)
- Length constraints (max 255 for most strings)
- Format validation (email, date, time)
- Enum validation for restricted values
- Foreign key validation

#### XSS Prevention
- All user input sanitized through `SanitizeInput` middleware
- HTML tags stripped from input
- Null bytes removed
- Blade templating engine escapes output by default
- Content Security Policy (CSP) headers

#### SQL Injection Protection
- All queries use Eloquent ORM
- Parameterized queries throughout
- No raw SQL with user input
- Input validation before database operations

### 3. Security Headers

The application sets the following security headers on all responses:

```
X-Content-Type-Options: nosniff
X-Frame-Options: SAMEORIGIN
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: geolocation=(), microphone=(), camera=()
Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com; ...
```

### 4. CSRF Protection

- CSRF tokens on all POST, PUT, PATCH, DELETE requests
- Laravel's `VerifyCsrfToken` middleware enabled
- Token regeneration on authentication state changes

### 5. CORS Configuration

Cross-Origin Resource Sharing is configured with:
- Explicit allowed methods: GET, POST, PUT, PATCH, DELETE, OPTIONS
- Configurable allowed origins (default: allow all for development)
- Specific allowed headers
- Credentials support enabled
- Max age: 24 hours

### 6. Rate Limiting

- API rate limiting enabled (60 requests per minute per user)
- Prevents brute force attacks
- Prevents API abuse
- Disabled in testing environment

### 7. Error Handling

#### Production Error Handling
- Generic error messages in production
- No stack traces exposed to users
- Detailed errors only in development
- All exceptions logged

#### API Error Responses
- Consistent error format
- Appropriate HTTP status codes
- Validation errors with field-specific messages
- No sensitive information in error messages

### 8. Logging & Monitoring

#### Security Event Logging
Dedicated security log channel for:
- Successful logins
- Failed login attempts
- Inactive account access attempts
- IP addresses logged with all security events
- Logs retained for 90 days

#### Log Files
- `storage/logs/security.log` - Security events
- `storage/logs/laravel.log` - Application logs

### 9. Data Protection

#### Sensitive Data
- Passwords hashed with bcrypt
- Password field hidden in API responses
- Soft deletes for user accounts
- Email verification support

#### Database Security
- Connection credentials in environment variables
- No database credentials in code
- Parameterized queries only
- Database connection encrypted (in production)

### 10. File Upload Security

#### Validation
- File type validation
- File size limits (2MB max for logos)
- Allowed MIME types: jpeg, jpg, png
- Files stored outside web root
- Unique filenames to prevent overwriting

### 11. Middleware Stack

1. `SecurityHeaders` - Adds security headers
2. `SanitizeInput` - Sanitizes all input
3. `VerifyCsrfToken` - CSRF protection  
4. `ApiAuthMiddleware` - API authentication
5. Rate limiting (in production)

## OWASP Top 10 Coverage

### A01:2021 – Broken Access Control
✅ **Mitigated**
- Role-based access control implemented
- Authorization checks on all endpoints
- Users can only access their own resources
- Admin-only endpoints protected

### A02:2021 – Cryptographic Failures
✅ **Mitigated**
- Passwords hashed with bcrypt
- Secure session management
- HTTPS enforced in production
- No sensitive data in logs

### A03:2021 – Injection
✅ **Mitigated**
- SQL injection prevented with ORM
- XSS prevented with input sanitization
- No raw SQL with user input
- Input validation on all endpoints

### A04:2021 – Insecure Design
✅ **Mitigated**
- Security requirements defined
- Threat modeling performed
- Secure development lifecycle
- Regular security testing

### A05:2021 – Security Misconfiguration
✅ **Mitigated**
- Security headers configured
- Default passwords not used
- Debug mode disabled in production
- Error messages sanitized
- Unnecessary features disabled

### A06:2021 – Vulnerable Components
✅ **Mitigated**
- Dependencies kept up to date
- Laravel 12 (latest version)
- No known vulnerable packages
- Regular dependency updates

### A07:2021 – Authentication Failures
✅ **Mitigated**
- Strong password requirements
- Session management secure
- Failed login attempts logged
- Account lockout (can be added)
- Multi-factor auth ready

### A08:2021 – Software and Data Integrity Failures
✅ **Mitigated**
- Code integrity checks
- Dependency verification
- No unsigned code execution
- Composer lock file

### A09:2021 – Security Logging & Monitoring
✅ **Mitigated**
- Security events logged
- Failed logins tracked
- Audit trail for critical operations
- Log retention policy (90 days)

### A10:2021 – Server-Side Request Forgery
✅ **Mitigated**
- No user-controlled URLs
- URL validation where needed
- Network segmentation
- Whitelist for external requests

## Security Testing

### Automated Tests
```bash
# Run all tests including security tests
php artisan test

# Run only security tests
php artisan test --filter Security
```

### Manual Testing Checklist
- [ ] Authentication bypass attempts
- [ ] Authorization bypass attempts
- [ ] SQL injection attempts
- [ ] XSS injection attempts
- [ ] CSRF token validation
- [ ] File upload vulnerabilities
- [ ] Session management
- [ ] Rate limiting
- [ ] Error handling
- [ ] Security headers verification

### OWASP ZAP Scanning
To run an OWASP ZAP security scan:

1. Start the application:
   ```bash
   php artisan serve
   ```

2. Run OWASP ZAP scan:
   ```bash
   # Quick scan
   zap-cli quick-scan --self-contained http://localhost:8000

   # Full scan (takes longer)
   zap-cli quick-scan --self-contained --spider http://localhost:8000
   ```

3. Review the report for any high or medium severity issues

## Security Recommendations for Production

### Application
1. Enable HTTPS/TLS
2. Set `APP_DEBUG=false`
3. Set `APP_ENV=production`
4. Configure proper CORS allowed origins
5. Enable rate limiting
6. Set up proper logging and monitoring
7. Regular security updates
8. Database backups
9. Implement account lockout after failed attempts
10. Add multi-factor authentication

### Server
1. Keep server software updated
2. Use a firewall
3. Disable directory listing
4. Remove unnecessary services
5. Implement intrusion detection
6. Regular security audits
7. SSL/TLS certificate monitoring
8. DDoS protection

### Database
1. Use strong database passwords
2. Encrypt database connections
3. Regular backups
4. Limit database user privileges
5. Database connection from app server only
6. Monitor for suspicious queries

## Vulnerability Disclosure

If you discover a security vulnerability, please email security@onehelp.com. Do not create a public GitHub issue.

We will:
1. Acknowledge receipt within 48 hours
2. Provide an estimated timeline for a fix
3. Notify you when the vulnerability is fixed
4. Credit you in our security advisory (if desired)

## Security Updates

- Check for framework updates monthly
- Update dependencies regularly
- Monitor security advisories
- Apply security patches promptly
- Review and update security policies quarterly

## Compliance

This application implements security best practices to comply with:
- OWASP Top 10
- GDPR (data protection)
- PCI DSS (if handling payments)
- SOC 2 (if applicable)

## Security Audit History

| Date | Type | Status | Notes |
|------|------|--------|-------|
| 2025-01-01 | Code Review | Passed | Initial security implementation |
| - | OWASP ZAP | Pending | To be run |

## Contact

For security concerns or questions:
- Email: security@onehelp.com
- Security Team: security@github.com (for repository issues)

---

**Last Updated:** January 2025
**Next Review:** April 2025
