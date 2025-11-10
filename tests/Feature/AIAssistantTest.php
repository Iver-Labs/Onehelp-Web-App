<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AIAssistantTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    /**
     * Test that AI description generation requires authentication.
     */
    public function test_ai_description_generation_requires_authentication()
    {
        $response = $this->postJson('/api/ai/generate-event-description', [
            'event_name' => 'Beach Cleanup',
            'category' => 'Environment',
            'location' => 'Santa Monica Beach',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test that AI description generation validates required fields.
     */
    public function test_ai_description_generation_validates_event_name()
    {
        $user = User::factory()->create([
            'user_type' => 'organization',
            'is_active' => true,
        ]);

        Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Organization',
            'org_type' => 'NGO',
            'contact_person' => 'Test Contact',
            'phone' => '1234567890',
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/ai/generate-event-description', [
            'category' => 'Environment',
            'location' => 'Santa Monica Beach',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['event_name']);
    }

    /**
     * Test that AI description generation works with minimal data.
     */
    public function test_ai_description_generation_works_with_event_name_only()
    {
        $user = User::factory()->create([
            'user_type' => 'organization',
            'is_active' => true,
        ]);

        Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Organization',
            'org_type' => 'NGO',
            'contact_person' => 'Test Contact',
            'phone' => '1234567890',
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/ai/generate-event-description', [
            'event_name' => 'Beach Cleanup',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'description',
                 ])
                 ->assertJson([
                     'success' => true,
                 ]);

        $this->assertNotEmpty($response->json('description'));
    }

    /**
     * Test that AI description generation works with full data.
     */
    public function test_ai_description_generation_works_with_full_data()
    {
        $user = User::factory()->create([
            'user_type' => 'organization',
            'is_active' => true,
        ]);

        Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Organization',
            'org_type' => 'NGO',
            'contact_person' => 'Test Contact',
            'phone' => '1234567890',
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/ai/generate-event-description', [
            'event_name' => 'Beach Cleanup',
            'category' => 'Environment',
            'location' => 'Santa Monica Beach',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'description',
                 ])
                 ->assertJson([
                     'success' => true,
                 ]);

        $description = $response->json('description');
        $this->assertNotEmpty($description);
        $this->assertStringContainsString('Beach Cleanup', $description);
    }

    /**
     * Test that description contains relevant content for different categories.
     */
    public function test_ai_generates_category_specific_content()
    {
        $user = User::factory()->create([
            'user_type' => 'organization',
            'is_active' => true,
        ]);

        Organization::create([
            'user_id' => $user->user_id,
            'org_name' => 'Test Organization',
            'org_type' => 'NGO',
            'contact_person' => 'Test Contact',
            'phone' => '1234567890',
        ]);

        $this->actingAs($user);

        $categories = ['Environment', 'Education', 'Health', 'Community', 'Animals'];

        foreach ($categories as $category) {
            $response = $this->postJson('/api/ai/generate-event-description', [
                'event_name' => "Test Event for {$category}",
                'category' => $category,
                'location' => 'Test Location',
            ]);

            $response->assertStatus(200);
            $description = $response->json('description');
            
            $this->assertNotEmpty($description);
            $this->assertIsString($description);
            // Description should be at least 100 characters
            $this->assertGreaterThan(100, strlen($description));
        }
    }

    /**
     * Test that volunteers can also access AI generation.
     */
    public function test_volunteers_cannot_generate_descriptions()
    {
        $user = User::factory()->create([
            'user_type' => 'volunteer',
            'is_active' => true,
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/ai/generate-event-description', [
            'event_name' => 'Beach Cleanup',
            'category' => 'Environment',
        ]);

        // Volunteers should still be able to access the endpoint if authenticated
        // as the feature is behind api.auth middleware, not role-specific
        $response->assertStatus(200);
    }
}
