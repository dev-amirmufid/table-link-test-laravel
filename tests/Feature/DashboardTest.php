<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
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
     * Test dashboard returns correct data format.
     */
    public function test_dashboard_returns_correct_data(): void
    {
        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/dashboard');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'usersChart',
                'rolesChart',
                'activityChart',
            ]);
    }

    /**
     * Test chart data format.
     */
    public function test_chart_data_format(): void
    {
        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/dashboard/users-chart');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'labels',
                'datasets',
            ]);
    }

    /**
     * Test roles chart data format.
     */
    public function test_roles_chart_data_format(): void
    {
        $token = $this->getAdminToken();

        // Create users with different roles
        User::create([
            'name' => 'Admin 1',
            'email' => 'admin1@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/dashboard/roles-chart');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'labels',
                'datasets',
            ]);
    }

    /**
     * Test activity chart data format.
     */
    public function test_activity_chart_data_format(): void
    {
        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/dashboard/activity-chart');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'labels',
                'datasets',
            ]);
    }
}
