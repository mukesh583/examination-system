@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
    </div>

    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ $error }}
        </div>
    @elseif(isset($metrics))
        <!-- Performance Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- CGPA Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">CGPA</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($metrics->cgpa, 2) }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Credits Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Credits</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $metrics->totalCredits }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Semesters Completed Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Semesters</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $metrics->completedSemesters }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pass Percentage Card -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Pass Rate</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ number_format($metrics->passPercentage, 1) }}%</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Category -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Performance Category</h2>
            <div class="text-2xl font-bold text-indigo-600">{{ $performanceCategory }}</div>
        </div>

        <!-- Top and Bottom Subjects -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Performing Subjects -->
            @if(isset($topSubjects) && $topSubjects->isNotEmpty())
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Top Performing Subjects</h2>
                <ul class="space-y-3">
                    @foreach($topSubjects as $result)
                    <li class="flex justify-between items-center">
                        <span class="text-gray-700">{{ $result['subject']->name }}</span>
                        <span class="font-semibold text-green-600">{{ number_format($result['marks'], 2) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Bottom Performing Subjects -->
            @if(isset($bottomSubjects) && $bottomSubjects->isNotEmpty())
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Areas for Improvement</h2>
                <ul class="space-y-3">
                    @foreach($bottomSubjects as $result)
                    <li class="flex justify-between items-center">
                        <span class="text-gray-700">{{ $result['subject']->name }}</span>
                        <span class="font-semibold text-orange-600">{{ number_format($result['marks'], 2) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        <!-- Failed Subjects -->
        @if($metrics->failedSubjects->isNotEmpty())
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-red-900 mb-4">Outstanding Failed Subjects</h2>
            <ul class="space-y-2">
                @foreach($metrics->failedSubjects as $result)
                <li class="text-red-700">
                    {{ $result->subject->name }} ({{ $result->subject->code }}) - {{ $result->semester->name }}
                </li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Semester Performance -->
        @if($metrics->highestSemester || $metrics->lowestSemester)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @if($metrics->highestSemester)
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-green-900 mb-2">Best Semester</h3>
                <p class="text-green-700">{{ $metrics->highestSemester->name }}</p>
            </div>
            @endif

            @if($metrics->lowestSemester)
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-orange-900 mb-2">Needs Improvement</h3>
                <p class="text-orange-700">{{ $metrics->lowestSemester->name }}</p>
            </div>
            @endif
        </div>
        @endif
    @endif
</div>
@endsection
