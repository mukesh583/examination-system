@echo off
echo ========================================
echo Add New Student User
echo ========================================
echo.
set /p name="Enter student name: "
set /p email="Enter email: "
set /p password="Enter password: "
echo.
echo Creating user...
C:\xampp\php\php.exe artisan tinker --execute="$user = App\Models\User::create(['name' => '%name%', 'email' => '%email%', 'password' => bcrypt('%password%')]); echo 'User created successfully! ID: ' . $user->id;"
echo.
pause
