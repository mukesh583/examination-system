<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * GradeScale Model
 * 
 * Represents the grading scale configuration that maps marks ranges to letter grades
 * and grade points. This model stores the grading criteria used for grade calculation.
 * 
 * @property int $id
 * @property string $grade
 * @property float $min_marks
 * @property float $max_marks
 * @property float $grade_point
 * @property bool $is_passing
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @package App\Models
 */
class GradeScale extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'grade',
        'min_marks',
        'max_marks',
        'grade_point',
        'is_passing',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'min_marks' => 'float',
        'max_marks' => 'float',
        'grade_point' => 'float',
        'is_passing' => 'boolean',
    ];

    /**
     * Get the grade scale entry for a given marks value.
     * 
     * @param float $marks The marks obtained
     * @return GradeScale|null The matching grade scale entry or null if not found
     */
    public static function getGradeForMarks(float $marks): ?GradeScale
    {
        return static::where('min_marks', '<=', $marks)
            ->where('max_marks', '>=', $marks)
            ->first();
    }

    /**
     * Get the grade point for a specific grade letter.
     * 
     * @param string $grade The grade letter (e.g., 'A+', 'B', 'F')
     * @return float|null The grade point or null if not found
     */
    public static function getGradePoint(string $grade): ?float
    {
        $gradeScale = static::where('grade', $grade)->first();
        return $gradeScale?->grade_point;
    }

    /**
     * Check if a specific grade is a passing grade.
     * 
     * @param string $grade The grade letter
     * @return bool True if passing, false otherwise
     */
    public static function isPassing(string $grade): bool
    {
        $gradeScale = static::where('grade', $grade)->first();
        return $gradeScale?->is_passing ?? false;
    }
}
