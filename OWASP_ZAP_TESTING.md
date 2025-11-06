# OWASP ZAP Security Testing Guide

This document provides instructions for running OWASP ZAP security scans on the OneHelp application.

## Prerequisites

### Install OWASP ZAP

**Option 1: Download from Official Website**
1. Visit https://www.zaproxy.org/download/
2. Download the appropriate installer for your OS
3. Install and run OWASP ZAP

**Option 2: Using Docker**
```bash
docker pull zaproxy/zap-stable
```

**Option 3: Using Package Manager**
```bash
# macOS
brew install --cask zap

# Linux (Debian/Ubuntu)
sudo apt install zaproxy

# Linux (Fedora)
sudo dnf install zaproxy
```

## Prepare the Application

### 1. Start the Application

```bash
cd /path/to/Onehelp-Web-App

# Install dependencies if not already installed
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
touch database/database.sqlite
sed -i 's/DB_CONNECTION=mariadb/DB_CONNECTION=sqlite/' .env
php artisan migrate
php artisan db:seed --class=DemoDataSeeder

# Start the server
php artisan serve
```

The application will be available at `http://localhost:8000`

### 2. Verify Application is Running

Open your browser and navigate to:
- http://localhost:8000 (should load the homepage)
- http://localhost:8000/api/events (should return JSON with events)

## Running OWASP ZAP Scans

### Method 1: Using ZAP GUI (Recommended for First Time)

1. **Open OWASP ZAP**
   - Launch the OWASP ZAP application
   - Click "No, I do not want to persist this session" for now

2. **Configure Target**
   - In the "Quick Start" tab, enter the URL: `http://localhost:8000`
   - Click "Attack" button

3. **Review Results**
   - Wait for the scan to complete (may take 5-15 minutes)
   - Review the "Alerts" tab for findings
   - Sort by risk level: High, Medium, Low, Informational

4. **Generate Report**
   - Go to Report → Generate HTML Report
   - Save the report for documentation

### Method 2: Using ZAP CLI (Automated)

**Install ZAP CLI:**
```bash
pip install zapcli
```

**Quick Scan:**
```bash
# Start ZAP in daemon mode
zap.sh -daemon -port 8090 -config api.disablekey=true

# Run quick scan
zap-cli quick-scan --self-contained \
  --start-options "-config api.disablekey=true" \
  http://localhost:8000

# Generate report
zap-cli report -o zap-report.html -f html
```

**Spider and Active Scan:**
```bash
# Start ZAP
zap.sh -daemon -port 8090 -config api.disablekey=true

# Spider the application
zap-cli open-url http://localhost:8000
zap-cli spider http://localhost:8000

# Run active scan
zap-cli active-scan --recursive http://localhost:8000

# Get alerts
zap-cli alerts

# Generate report
zap-cli report -o zap-report.html -f html

# Shutdown ZAP
zap-cli shutdown
```

### Method 3: Using ZAP Docker

**Quick Scan:**
```bash
docker run -t zaproxy/zap-stable zap-baseline.py \
  -t http://host.docker.internal:8000 \
  -r zap-report.html
```

**Full Scan:**
```bash
docker run -t zaproxy/zap-stable zap-full-scan.py \
  -t http://host.docker.internal:8000 \
  -r zap-report.html
```

## Authenticated Scanning

To scan authenticated areas of the application:

### 1. Manual Login in ZAP Browser

1. In ZAP, go to Tools → Options → HUD
2. Enable "HUD in Scope Only"
3. In the Sites tree, right-click the site → Include in Context → Default Context
4. Go to the Manual Explore tab
5. Enter URL: http://localhost:8000/login
6. Click "Launch Browser"
7. In the launched browser, login with demo credentials:
   - Email: `admin@onehelp.com`
   - Password: `password123`
8. Navigate to authenticated areas
9. Go back to ZAP and run Active Scan on the context

### 2. Automated Form-Based Authentication

1. In ZAP, go to Tools → Options → Authentication
2. Select your context
3. Set Authentication Method to "Form-based Authentication"
4. Configure:
   - Login URL: `http://localhost:8000/login`
   - Login request POST data: `email={%username%}&password={%password%}`
   - Username parameter: `email`
   - Password parameter: `password`
   - Logged in indicator: Look for text that appears when logged in (e.g., "Dashboard")
5. Add a user with credentials:
   - Username: `admin@onehelp.com`
   - Password: `password123`
6. Run the scan with this user context

## Understanding ZAP Results

### Risk Levels

