<?php

namespace Database\Seeders;

use App\Enums\GradeEnum;
use App\Enums\SemesterStatusEnum;
use App\Models\User;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Result;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 students with realistic data
        $students = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@university.edu',
                'student_id' => 'STU20001',
                'enrollment_year' => 2020,
                'program' => 'Computer Science',
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob.smith@university.edu',
                'student_id' => 'STU20002',
                'enrollment_year' => 2020,
                'program' => 'Computer Science',
            ],
            [
                'name' => 'Carol Williams',
                'email' => 'carol.williams@university.edu',
                'student_id' => 'STU21001',
                'enrollment_year' => 2021,
                'program' => 'Information Technology',
            ],
            [
                'name' => 'David Brown',
                'email' => 'david.brown@university.edu',
                'student_id' => 'STU21002',
                'enrollment_year' => 2021,
                'program' => 'Electronics',
            ],
            [
                'name' => 'Emma Davis',
                'email' => 'emma.davis@university.edu',
                'student_id' => 'STU22001',
                'enrollment_year' => 2022,
                'program' => 'Computer Science',
            ],
        ];

        $createdStudents = [];
        foreach ($students as $studentData) {
            $createdStudents[] = User::create([
                'name' => $studentData['name'],
                'email' => $studentData['email'],
                'password' => Hash::make('password'), // Default password for demo
                'student_id' => $studentData['student_id'],
                'enrollment_year' => $studentData['enrollment_year'],
                'program' => $studentData['program'],
                'email_verified_at' => now(),
            ]);
        }

        // Create 4 semesters
        $semesters = [
            [
                'name' => 'Semester 1',
                'academic_year' => '2023-2024',
                'start_date' => '2023-01-01',
                'end_date' => '2023-06-30',
                'status' => SemesterStatusEnum::COMPLETED,
            ],
            [
                'name' => 'Semester 2',
                'academic_year' => '2023-2024',
                'start_date' => '2023-07-01',
                'end_date' => '2023-12-31',
                'status' => SemesterStatusEnum::COMPLETED,
            ],
            [
                'name' => 'Semester 3',
                'academic_year' => '2024-2025',
                'start_date' => '2024-01-01',
                'end_date' => '2024-06-30',
                'status' => SemesterStatusEnum::COMPLETED,
            ],
            [
                'name' => 'Semester 4',
                'academic_year' => '2024-2025',
                'start_date' => '2024-07-01',
                'end_date' => '2024-12-31',
                'status' => SemesterStatusEnum::CURRENT,
            ],
        ];

        $createdSemesters = [];
        foreach ($semesters as $semesterData) {
            $createdSemesters[] = Semester::create($semesterData);
        }

        // Create subjects for each semester (8-10 subjects per semester)
        $semester1Subjects = [
            ['code' => 'CS101', 'name' => 'Introduction to Programming', 'credits' => 4, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'MA101', 'name' => 'Engineering Mathematics I', 'credits' => 4, 'max_marks' => 100, 'department' => 'Mathematics'],
            ['code' => 'PH101', 'name' => 'Physics I', 'credits' => 3, 'max_marks' => 100, 'department' => 'Physics'],
            ['code' => 'CS102', 'name' => 'Digital Logic Design', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'EN101', 'name' => 'Technical Communication', 'credits' => 2, 'max_marks' => 100, 'department' => 'English'],
            ['code' => 'CS103', 'name' => 'Computer Organization', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'MA102', 'name' => 'Discrete Mathematics', 'credits' => 3, 'max_marks' => 100, 'department' => 'Mathematics'],
            ['code' => 'CS104', 'name' => 'Programming Lab', 'credits' => 2, 'max_marks' => 100, 'department' => 'Computer Science'],
        ];

        $semester2Subjects = [
            ['code' => 'CS201', 'name' => 'Data Structures and Algorithms', 'credits' => 4, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS202', 'name' => 'Object-Oriented Programming', 'credits' => 4, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'MA201', 'name' => 'Engineering Mathematics II', 'credits' => 4, 'max_marks' => 100, 'department' => 'Mathematics'],
            ['code' => 'CS203', 'name' => 'Computer Networks', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS204', 'name' => 'Database Management Systems', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'EC201', 'name' => 'Digital Electronics', 'credits' => 3, 'max_marks' => 100, 'department' => 'Electronics'],
            ['code' => 'CS205', 'name' => 'Data Structures Lab', 'credits' => 2, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'MA202', 'name' => 'Probability and Statistics', 'credits' => 3, 'max_marks' => 100, 'department' => 'Mathematics'],
            ['code' => 'CS206', 'name' => 'Web Technologies', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
        ];

        $semester3Subjects = [
            ['code' => 'CS301', 'name' => 'Operating Systems', 'credits' => 4, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS302', 'name' => 'Software Engineering', 'credits' => 4, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS303', 'name' => 'Theory of Computation', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS304', 'name' => 'Compiler Design', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS305', 'name' => 'Computer Graphics', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS306', 'name' => 'Artificial Intelligence', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS307', 'name' => 'Operating Systems Lab', 'credits' => 2, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS308', 'name' => 'Software Engineering Lab', 'credits' => 2, 'max_marks' => 100, 'department' => 'Computer Science'],
        ];

        $semester4Subjects = [
            ['code' => 'CS401', 'name' => 'Machine Learning', 'credits' => 4, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS402', 'name' => 'Cloud Computing', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS403', 'name' => 'Cybersecurity', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS404', 'name' => 'Mobile Application Development', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS405', 'name' => 'Big Data Analytics', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS406', 'name' => 'Internet of Things', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS407', 'name' => 'Blockchain Technology', 'credits' => 3, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS408', 'name' => 'Machine Learning Lab', 'credits' => 2, 'max_marks' => 100, 'department' => 'Computer Science'],
            ['code' => 'CS409', 'name' => 'Project Work', 'credits' => 4, 'max_marks' => 100, 'department' => 'Computer Science'],
        ];

        $allSemesterSubjects = [
            $semester1Subjects,
            $semester2Subjects,
            $semester3Subjects,
            $semester4Subjects,
        ];

        $createdSubjects = [];
        foreach ($allSemesterSubjects as $semesterIndex => $subjects) {
            $createdSubjects[$semesterIndex] = [];
            foreach ($subjects as $subjectData) {
                $createdSubjects[$semesterIndex][] = Subject::create($subjectData);
            }
        }

        // Create results for all students across all semesters
        // Mix of passing and failing grades
        foreach ($createdStudents as $student) {
            foreach ($createdSemesters as $semesterIndex => $semester) {
                $subjects = $createdSubjects[$semesterIndex];
                
                foreach ($subjects as $subjectIndex => $subject) {
                    // Generate varied performance - some students do better than others
                    // Alice (excellent student) - mostly A+ and A
                    // Bob (good student) - mostly A and B
                    // Carol (average student) - mostly B and C
                    // David (struggling student) - mostly C, D, and some failures
                    // Emma (improving student) - starts with C/D, improves to B/A
                    
                    $marks = $this->generateMarksForStudent($student, $semesterIndex, $subjectIndex);
                    $grade = $this->calculateGrade($marks);
                    
                    Result::create([
                        'student_id' => $student->id,
                        'semester_id' => $semester->id,
                        'subject_id' => $subject->id,
                        'marks_obtained' => $marks,
                        'grade' => $grade,
                        'is_passed' => $grade !== GradeEnum::F,
                    ]);
                }
            }
        }

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Created ' . count($createdStudents) . ' students');
        $this->command->info('Created ' . count($createdSemesters) . ' semesters');
        $this->command->info('Created subjects and results for all students');
        $this->command->info('');
        $this->command->info('Demo login credentials:');
        $this->command->info('Email: alice.johnson@university.edu | Password: password');
        $this->command->info('Email: bob.smith@university.edu | Password: password');
        $this->command->info('Email: carol.williams@university.edu | Password: password');
        $this->command->info('Email: david.brown@university.edu | Password: password');
        $this->command->info('Email: emma.davis@university.edu | Password: password');
    }

    /**
     * Generate marks for a student based on their performance profile.
     *
     * @param User $student
     * @param int $semesterIndex
     * @param int $subjectIndex
     * @return float
     */
    private function generateMarksForStudent(User $student, int $semesterIndex, int $subjectIndex): float
    {
        $studentName = $student->name;
        
        // Alice Johnson - Excellent student (85-98 range)
        if (str_contains($studentName, 'Alice')) {
            return fake()->randomFloat(2, 85, 98);
        }
        
        // Bob Smith - Good student (70-90 range, occasional dip)
        if (str_contains($studentName, 'Bob')) {
            // Occasional lower score (10% chance)
            if (fake()->boolean(10)) {
                return fake()->randomFloat(2, 60, 70);
            }
            return fake()->randomFloat(2, 75, 90);
        }
        
        // Carol Williams - Average student (55-75 range)
        if (str_contains($studentName, 'Carol')) {
            return fake()->randomFloat(2, 55, 75);
        }
        
        // David Brown - Struggling student (35-65 range, some failures)
        if (str_contains($studentName, 'David')) {
            // 20% chance of failure
            if (fake()->boolean(20)) {
                return fake()->randomFloat(2, 20, 39);
            }
            return fake()->randomFloat(2, 45, 65);
        }
        
        // Emma Davis - Improving student (starts low, improves over semesters)
        if (str_contains($studentName, 'Emma')) {
            $baseScore = 50 + ($semesterIndex * 8); // Improves by ~8 points per semester
            $variance = 10;
            return fake()->randomFloat(2, $baseScore - $variance, $baseScore + $variance);
        }
        
        // Default fallback
        return fake()->randomFloat(2, 40, 85);
    }

    /**
     * Calculate grade based on marks.
     *
     * @param float $marks
     * @return GradeEnum
     */
    private function calculateGrade(float $marks): GradeEnum
    {
        return match(true) {
            $marks >= 90 => GradeEnum::A_PLUS,
            $marks >= 80 => GradeEnum::A,
            $marks >= 70 => GradeEnum::B,
            $marks >= 60 => GradeEnum::C,
            $marks >= 50 => GradeEnum::D,
            $marks >= 40 => GradeEnum::E,
            default => GradeEnum::F,
        };
    }
}
