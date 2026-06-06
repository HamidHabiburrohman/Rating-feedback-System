<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $units = $user->units; // Assuming many-to-many relationship

        return view('employee.dashboard', compact('units'));
    }
}
