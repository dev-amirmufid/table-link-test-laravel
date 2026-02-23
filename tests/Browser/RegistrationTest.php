<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegistrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test user can see registration page.
     */
    public function test_user_can_see_registration_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->assertSee('Register for TableLink')
                ->assertSee('Name')
                ->assertSee('Email')
                ->assertSee('Password')
                ->assertSee('Confirm Password');
        });
    }

    /**
     * Test user can register successfully.
     */
    public function test_user_can_register_successfully(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'New User')
                ->type('email', 'newuser@example.com')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->press('Register')
                ->assertPathIs('/dashboard')
                ->assertSee('Welcome');
        });
    }

    /**
     * Test registration validates unique email.
     */
    public function test_registration_validates_unique_email(): void
    {
        // Create user first
        \App\Models\User::create([
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
            'role' => 'user',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'New User')
                ->type('email', 'existing@example.com')
                ->type('password', 'password123')
                ->type('password_confirmation', 'password123')
                ->press('Register')
                ->assertSee('has already been taken');
        });
    }

    /**
     * Test registration validates password confirmation.
     */
    public function test_registration_validates_password_confirmation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'New User')
                ->type('email', 'newuser@example.com')
                ->type('password', 'password123')
                ->type('password_confirmation', 'differentpassword')
                ->press('Register')
                ->assertSee('does not match');
        });
    }

    /**
     * Test registration redirects to login for existing user.
     */
    public function test_registration_page_has_login_link(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->assertSee('Already have an account')
                ->clickLink('Login')
                ->assertPathIs('/login');
        });
    }
}
