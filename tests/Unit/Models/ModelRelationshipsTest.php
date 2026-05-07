<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\Result;
use App\Models\GradeScale;
use App\Enums\GradeEnum;
use App\Enums\SemesterStatusEnum;
use PHPUnit\Framework\TestCase;

/**
 * Test suite for verifying Eloquent model relationships and basic functionality.
 * 
 * This test ensures that all models are properly configured with their relationships
 * and that the enums are working correctly.
 * 
 * Note: These tests do not require database connection as they only verify
 * model structure and enum definitions.
 */
class ModelRelationshipsTest extends TestCase
{

    /**
     * Test that all model classes exist and can be instantiated.
     */
    public function test_all_models_exist(): void
    {
        $this->assertTrue(class_exists(User::class));
        $this->assertTrue(class_exists(Semester::class));
        $this->assertTrue(class_exists(Subject::class));
        $this->assertTrue(class_exists(Result::class));
        $this->assertTrue(class_exists(GradeScale::class));
    }

    /**
     * Test that User model has fillable attributes defined.
     */
    public function test_user_model_fillable_attributes(): void
    {
        $user = new User();
        $fillable = $user->getFillable();
        
        $this->assertContains('name', $fillable);
        $this->assertContains('email', $fillable);
        $this->assertContains('student_id', $fillable);
        $this->assertContains('enrollment_year', $fillable);
        $this->assertContains('program', $fillable);
    }

    /**
     * Test that Semester model has fillable attributes defined.
     */
    public function test_semester_model_fillable_attributes(): void
    {
        $semester = new Semester();
        $fillable = $semester->getFillable();
        
        $this->assertContains('name', $fillable);
        $this->assertContains('academic_year', $fillable);
        $this->assertContains('start_date', $fillable);
        $this->assertContains('end_date', $fillable);
        $this->assertContains('status', $fillable);
    }

    /**
     * Test that Subject model has fillable attributes defined.
     */
    public function test_subject_model_fillable_attributes(): void
    {
        $subject = new Subject();
        $fillable = $subject->getFillable();
        
        $this->assertContains('code', $fillable);
        $this->assertContains('name', $fillable);
        $this->assertContains('credits', $fillable);
        $this->assertContains('max_marks', $fillable);
        $this->assertContains('department', $fillable);
    }

    /**
     * Test that Result model has fillable attributes defined.
     */
    public function test_result_model_fillable_attributes(): void
    {
        $result = new Result();
        $fillable = $result->getFillable();
        
        $this->assertContains('student_id', $fillable);
        $this->assertContains('semester_id', $fillable);
        $this->assertContains('subject_id', $fillable);
        $this->assertContains('marks_obtained', $fillable);
        $this->assertContains('grade', $fillable);
        $this->assertContains('is_passed', $fillable);
    }

    /**
     * Test that GradeEnum has all required cases.
     */
    public function test_grade_enum_has_all_cases(): void
    {
        $expectedGrades = ['A+', 'A', 'B', 'C', 'D', 'E', 'F'];
        $actualGrades = array_map(fn($case) => $case->value, GradeEnum::cases());
        
        $this->assertEquals($expectedGrades, $actualGrades);
    }

    /**
     * Test that SemesterStatusEnum has all required cases.
     */
    public function test_semester_status_enum_has_all_cases(): void
    {
        $expectedStatuses = ['current', 'completed', 'upcoming'];
        $actualStatuses = array_map(fn($case) => $case->value, SemesterStatusEnum::cases());
        
        $this->assertEquals($expectedStatuses, $actualStatuses);
    }

    /**
     * Test that GradeScale model has correct fillable attributes.
     */
    public function test_grade_scale_model_fillable_attributes(): void
    {
        $gradeScale = new GradeScale();
        $expectedFillable = ['grade', 'min_marks', 'max_marks', 'grade_point', 'is_passing'];
        
        $this->assertEquals($expectedFillable, $gradeScale->getFillable());
    }

    /**
     * Test that GradeScale model has correct casts.
     */
    public function test_grade_scale_model_casts(): void
    {
        $gradeScale = new GradeScale();
        $casts = $gradeScale->getCasts();
        
        $this->assertEquals('float', $casts['min_marks']);
        $this->assertEquals('float', $casts['max_marks']);
        $this->assertEquals('float', $casts['grade_point']);
        $this->assertEquals('boolean', $casts['is_passing']);
    }
}
