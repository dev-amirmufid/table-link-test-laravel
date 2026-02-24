<?php

namespace App\Http\Controllers\Web\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class WebDashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index(): View
    {
        return view('dashboard.admin');
    }
}
