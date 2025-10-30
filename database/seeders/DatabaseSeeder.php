<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        /** --------------------------------------------------
         *  USERS
         *  -------------------------------------------------- */
        $users = [
            [
                'email' => 'org@example.com',
                'password_hash' => bcrypt('password'),
                'user_type' => 'organization',
                'created_at' => $now,
                'last_login' => $now,
                'is_active' => true,
            ],
            [
                'email' => 'volunteer@example.com',
                'password_hash' => bcrypt('password'),
                'user_type' => 'volunteer',
                'created_at' => $now,
                'last_login' => $now,
                'is_active' => true,
            ],
        ];
        DB::table('users')->insert($users);

        /** --------------------------------------------------
         *  ORGANIZATIONS
         *  -------------------------------------------------- */
        $organizationId = DB::table('organizations')->insertGetId([
            'user_id' => 1,
            'org_name' => 'Helping Hands Org',
            'org_type' => 'Non-Profit',
            'registration_number' => 'HH-2025-001',
            'contact_person' => 'Jane Doe',
            'phone' => '+639171234567',
            'address' => '123 Charity Street, Manila',
            'description' => 'We organize volunteer activities for communities.',
            'logo_image' => 'logos/hh.png',
            'is_verified' => true,
            'verified_at' => $now,
        ]);

        /** --------------------------------------------------
         *  VOLUNTEERS
         *  -------------------------------------------------- */
        $volunteerId = DB::table('volunteers')->insertGetId([
            'user_id' => 2,
            'first_name' => 'John',
            'last_name' => 'Santos',
            'phone' => '+639998887777',
            'address' => 'Quezon City, PH',
            'date_of_birth' => '2000-04-15',
            'bio' => 'Enthusiastic volunteer passionate about social work.',
            'profile_image' => 'profiles/john.png',
            'total_hours' => 120,
            'events_completed' => 5,
        ]);

        /** --------------------------------------------------
         *  ORGANIZATION VERIFICATION
         *  -------------------------------------------------- */
        DB::table('organization_verifications')->insert([
            'organization_id' => $organizationId,
            'document_type' => 'Business Permit',
            'document_url' => 'docs/permit.pdf',
            'verification_status' => 'approved',
            'admin_notes' => 'Verified successfully.',
            'submitted_at' => $now,
            'reviewed_at' => $now,
        ]);

        /** --------------------------------------------------
         *  SKILLS
         *  -------------------------------------------------- */
        $skillIds = [];
        $skillIds[] = DB::table('skills')->insertGetId([
            'skill_name' => 'First Aid',
            'description' => 'Knowledge of basic first aid techniques.',
            'category' => 'Medical',
        ]);
        $skillIds[] = DB::table('skills')->insertGetId([
            'skill_name' => 'Cooking',
            'description' => 'Ability to prepare meals for events.',
            'category' => 'Hospitality',
        ]);

        /** --------------------------------------------------
         *  VOLUNTEER SKILLS
         *  -------------------------------------------------- */
        DB::table('volunteer_skills')->insert([
            'volunteer_id' => $volunteerId,
            'skill_id' => $skillIds[0],
            'proficiency_level' => 'Intermediate',
        ]);

        /** --------------------------------------------------
         *  EVENTS
         *  -------------------------------------------------- */
        $eventId = DB::table('events')->insertGetId([
            'organization_id' => $organizationId,
            'event_name' => 'Community Clean-up Drive',
            'description' => 'A local park cleaning event to promote environmental awareness.',
            'category' => 'Environment',
            'event_date' => '2025-11-10',
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'location' => 'Luneta Park, Manila',
            'max_volunteers' => 50,
            'registered_count' => 1,
            'status' => 'upcoming',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        /** --------------------------------------------------
         *  EVENT SKILLS
         *  -------------------------------------------------- */
        DB::table('event_skills')->insert([
            'event_id' => $eventId,
            'skill_id' => $skillIds[0],
            'is_required' => true,
        ]);

        /** --------------------------------------------------
         *  EVENT IMAGES
         *  -------------------------------------------------- */
        DB::table('event_images')->insert([
            'event_id' => $eventId,
            'image_url' => 'events/cleanup.jpg',
            'is_primary' => true,
            'uploaded_at' => $now,
        ]);

        /** --------------------------------------------------
         *  EVENT REGISTRATIONS
         *  -------------------------------------------------- */
        $registrationId = DB::table('event_registrations')->insertGetId([
            'event_id' => $eventId,
            'volunteer_id' => $volunteerId,
            'registered_at' => $now,
            'status' => 'confirmed',
            'notes' => 'Excited to join!',
            'hours_contributed' => 0,
            'certificate_issued' => false,
        ]);

        /** --------------------------------------------------
         *  ATTENDANCE
         *  -------------------------------------------------- */
        DB::table('attendances')->insert([
            'registration_id' => $registrationId,
            'check_in_time' => $now,
            'check_out_time' => null,
            'status' => 'present',
            'notes' => 'On time',
        ]);

        /** --------------------------------------------------
         *  NOTIFICATIONS
         *  -------------------------------------------------- */
        DB::table('notifications')->insert([
            'user_id' => 2,
            'notification_type' => 'event_update',
            'message' => 'Your event registration has been confirmed.',
            'is_read' => false,
            'created_at' => $now,
            'reference_type' => 'EventRegistration',
            'reference_id' => $registrationId,
        ]);

        /** --------------------------------------------------
         *  FEEDBACK
         *  -------------------------------------------------- */
        DB::table('feedbacks')->insert([
            'registration_id' => $registrationId,
            'rating' => 5,
            'comment' => 'Amazing event, very well organized!',
            'created_at' => $now,
        ]);
    }
}
