<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test user can see login page.
     */
    public function test_user_can_see_login_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Login to TableLink')
                ->assertSee('Email')
                ->assertSee('Password');
        });
    }

    /**
     * Test user can login with valid credentials.
     */
    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'password123')
                ->press('Login')
                ->assertPathIs('/dashboard');
        });
    }

    /**
     * Test admin is redirected to admin dashboard.
     */
    public function test_admin_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->browse(function (Browser $browser) use ($admin) {
            $browser->visit('/login')
                ->type('email', $admin->email)
                ->type('password', 'password123')
                ->press('Login')
                ->assertPathIs('/admin/dashboard');
        });
    }

    /**
     * Test user cannot login with invalid credentials.
     */
    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'wrongpassword')
                ->press('Login')
                ->assertPathIs('/login')
                ->assertSee('Invalid credentials');
        });
    }

    /**
     * Test user can logout.
     */
    public function test_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/dashboard')
                ->press('Logout')
                ->assertPathIs('/login');
        });
    }

    /**
     * Test login page shows demo credentials.
     */
    public function test_login_page_shows_demo_credentials(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Demo Credentials')
                ->assertSee('admin@tablelink.com')
                ->assertSee('ahmad@example.com');
        });
    }
}
