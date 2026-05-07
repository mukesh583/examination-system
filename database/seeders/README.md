# Database Seeders

This directory contains database seeders for the Examination Management System.

## Available Seeders

### 1. GradeScaleSeeder
Seeds the grade scale table with the standard grading system:
- **A+**: 90-100 (Grade Point: 10.0)
- **A**: 80-89.99 (Grade Point: 9.0)
- **B**: 70-79.99 (Grade Point: 8.0)
- **C**: 60-69.99 (Grade Point: 7.0)
- **D**: 50-59.99 (Grade Point: 6.0)
- **E**: 40-49.99 (Grade Point: 5.0)
- **F**: 0-39.99 (Grade Point: 0.0) - Failing grade

### 2. DemoDataSeeder
Seeds the database with realistic demo data for testing and development:

#### Students Created (5 students)
1. **Alice Johnson** (STU20001) - Excellent student
   - Email: alice.johnson@university.edu
   - Program: Computer Science
   - Performance: 85-98% range

2. **Bob Smith** (STU20002) - Good student
   - Email: bob.smith@university.edu
   - Program: Computer Science
   - Performance: 70-90% range with occasional dips

3. **Carol Williams** (STU21001) - Average student
   - Email: carol.williams@university.edu
   - Program: Information Technology
   - Performance: 55-75% range

4. **David Brown** (STU21002) - Struggling student
   - Email: david.brown@university.edu
   - Program: Electronics
   - Performance: 35-65% range with some failures

5. **Emma Davis** (STU22001) - Improving student
   - Email: emma.davis@university.edu
   - Program: Computer Science
   - Performance: Starts at 50%, improves by ~8% each semester

**Default Password for all demo users**: `password`

#### Semesters Created (4 semesters)
1. **Semester 1** (2023-2024) - Completed
2. **Semester 2** (2023-2024) - Completed
3. **Semester 3** (2024-2025) - Completed
4. **Semester 4** (2024-2025) - Current

#### Subjects Per Semester
- **Semester 1**: 8 subjects (Introduction to Programming, Mathematics, Physics, etc.)
- **Semester 2**: 9 subjects (Data Structures, OOP, Networks, Databases, etc.)
- **Semester 3**: 8 subjects (Operating Systems, Software Engineering, AI, etc.)
- **Semester 4**: 9 subjects (Machine Learning, Cloud Computing, Cybersecurity, etc.)

#### Results
- Complete results for all 5 students across all 4 semesters
- Mix of passing and failing grades based on student performance profiles
- Realistic grade distribution

## Usage

### Seed All Data
To seed the database with both grade scales and demo data:

```bash
php artisan db:seed
```

### Seed Specific Seeder
To run a specific seeder:

```bash
# Seed only grade scales
php artisan db:seed --class=GradeScaleSeeder

# Seed only demo data
php artisan db:seed --class=DemoDataSeeder
```

### Fresh Migration with Seeding
To reset the database and seed fresh data:

```bash
php artisan migrate:fresh --seed
```

## Model Factories

The following factories are available for testing:

### UserFactory
Creates student users with realistic data.

```php
// Create a single student
User::factory()->create();

// Create multiple students
User::factory()->count(10)->create();

// Create with specific attributes
User::factory()->create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'program' => 'Computer Science',
]);
```

### SemesterFactory
Creates academic semesters.

```php
// Create a semester
Semester::factory()->create();

// Create a current semester
Semester::factory()->current()->create();

// Create a completed semester
Semester::factory()->completed()->create();

// Create an upcoming semester
Semester::factory()->upcoming()->create();
```

### SubjectFactory
Creates academic subjects from a predefined list.

```php
// Create a subject
Subject::factory()->create();

// Create with specific credits
Subject::factory()->withCredits(4)->create();

// Create with specific max marks
Subject::factory()->withMaxMarks(100)->create();
```

### ResultFactory
Creates examination results with automatic grade calculation.

```php
// Create a result (random marks and grade)
Result::factory()->create();

// Create a passing result
Result::factory()->passed()->create();

// Create a failing result
Result::factory()->failed()->create();

// Create with specific marks
Result::factory()->withMarks(85.5)->create();

// Create with specific grade
Result::factory()->withGrade(GradeEnum::A_PLUS)->create();

// Create complete result set for a student
$student = User::factory()->create();
$semester = Semester::factory()->create();
$subjects = Subject::factory()->count(8)->create();

foreach ($subjects as $subject) {
    Result::factory()->create([
        'student_id' => $student->id,
        'semester_id' => $semester->id,
        'subject_id' => $subject->id,
    ]);
}
```

## Testing with Demo Data

After seeding, you can log in with any of the demo accounts:

```
Email: alice.johnson@university.edu
Password: password

Email: bob.smith@university.edu
Password: password

Email: carol.williams@university.edu
Password: password

Email: david.brown@university.edu
Password: password

Email: emma.davis@university.edu
Password: password
```

Each student has:
- Results across 4 semesters
- 8-9 subjects per semester
- Varied performance patterns for realistic testing
- Complete CGPA and SGPA calculations

## Notes

- The demo data is designed to showcase different student performance profiles
- Grade calculations are automatic based on marks obtained
- All timestamps are set to the current time during seeding
- The seeder is idempotent - running it multiple times will create duplicate data
- Use `migrate:fresh --seed` to reset and reseed the database
