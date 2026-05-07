<?php

namespace App\Repositories;

use App\Models\Result;
use App\Repositories\Contracts\ResultRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResultRepository implements ResultRepositoryInterface
{
    /**
     * Cache TTL in seconds (1 hour).
     */
    private const CACHE_TTL = 3600;

    /**
     * Find all results for a specific student.
     *
     * @param int $studentId
     * @return Collection
     */
    public function findByStudent(int $studentId): Collection
    {
        return Cache::remember(
            "results.student.{$studentId}",
            self::CACHE_TTL,
            fn() => Result::with(['subject', 'semester'])
                ->where('student_id', $studentId)
                ->orderBy('semester_id')
                ->orderBy('subject_id')
                ->get()
        );
    }

    /**
     * Find all results for a specific semester.
     *
     * @param int $semesterId
     * @return Collection
     */
    public function findBySemester(int $semesterId): Collection
    {
        return Cache::remember(
            "results.semester.{$semesterId}",
            self::CACHE_TTL,
            fn() => Result::with(['subject', 'semester', 'student'])
                ->where('semester_id', $semesterId)
                ->orderBy('student_id')
                ->orderBy('subject_id')
                ->get()
        );
    }

    /**
     * Find results for a specific semester and student.
     *
     * @param int $semesterId
     * @param int $studentId
     * @return Collection
     */
    public function findBySemesterAndStudent(int $semesterId, int $studentId): Collection
    {
        return Cache::remember(
            "results.{$studentId}.{$semesterId}",
            self::CACHE_TTL,
            fn() => Result::with(['subject', 'semester'])
                ->where('student_id', $studentId)
                ->where('semester_id', $semesterId)
                ->orderBy('subject_id')
                ->get()
        );
    }

    /**
     * Search results by subject name or code.
     *
     * @param int $studentId
     * @param string $query
     * @return Collection
     */
    public function search(int $studentId, string $query): Collection
    {
        return Result::with(['subject', 'semester'])
            ->where('student_id', $studentId)
            ->whereHas('subject', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('code', 'like', "%{$query}%");
            })
            ->orderBy('semester_id', 'desc')
            ->get();
    }

    /**
     * Filter results by pass/fail status.
     *
     * @param int $studentId
     * @param string $status
     * @return Collection
     */
    public function filterByStatus(int $studentId, string $status): Collection
    {
        $query = Result::with(['subject', 'semester'])
            ->where('student_id', $studentId);

        if ($status === 'passed') {
            $query->where('is_passed', true);
        } elseif ($status === 'failed') {
            $query->where('is_passed', false);
        }

        return $query->orderBy('semester_id', 'desc')
            ->orderBy('subject_id')
            ->get();
    }

    /**
     * Create a new result.
     *
     * @param array $data
     * @return Result
     * @throws \Exception
     */
    public function create(array $data): Result
    {
        // Validate marks range
        if ($data['marks_obtained'] < 0 || $data['marks_obtained'] > 100) {
            throw new \InvalidArgumentException(
                "Marks must be between 0 and 100. Received: {$data['marks_obtained']}"
            );
        }

        // Check for duplicates
        $exists = Result::where('student_id', $data['student_id'])
            ->where('semester_id', $data['semester_id'])
            ->where('subject_id', $data['subject_id'])
            ->exists();

        if ($exists) {
            throw new \RuntimeException(
                'A result already exists for this student, semester, and subject combination.'
            );
        }

        try {
            $result = DB::transaction(function () use ($data) {
                return Result::create($data);
            });

            // Invalidate relevant caches
            $this->invalidateCache($data['student_id'], $data['semester_id']);

            return $result;
        } catch (\Exception $e) {
            Log::error('Database error creating result', [
                'data' => $data,
                'error' => $e->getMessage()
            ]);

            throw new \RuntimeException(
                'Failed to store result. Please try again.',
                0,
                $e
            );
        }
    }

    /**
     * Update an existing result.
     *
     * @param int $id
     * @param array $data
     * @return Result
     * @throws \Exception
     */
    public function update(int $id, array $data): Result
    {
        // Validate marks range if provided
        if (isset($data['marks_obtained'])) {
            if ($data['marks_obtained'] < 0 || $data['marks_obtained'] > 100) {
                throw new \InvalidArgumentException(
                    "Marks must be between 0 and 100. Received: {$data['marks_obtained']}"
                );
            }
        }

        try {
            $result = Result::findOrFail($id);

            $result->update($data);

            // Invalidate relevant caches
            $this->invalidateCache($result->student_id, $result->semester_id);

            return $result->fresh(['subject', 'semester']);
        } catch (\Exception $e) {
            Log::error('Database error updating result', [
                'id' => $id,
                'data' => $data,
                'error' => $e->getMessage()
            ]);

            throw new \RuntimeException(
                'Failed to update result. Please try again.',
                0,
                $e
            );
        }
    }

    /**
     * Invalidate caches related to a student and semester.
     *
     * @param int $studentId
     * @param int $semesterId
     * @return void
     */
    private function invalidateCache(int $studentId, int $semesterId): void
    {
        Cache::forget("results.student.{$studentId}");
        Cache::forget("results.semester.{$semesterId}");
        Cache::forget("results.{$studentId}.{$semesterId}");
    }
}
