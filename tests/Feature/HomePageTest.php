<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Event;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function home_page_displays_dynamic_events_from_database()
    {
        // Create test organization
        $user = User::create([
            'email' => 'org@test.com',
            'password_hash' => bcrypt('password'),
            'user_type' => 'organization',
            'is_active' => true,
            'created_at' => now(),
        ]);

        $organization = Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Organization',
            'org_type' => 'NGO',
            'contact_person' => 'John Doe',
            'phone' => '1234567890',
            'address' => 'Test Address',
            'description' => 'Test description',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create test events
        $events = [];
        for ($i = 1; $i <= 4; $i++) {
            $events[] = Event::create([
                'organization_id' => $organization->organization_id,
                'event_name' => "Test Event {$i}",
                'description' => "Description for test event {$i}",
                'category' => 'Environment',
                'event_date' => now()->addDays($i)->format('Y-m-d'),
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'location' => 'Test Location',
                'max_volunteers' => 50,
                'registered_count' => 0,
                'status' => 'open',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Visit home page
        $response = $this->get('/');

        // Assert events are displayed
        $response->assertStatus(200);
        foreach ($events as $event) {
            $response->assertSee($event->event_name);
            $response->assertSee($organization->org_name);
        }
    }

    /** @test */
    public function home_page_shows_fallback_events_when_no_events_exist()
    {
        // Visit home page with no events in database
        $response = $this->get('/');

        $response->assertStatus(200);
        // Should show fallback hardcoded events
        $response->assertSee('Cleanup Drive');
        $response->assertSee('Green Earth Alliance');
    }

    /** @test */
    public function home_page_displays_organization_name_for_events()
    {
        // Create test organization
        $user = User::create([
            'email' => 'testorg@test.com',
            'password_hash' => bcrypt('password'),
            'user_type' => 'organization',
            'is_active' => true,
            'created_at' => now(),
        ]);

        $organization = Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Green Future Foundation',
            'org_type' => 'NGO',
            'contact_person' => 'Jane Doe',
            'phone' => '1234567890',
            'address' => 'Test Address',
            'description' => 'Environmental conservation',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create event
        $event = Event::create([
            'organization_id' => $organization->organization_id,
            'event_name' => 'Beach Cleanup Drive',
            'description' => 'Join us for a beach cleanup',
            'category' => 'Environment',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '08:00:00',
            'end_time' => '12:00:00',
            'location' => 'Manila Bay',
            'max_volunteers' => 100,
            'registered_count' => 0,
            'status' => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get('/');

        // Assert both event name and organization name are displayed
        $response->assertStatus(200);
        $response->assertSee('Beach Cleanup Drive');
        $response->assertSee('Green Future Foundation');
    }

    /** @test */
    public function home_page_only_shows_open_future_events()
    {
        // Create test organization
        $user = User::create([
            'email' => 'org2@test.com',
            'password_hash' => bcrypt('password'),
            'user_type' => 'organization',
            'is_active' => true,
            'created_at' => now(),
        ]);

        $organization = Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Org',
            'org_type' => 'NGO',
            'contact_person' => 'Test Person',
            'phone' => '1234567890',
            'address' => 'Test Address',
            'description' => 'Test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create past event (should not be displayed)
        Event::create([
            'organization_id' => $organization->organization_id,
            'event_name' => 'Past Event',
            'description' => 'This event has passed',
            'category' => 'Environment',
            'event_date' => now()->subDays(5)->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'location' => 'Test Location',
            'max_volunteers' => 50,
            'registered_count' => 0,
            'status' => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create closed event (should not be displayed)
        Event::create([
            'organization_id' => $organization->organization_id,
            'event_name' => 'Closed Event',
            'description' => 'This event is closed',
            'category' => 'Environment',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'location' => 'Test Location',
            'max_volunteers' => 50,
            'registered_count' => 0,
            'status' => 'closed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create future open event (should be displayed)
        Event::create([
            'organization_id' => $organization->organization_id,
            'event_name' => 'Future Event',
            'description' => 'This event is in the future and open',
            'category' => 'Environment',
            'event_date' => now()->addDays(10)->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time' => '17:00:00',
            'location' => 'Test Location',
            'max_volunteers' => 50,
            'registered_count' => 0,
            'status' => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Past Event');
        $response->assertDontSee('Closed Event');
        $response->assertSee('Future Event');
    }
}
