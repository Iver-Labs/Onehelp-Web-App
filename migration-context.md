# Migration and Model Context

This document provides a summary of the database migrations, their relationships, and the content of the `User` model. It also answers the question about the existence of controllers for the volunteer profile and account.

## Migration Summary

The migrations define the following tables and their relationships:

### Core Tables

*   **`users`**: The central table for authentication. It stores the user's email, password, and `user_type` (volunteer, organization, or admin).
    *   `user_id` (Primary Key)
    *   `email`
    *   `password_hash`
    *   `user_type`
    *   `email_verified_at`
    *   `last_login`
    *   `is_active`
    *   `remember_token`

*   **`volunteers`**: Stores profile information for volunteers. It has a one-to-one relationship with the `users` table.
    *   `volunteer_id` (Primary Key)
    *   `user_id` (Foreign Key to `users.user_id`)
    *   `first_name`, `last_name`, `address`, `date_of_birth`, `bio`, `profile_image`
    *   `total_hours`, `events_completed`

*   **`organizations`**: Stores profile information for organizations. It has a one-to-one relationship with the `users` table.
    *   `organization_id` (Primary Key)
    *   `user_id` (Foreign Key to `users.user_id`)
    *   `org_name`, `org_type`, `registration_number`, `contact_person`, `phone`, `address`, `description`, `logo_image`
    *   `is_verified`, `verified_at`

*   **`events`**: Stores information about volunteering events created by organizations.
    *   `event_id` (Primary Key)
    *   `organization_id` (Foreign Key to `organizations.organization_id`)
    *   `event_name`, `description`, `category`, `event_date`, `start_time`, `end_time`, `location`
    *   `max_volunteers`, `registered_count`, `status`

*   **`skills`**: A master list of skills that can be associated with volunteers or required for events.
    *   `skill_id` (Primary Key)
    *   `skill_name`, `description`, `category`

### Relationship / Pivot Tables

*   **`event_registrations`**: Connects volunteers to events they have registered for. This is a many-to-many relationship between `volunteers` and `events`.
    *   `registration_id` (Primary Key)
    *   `event_id` (Foreign Key to `events.event_id`)
    *   `volunteer_id` (Foreign Key to `volunteers.volunteer_id`)
    *   `status`, `hours_contributed`, `certificate_issued`

*   **`volunteer_skills`**: Connects volunteers with their skills. This is a many-to-many relationship between `volunteers` and `skills`.
    *   `volunteer_skill_id` (Primary Key)
    *   `volunteer_id` (Foreign Key to `volunteers.volunteer_id`)
    *   `skill_id` (Foreign Key to `skills.skill_id`)
    *   `proficiency_level`

*   **`event_skills`**: Specifies the skills required for an event. This is a many-to-many relationship between `events` and `skills`.
    *   `event_skill_id` (Primary Key)
    *   `event_id` (Foreign Key to `events.event_id`)
    *   `skill_id` (Foreign Key to `skills.skill_id`)
    *   `is_required`

### Supporting Tables

*   **`event_images`**: Stores multiple images for an event.
    *   `image_id` (Primary Key)
    *   `event_id` (Foreign Key to `events.event_id`)
    *   `image_url`, `is_primary`

*   **`attendances`**: Tracks volunteer attendance for an event.
    *   `attendance_id` (Primary Key)
    *   `registration_id` (Foreign Key to `event_registrations.registration_id`)
    *   `check_in_time`, `check_out_time`, `status`

*   **`organization_verifications`**: Stores documents for verifying organizations.
    *   `verification_id` (Primary Key)
    *   `organization_id` (Foreign Key to `organizations.organization_id`)
    *   `document_type`, `document_url`, `verification_status`

*   **`notifications`**: Stores notifications for users.
    *   `notification_id` (Primary Key)
    *   `user_id` (Foreign Key to `users.user_id`)
    *   `notification_type`, `message`, `is_read`

*   **`feedbacks`**: Stores feedback and ratings from volunteers for events.
    *   `feedback_id` (Primary Key)
    *   `registration_id` (Foreign Key to `event_registrations.registration_id`)
    *   `rating`, `comment`

*   **`cache`** and **`sessions`**: Standard Laravel tables for caching and session management.

## User Model (`app/Models/User.php`)

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'email', 
        'password_hash', 
        'user_type', 
        'created_at', 
        'last_login', 
        'is_active'
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'last_login' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    // Override the default password column name
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Relationships
    public function volunteer() {
        return $this->hasOne(Volunteer::class, 'user_id', 'user_id');
    }

    public function organization() {
        return $this->hasOne(Organization::class, 'user_id', 'user_id');
    }

    public function notifications() {
        return $this->hasMany(Notification::class, 'user_id', 'user_id');
    }

    // Helper methods
    public function isVolunteer()
    {
        return $this->user_type === 'volunteer';
    }

    public function isOrganization()
    {
        return $this->user_type === 'organization';
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    // Update last login timestamp
    public function updateLastLogin()
    {
        $this->last_login = now();
        $this->save();
    }
}
```

## Controller for Volunteer Profile and Account

**Yes, there are existing methods in the `VolunteerController` for handling the profile and account pages.**

The file `app/Http/Controllers/VolunteerController.php` contains the following methods:

*   `public function profile()`: This method is responsible for displaying the volunteer's profile page. It fetches the volunteer's data and passes it to the `volunteer.profile` view.
*   `public function account()`: This method is responsible for displaying the account settings page. It fetches the user's data and passes it to the `volunteer.account` view.
