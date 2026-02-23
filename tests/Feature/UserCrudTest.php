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
     * Get auth token for admin.
     */
    private function getAdminToken(): string
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        return $admin->createToken('api-token')->plainTextToken;
    }

    /**
     * Test admin can list users.
     */
    public function test_admin_can_list_users(): void
    {
        // Create multiple users
        User::factory()->count(5)->create();

        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
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

        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
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
     * Test admin can update user.
     */
    public function test_admin_can_update_user(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/users/' . $user->id, [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'User updated successfully',
            ]);

        $user->refresh();
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);
    }

    /**
     * Test admin can update user role.
     */
    public function test_admin_can_update_user_role(): void
    {
        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/users/' . $user->id, [
                'role' => 'admin',
            ]);

        $response->assertStatus(200);

        $user->refresh();
        $this->assertEquals('admin', $user->role);
    }

    /**
     * Test admin can delete user (soft delete).
     */
    public function test_admin_can_delete_user(): void
    {
        $user = User::factory()->create();

        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
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

        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/users?per_page=5');

        $response->assertStatus(200);

        $data = $response->json('users');
        $this->assertEquals(5, count($data['data']));
    }

    /**
     * Test email uniqueness validation when updating user.
     */
    public function test_update_user_validates_unique_email(): void
    {
        $user1 = User::factory()->create([
            'email' => 'user1@example.com',
        ]);
        $user2 = User::factory()->create([
            'email' => 'user2@example.com',
        ]);

        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/users/' . $user1->id, [
                'email' => 'user2@example.com',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test 404 returned for non-existent user.
     */
    public function test_returns_404_for_non_existent_user(): void
    {
        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/users/99999');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
                'message' => 'User not found',
            ]);
    }
}
