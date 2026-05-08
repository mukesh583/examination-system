<?php

/**
 * Quick script to add a test student account
 * Run: php add-test-student.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Create test student
$student = User::updateOrCreate(
    ['email' => 'test@student.com'],
    [
        'name' => 'Test Student',
        'email' => 'test@student.com',
        'password' => Hash::make('password'),
        'student_id' => 'TEST001',
        'enrollment_year' => date('Y'),
        'program' => 'Computer Science',
        'role' => 'student',
        'email_verified_at' => now(),
    ]
);

echo "✅ Test student created successfully!\n";
echo "Email: test@student.com\n";
echo "Password: password\n";
