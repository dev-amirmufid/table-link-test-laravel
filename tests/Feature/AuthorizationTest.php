<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can access admin dashboard.
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin, 'web')
            ->getJson('/api/dashboard/charts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'charts' => [
                    'line',
                    'bar',
                    'pie',
                ],
                'stats',
            ]);
    }

    /**
     * Test regular user cannot access admin dashboard.
     */
    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($user, 'web')
            ->getJson('/api/dashboard/charts');

        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
            ]);
    }

    /**
     * Test admin can access user management.
     */
    public function test_admin_can_access_user_management(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin, 'web')
            ->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'users',
            ]);
    }

    /**
     * Test regular user cannot access user management.
     */
    public function test_regular_user_cannot_access_user_management(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($user, 'web')
            ->getJson('/api/users');

        $response->assertStatus(403);
    }

    /**
     * Test admin can access flight management.
     */
    public function test_admin_can_access_flight_management(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin, 'web')
            ->getJson('/api/flights');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'flights',
            ]);
    }

    /**
     * Test regular user cannot access flight management.
     */
    public function test_regular_user_cannot_access_flight_management(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($user, 'web')
            ->getJson('/api/flights');

        $response->assertStatus(403);
    }
}
