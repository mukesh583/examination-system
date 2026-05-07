<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subjects = [
            ['name' => 'Data Structures and Algorithms', 'code' => 'CS201', 'department' => 'Computer Science'],
            ['name' => 'Database Management Systems', 'code' => 'CS202', 'department' => 'Computer Science'],
            ['name' => 'Operating Systems', 'code' => 'CS203', 'department' => 'Computer Science'],
            ['name' => 'Computer Networks', 'code' => 'CS204', 'department' => 'Computer Science'],
            ['name' => 'Software Engineering', 'code' => 'CS205', 'department' => 'Computer Science'],
            ['name' => 'Web Technologies', 'code' => 'CS206', 'department' => 'Computer Science'],
            ['name' => 'Artificial Intelligence', 'code' => 'CS301', 'department' => 'Computer Science'],
            ['name' => 'Machine Learning', 'code' => 'CS302', 'department' => 'Computer Science'],
            ['name' => 'Cloud Computing', 'code' => 'CS303', 'department' => 'Computer Science'],
            ['name' => 'Cybersecurity', 'code' => 'CS304', 'department' => 'Computer Science'],
            ['name' => 'Digital Electronics', 'code' => 'EC101', 'department' => 'Electronics'],
            ['name' => 'Microprocessors', 'code' => 'EC102', 'department' => 'Electronics'],
            ['name' => 'Signal Processing', 'code' => 'EC201', 'department' => 'Electronics'],
            ['name' => 'Communication Systems', 'code' => 'EC202', 'department' => 'Electronics'],
            ['name' => 'Engineering Mathematics I', 'code' => 'MA101', 'department' => 'Mathematics'],
            ['name' => 'Engineering Mathematics II', 'code' => 'MA102', 'department' => 'Mathematics'],
            ['name' => 'Discrete Mathematics', 'code' => 'MA201', 'department' => 'Mathematics'],
            ['name' => 'Probability and Statistics', 'code' => 'MA202', 'department' => 'Mathematics'],
            ['name' => 'Physics I', 'code' => 'PH101', 'department' => 'Physics'],
            ['name' => 'Physics II', 'code' => 'PH102', 'department' => 'Physics'],
        ];

        $subject = fake()->randomElement($subjects);

        return [
            'code' => $subject['code'],
            'name' => $subject['name'],
            'credits' => fake()->numberBetween(2, 4),
            'max_marks' => 100,
            'department' => $subject['department'],
        ];
    }

    /**
     * Create a subject with specific credits.
     *
     * @param int $credits
     * @return static
     */
    public function withCredits(int $credits): static
    {
        return $this->state(fn (array $attributes) => [
            'credits' => $credits,
        ]);
    }

    /**
     * Create a subject with specific max marks.
     *
     * @param int $maxMarks
     * @return static
     */
    public function withMaxMarks(int $maxMarks): static
    {
        return $this->state(fn (array $attributes) => [
            'max_marks' => $maxMarks,
        ]);
    }
}
