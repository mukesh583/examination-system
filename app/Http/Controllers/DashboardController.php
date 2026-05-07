<?php

namespace App\Http\Controllers;

use App\Services\ProgressTrackerService;
use App\Services\PerformanceAnalyzerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private ProgressTrackerService $progressTracker,
        private PerformanceAnalyzerService $performanceAnalyzer
    ) {}

    /**
     * Display comprehensive dashboard with performance metrics.
     */
    public function index(Request $request): View
    {
        try {
            $student = $request->user();

            // Get comprehensive performance metrics
            $metrics = $this->progressTracker->getProgressMetrics($student->id);

            // Get performance category
            $performanceCategory = $this->getPerformanceCategory($metrics->cgpa);

            // Get top and bottom performing subjects
            $topSubjects = $this->performanceAnalyzer->getTopPerformingSubjects($student->id, 3);
            $bottomSubjects = $this->performanceAnalyzer->getBottomPerformingSubjects($student->id, 3);

            return view('dashboard.index', [
                'metrics' => $metrics,
                'performanceCategory' => $performanceCategory,
                'topSubjects' => $topSubjects,
                'bottomSubjects' => $bottomSubjects,
            ]);
        } catch (\Exception $e) {
            Log::error('Error displaying dashboard', [
                'student_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return view('dashboard.index', [
                'error' => 'Unable to load dashboard. Please try again later.',
            ]);
        }
    }

    /**
     * Determine performance category based on CGPA.
     */
    private function getPerformanceCategory(float $cgpa): string
    {
        return match(true) {
            $cgpa >= 7.5 => 'Distinction',
            $cgpa >= 6.0 => 'First Class',
            $cgpa >= 5.0 => 'Second Class',
            $cgpa >= 4.0 => 'Pass',
            default => 'Below Pass',
        };
    }
}
