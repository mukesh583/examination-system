<?php

namespace Database\Factories;

use App\Enums\GradeEnum;
use App\Models\Result;
use App\Models\User;
use App\Models\Semester;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Result::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $marks = fake()->randomFloat(2, 0, 100);
        $grade = $this->calculateGrade($marks);

        return [
            'student_id' => User::factory(),
            'semester_id' => Semester::factory(),
            'subject_id' => Subject::factory(),
            'marks_obtained' => $marks,
            'grade' => $grade,
            'is_passed' => $grade !== GradeEnum::F,
        ];
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

    /**
     * Indicate that the result is a passing grade.
     *
     * @return static
     */
    public function passed(): static
    {
        return $this->state(function (array $attributes) {
            $marks = fake()->randomFloat(2, 40, 100);
            $grade = $this->calculateGrade($marks);
            
            return [
                'marks_obtained' => $marks,
                'grade' => $grade,
                'is_passed' => true,
            ];
        });
    }

    /**
     * Indicate that the result is a failing grade.
     *
     * @return static
     */
    public function failed(): static
    {
        return $this->state(function (array $attributes) {
            $marks = fake()->randomFloat(2, 0, 39.99);
            
            return [
                'marks_obtained' => $marks,
                'grade' => GradeEnum::F,
                'is_passed' => false,
            ];
        });
    }

    /**
     * Create a result with specific marks.
     *
     * @param float $marks
     * @return static
     */
    public function withMarks(float $marks): static
    {
        $grade = $this->calculateGrade($marks);
        
        return $this->state(fn (array $attributes) => [
            'marks_obtained' => $marks,
            'grade' => $grade,
            'is_passed' => $grade !== GradeEnum::F,
        ]);
    }

    /**
     * Create a result with a specific grade.
     *
     * @param GradeEnum $grade
     * @return static
     */
    public function withGrade(GradeEnum $grade): static
    {
        // Generate marks within the range for the specified grade
        $marks = match($grade) {
            GradeEnum::A_PLUS => fake()->randomFloat(2, 90, 100),
            GradeEnum::A => fake()->randomFloat(2, 80, 89.99),
            GradeEnum::B => fake()->randomFloat(2, 70, 79.99),
            GradeEnum::C => fake()->randomFloat(2, 60, 69.99),
            GradeEnum::D => fake()->randomFloat(2, 50, 59.99),
            GradeEnum::E => fake()->randomFloat(2, 40, 49.99),
            GradeEnum::F => fake()->randomFloat(2, 0, 39.99),
        };
        
        return $this->state(fn (array $attributes) => [
            'marks_obtained' => $marks,
            'grade' => $grade,
            'is_passed' => $grade !== GradeEnum::F,
        ]);
    }
}
