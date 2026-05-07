# Admin User Setup Guide

## Overview
This document explains the admin user functionality in the Examination Management System.

## Admin User Details

### Default Admin Credentials
- **Email:** `admin@examination-system.com`
- **Password:** `admin123`
- **Student ID:** `ADMIN001`
- **Role:** `admin`

⚠️ **IMPORTANT:** Change the admin password after first login for security!

## Database Changes

### Migration
A new migration has been added to include a `role` column in the `users` table:
- **Column:** `role` (string)
- **Default Value:** `student`
- **Possible Values:** `admin`, `student`
- **Indexed:** Yes (for performance)

### User Model Updates
The `User` model now includes:
- `role` field in `$fillable` array
- `isAdmin()` method - Returns `true` if user is an admin
- `isStudent()` method - Returns `true` if user is a student

## Admin Middleware

### EnsureUserIsAdmin Middleware
A new middleware has been created to protect admin-only routes:
- **Class:** `App\Http\Middleware\EnsureUserIsAdmin`
- **Alias:** `admin`
- **Functionality:** 
  - Checks if user is authenticated
  - Verifies user has admin role
  - Returns 403 error if not authorized

### Usage in Routes
To protect admin routes, use the `admin` middleware:

```php
// Single route
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth', 'admin']);

// Route group
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index']);
    Route::get('/admin/students', [AdminController::class, 'students']);
    Route::post('/admin/students', [AdminController::class, 'createStudent']);
});
```

## Seeding Admin User

### Running the Seeder
To create the admin user, run:

```bash
php artisan db:seed --class=AdminSeeder
```

Or to run all seeders (including admin):

```bash
php artisan db:seed
```

### AdminSeeder Details
The `AdminSeeder` creates a default admin user with:
- Full administrative privileges
- Pre-verified email
- Default credentials (should be changed)

## Security Recommendations

1. **Change Default Password**
   - Login with default credentials
   - Navigate to profile/settings
   - Update password immediately

2. **Protect Admin Routes**
   - Always use `admin` middleware on admin routes
   - Never expose admin functionality to regular users

3. **Environment-Specific Credentials**
   - Use different admin credentials for production
   - Store sensitive credentials in `.env` file
   - Never commit admin credentials to version control

4. **Regular Audits**
   - Review admin access logs regularly
   - Monitor admin actions
   - Implement admin activity logging

## Admin Capabilities

Admins can:
- View all student records
- Manage student accounts
- Create/edit/delete results
- Manage semesters and subjects
- Access system-wide reports
- Configure system settings
- Manage grade scales

## Testing Admin Functionality

### Login as Admin
1. Navigate to login page
2. Enter admin email: `admin@examination-system.com`
3. Enter admin password: `admin123`
4. Access admin dashboard

### Verify Admin Access
```php
// In controller or view
if (auth()->user()->isAdmin()) {
    // Admin-only code
}

// Or using middleware
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'admin']);
```

## Troubleshooting

### Admin Cannot Login
- Verify admin user exists in database
- Check `role` column is set to `admin`
- Ensure migration has been run
- Verify seeder has been executed

### 403 Forbidden Error
- Check user is logged in
- Verify user has `admin` role
- Ensure `admin` middleware is registered in `Kernel.php`

### Creating Additional Admins
To create more admin users, either:
1. Update existing user's role in database:
   ```sql
   UPDATE users SET role = 'admin' WHERE email = 'user@example.com';
   ```
2. Create new user with admin role programmatically:
   ```php
   User::create([
       'name' => 'New Admin',
       'email' => 'newadmin@example.com',
       'password' => Hash::make('password'),
       'student_id' => 'ADMIN002',
       'enrollment_year' => date('Y'),
       'program' => 'Administration',
       'role' => 'admin',
   ]);
   ```

## Files Modified/Created

### New Files
- `database/migrations/2026_05_07_153051_add_role_to_users_table.php`
- `database/seeders/AdminSeeder.php`
- `app/Http/Middleware/EnsureUserIsAdmin.php`
- `ADMIN_SETUP.md` (this file)

### Modified Files
- `app/Models/User.php` - Added `role` to fillable, added helper methods
- `app/Http/Kernel.php` - Registered `admin` middleware
- `database/seeders/DatabaseSeeder.php` - Added AdminSeeder call

## Next Steps

1. Run the migration: `php artisan migrate`
2. Seed the admin user: `php artisan db:seed --class=AdminSeeder`
3. Login with admin credentials
4. Change the default password
5. Protect admin routes with `admin` middleware
6. Build admin dashboard and management interfaces

## Support

For issues or questions about admin functionality, refer to:
- Laravel Authentication Documentation
- Laravel Middleware Documentation
- Project README.md
