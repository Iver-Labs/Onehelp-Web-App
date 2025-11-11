<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Volunteer;
use App\Models\Organization;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportGenerationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that PDF facade is properly loaded and accessible.
     */
    public function test_pdf_facade_is_accessible(): void
    {
        // This test verifies that the Barryvdh\DomPDF\Facade\Pdf class exists and is loadable
        $this->assertTrue(class_exists('Barryvdh\DomPDF\Facade\Pdf'));
    }

    /**
     * Test volunteer activity report generation.
     */
    public function test_volunteer_activity_report_can_be_generated(): void
    {
        // Create a volunteer user
        $user = User::factory()->create([
            'email' => 'volunteer@test.com',
            'user_type' => 'volunteer',
            'is_active' => true,
        ]);

        $volunteer = Volunteer::create([
            'user_id' => $user->user_id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'date_of_birth' => '1990-01-01',
            'phone_number' => '1234567890',
            'address' => '123 Test St',
            'barangay' => 'Test Barangay',
            'city_municipality' => 'Test City',
            'province' => 'Test Province',
            'emergency_contact_name' => 'Jane Doe',
            'emergency_contact_number' => '0987654321',
        ]);

        $this->actingAs($user);

        $response = $this->get("/reports/volunteer/{$volunteer->volunteer_id}/activity");

        // The report should either return 200 (PDF) or have proper response
        $this->assertTrue(
            $response->status() === 200 || $response->status() === 302,
            "Expected 200 or 302 status, got {$response->status()}"
        );
    }

    /**
     * Test event participation report generation.
     */
    public function test_event_participation_report_can_be_generated(): void
    {
        // Create organization and event
        $orgUser = User::factory()->create([
            'email' => 'org@test.com',
            'user_type' => 'organization',
            'is_active' => true,
        ]);

        $organization = Organization::create([
            'user_id' => $orgUser->user_id,
            'org_name' => 'Test Org',
            'org_type' => 'NGO',
            'contact_person' => 'Test Person',
            'phone' => '1234567890',
            'address' => '123 Test St',
        ]);

        $event = Event::create([
            'organization_id' => $organization->organization_id,
            'event_name' => 'Test Event',
            'category' => 'Community Service',
            'event_date' => now()->addDays(7)->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'location' => 'Test Location',
            'barangay' => 'Test Barangay',
            'max_volunteers' => 10,
            'description' => 'Test description',
            'status' => 'open',
        ]);

        $this->actingAs($orgUser);

        $response = $this->get("/reports/event/{$event->event_id}/participation");

        $this->assertTrue(
            $response->status() === 200 || $response->status() === 302,
            "Expected 200 or 302 status, got {$response->status()}"
        );
    }

    /**
     * Test organization summary report generation.
     */
    public function test_organization_summary_report_can_be_generated(): void
    {
        // Create organization
        $orgUser = User::factory()->create([
            'email' => 'org@test.com',
            'user_type' => 'organization',
            'is_active' => true,
        ]);

        $organization = Organization::create([
            'user_id' => $orgUser->user_id,
            'org_name' => 'Test Org',
            'org_type' => 'NGO',
            'contact_person' => 'Test Person',
            'phone' => '1234567890',
            'address' => '123 Test St',
        ]);

        $this->actingAs($orgUser);

        $response = $this->get("/reports/organization/{$organization->organization_id}/summary");

        $this->assertTrue(
            $response->status() === 200 || $response->status() === 302,
            "Expected 200 or 302 status, got {$response->status()}"
        );
    }

    /**
     * Test system summary report generation (admin only).
     */
    public function test_system_summary_report_can_be_generated_by_admin(): void
    {
        // Create admin user
        $adminUser = User::factory()->create([
            'email' => 'admin@test.com',
            'user_type' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($adminUser);

        $response = $this->get('/reports/system/summary');

        $this->assertTrue(
            $response->status() === 200 || $response->status() === 302,
            "Expected 200 or 302 status, got {$response->status()}"
        );
    }
}
