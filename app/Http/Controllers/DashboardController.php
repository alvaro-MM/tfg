<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\AdminDashboardController;

class DashboardController extends Controller
{
    public function __invoke()
    {

        $user = auth()->user()->fresh();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin-dashboard.index');
        }

        if ($user->hasRole('staff')) {
            return redirect()->route('staff.dashboard');
        }

//        if (auth()->user()->hasRole('staff')) {
//            return redirect()->route('staff.dashboard');
//        }

        return view('dashboard');
    }
}

