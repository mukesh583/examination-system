<?php

namespace App\Repositories\Contracts;

use App\Models\Semester;
use Illuminate\Support\Collection;

interface SemesterRepositoryInterface
{
    /**
     * Find all semesters for a specific student.
     *
     * @param int $studentId
     * @return Collection
     */
    public function findByStudent(int $studentId): Collection;

    /**
     * Find a semester by ID.
     *
     * @param int $id
     * @return Semester|null
     */
    public function findById(int $id): ?Semester;

    /**
     * Get all semesters ordered by start date.
     *
     * @return Collection
     */
    public function getAllOrdered(): Collection;

    /**
     * Get active/current semester.
     *
     * @return Semester|null
     */
    public function getActiveSemester(): ?Semester;
}
