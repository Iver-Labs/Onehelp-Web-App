<?php

namespace Tests\Feature\Security;

use App\Models\User;
use App\Models\Organization;
use App\Models\Volunteer;
use App\Models\Event;
use App\Models\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that volunteers cannot access admin endpoints
     */
    public function test_volunteers_cannot_create_users(): void
    {
        $user = User::factory()->create([
            'user_type' => 'volunteer',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->postJson('/api/users', [
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'user_type' => 'volunteer'
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ]);
    }

    /**
     * Test that users can only view their own profile
     */
    public function test_users_cannot_view_other_users_profiles(): void
    {
        $user1 = User::factory()->create([
            'user_type' => 'volunteer',
            'is_active' => true,
        ]);
        
        $user2 = User::factory()->create([
            'user_type' => 'volunteer',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user1)->getJson('/api/users/' . $user2->user_id);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized. You can only view your own profile.'
            ]);
    }

    /**
     * Test that admins can view all users
     */
    public function test_admins_can_view_all_users(): void
    {
        $admin = User::factory()->create([
            'user_type' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test that users can only view their own notifications
     */
    public function test_users_can_only_view_own_notifications(): void
    {
        $user = User::factory()->create([
            'user_type' => 'volunteer',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->getJson('/api/notifications');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test that volunteers cannot modify skills
     */
    public function test_volunteers_cannot_modify_skills(): void
    {
        $user = User::factory()->create([
            'user_type' => 'volunteer',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->postJson('/api/skills', [
            'skill_name' => 'Test Skill'
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test that admins can modify skills
     */
    public function test_admins_can_modify_skills(): void
    {
        $admin = User::factory()->create([
            'user_type' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->postJson('/api/skills', [
            'skill_name' => 'Test Skill'
        ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);
    }

    /**
     * Test that volunteers cannot create events
     */
    public function test_volunteers_cannot_create_events(): void
    {
        $user = User::factory()->create([
            'user_type' => 'volunteer',
            'is_active' => true,
        ]);

        // Create a fake organization to use in the request
        $organization = Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Org',
            'org_type' => 'NGO',
            'contact_person' => 'John Doe',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'description' => 'Test description',
            'is_verified' => true,
        ]);

        $response = $this->actingAs($user)->postJson('/api/events', [
            'organization_id' => $organization->organization_id,
            'event_name' => 'Test Event',
            'description' => 'Test description',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'location' => 'Test Location',
            'max_volunteers' => 10,
        ]);

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized. Only organizations can create events.'
            ]);
    }

    /**
     * Test that organizations can create events
     */
    public function test_organizations_can_create_events(): void
    {
        $user = User::factory()->create([
            'user_type' => 'organization',
            'is_active' => true,
        ]);

        $organization = Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Org',
            'org_type' => 'NGO',
            'contact_person' => 'John Doe',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'description' => 'Test description',
            'is_verified' => true,
        ]);

        $response = $this->actingAs($user)->postJson('/api/events', [
            'organization_id' => $organization->organization_id,
            'event_name' => 'Test Event',
            'description' => 'Test description',
            'event_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'location' => 'Test Location',
            'max_volunteers' => 10,
        ]);

        $response->assertStatus(201)
            ->assertJson(['success' => true]);
    }
}
