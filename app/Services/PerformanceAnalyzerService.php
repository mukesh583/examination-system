<?php

namespace App\Services;

use App\Models\Semester;
use App\Repositories\Contracts\ResultRepositoryInterface;
use App\Repositories\Contracts\SemesterRepositoryInterface;
use Illuminate\Support\Collection;

class PerformanceAnalyzerService
{
    public function __construct(
        private ResultRepositoryInterface $resultRepository,
        private SemesterRepositoryInterface $semesterRepository,
        private GpaCalculatorService $gpaCalculator
    ) {
    }

    /**
     * Get marks distribution for a semester (for bar chart).
     *
     * @param Semester $semester
     * @param int $studentId
     * @return array
     */
    public function getMarksDistribution(Semester $semester, int $studentId): array
    {
        $results = $this->resultRepository->findBySemesterAndStudent(
            $semester->id,
            $studentId
        );

        $distribution = [];
        foreach ($results as $result) {
            $distribution[$result->subject->name] = $result->marks_obtained;
        }

        return $distribution;
    }

    /**
     * Get grade distribution for a student (for pie chart).
     *
     * @param int $studentId
     * @return array
     */
    public function getGradeDistribution(int $studentId): array
    {
        $results = $this->resultRepository->findByStudent($studentId);

        $distribution = [
            'A+' => 0,
            'A' => 0,
            'B' => 0,
            'C' => 0,
            'D' => 0,
            'E' => 0,
            'F' => 0,
        ];

        foreach ($results as $result) {
            $gradeValue = $result->grade->value;
            if (isset($distribution[$gradeValue])) {
                $distribution[$gradeValue]++;
            }
        }

        return $distribution;
    }

    /**
     * Get top performing subjects.
     *
     * @param int $studentId
     * @param int $limit
     * @return Collection
     */
    public function getTopPerformingSubjects(int $studentId, int $limit = 3): Collection
    {
        $results = $this->resultRepository->findByStudent($studentId);

        return $results
            ->sortByDesc('marks_obtained')
            ->take($limit)
            ->map(fn($result) => [
                'subject' => $result->subject,
                'marks' => $result->marks_obtained,
                'grade' => $result->grade,
                'semester' => $result->semester
            ]);
    }

    /**
     * Get bottom performing subjects.
     *
     * @param int $studentId
     * @param int $limit
     * @return Collection
     */
    public function getBottomPerformingSubjects(int $studentId, int $limit = 3): Collection
    {
        $results = $this->resultRepository->findByStudent($studentId);

        return $results
            ->sortBy('marks_obtained')
            ->take($limit)
            ->map(fn($result) => [
                'subject' => $result->subject,
                'marks' => $result->marks_obtained,
                'grade' => $result->grade,
                'semester' => $result->semester
            ]);
    }

    /**
     * Get semester comparison data (average marks and SGPA per semester).
     *
     * @param int $studentId
     * @return array
     */
    public function getSemesterComparison(int $studentId): array
    {
        $semesters = $this->semesterRepository->findByStudent($studentId);
        $comparison = [];

        foreach ($semesters as $semester) {
            $results = $this->resultRepository->findBySemesterAndStudent(
                $semester->id,
                $studentId
            );

            if ($results->isNotEmpty()) {
                $averageMarks = $results->avg('marks_obtained');
                $sgpa = $this->gpaCalculator->calculateSGPA($results);

                $comparison[] = [
                    'semester' => $semester->name,
                    'average_marks' => round($averageMarks, 2),
                    'sgpa' => $sgpa,
                    'total_subjects' => $results->count(),
                    'passed_subjects' => $results->filter(fn($r) => $r->is_passed)->count()
                ];
            }
        }

        return $comparison;
    }
}
