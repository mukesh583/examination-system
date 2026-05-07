<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGradeScaleRequest;
use App\Http\Requests\UpdateGradeScaleRequest;
use App\Models\GradeScale;
use App\Services\GradeCalculatorService;
use Illuminate\Http\Request;

class GradeScaleController extends Controller
{
    /**
     * The grade calculator service instance.
     *
     * @var GradeCalculatorService
     */
    protected $gradeCalculator;

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
        $query = GradeScale::query();

        // Search functionality by grade letter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('grade', 'like', "%{$search}%");
        }

        $gradeScales = $query->orderBy('min_marks', 'desc')->paginate(15)->withQueryString();

        return view('admin.grade-scales.index', compact('gradeScales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.grade-scales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGradeScaleRequest $request)
    {
        GradeScale::create($request->validated());

        return redirect()
            ->route('admin.grade-scales.index')
            ->with('success', 'Grade scale created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GradeScale $gradeScale)
    {
        return view('admin.grade-scales.show', compact('gradeScale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GradeScale $gradeScale)
    {
        return view('admin.grade-scales.edit', compact('gradeScale'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGradeScaleRequest $request, GradeScale $gradeScale)
    {
        $gradeScale->update($request->validated());

        // Recalculate affected results
        $updatedCount = $this->gradeCalculator->recalculateResultGrades($gradeScale);

        return redirect()
            ->route('admin.grade-scales.index')
            ->with('success', "Grade scale updated successfully. {$updatedCount} results recalculated.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GradeScale $gradeScale)
    {
        // No referential integrity check needed per requirements
        $gradeScale->delete();

        return redirect()
            ->route('admin.grade-scales.index')
            ->with('success', 'Grade scale deleted successfully.');
    }
}
