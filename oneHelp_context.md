
# OneHelp Project Context

## 1. Project Overview

**OneHelp** is a web application designed to connect volunteers with organizations that need their help. It serves as a platform for volunteer management, allowing organizations to post events and recruit volunteers, and for volunteers to find opportunities that match their skills and interests.

The application appears to have three main user roles:
- **Volunteers:** Individuals looking for volunteering opportunities.
- **Organizations:** Non-profits, NGOs, or other groups that need volunteers.
- **Admins:** Superusers who manage the platform.

The core functionality includes user registration and login, event creation and management, volunteer registration for events, and a messaging system for communication between users.

## 2. Core Technologies

- **Backend:** Laravel (PHP framework)
- **Frontend:** Blade templates with Tailwind CSS and vanilla JavaScript.
- **Database:** MySQL (implied by the migration syntax)
- **Dependencies:**
    - `laravel/framework`: The core Laravel framework.
    - `laravel/tinker`: An interactive REPL for Laravel.
    - `fakerphp/faker`: A library for generating fake data.
    - `phpunit/phpunit`: A testing framework for PHP.
    - `tailwindcss`: A utility-first CSS framework.
    - `vite`: A modern frontend build tool.

## 3. Database Schema

The database schema is defined by the migration files in `database/migrations`.

- **`users`**: Stores basic user information, including email, password, and user type.
- **`volunteers`**: Stores profile information for volunteers, linked to the `users` table.
- **`organizations`**: Stores profile information for organizations, linked to the `users` table.
- **`events`**: Stores information about volunteering events created by organizations.
- **`event_registrations`**: A pivot table that links volunteers to events they have registered for.
- **`skills`**: A table that stores a list of skills that can be associated with volunteers or required for events.
- **`volunteer_skills`**: A pivot table that links volunteers to their skills.
- **`event_skills`**: A pivot table that links events to the skills required for them.
- **`event_images`**: Stores images associated with events.
- **`attendances`**: Tracks volunteer attendance at events.
- **`organization_verifications`**: Stores documents for verifying organizations.
- **`notifications`**: Stores notifications for users.
- **`feedbacks`**: Stores feedback from volunteers about events.
- **`messages`**: Stores messages sent between users.
- **`cache`** and **`sessions`**: Standard Laravel tables for caching and session management.

## 4. Model-View-Controller (MVC) Breakdown

### Models (`app/Models`)

- **`User`**: Represents a user of the application.
    - **Relationships:**
        - `hasOne('Volunteer')`
        - `hasOne('Organization')`
        - `hasMany('Notification')`
        - `hasMany('Message')` (sent and received)
- **`Volunteer`**: Represents a volunteer user.
    - **Relationships:**
        - `belongsTo('User')`
        - `hasMany('EventRegistration')`
        - `belongsToMany('Skill', 'volunteer_skills')`
- **`Organization`**: Represents an organization user.
    - **Relationships:**
        - `belongsTo('User')`
        - `hasMany('Event')`
        - `hasMany('OrganizationVerification')`
- **`Event`**: Represents a volunteering event.
    - **Relationships:**
        - `belongsTo('Organization')`
        - `hasMany('EventRegistration')`
        - `belongsToMany('Skill', 'event_skills')`
        - `hasMany('EventImage')`
- **`EventRegistration`**: Represents a volunteer's registration for an event.
    - **Relationships:**
        - `belongsTo('Volunteer')`
        - `belongsTo('Event')`
        - `hasMany('Attendance')`
        - `hasOne('Feedback')`
- **`Skill`**: Represents a skill.
    - **Relationships:**
        - `belongsToMany('Volunteer', 'volunteer_skills')`
        - `belongsToMany('Event', 'event_skills')`
- **`Message`**: Represents a message between two users.
    - **Relationships:**
        - `belongsTo('User', 'sender')`
        - `belongsTo('User', 'receiver')`
- **Other Models:** `Attendance`, `EventImage`, `EventSkill`, `Feedback`, `Notification`, `OrganizationVerification`, `VolunteerSkill` are mostly pivot or data models with straightforward relationships.

### Controllers (`app/Http/Controllers`)

- **`VolunteerController`**: Handles the volunteer-facing parts of the application, including the dashboard, profile management, and account settings. The `messages` method is responsible for displaying the messaging interface for volunteers.
- **`MessagesController`**: Handles the logic for the messaging system, including fetching conversations, sending messages, and marking messages as read.
- **`Auth/RegisterController`**: Handles user registration.
- **`Auth/LoginController`**: Handles user login and logout.
- **API Controllers (`UserController`, `EventController`, etc.):** These controllers provide a RESTful API for their corresponding models. They are not used by the web routes but are available for external consumption.

### Views (`resources/views`)

The views are organized by feature. The main layouts are in `resources/views/layouts`.

- **`volunteer/`**: Contains the views for the volunteer dashboard, profile, account settings, and messages.
    - **`messages.blade.php`**: The main view for the messaging feature. It displays a list of conversations and the messages in the selected conversation.
- **`pages/`**: Contains static pages like `about.blade.php`.
- **`auth/`**: Contains the login and registration views.
- **`layouts/`**: Contains the main layout files for the application.

## 5. Routing

- **`routes/web.php`**: Defines the web-facing routes of the application.
    - `/`: The homepage.
    - `/login`, `/register`, `/logout`: Authentication routes.
    - `/volunteer/*`: Routes for the volunteer dashboard, profile, and messages, protected by the `auth` middleware.
    - `/organization/dashboard`, `/admin/dashboard`: Placeholder routes for organization and admin dashboards.
- **`routes/api.php`**: Defines the API routes.
    - Provides `apiResource` routes for all the main models, allowing for CRUD operations via a RESTful API.

## 6. Database Seeding

- **`database/seeders/DatabaseSeeder.php`**: The main seeder file that calls other seeders to populate the database with initial data. It seeds users, volunteers, organizations, events, skills, and messages.
- **`database/seeders/SkillSeeder.php`** and **`database/seeders/UserSeeder.php`**: Additional seeders for skills and users.

## 7. User Roles

- **Volunteer:** Can create a profile, search for events, register for events, and communicate with organizations.
- **Organization:** Can create a profile, post events, manage event registrations, and communicate with volunteers.
- **Admin:** (partially implemented) Has access to an admin dashboard, but the functionality is not yet defined.

This document provides a comprehensive overview of the OneHelp project. It should be a useful reference for understanding the codebase and its architecture.
