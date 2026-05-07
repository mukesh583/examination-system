<?php

namespace App\Services;

use App\Models\Semester;
use App\Models\User;
use App\Repositories\Contracts\ResultRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ResultExportService
{
    public function __construct(
        private ResultRepositoryInterface $resultRepository,
        private GpaCalculatorService $gpaCalculator
    ) {
    }

    /**
     * Export semester results to PDF.
     *
     * @param Semester $semester
     * @param int $studentId
     * @return \Illuminate\Http\Response
     */
    public function exportToPdf(Semester $semester, int $studentId)
    {
        try {
            $results = $this->resultRepository->findBySemesterAndStudent(
                $semester->id,
                $studentId
            );
            $student = User::findOrFail($studentId);

            $sgpa = $results->isNotEmpty()
                ? $this->gpaCalculator->calculateSGPA($results)
                : 0.0;

            $pdf = Pdf::loadView('exports.results-pdf', [
                'student' => $student,
                'semester' => $semester,
                'results' => $results,
                'sgpa' => $sgpa,
                'generatedAt' => now()
            ]);

            return $pdf->download("results_{$semester->name}_{$student->student_id}.pdf");
        } catch (\Exception $e) {
            Log::error('PDF generation failed', [
                'semester_id' => $semester->id,
                'student_id' => $studentId,
                'error' => $e->getMessage()
            ]);

            throw new \RuntimeException(
                'Failed to generate PDF. Please try again.',
                0,
                $e
            );
        }
    }

    /**
     * Export semester results to CSV.
     *
     * @param Semester $semester
     * @param int $studentId
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function exportToCsv(Semester $semester, int $studentId)
    {
        try {
            $results = $this->resultRepository->findBySemesterAndStudent(
                $semester->id,
                $studentId
            );
            $student = User::findOrFail($studentId);

            $filename = "results_{$semester->name}_{$student->student_id}.csv";

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function () use ($results, $student, $semester) {
                $file = fopen('php://output', 'w');

                // Add metadata
                fputcsv($file, ['Student Name', $student->name]);
                fputcsv($file, ['Student ID', $student->student_id]);
                fputcsv($file, ['Semester', $semester->name]);
                fputcsv($file, ['Academic Year', $semester->academic_year]);
                fputcsv($file, ['Generated At', now()->format('Y-m-d H:i:s')]);
                fputcsv($file, []); // Empty row

                // Add headers
                fputcsv($file, [
                    'Subject Code',
                    'Subject Name',
                    'Credits',
                    'Marks Obtained',
                    'Max Marks',
                    'Percentage',
                    'Grade',
                    'Status'
                ]);

                // Add results
                foreach ($results as $result) {
                    fputcsv($file, [
                        $result->subject->code,
                        $result->subject->name,
                        $result->subject->credits,
                        $result->marks_obtained,
                        $result->subject->max_marks,
                        round(($result->marks_obtained / $result->subject->max_marks) * 100, 2),
                        $result->grade->value,
                        $result->is_passed ? 'Passed' : 'Failed'
                    ]);
                }

                // Add SGPA
                if ($results->isNotEmpty()) {
                    $sgpa = $this->gpaCalculator->calculateSGPA($results);
                    fputcsv($file, []); // Empty row
                    fputcsv($file, ['SGPA', $sgpa]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('CSV generation failed', [
                'semester_id' => $semester->id,
                'student_id' => $studentId,
                'error' => $e->getMessage()
            ]);

            throw new \RuntimeException(
                'Failed to generate CSV. Please try again.',
                0,
                $e
            );
        }
    }
}
