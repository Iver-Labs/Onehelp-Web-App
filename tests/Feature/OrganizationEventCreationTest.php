<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrganizationEventCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an organization can create an event
     */
    public function test_organization_can_create_event(): void
    {
        // Create a test user with organization
        $user = User::create([
            'email' => 'org@test.com',
            'password_hash' => bcrypt('password'),
            'user_type' => 'organization',
        ]);

        $organization = Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Organization',
            'org_type' => 'NGO',
            'contact_person' => 'John Doe',
        ]);

        // Authenticate as the organization user
        $this->actingAs($user);

        // Submit event creation form
        $response = $this->post(route('organization.events.store'), [
            'event_name' => 'Community Cleanup',
            'description' => 'Help clean up the local park',
            'category' => 'Environment',
            'event_date' => '2025-12-15',
            'start_time' => '09:00',
            'end_time' => '12:00',
            'location' => 'Central Park',
            'max_volunteers' => 30,
            'status' => 'open',
        ]);

        // Assert event was created
        $this->assertDatabaseHas('events', [
            'event_name' => 'Community Cleanup',
            'organization_id' => $organization->organization_id,
            'location' => 'Central Park',
            'max_volunteers' => 30,
        ]);

        // Assert redirect to dashboard with success message
        $response->assertRedirect(route('organization.dashboard'));
        $response->assertSessionHas('success');
    }

    /**
     * Test event creation validation
     */
    public function test_event_creation_requires_valid_data(): void
    {
        $user = User::create([
            'email' => 'org2@test.com',
            'password_hash' => bcrypt('password'),
            'user_type' => 'organization',
        ]);

        $organization = Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Organization 2',
            'org_type' => 'NGO',
            'contact_person' => 'Jane Doe',
        ]);

        $this->actingAs($user);

        // Submit with invalid data (end_time before start_time)
        $response = $this->post(route('organization.events.store'), [
            'event_name' => 'Invalid Event',
            'event_date' => '2025-12-15',
            'start_time' => '14:00',
            'end_time' => '12:00',  // Invalid: before start_time
            'location' => 'Some Location',
            'max_volunteers' => 10,
        ]);

        // Assert validation failed
        $response->assertSessionHasErrors('end_time');

        // Assert event was not created
        $this->assertDatabaseMissing('events', [
            'event_name' => 'Invalid Event',
        ]);
    }

    /**
     * Test that the create event form can be accessed
     */
    public function test_organization_can_access_create_event_form(): void
    {
        $user = User::create([
            'email' => 'org3@test.com',
            'password_hash' => bcrypt('password'),
            'user_type' => 'organization',
        ]);

        $organization = Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Organization 3',
            'org_type' => 'NGO',
            'contact_person' => 'Bob Smith',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('organization.events.create'));

        $response->assertStatus(200);
        $response->assertViewIs('organization.create-event');
    }
}
