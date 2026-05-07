<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStudentOwnsResult
{
    /**
     * Handle an incoming request.
     *
     * Ensures that students can only access their own results.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If accessing a specific result, verify ownership
        if ($request->route('result')) {
            $result = $request->route('result');
            
            if ($result->student_id !== $user->id) {
                abort(403, 'You are not authorized to view this result.');
            }
        }

        // If accessing semester results, the controller will filter by student_id
        // This middleware ensures authentication is required
        
        return $next($request);
    }
}
