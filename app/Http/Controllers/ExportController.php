<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Services\ResultExportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ExportController extends Controller
{
    public function __construct(
        private ResultExportService $exportService
    ) {}

    /**
     * Generate and download PDF for semester results.
     */
    public function pdf(Semester $semester, Request $request): Response
    {
        try {
            $student = $request->user();

            return $this->exportService->exportToPdf($semester, $student->id);
        } catch (\Exception $e) {
            Log::error('Error generating PDF', [
                'semester_id' => $semester->id,
                'student_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Unable to generate PDF. Please try again later.');
        }
    }

    /**
     * Generate and download CSV for semester results.
     */
    public function csv(Semester $semester, Request $request): Response
    {
        try {
            $student = $request->user();

            return $this->exportService->exportToCsv($semester, $student->id);
        } catch (\Exception $e) {
            Log::error('Error generating CSV', [
                'semester_id' => $semester->id,
                'student_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Unable to generate CSV. Please try again later.');
        }
    }
}
