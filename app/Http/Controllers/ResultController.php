<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultFilterRequest;
use App\Repositories\Contracts\ResultRepositoryInterface;
use App\Repositories\Contracts\SemesterRepositoryInterface;
use App\Services\GpaCalculatorService;
use App\Services\PerformanceAnalyzerService;
use App\Models\Semester;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function __construct(
        private ResultRepositoryInterface $resultRepository,
        private SemesterRepositoryInterface $semesterRepository,
        private GpaCalculatorService $gpaCalculator,
        private PerformanceAnalyzerService $performanceAnalyzer
    ) {}

    /**
     * Display all semesters with result summaries.
     */
    public function index(Request $request): View
    {
        try {
            $student = $request->user();
            $semesters = $this->semesterRepository->findByStudent($student->id);

            // Calculate SGPA for each semester
            $semestersWithMetrics = $semesters->map(function ($semester) use ($student) {
                $results = $this->resultRepository->findBySemesterAndStudent(
                    $semester->id,
                    $student->id
                );

                $sgpa = $results->isNotEmpty() 
                    ? $this->gpaCalculator->calculateSGPA($results) 
                    : 0.0;

                $semester->sgpa = $sgpa;
                $semester->subject_count = $results->count();
                $semester->total_credits = $results->sum(fn($r) => $r->subject->credits);
                $semester->passed_count = $results->where('is_passed', true)->count();
                $semester->failed_count = $results->where('is_passed', false)->count();

                return $semester;
            });

            return view('results.index', [
                'semesters' => $semestersWithMetrics,
            ]);
        } catch (\Exception $e) {
            Log::error('Error displaying semester list', [
                'student_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return view('results.index', [
                'semesters' => collect(),
                'error' => 'Unable to load semesters. Please try again later.',
            ]);
        }
    }

    /**
     * Display results for a specific semester with filters.
     */
    public function show(Semester $semester, ResultFilterRequest $request): View
    {
        try {
            $student = $request->user();
            
            $results = $this->resultRepository->findBySemesterAndStudent(
                $semester->id,
                $student->id
            );

            // Apply filters
            if ($request->filled('search')) {
                $search = strtolower($request->input('search'));
                $results = $results->filter(function ($result) use ($search) {
                    return str_contains(strtolower($result->subject->name), $search) ||
                           str_contains(strtolower($result->subject->code), $search);
                });
            }

            if ($request->filled('status') && $request->input('status') !== 'all') {
                $isPassed = $request->input('status') === 'passed';
                $results = $results->where('is_passed', $isPassed);
            }

            // Apply sorting
            if ($request->filled('sort_by')) {
                $sortBy = $request->input('sort_by');
                $sortOrder = $request->input('sort_order', 'asc');

                $results = match($sortBy) {
                    'subject_name' => $sortOrder === 'asc' 
                        ? $results->sortBy('subject.name')
                        : $results->sortByDesc('subject.name'),
                    'marks' => $sortOrder === 'asc'
                        ? $results->sortBy('marks_obtained')
                        : $results->sortByDesc('marks_obtained'),
                    'grade' => $sortOrder === 'asc'
                        ? $results->sortBy('grade')
                        : $results->sortByDesc('grade'),
                    default => $results,
                };
            }

            $sgpa = $results->isNotEmpty() 
                ? $this->gpaCalculator->calculateSGPA($results) 
                : 0.0;

            return view('results.semester', [
                'semester' => $semester,
                'results' => $results,
                'sgpa' => $sgpa,
            ]);
        } catch (\Exception $e) {
            Log::error('Error displaying semester results', [
                'semester_id' => $semester->id,
                'student_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return view('results.semester', [
                'semester' => $semester,
                'results' => collect(),
                'sgpa' => 0.0,
                'error' => 'Unable to load results. Please try again later.',
            ]);
        }
    }

    /**
     * Real-time search for subjects.
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $student = $request->user();
            $query = $request->input('query', '');

            if (empty($query)) {
                return response()->json([]);
            }

            $results = $this->resultRepository->search($student->id, $query);

            return response()->json($results->map(function ($result) {
                return [
                    'id' => $result->id,
                    'subject_code' => $result->subject->code,
                    'subject_name' => $result->subject->name,
                    'marks_obtained' => $result->marks_obtained,
                    'grade' => $result->grade->value,
                    'is_passed' => $result->is_passed,
                    'semester_name' => $result->semester->name,
                ];
            }));
        } catch (\Exception $e) {
            Log::error('Error searching results', [
                'student_id' => $request->user()->id,
                'query' => $request->input('query'),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Search failed. Please try again.',
            ], 500);
        }
    }
}
