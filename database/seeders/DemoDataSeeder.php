<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Volunteer;
use App\Models\Organization;
use App\Models\Event;
use App\Models\Skill;
use App\Models\Notification;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Skills
        $skills = [
            ['skill_name' => 'First Aid', 'description' => 'Basic first aid and CPR training'],
            ['skill_name' => 'Teaching', 'description' => 'Experience in teaching and tutoring'],
            ['skill_name' => 'Construction', 'description' => 'Construction and building skills'],
            ['skill_name' => 'Cooking', 'description' => 'Food preparation and cooking'],
            ['skill_name' => 'Event Planning', 'description' => 'Organizing and planning events'],
            ['skill_name' => 'Fundraising', 'description' => 'Raising funds for causes'],
            ['skill_name' => 'Social Media', 'description' => 'Managing social media accounts'],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        // Create Admin User
        $adminUser = User::create([
            'email' => 'admin@onehelp.com',
            'password_hash' => Hash::make('password123'),
            'user_type' => 'admin',
            'is_active' => true,
            'created_at' => now(),
        ]);

        // Create Volunteer Users
        $volunteer1User = User::create([
            'email' => 'john.volunteer@example.com',
            'password_hash' => Hash::make('password123'),
            'user_type' => 'volunteer',
            'is_active' => true,
            'created_at' => now(),
        ]);

        $volunteer1 = Volunteer::create([
            'user_id' => $volunteer1User->user_id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'date_of_birth' => '1995-05-15',
            'address' => '123 Main St, City, State',
            'bio' => 'Passionate about helping the community and making a difference.',
            'total_hours' => 45,
            'events_completed' => 5,
        ]);

        $volunteer2User = User::create([
            'email' => 'sarah.volunteer@example.com',
            'password_hash' => Hash::make('password123'),
            'user_type' => 'volunteer',
            'is_active' => true,
            'created_at' => now(),
        ]);

        $volunteer2 = Volunteer::create([
            'user_id' => $volunteer2User->user_id,
            'first_name' => 'Sarah',
            'last_name' => 'Smith',
            'date_of_birth' => '1998-08-22',
            'address' => '456 Oak Ave, City, State',
            'bio' => 'Love working with children and teaching.',
            'total_hours' => 30,
            'events_completed' => 3,
        ]);

        // Create Organization Users
        $org1User = User::create([
            'email' => 'contact@helpinghands.org',
            'password_hash' => Hash::make('password123'),
            'user_type' => 'organization',
            'is_active' => true,
            'created_at' => now(),
        ]);

        $org1 = Organization::create([
            'user_id' => $org1User->user_id,
            'org_name' => 'Helping Hands Foundation',
            'org_type' => 'NGO',
            'contact_person' => 'Mary Johnson',
            'phone' => '555-0123',
            'address' => '789 Charity Lane, City, State',
            'description' => 'A nonprofit dedicated to community service and social welfare.',
            'registration_number' => 'REG-12345',
            'is_verified' => true,
        ]);

        $org2User = User::create([
            'email' => 'info@communitycare.org',
            'password_hash' => Hash::make('password123'),
            'user_type' => 'organization',
            'is_active' => true,
            'created_at' => now(),
        ]);

        $org2 = Organization::create([
            'user_id' => $org2User->user_id,
            'org_name' => 'Community Care Network',
            'org_type' => 'Community Group',
            'contact_person' => 'Robert Williams',
            'phone' => '555-0456',
            'address' => '321 Service Blvd, City, State',
            'description' => 'Building stronger communities through volunteer action.',
            'registration_number' => 'REG-67890',
            'is_verified' => true,
        ]);

        // Create Events
        Event::create([
            'organization_id' => $org1->organization_id,
            'event_name' => 'Food Bank Volunteer Day',
            'description' => 'Help sort and pack food for families in need',
            'long_description' => 'Join us for a rewarding day of service at the local food bank. We will be sorting, packing, and distributing food to families in our community who are experiencing food insecurity.',
            'event_date' => now()->addDays(7)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '15:00',
            'location' => '100 Food Bank Drive, City, State',
            'max_volunteers' => 20,
            'status' => 'open',
            'created_at' => now(),
        ]);

        Event::create([
            'organization_id' => $org1->organization_id,
            'event_name' => 'Beach Cleanup Initiative',
            'description' => 'Clean up our local beach and protect marine life',
            'long_description' => 'Help us keep our beaches clean! Volunteers will collect trash and recyclables from the beach and surrounding areas. All supplies will be provided.',
            'event_date' => now()->addDays(14)->format('Y-m-d'),
            'start_time' => '08:00',
            'end_time' => '12:00',
            'location' => 'Sunset Beach, City, State',
            'max_volunteers' => 50,
            'status' => 'open',
            'created_at' => now(),
        ]);

        Event::create([
            'organization_id' => $org2->organization_id,
            'event_name' => 'Youth Tutoring Program',
            'description' => 'Tutor students in math and reading',
            'long_description' => 'Our after-school tutoring program needs volunteers to help students with homework and academic skills. Perfect for teachers, college students, or anyone who loves working with kids.',
            'event_date' => now()->addDays(21)->format('Y-m-d'),
            'start_time' => '15:00',
            'end_time' => '18:00',
            'location' => 'Community Center, 555 Learning Way, City, State',
            'max_volunteers' => 15,
            'status' => 'open',
            'created_at' => now(),
        ]);

        // Create Notifications
        Notification::create([
            'user_id' => $volunteer1User->user_id,
            'notification_type' => 'welcome',
            'message' => 'Welcome to OneHelp! Start exploring volunteer opportunities.',
            'is_read' => true,
            'created_at' => now()->subDays(10),
        ]);

        Notification::create([
            'user_id' => $org1User->user_id,
            'notification_type' => 'verification_approved',
            'message' => 'Your organization has been verified! You can now create events.',
            'is_read' => true,
            'created_at' => now()->subDays(5),
        ]);

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@onehelp.com / password123');
        $this->command->info('Volunteer: john.volunteer@example.com / password123');
        $this->command->info('Organization: contact@helpinghands.org / password123');
    }
}
