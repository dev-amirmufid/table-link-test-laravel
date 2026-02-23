<?php

namespace Tests\Browser;

use App\Models\Flight;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdminDashboardTest extends DuskTestCase
{
    use DatabaseMigrations;

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

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/dashboard')
                ->assertPathIs('/admin/dashboard')
                ->assertSee('Admin Dashboard');
        });
    }

    /**
     * Test admin dashboard shows stats cards.
     */
    public function test_admin_dashboard_shows_stats_cards(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create some test data
        User::factory()->count(5)->create();
        Flight::factory()->count(3)->create();

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/dashboard')
                ->assertSee('Total Users')
                ->assertSee('Total Flights')
                ->assertSee('Admins')
                ->assertSee('Regular Users');
        });
    }

    /**
     * Test admin dashboard shows charts.
     */
    public function test_admin_dashboard_shows_charts(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/dashboard')
                ->assertSee('User Registration Trend')
                ->assertSee('Flights by Airline')
                ->assertSee('Flight Class Distribution');
        });
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

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/users')
                ->assertPathIs('/admin/users')
                ->assertSee('User Management');
        });
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

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/flights')
                ->assertPathIs('/admin/flights')
                ->assertSee('Flight Information');
        });
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

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/admin/dashboard')
                ->assertSee('403');
        });
    }

    /**
     * Test admin can navigate to different sections from dashboard.
     */
    public function test_admin_can_navigate_from_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->loginAs($admin)
                ->visit('/admin/dashboard')
                ->clickLink('Users')
                ->assertPathIs('/admin/users')
                ->clickLink('Flights')
                ->assertPathIs('/admin/flights');
        });
    }
}
