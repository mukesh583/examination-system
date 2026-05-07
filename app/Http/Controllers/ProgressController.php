<?php

namespace App\Http\Controllers;

use App\Services\ProgressTrackerService;
use App\Services\PerformanceAnalyzerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProgressController extends Controller
{
    public function __construct(
        private ProgressTrackerService $progressTracker,
        private PerformanceAnalyzerService $performanceAnalyzer
    ) {}

    /**
     * Display progress tracking with charts.
     */
    public function index(Request $request): View
    {
        try {
            $student = $request->user();

            $metrics = $this->progressTracker->getProgressMetrics($student->id);

            return view('progress.index', [
                'metrics' => $metrics,
            ]);
        } catch (\Exception $e) {
            Log::error('Error displaying progress', [
                'student_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return view('progress.index', [
                'error' => 'Unable to load progress data. Please try again later.',
            ]);
        }
    }

    /**
     * Return JSON data for Chart.js visualizations.
     */
    public function chartData(Request $request): JsonResponse
    {
        try {
            $student = $request->user();

            // Get semester trends for SGPA line chart
            $semesterTrends = $this->progressTracker->getSemesterTrends($student->id);

            // Get grade distribution for pie chart
            $gradeDistribution = $this->performanceAnalyzer->getGradeDistribution($student->id);

            // Get semester comparison for bar chart
            $semesterComparison = $this->performanceAnalyzer->getSemesterComparison($student->id);

            return response()->json([
                'semesterTrends' => $semesterTrends,
                'gradeDistribution' => $gradeDistribution,
                'semesterComparison' => $semesterComparison,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching chart data', [
                'student_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Unable to load chart data.',
            ], 500);
        }
    }
}
