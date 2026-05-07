<?php

namespace App\DTOs;

use App\Models\Semester;
use Illuminate\Support\Collection;

class PerformanceMetricsDTO
{
    public function __construct(
        public readonly float $cgpa,
        public readonly int $totalCredits,
        public readonly int $completedSemesters,
        public readonly float $passPercentage,
        public readonly ?Semester $highestSemester,
        public readonly ?Semester $lowestSemester,
        public readonly Collection $failedSubjects
    ) {
    }
}
