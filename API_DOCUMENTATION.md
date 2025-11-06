# OneHelp API Documentation

## Overview
The OneHelp API is a RESTful API that provides access to volunteer management functionality. All API endpoints are prefixed with `/api`.

## Authentication
Most API endpoints require authentication. The API uses session-based authentication via Laravel's built-in authentication system.

### Login
Users must first login through the web interface at `/login` to establish an authenticated session.

### Protected Endpoints
Protected endpoints require the `api.auth` middleware and will return a 401 Unauthorized response if not authenticated.

## Base URL
```
http://localhost:8000/api
```

## Response Format
All responses follow a consistent JSON format:

```json
{
    "success": true|false,
    "message": "Optional message",
    "data": {} or [],
    "errors": {} (only on validation errors)
}
```

## Error Codes
- `200` - Success
- `201` - Created
- `204` - No Content (successful deletion)
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

## Endpoints

### Authentication & Users

#### List Users
```
GET /api/users
Authorization: Required (Admin only)
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "user_id": 1,
            "email": "user@example.com",
            "user_type": "volunteer",
            "is_active": true,
            "created_at": "2025-01-01T00:00:00.000000Z"
        }
    ]
}
```

#### Get User
```
GET /api/users/{id}
Authorization: Required (Own profile or Admin)
```

#### Create User
```
POST /api/users
Authorization: Required (Admin only)

Body:
{
    "email": "user@example.com",
    "password": "password123",
    "user_type": "volunteer|organization|admin",
    "is_active": true
}
```

#### Update User
```
PUT /api/users/{id}
Authorization: Required (Own profile or Admin)

Body:
{
    "email": "newemail@example.com",
    "password": "newpassword123"
}
```

#### Delete User
```
DELETE /api/users/{id}
Authorization: Required (Admin only)
```

---

### Events

#### List Events (Public)
```
GET /api/events
Authorization: Not required

Response:
{
    "success": true,
    "data": [
        {
            "event_id": 1,
            "organization_id": 1,
            "event_name": "Beach Cleanup",
            "description": "Help clean up our local beach",
            "event_date": "2025-02-15",
            "start_time": "09:00",
            "end_time": "12:00",
            "location": "Sunset Beach",
            "max_volunteers": 50,
            "status": "open",
            "organization": {},
            "skills": [],
            "images": []
        }
    ]
}
```

#### Get Event (Public)
```
GET /api/events/{id}
Authorization: Not required
```

#### Create Event
```
POST /api/events
Authorization: Required (Organization only)

Body:
{
    "organization_id": 1,
    "event_name": "Community Garden Planting",
    "description": "Help plant vegetables in our community garden",
    "long_description": "Full description...",
    "event_date": "2025-03-01",
    "start_time": "09:00",
    "end_time": "15:00",
    "location": "123 Garden Street",
    "max_volunteers": 20
}
```

#### Update Event
```
PUT /api/events/{id}
Authorization: Required (Event owner organization only)
```

#### Delete Event
```
DELETE /api/events/{id}
Authorization: Required (Event owner organization only)
```

---

### Event Registrations

#### List Registrations
```
GET /api/registrations
Authorization: Required
- Volunteers see their own registrations
- Organizations see registrations for their events
- Admins see all registrations
```

#### Create Registration
```
POST /api/registrations
Authorization: Required (Volunteer only)

Body:
{
    "event_id": 1,
    "motivation": "I'm passionate about environmental conservation"
}
```

#### Update Registration Status
```
PUT /api/registrations/{id}
Authorization: Required
- Organizations can approve/reject
- Volunteers can cancel
- Admins can do anything

Body (Organization):
{
    "status": "approved|rejected",
    "hours_contributed": 4
}

Body (Volunteer):
{
    "status": "cancelled"
}
```

#### Delete Registration
```
DELETE /api/registrations/{id}
Authorization: Required (Own registration or Admin)
```

---

### Skills

#### List Skills (Public)
```
GET /api/skills
Authorization: Not required
```

#### Get Skill (Public)
```
GET /api/skills/{id}
Authorization: Not required
```

#### Create Skill
```
POST /api/skills
Authorization: Required (Admin only)

Body:
{
    "skill_name": "First Aid",
    "description": "Basic first aid and CPR training"
}
```

#### Update Skill
```
PUT /api/skills/{id}
Authorization: Required (Admin only)
```

#### Delete Skill
```
DELETE /api/skills/{id}
Authorization: Required (Admin only)
```

---

### Notifications

#### List Notifications
```
GET /api/notifications
Authorization: Required (Own notifications only)
```

#### Get Notification
```
GET /api/notifications/{id}
Authorization: Required (Own notification only)
```

#### Mark as Read
```
PUT /api/notifications/{id}
Authorization: Required (Own notification only)

Body:
{
    "is_read": true
}
```

---

### Feedback

#### List Feedback
```
GET /api/feedbacks
Authorization: Required
- Volunteers see their own feedback
- Organizations see feedback for their events
- Admins see all feedback
```

#### Submit Feedback
```
POST /api/feedbacks
Authorization: Required (Volunteer only)

Body:
{
    "registration_id": 1,
    "rating": 5,
    "comments": "Great event, very well organized!"
}
```

---

### Attendance

#### List Attendance
```
GET /api/attendances
Authorization: Required (Organization or Admin)
```

#### Record Attendance
```
POST /api/attendances
Authorization: Required (Organization or Admin)

Body:
{
    "registration_id": 1,
    "check_in_time": "2025-02-15 09:15:00",
    "check_out_time": "2025-02-15 12:00:00",
    "status": "present",
    "hours_logged": 2.75
}
```

---

### Organization Verification

#### Submit Verification Request
```
POST /api/verifications
Authorization: Required (Organization only)

Body:
{
    "organization_id": 1,
    "document_type": "registration_certificate",
    "document_url": "https://example.com/cert.pdf",
    "notes": "Official registration certificate"
}
```

#### Update Verification Status
```
PUT /api/verifications/{id}
Authorization: Required (Admin only)

Body:
{
    "status": "approved|rejected",
    "admin_notes": "Verification complete"
}
```

---

## Security Features

### Input Validation
All input is validated before processing. Invalid input returns a 422 status code with detailed error messages.

### XSS Prevention
All user input is automatically sanitized to prevent Cross-Site Scripting (XSS) attacks.

### SQL Injection Protection
All database queries use parameterized queries to prevent SQL injection.

### Rate Limiting
API endpoints are rate-limited to prevent abuse (disabled in testing environment).

### CORS
Cross-Origin Resource Sharing (CORS) is configured to allow requests from authorized origins only.

### Security Headers
All responses include security headers:
- X-Content-Type-Options: nosniff
- X-Frame-Options: SAMEORIGIN
- X-XSS-Protection: 1; mode=block
- Content-Security-Policy
- Referrer-Policy: strict-origin-when-cross-origin

## Demo Data

To seed the database with demo data for testing:

```bash
php artisan db:seed --class=DemoDataSeeder
```

**Demo Credentials:**
- Admin: `admin@onehelp.com` / `password123`
- Volunteer: `john.volunteer@example.com` / `password123`
- Organization: `contact@helpinghands.org` / `password123`

## Testing

Run the test suite:

```bash
php artisan test
```

Run only security tests:

```bash
php artisan test --filter Security
```

## Support

For issues or questions, please contact the development team or create an issue in the repository.
