<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class WebController extends Controller
{
    /**
     * Show admin dashboard.
     */
    public function adminDashboard()
    {
        return view('dashboard.admin');
    }

    /**
     * Show user dashboard.
     */
    public function userDashboard()
    {
        return view('dashboard.user');
    }

    /**
     * Show flight information page.
     */
    public function flights()
    {
        return view('flights.index');
    }

    /**
     * Show user management page.
     */
    public function users()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show login page.
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Show register page.
     */
    public function register()
    {
        return view('auth.register');
    }
}