- **High**: Critical vulnerabilities that should be fixed immediately
- **Medium**: Important issues that should be addressed
- **Low**: Minor issues or information disclosure
- **Informational**: No immediate risk, best practices

### Common Alerts Expected to PASS

✅ **Alerts that should NOT appear:**
- SQL Injection
- Cross-Site Scripting (XSS)
- Cross-Site Request Forgery (CSRF)
- Directory Browsing
- Path Traversal
- Remote Code Execution
- Command Injection
- Insecure HTTP Methods
- Missing Authentication
- Broken Access Control

✅ **Headers that should be present:**
- X-Content-Type-Options: nosniff
- X-Frame-Options: SAMEORIGIN
- X-XSS-Protection: 1; mode=block
- Content-Security-Policy
- Referrer-Policy

### Alerts that May Appear (False Positives or Acceptable)

⚠️ **Informational Alerts (usually acceptable):**
- "Timestamp Disclosure" - Acceptable for API timestamps
- "Information Disclosure - Suspicious Comments" - May appear in JS/CSS
- "Incomplete or No Cache-control" - Acceptable for API responses
- "Cookie Without Secure Flag" - Only needed in production with HTTPS
- "Cookie Without HttpOnly Flag" - Check if needed for specific cookies
- "Content Security Policy (CSP) Header Not Set" - We have CSP, may be version-specific

## Verifying Security Features

### 1. Input Validation

**Test SQL Injection:**
```bash
# Should be rejected with validation error
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"'; DROP TABLE users;--","user_type":"volunteer"}'
```

**Expected:** 401 Unauthorized (not authenticated)

### 2. XSS Prevention

```bash
# Should sanitize the input
curl -X POST http://localhost:8000/api/skills \
  -H "Content-Type: application/json" \
  -d '{"skill_name":"<script>alert(\"XSS\")</script>Test"}'
```

**Expected:** 401 Unauthorized (not authenticated)

### 3. Authentication Required

```bash
# Should return 401
curl http://localhost:8000/api/users
```

**Expected:** 
```json
{
  "success": false,
  "message": "Unauthenticated. Please login to access this resource."
}
```

### 4. Authorization Checks

```bash
# Attempt to access admin endpoint as non-admin (after login)
# Should return 403 Forbidden
```

### 5. CSRF Protection

```bash
# Attempt POST without CSRF token
curl -X POST http://localhost:8000/login \
  -d "email=test@example.com&password=password"
```

**Expected:** CSRF token mismatch error

## Remediation Guide

If ZAP finds any issues:

### High Priority Issues

1. **SQL Injection**
   - ❌ Should not occur (we use ORM)
   - If found: Review raw SQL queries, ensure parameterization

2. **XSS (Cross-Site Scripting)**
   - ❌ Should not occur (we sanitize input)
   - If found: Check input sanitization middleware

3. **Broken Authentication**
   - ❌ Should not occur (Laravel's auth is secure)
   - If found: Review authentication logic

4. **Broken Access Control**
   - ❌ Should not occur (we have RBAC)
   - If found: Review authorization checks in controllers

### Medium Priority Issues

1. **CSRF**
   - ❌ Should not occur (enabled by default)
   - If found: Ensure VerifyCsrfToken middleware is active

2. **Security Headers**
   - ✅ Should be present
   - If missing: Check SecurityHeaders middleware

3. **Sensitive Data Exposure**
   - Review what data is returned in API responses
   - Ensure passwords are hidden

## Continuous Security Testing

### In CI/CD Pipeline

Add OWASP ZAP scan to your CI/CD:

```yaml
# .github/workflows/security-scan.yml
name: Security Scan

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  zap-scan:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      
      - name: Install Dependencies
        run: composer install
      
      - name: Start Application
        run: |
          php artisan key:generate
          php artisan migrate
          php artisan serve &
          sleep 5
      
      - name: ZAP Scan
        uses: zaproxy/action-baseline@v0.7.0
        with:
          target: 'http://localhost:8000'
```

### Regular Scans

- Run ZAP scan weekly
- Before major releases
- After security-related changes
- When adding new endpoints

## Resources

- [OWASP ZAP Documentation](https://www.zaproxy.org/docs/)
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [ZAP Automation Framework](https://www.zaproxy.org/docs/automate/)
- [ZAP API Documentation](https://www.zaproxy.org/docs/api/)

## Support

If you encounter any issues or have questions:
1. Check the ZAP documentation
2. Review the SECURITY.md file
3. Contact the development team
4. Create an issue in the repository

---

**Last Updated:** January 2025
