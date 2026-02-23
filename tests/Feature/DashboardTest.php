<?php

namespace Tests\Feature;

use App\Models\Flight;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardTest extends TestCase
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
     * Test dashboard returns correct stats.
     */
    public function test_dashboard_returns_correct_stats(): void
    {
        // Create users
        User::factory()->count(5)->create([
            'role' => 'user',
        ]);
        User::create([
            'name' => 'Admin',
            'email' => 'admin2@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create flights
        Flight::factory()->count(10)->create();

        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/dashboard/charts');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'stats' => [
                    'total_users' => 7, // 5 users + 1 admin from getAdminToken + 1 admin above = 7
                    'total_flights' => 10,
                ],
            ]);
    }

    /**
     * Test dashboard returns chart data structure.
     */
    public function test_dashboard_returns_chart_data_structure(): void
    {
        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/dashboard/charts');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'charts' => [
                    'line' => [
                        'labels',
                        'data',
                    ],
                    'bar' => [
                        'labels',
                        'data',
                    ],
                    'pie' => [
                        'labels',
                        'data',
                    ],
                ],
                'stats' => [
                    'total_users',
                    'total_flights',
                    'total_admins',
                    'total_regular_users',
                ],
            ]);
    }

    /**
     * Test line chart has correct data.
     */
    public function test_line_chart_has_data(): void
    {
        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/dashboard/charts');

        $response->assertStatus(200);

        $lineChart = $response->json('charts.line');
        $this->assertNotEmpty($lineChart['labels']);
        $this->assertNotEmpty($lineChart['data']);
    }

    /**
     * Test bar chart has data.
     */
    public function test_bar_chart_has_data(): void
    {
        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/dashboard/charts');

        $response->assertStatus(200);

        $barChart = $response->json('charts.bar');
        $this->assertNotEmpty($barChart['labels']);
        $this->assertNotEmpty($barChart['data']);
    }

    /**
     * Test pie chart has data.
     */
    public function test_pie_chart_has_data(): void
    {
        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/dashboard/charts');

        $response->assertStatus(200);

        $pieChart = $response->json('charts.pie');
        $this->assertNotEmpty($pieChart['labels']);
        $this->assertNotEmpty($pieChart['data']);
    }

    /**
     * Test admin count is correct.
     */
    public function test_admin_count_is_correct(): void
    {
        // Create additional admins
        User::factory()->count(2)->create([
            'role' => 'admin',
        ]);

        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/dashboard/charts');

        $response->assertStatus(200);

        // 2 admins from factory + 1 from getAdminToken = 3 admins
        $this->assertEquals(3, $response->json('stats.total_admins'));
    }

    /**
     * Test regular user count is correct.
     */
    public function test_regular_user_count_is_correct(): void
    {
        User::factory()->count(5)->create([
            'role' => 'user',
        ]);

        $token = $this->getAdminToken();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/dashboard/charts');

        $response->assertStatus(200);

        // 5 users from factory = 5 (getAdminToken creates admin)
        $this->assertEquals(5, $response->json('stats.total_regular_users'));
    }
}
