<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\Result;
use App\Models\GradeScale;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with entity statistics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Count total records for each entity
        $totalStudents = User::where('role', 'student')->count();
        $totalSubjects = Subject::count();
        $totalSemesters = Semester::count();
        $totalResults = Result::count();
        $totalGradeScales = GradeScale::count();
        
        // Get recent results with relationships
        $recentResults = Result::with(['student', 'subject', 'semester'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalSubjects',
            'totalSemesters',
            'totalResults',
            'totalGradeScales',
            'recentResults'
        ));
    }
}
