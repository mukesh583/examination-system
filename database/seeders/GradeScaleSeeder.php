<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeScaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gradeScales = [
            [
                'grade' => 'A+',
                'min_marks' => 90.00,
                'max_marks' => 100.00,
                'grade_point' => 10.0,
                'is_passing' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade' => 'A',
                'min_marks' => 80.00,
                'max_marks' => 89.99,
                'grade_point' => 9.0,
                'is_passing' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade' => 'B',
                'min_marks' => 70.00,
                'max_marks' => 79.99,
                'grade_point' => 8.0,
                'is_passing' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade' => 'C',
                'min_marks' => 60.00,
                'max_marks' => 69.99,
                'grade_point' => 7.0,
                'is_passing' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade' => 'D',
                'min_marks' => 50.00,
                'max_marks' => 59.99,
                'grade_point' => 6.0,
                'is_passing' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade' => 'E',
                'min_marks' => 40.00,
                'max_marks' => 49.99,
                'grade_point' => 5.0,
                'is_passing' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'grade' => 'F',
                'min_marks' => 0.00,
                'max_marks' => 39.99,
                'grade_point' => 0.0,
                'is_passing' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('grade_scales')->insert($gradeScales);
    }
}
