<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\AdminDashboardController;

class DashboardController extends Controller
{
    public function __invoke()
    {
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin-dashboard.index');
        }

        return view('dashboard');
    }
}
