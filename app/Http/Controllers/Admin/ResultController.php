<?php

namespace App\Http\Controllers\Admin;

use App\Enums\GradeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreResultRequest;
use App\Http\Requests\UpdateResultRequest;
use App\Models\Result;
use App\Models\Semester;
use App\Models\Subject;
use App\Models\User;
use App\Services\GradeCalculatorService;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * The grade calculator service instance.
     *
     * @var GradeCalculatorService
     */
    protected GradeCalculatorService $gradeCalculator;

    /**
     * Create a new controller instance.
     *
     * @param GradeCalculatorService $gradeCalculator
     */
    public function __construct(GradeCalculatorService $gradeCalculator)
    {
        $this->gradeCalculator = $gradeCalculator;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Result::with(['student', 'subject', 'semester']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Search by student name
                $q->whereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('name', 'like', "%{$search}%");
                })
                // Search by subject name
                ->orWhereHas('subject', function ($subjectQuery) use ($search) {
                    $subjectQuery->where('name', 'like', "%{$search}%");
                })
                // Search by semester name
                ->orWhereHas('semester', function ($semesterQuery) use ($search) {
                    $semesterQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        $results = $query->latest()->paginate(15)->withQueryString();

        return view('admin.results.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $students = User::where('role', 'student')->orderBy('name')->get();
        $semesters = Semester::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.results.create', compact('students', 'semesters', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResultRequest $request)
    {
        $validated = $request->validated();

        // Get the subject to retrieve max_marks
        $subject = Subject::findOrFail($validated['subject_id']);

        try {
            // Calculate grade using GradeCalculatorService
            $gradeData = $this->gradeCalculator->calculateGradeFromMarks(
                $validated['marks_obtained'],
                $subject->max_marks
            );

            // Add grade data to validated data
            $validated['grade'] = GradeEnum::from($gradeData['grade']);
            $validated['is_passed'] = $gradeData['is_passed'];

            // Create the result
            Result::create($validated);

            return redirect()
                ->route('admin.results.index')
                ->with('success', 'Result created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Grade calculation failed: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Result $result)
    {
        $result->load(['student', 'subject', 'semester']);

        return view('admin.results.show', compact('result'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Result $result)
    {
        $result->load(['student', 'subject', 'semester']);
        $students = User::where('role', 'student')->orderBy('name')->get();
        $semesters = Semester::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('admin.results.edit', compact('result', 'students', 'semesters', 'subjects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResultRequest $request, Result $result)
    {
        $validated = $request->validated();

        // Get the subject to retrieve max_marks
        $subject = Subject::findOrFail($validated['subject_id']);

        // Check if marks_obtained has changed
        $marksChanged = $result->marks_obtained != $validated['marks_obtained'];

        try {
            // Recalculate grade if marks_obtained has changed
            if ($marksChanged) {
                $gradeData = $this->gradeCalculator->calculateGradeFromMarks(
                    $validated['marks_obtained'],
                    $subject->max_marks
                );

                // Update grade data
                $validated['grade'] = GradeEnum::from($gradeData['grade']);
                $validated['is_passed'] = $gradeData['is_passed'];
            }

            // Update the result
            $result->update($validated);

            return redirect()
                ->route('admin.results.index')
                ->with('success', 'Result updated successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Grade calculation failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Result $result)
    {
        $result->delete();

        return redirect()
            ->route('admin.results.index')
            ->with('success', 'Result deleted successfully.');
    }
}
