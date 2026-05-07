<?php

namespace App\Models;

use App\Enums\GradeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Result Model
 * 
 * Represents an examination result for a student in a specific subject during a semester.
 * 
 * @property int $id
 * @property int $student_id
 * @property int $semester_id
 * @property int $subject_id
 * @property float $marks_obtained
 * @property GradeEnum $grade
 * @property bool $is_passed
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read float $percentage
 * @property-read User $student
 * @property-read Semester $semester
 * @property-read Subject $subject
 * 
 * @package App\Models
 */
class Result extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'semester_id',
        'subject_id',
        'marks_obtained',
        'grade',
        'is_passed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'marks_obtained' => 'float',
        'grade' => GradeEnum::class,
        'is_passed' => 'boolean',
    ];

    /**
     * Get the student who owns this result.
     *
     * @return BelongsTo
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the user who owns this result (alias for student).
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the semester this result belongs to.
     *
     * @return BelongsTo
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Get the subject this result is for.
     *
     * @return BelongsTo
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the percentage score for this result.
     * 
     * Calculates the percentage by dividing marks obtained by the subject's maximum marks.
     *
     * @return float
     */
    public function getPercentageAttribute(): float
    {
        if (!$this->subject || $this->subject->max_marks == 0) {
            return 0.0;
        }
        
        return ($this->marks_obtained / $this->subject->max_marks) * 100;
    }
}
