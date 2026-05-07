<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle authentication exceptions
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Authentication required',
                    'message' => $e->getMessage()
                ], 401);
            }

            return redirect()->route('login')
                ->with('error', 'Please log in to access your results.');
        });

        // Handle unauthorized access exceptions
        $this->renderable(function (UnauthorizedAccessException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Unauthorized',
                    'message' => $e->getMessage()
                ], 403);
            }

            return redirect()->back()
                ->with('error', $e->getMessage());
        });

        // Handle validation exceptions
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'message' => $e->getMessage()
                ], 422);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        });

        // Handle data not found exceptions
        $this->renderable(function (DataNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Not found',
                    'message' => $e->getMessage()
                ], 404);
            }

            return redirect()->back()
                ->with('error', $e->getMessage());
        });

        // Handle GPA calculation exceptions
        $this->renderable(function (GpaCalculationException $e, $request) {
            \Log::error('GPA calculation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Calculation error',
                    'message' => 'Unable to calculate GPA. Please contact support.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Unable to calculate GPA. Please contact support.');
        });

        // Handle PDF generation exceptions
        $this->renderable(function (PdfGenerationException $e, $request) {
            \Log::error('PDF generation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Export error',
                    'message' => 'Unable to generate PDF. Please try again later.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Unable to generate PDF. Please try again later.');
        });

        // Handle CSV export exceptions
        $this->renderable(function (CsvExportException $e, $request) {
            \Log::error('CSV export failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Export error',
                    'message' => 'Unable to generate CSV. Please try again later.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Unable to generate CSV. Please try again later.');
        });

        // Handle storage exceptions
        $this->renderable(function (StorageException $e, $request) {
            \Log::error('Storage operation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Storage error',
                    'message' => 'Unable to save data. Please try again later.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Unable to save data. Please try again later.');
        });
    }
}
