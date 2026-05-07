<?php

namespace App\Services;

use App\DTOs\PerformanceMetricsDTO;
use App\Models\Semester;
use App\Repositories\Contracts\ResultRepositoryInterface;
use App\Repositories\Contracts\SemesterRepositoryInterface;
use Illuminate\Support\Collection;

class ProgressTrackerService
{
    public function __construct(
        private GpaCalculatorService $gpaCalculator,
        private ResultRepositoryInterface $resultRepository,
        private SemesterRepositoryInterface $semesterRepository
    ) {
    }

    /**
     * Get comprehensive performance metrics for a student.
     *
     * @param int $studentId
     * @return PerformanceMetricsDTO
     */
    public function getProgressMetrics(int $studentId): PerformanceMetricsDTO
    {
        $semesters = $this->semesterRepository->findByStudent($studentId);
        $allResults = $this->resultRepository->findByStudent($studentId);

        return new PerformanceMetricsDTO(
            cgpa: $this->gpaCalculator->calculateCGPA($studentId),
            totalCredits: $this->calculateTotalCredits($allResults),
            completedSemesters: $semesters->count(),
            passPercentage: $this->calculatePassPercentage($allResults),
            highestSemester: $this->findHighestPerformingSemester($semesters, $studentId),
            lowestSemester: $this->findLowestPerformingSemester($semesters, $studentId),
            failedSubjects: $this->getFailedSubjects($allResults)
        );
    }

    /**
     * Get semester-wise SGPA trends for charting.
     *
     * @param int $studentId
     * @return array
     */
    public function getSemesterTrends(int $studentId): array
    {
        $semesters = $this->semesterRepository->findByStudent($studentId);
        $trends = [];

        foreach ($semesters as $semester) {
            $results = $this->resultRepository->findBySemesterAndStudent(
                $semester->id,
                $studentId
            );

            if ($results->isNotEmpty()) {
                $trends[$semester->name] = $this->gpaCalculator->calculateSGPA($results);
            }
        }

        return $trends;
    }

    /**
     * Calculate total credits earned (only passing subjects).
     *
     * @param Collection $results
     * @return int
     */
    private function calculateTotalCredits(Collection $results): int
    {
        return $results
            ->filter(fn($result) => $result->is_passed)
            ->sum(fn($result) => $result->subject->credits);
    }

    /**
     * Calculate pass percentage.
     *
     * @param Collection $results
     * @return float
     */
    private function calculatePassPercentage(Collection $results): float
    {
        if ($results->isEmpty()) {
            return 0.0;
        }

        $passedCount = $results->filter(fn($result) => $result->is_passed)->count();
        return round(($passedCount / $results->count()) * 100, 2);
    }

    /**
     * Find the highest performing semester.
     *
     * @param Collection $semesters
     * @param int $studentId
     * @return Semester|null
     */
    private function findHighestPerformingSemester(Collection $semesters, int $studentId): ?Semester
    {
        $highestSemester = null;
        $highestSgpa = 0.0;

        foreach ($semesters as $semester) {
            $results = $this->resultRepository->findBySemesterAndStudent(
                $semester->id,
                $studentId
            );

            if ($results->isNotEmpty()) {
                $sgpa = $this->gpaCalculator->calculateSGPA($results);
                if ($sgpa > $highestSgpa) {
                    $highestSgpa = $sgpa;
                    $highestSemester = $semester;
                }
            }
        }

        return $highestSemester;
    }

    /**
     * Find the lowest performing semester.
     *
     * @param Collection $semesters
     * @param int $studentId
     * @return Semester|null
     */
    private function findLowestPerformingSemester(Collection $semesters, int $studentId): ?Semester
    {
        $lowestSemester = null;
        $lowestSgpa = PHP_FLOAT_MAX;

        foreach ($semesters as $semester) {
            $results = $this->resultRepository->findBySemesterAndStudent(
                $semester->id,
                $studentId
            );

            if ($results->isNotEmpty()) {
                $sgpa = $this->gpaCalculator->calculateSGPA($results);
                if ($sgpa < $lowestSgpa) {
                    $lowestSgpa = $sgpa;
                    $lowestSemester = $semester;
                }
            }
        }

        return $lowestSemester;
    }

    /**
     * Get list of failed subjects.
     *
     * @param Collection $results
     * @return Collection
     */
    private function getFailedSubjects(Collection $results): Collection
    {
        return $results
            ->filter(fn($result) => !$result->is_passed)
            ->map(fn($result) => [
                'subject' => $result->subject,
                'semester' => $result->semester,
                'marks' => $result->marks_obtained,
                'grade' => $result->grade
            ]);
    }
}
