<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'student_id' => ['required', 'string', 'max:255', 'unique:users'],
            'enrollment_year' => ['required', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'program' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'student_id' => $request->student_id,
            'enrollment_year' => $request->enrollment_year,
            'program' => $request->program,
            'password' => Hash::make($request->password),
        ]);

        // Log the user in
        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Registration successful! Welcome to the Examination Management System.');
    }
}
