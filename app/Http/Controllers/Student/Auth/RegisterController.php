<?php

namespace App\Http\Controllers\Student\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Auth\StudentRegisterRequest;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.student.register');
    }

    public function register(StudentRegisterRequest $request)
    {
        // Implement registration logic
    }
}
