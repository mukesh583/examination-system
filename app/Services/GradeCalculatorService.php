<?php

namespace App\Services;

use App\Enums\GradeEnum;
use App\Models\GradeScale;
use App\Models\Result;

class GradeCalculatorService
{
    /**
     * Calculate grade from marks using the GradeScale model.
     *
     * @param float $marksObtained
     * @param int $maxMarks
     * @return array ['grade' => string, 'grade_point' => float, 'is_passed' => bool]
     * @throws \Exception
     */
    public function calculateGradeFromMarks(float $marksObtained, int $maxMarks): array
    {
        // Calculate percentage
        $percentage = ($marksObtained / $maxMarks) * 100;
        
        // Find matching grade scale
        $gradeScale = GradeScale::where('min_marks', '<=', $percentage)
            ->where('max_marks', '>=', $percentage)
            ->first();
        
        if (!$gradeScale) {
            throw new \Exception("No matching grade scale found for {$percentage}%. Please configure grade scales.");
        }
        
        return [
            'grade' => $gradeScale->grade,
            'grade_point' => $gradeScale->grade_point,
            'is_passed' => $gradeScale->is_passing,
        ];
    }

    /**
     * Calculate letter grade based on marks obtained (legacy method).
     *
     * @param float $marks
     * @return GradeEnum
     */
    public function calculateGrade(float $marks): GradeEnum
    {
        return match (true) {
            $marks >= 90 => GradeEnum::A_PLUS,
            $marks >= 80 => GradeEnum::A,
            $marks >= 70 => GradeEnum::B,
            $marks >= 60 => GradeEnum::C,
            $marks >= 50 => GradeEnum::D,
            $marks >= 40 => GradeEnum::E,
            default => GradeEnum::F,
        };
    }

    /**
     * Get grade point for a given grade on a 10-point scale (legacy method).
     *
     * @param GradeEnum $grade
     * @return float
     */
    public function getGradePoint(GradeEnum $grade): float
    {
        return match ($grade) {
            GradeEnum::A_PLUS => 10.0,
            GradeEnum::A => 9.0,
            GradeEnum::B => 8.0,
            GradeEnum::C => 7.0,
            GradeEnum::D => 6.0,
            GradeEnum::E => 5.0,
            GradeEnum::F => 0.0,
        };
    }

    /**
     * Check if a grade is passing (legacy method).
     *
     * @param GradeEnum $grade
     * @return bool
     */
    public function isPassing(GradeEnum $grade): bool
    {
        return $grade !== GradeEnum::F;
    }

    /**
     * Recalculate all results affected by a grade scale update.
     *
     * @param GradeScale $updatedGradeScale
     * @return int Number of results updated
     */
    public function recalculateResultGrades(GradeScale $updatedGradeScale): int
    {
        $updatedCount = 0;
        
        // Get all results with their subjects
        $results = Result::with('subject')->get();
        
        foreach ($results as $result) {
            // Calculate percentage for this result
            $percentage = ($result->marks_obtained / $result->subject->max_marks) * 100;
            
            // Check if this result falls within the updated grade scale range
            if ($percentage >= $updatedGradeScale->min_marks && $percentage <= $updatedGradeScale->max_marks) {
                try {
                    // Recalculate grade
                    $gradeData = $this->calculateGradeFromMarks(
                        $result->marks_obtained,
                        $result->subject->max_marks
                    );
                    
                    // Update the result
                    $result->update([
                        'grade' => GradeEnum::from($gradeData['grade']),
                        'is_passed' => $gradeData['is_passed'],
                    ]);
                    
                    $updatedCount++;
                } catch (\Exception $e) {
                    // Log error but continue processing other results
                    \Log::error("Failed to recalculate grade for result {$result->id}: " . $e->getMessage());
                }
            }
        }
        
        return $updatedCount;
    }
}
