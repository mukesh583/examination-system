<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'System Administrator',
            'email' => 'admin@examination-system.com',
            'password' => Hash::make('admin123'),
            'student_id' => 'ADMIN001',
            'enrollment_year' => date('Y'),
            'program' => 'Administration',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('');
        $this->command->info('Admin Login Credentials:');
        $this->command->info('Email: admin@examination-system.com');
        $this->command->info('Password: admin123');
        $this->command->info('');
        $this->command->warn('IMPORTANT: Please change the admin password after first login!');
    }
}
