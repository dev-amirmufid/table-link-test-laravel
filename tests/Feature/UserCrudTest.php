<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function getAdminToken(): string
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        return $admin->createToken('auth-token')->plainTextToken;
    }

    /**
     * Test admin can list users.
     */
    public function test_admin_can_list_users(): void
    {
        $token = $this->getAdminToken();

        User::create([
            'name' => 'Test User 1',
            'email' => 'user1@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Test User 2',
            'email' => 'user2@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'users' => [
                    'data',
                    'current_page',
                    'last_page',
                ],
            ]);
    }

    /**
     * Test admin can create user.
     */
    public function test_admin_can_create_user(): void
    {
        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/users', [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'password123',
                'role' => 'user',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
        ]);
    }

    /**
     * Test admin can update user.
     */
    public function test_admin_can_update_user(): void
    {
        $token = $this->getAdminToken();

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/users/' . $user->id, [
                'name' => 'Updated User',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Updated User',
        ]);
    }

    /**
     * Test admin can delete user (soft delete).
     */
    public function test_admin_can_delete_user(): void
    {
        $token = $this->getAdminToken();

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/users/' . $user->id);

        $response->assertStatus(200);

        // Check soft delete
        $this->assertSoftDeleted('users', [
            'email' => 'test@example.com',
        ]);
    }

    /**
     * Test user cannot be created with duplicate email during update.
     */
    public function test_update_user_email_uniqueness(): void
    {
        $token = $this->getAdminToken();

        $user1 = User::create([
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'User 2',
            'email' => 'user2@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->putJson('/api/users/' . $user1->id, [
                'email' => 'user2@example.com',
            ]);

        $response->assertStatus(422);
    }
}
