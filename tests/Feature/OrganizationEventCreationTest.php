<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use App\Models\Event;
use App\Models\EventImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class OrganizationEventCreationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an organization can create an event
     */
    public function test_organization_can_create_event(): void
    {
        Storage::fake('public');

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

        // Create a fake image file
        $image = \Illuminate\Http\UploadedFile::fake()->image('event.jpg', 800, 600);

        // Submit event creation form
        $response = $this->post(route('organization.events.store'), [
            'event_name' => 'Community Cleanup',
            'description' => 'Help clean up the local park',
            'category' => 'Environment',
            'event_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'location' => 'Central Park',
            'max_volunteers' => 30,
            'status' => 'open',
            'event_image' => $image,
        ]);

        // Assert event was created
        $this->assertDatabaseHas('events', [
            'event_name' => 'Community Cleanup',
            'organization_id' => $organization->organization_id,
            'location' => 'Central Park',
            'max_volunteers' => 30,
        ]);

        // Assert event image was created
        $event = Event::where('event_name', 'Community Cleanup')->first();
        $this->assertDatabaseHas('event_images', [
            'event_id' => $event->event_id,
            'is_primary' => true,
        ]);

        // Assert image file was stored in events directory
        $files = Storage::disk('public')->files('events');
        $this->assertNotEmpty($files, 'No files found in events directory');

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

        $image = \Illuminate\Http\UploadedFile::fake()->image('event.jpg');

        // Submit with invalid data (end_time before start_time)
        $response = $this->post(route('organization.events.store'), [
            'event_name' => 'Invalid Event',
            'event_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '14:00',
            'end_time' => '12:00',  // Invalid: before start_time
            'location' => 'Some Location',
            'max_volunteers' => 10,
            'event_image' => $image,
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

    /**
     * Test that event creation requires an image
     */
    public function test_event_creation_requires_image(): void
    {
        $user = User::create([
            'email' => 'org4@test.com',
            'password_hash' => bcrypt('password'),
            'user_type' => 'organization',
        ]);

        $organization = Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Organization 4',
            'org_type' => 'NGO',
            'contact_person' => 'Alice Johnson',
        ]);

        $this->actingAs($user);

        // Submit without image
        $response = $this->post(route('organization.events.store'), [
            'event_name' => 'No Image Event',
            'description' => 'Event without image',
            'event_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'location' => 'Some Place',
            'max_volunteers' => 20,
        ]);

        $response->assertSessionHasErrors('event_image');
    }

    /**
     * Test that event creation validates image type
     */
    public function test_event_creation_validates_image_type(): void
    {
        $user = User::create([
            'email' => 'org5@test.com',
            'password_hash' => bcrypt('password'),
            'user_type' => 'organization',
        ]);

        $organization = Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Organization 5',
            'org_type' => 'NGO',
            'contact_person' => 'Charlie Brown',
        ]);

        $this->actingAs($user);

        // Create an invalid file (not an image)
        $file = \Illuminate\Http\UploadedFile::fake()->create('document.pdf', 1000);

        $response = $this->post(route('organization.events.store'), [
            'event_name' => 'Invalid Image Event',
            'description' => 'Event with invalid image',
            'event_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'location' => 'Some Place',
            'max_volunteers' => 20,
            'event_image' => $file,
        ]);

        $response->assertSessionHasErrors('event_image');
    }

    /**
     * Test that event creation accepts PNG images
     */
    public function test_event_creation_accepts_png_images(): void
    {
        $user = User::create([
            'email' => 'org6@test.com',
            'password_hash' => bcrypt('password'),
            'user_type' => 'organization',
        ]);

        $organization = Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Organization 6',
            'org_type' => 'NGO',
            'contact_person' => 'Diana Prince',
        ]);

        $this->actingAs($user);

        Storage::fake('public');

        // Create a PNG image
        $image = \Illuminate\Http\UploadedFile::fake()->image('event.png', 800, 600);

        $response = $this->post(route('organization.events.store'), [
            'event_name' => 'PNG Image Event',
            'description' => 'Event with PNG image',
            'category' => 'Community',
            'event_date' => Carbon::tomorrow()->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'location' => 'Some Place',
            'max_volunteers' => 20,
            'event_image' => $image,
        ]);

        $response->assertRedirect(route('organization.dashboard'));
        $this->assertDatabaseHas('events', ['event_name' => 'PNG Image Event']);
    }
}
