<?php

namespace App\Models;

use App\Enums\SemesterStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Semester Model
 * 
 * Represents an academic semester during which courses are taken and examinations are conducted.
 * 
 * @property int $id
 * @property string $name
 * @property string $academic_year
 * @property \Carbon\Carbon $start_date
 * @property \Carbon\Carbon $end_date
 * @property SemesterStatusEnum $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @package App\Models
 */
class Semester extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'academic_year',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'status' => SemesterStatusEnum::class,
    ];

    /**
     * Get all results for this semester.
     *
     * @return HasMany
     */
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Get all subjects associated with this semester through results.
     *
     * @return BelongsToMany
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'results')
            ->withPivot('student_id', 'marks_obtained', 'grade', 'is_passed')
            ->withTimestamps();
    }

    /**
     * Check if the semester is currently active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === SemesterStatusEnum::CURRENT;
    }
}
