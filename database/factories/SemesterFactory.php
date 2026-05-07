<?php

namespace Database\Factories;

use App\Enums\SemesterStatusEnum;
use App\Models\Semester;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Semester>
 */
class SemesterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Semester::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $semesterNumber = fake()->numberBetween(1, 8);
        $year = fake()->numberBetween(2020, 2024);
        $startMonth = ($semesterNumber % 2 === 1) ? 1 : 7; // Odd semesters start in January, even in July
        
        $startDate = now()->setYear($year)->setMonth($startMonth)->setDay(1);
        $endDate = $startDate->copy()->addMonths(6)->subDay();

        return [
            'name' => "Semester {$semesterNumber}",
            'academic_year' => $year . '-' . ($year + 1),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement(SemesterStatusEnum::cases()),
        ];
    }

    /**
     * Indicate that the semester is currently active.
     *
     * @return static
     */
    public function current(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SemesterStatusEnum::CURRENT,
        ]);
    }

    /**
     * Indicate that the semester is completed.
     *
     * @return static
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SemesterStatusEnum::COMPLETED,
        ]);
    }

    /**
     * Indicate that the semester is upcoming.
     *
     * @return static
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SemesterStatusEnum::UPCOMING,
        ]);
    }
}
