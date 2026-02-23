<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    /**
     * Show user dashboard
     */
    public function index(): View
    {
        $user = auth()->user();
        return view('dashboard.user', compact('user'));
    }
}
