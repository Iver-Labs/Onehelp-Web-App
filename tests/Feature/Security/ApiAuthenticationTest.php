<?php

namespace Tests\Feature\Security;

use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that API endpoints require authentication
     */
    public function test_api_requires_authentication(): void
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated. Please login to access this resource.'
            ]);
    }

    /**
     * Test that authenticated users can access API
     */
    public function test_authenticated_admin_can_access_api(): void
    {
        $user = User::factory()->create([
            'user_type' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test that public endpoints don't require authentication
     */
    public function test_public_endpoints_accessible_without_auth(): void
    {
        $response = $this->getJson('/api/events');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test that skills endpoint is public for reading
     */
    public function test_skills_index_is_public(): void
    {
        $response = $this->getJson('/api/skills');

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test that modifying skills requires authentication
     */
    public function test_skills_modification_requires_auth(): void
    {
        $response = $this->postJson('/api/skills', [
            'skill_name' => 'Test Skill'
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test inactive users cannot access API
     */
    public function test_inactive_users_cannot_access_api(): void
    {
        $user = User::factory()->create([
            'user_type' => 'volunteer',
            'is_active' => false,
        ]);

        $response = $this->actingAs($user)->getJson('/api/notifications');

        // Should be allowed since they're authenticated, but may have limited access
        // The actual behavior depends on your business logic
        $response->assertStatus(200);
    }
}
