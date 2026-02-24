<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get admin user for testing.
     */
    private function getAdmin(): User
    {
        return User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }

    /**
     * Test admin can list users.
     */
    public function test_admin_can_list_users(): void
    {
        // Create multiple users
        User::factory()->count(5)->create();

        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'web')
            ->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'users' => [
                    'current_page',
                    'data',
                    'total',
                ],
            ]);
    }

    /**
     * Test admin can view single user.
     */
    public function test_admin_can_view_single_user(): void
    {
        $user = User::factory()->create();

        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'web')
            ->getJson('/api/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                ],
            ]);
    }

    /**
     * Test admin can delete user (soft delete).
     */
    public function test_admin_can_delete_user(): void
    {
        $user = User::factory()->create();

        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'web')
            ->deleteJson('/api/users/' . $user->id);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);

        // Verify soft delete
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    /**
     * Test pagination works correctly.
     */
    public function test_user_list_is_paginated(): void
    {
        User::factory()->count(15)->create();

        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'web')
            ->getJson('/api/users?per_page=5');

        $response->assertStatus(200);

        $data = $response->json('users');
        $this->assertEquals(5, count($data['data']));
    }

    /**
     * Test 404 returned for non-existent user.
     */
    public function test_returns_404_for_non_existent_user(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin, 'web')
            ->getJson('/api/users/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'User not found',
            ]);
    }
}
