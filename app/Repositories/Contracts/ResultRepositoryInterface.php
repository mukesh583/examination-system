<?php

namespace App\Repositories\Contracts;

use App\Models\Result;
use Illuminate\Support\Collection;

interface ResultRepositoryInterface
{
    /**
     * Find all results for a specific student.
     *
     * @param int $studentId
     * @return Collection
     */
    public function findByStudent(int $studentId): Collection;

    /**
     * Find all results for a specific semester.
     *
     * @param int $semesterId
     * @return Collection
     */
    public function findBySemester(int $semesterId): Collection;

    /**
     * Find results for a specific semester and student.
     *
     * @param int $semesterId
     * @param int $studentId
     * @return Collection
     */
    public function findBySemesterAndStudent(int $semesterId, int $studentId): Collection;

    /**
     * Search results by subject name or code.
     *
     * @param int $studentId
     * @param string $query
     * @return Collection
     */
    public function search(int $studentId, string $query): Collection;

    /**
     * Filter results by pass/fail status.
     *
     * @param int $studentId
     * @param string $status
     * @return Collection
     */
    public function filterByStatus(int $studentId, string $status): Collection;

    /**
     * Create a new result.
     *
     * @param array $data
     * @return Result
     */
    public function create(array $data): Result;

    /**
     * Update an existing result.
     *
     * @param int $id
     * @param array $data
     * @return Result
     */
    public function update(int $id, array $data): Result;
}
