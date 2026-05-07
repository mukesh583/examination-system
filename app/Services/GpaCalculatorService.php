<?php

namespace App\Services;

use App\Repositories\Contracts\ResultRepositoryInterface;
use Illuminate\Support\Collection;

class GpaCalculatorService
{
    public function __construct(
        private GradeCalculatorService $gradeCalculator,
        private ResultRepositoryInterface $resultRepository
    ) {
    }

    /**
     * Calculate SGPA (Semester Grade Point Average) for a set of results.
     *
     * @param Collection $results
     * @return float
     * @throws \Exception
     */
    public function calculateSGPA(Collection $results): float
    {
        if ($results->isEmpty()) {
            throw new \InvalidArgumentException(
                'Cannot calculate SGPA with no results.'
            );
        }

        $totalWeightedPoints = 0;
        $totalCredits = 0;

        foreach ($results as $result) {
            if (!$result->subject) {
                throw new \RuntimeException(
                    "Subject not found for result ID {$result->id}"
                );
            }

            $gradePoint = $this->gradeCalculator->getGradePoint($result->grade);
            $credits = $result->subject->credits;

            if ($credits <= 0) {
                throw new \InvalidArgumentException(
                    "Invalid credits value for subject {$result->subject->code}"
                );
            }

            $totalWeightedPoints += $gradePoint * $credits;
            $totalCredits += $credits;
        }

        if ($totalCredits === 0) {
            throw new \RuntimeException(
                'Total credits cannot be zero for GPA calculation.'
            );
        }

        return round($totalWeightedPoints / $totalCredits, 2);
    }

    /**
     * Calculate CGPA (Cumulative Grade Point Average) for a student.
     *
     * @param int $studentId
     * @return float
     */
    public function calculateCGPA(int $studentId): float
    {
        $allResults = $this->resultRepository->findByStudent($studentId);

        if ($allResults->isEmpty()) {
            return 0.0;
        }

        return $this->calculateSGPA($allResults);
    }
}
