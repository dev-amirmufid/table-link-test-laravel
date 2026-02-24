<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user registration via API.
     */
    public function test_user_can_register_via_api(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'user',
                'token',
            ])
            ->assertJson([
                'success' => true,
                'message' => 'User registered successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'role' => 'user',
        ]);
    }

    /**
     * Test user registration validation.
     */
    public function test_user_registration_validates_email_unique(): void
    {
        // Create existing user
        User::create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $response = $this->postJson('/api/auth/register', [
            'name' => 'New User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    /**
     * Test user registration validates password confirmation.
     */
    public function test_user_registration_validates_password_confirmation(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Test User',
            'email' => 'test2@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /**
     * Test user login via API.
     */
    public function test_user_can_login_via_api(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'user',
                'token',
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Login successful',
            ]);

        // Verify last_login is updated
        $user->refresh();
        $this->assertNotNull($user->last_login);
    }

    /**
     * Test user login with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials',
            ]);
    }

    /**
     * Test user can logout via API.
     */
    public function test_user_can_logout_via_api(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($user, 'web')
            ->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logout successful',
            ]);
    }

    /**
     * Test authenticated user can get their info.
     */
    public function test_authenticated_user_can_get_info(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($user, 'web')
            ->getJson('/api/auth/user');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'user' => [
                    'email' => 'test@example.com',
                    'name' => 'Test User',
                ],
            ]);
    }

    /**
     * Test unauthenticated user cannot access protected routes.
     */
    public function test_unauthenticated_user_cannot_access_protected_routes(): void
    {
        $response = $this->getJson('/api/auth/user');

        $response->assertStatus(401);
    }
}
