@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Semester Results</h1>
    </div>

    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ $error }}
        </div>
    @elseif(isset($semesters) && $semesters->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($semesters as $semester)
            <div class="bg-white shadow rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $semester->name }}</h3>
                        @if($semester->status->value === 'current')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Current</span>
                        @elseif($semester->status->value === 'completed')
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Completed</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Upcoming</span>
                        @endif
                    </div>

                    <div class="space-y-2 mb-4">
                        <p class="text-sm text-gray-600">Academic Year: {{ $semester->academic_year }}</p>
                        <p class="text-sm text-gray-600">Subjects: {{ $semester->subject_count }}</p>
                        <p class="text-sm text-gray-600">Total Credits: {{ $semester->total_credits }}</p>
                        
                        @if($semester->sgpa > 0)
                            <div class="mt-3 pt-3 border-t border-gray-200">
                                <p class="text-sm text-gray-600">SGPA</p>
                                <p class="text-2xl font-bold text-indigo-600">{{ number_format($semester->sgpa, 2) }}</p>
                            </div>
                        @endif

                        @if($semester->passed_count > 0 || $semester->failed_count > 0)
                            <div class="flex space-x-4 text-sm">
                                <span class="text-green-600">Passed: {{ $semester->passed_count }}</span>
                                @if($semester->failed_count > 0)
                                    <span class="text-red-600">Failed: {{ $semester->failed_count }}</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('results.semester', $semester) }}" class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded transition-colors">
                        View Results
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-gray-600">No semester results available yet.</p>
        </div>
    @endif
</div>
@endsection
