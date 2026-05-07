<?php

namespace App\Repositories;

use App\Enums\SemesterStatusEnum;
use App\Models\Semester;
use App\Repositories\Contracts\SemesterRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SemesterRepository implements SemesterRepositoryInterface
{
    /**
     * Cache TTL in seconds (1 hour).
     */
    private const CACHE_TTL = 3600;

    /**
     * Find all semesters for a specific student.
     *
     * @param int $studentId
     * @return Collection
     */
    public function findByStudent(int $studentId): Collection
    {
        return Cache::remember(
            "semesters.student.{$studentId}",
            self::CACHE_TTL,
            fn() => Semester::whereHas('results', function ($query) use ($studentId) {
                $query->where('student_id', $studentId);
            })
                ->withCount(['results' => function ($query) use ($studentId) {
                    $query->where('student_id', $studentId);
                }])
                ->with(['results' => function ($query) use ($studentId) {
                    $query->where('student_id', $studentId)
                        ->with('subject');
                }])
                ->orderBy('start_date', 'desc')
                ->get()
        );
    }

    /**
     * Find a semester by ID.
     *
     * @param int $id
     * @return Semester|null
     */
    public function findById(int $id): ?Semester
    {
        return Cache::remember(
            "semester.{$id}",
            self::CACHE_TTL,
            fn() => Semester::find($id)
        );
    }

    /**
     * Get all semesters ordered by start date.
     *
     * @return Collection
     */
    public function getAllOrdered(): Collection
    {
        return Cache::remember(
            'semesters.all.ordered',
            self::CACHE_TTL,
            fn() => Semester::orderBy('start_date', 'desc')->get()
        );
    }

    /**
     * Get active/current semester.
     *
     * @return Semester|null
     */
    public function getActiveSemester(): ?Semester
    {
        return Cache::remember(
            'semester.active',
            self::CACHE_TTL,
            fn() => Semester::where('status', SemesterStatusEnum::CURRENT)->first()
        );
    }
}
