@extends('layouts.admin')

@section('content')
<div class="flex-1 p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Student Details</h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.students.edit', $student) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                    Edit Student
                </a>
                <a href="{{ route('admin.students.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Student Information Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-center mb-6">
                <div class="w-20 h-20 rounded-full bg-indigo-100 flex items-center justify-center mr-4">
                    <span class="text-indigo-600 font-bold text-3xl">{{ substr($student->name, 0, 1) }}</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $student->name }}</h2>
                    <p class="text-gray-600">{{ $student->email }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Student ID</h3>
                    <p class="text-lg text-gray-900">{{ $student->student_id }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Enrollment Year</h3>
                    <p class="text-lg text-gray-900">{{ $student->enrollment_year }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Program</h3>
                    <p class="text-lg text-gray-900">{{ $student->program }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Role</h3>
                    <p class="text-lg text-gray-900">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($student->role) }}
                        </span>
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Account Created</h3>
                    <p class="text-lg text-gray-900">{{ $student->created_at->format('F d, Y') }}</p>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Last Updated</h3>
                    <p class="text-lg text-gray-900">{{ $student->updated_at->format('F d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Results Summary Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Academic Summary</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-600 mb-1">Total Results</h4>
                    <p class="text-3xl font-bold text-blue-900">{{ $student->results()->count() }}</p>
                </div>

                <div class="bg-green-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-green-600 mb-1">Semesters</h4>
                    <p class="text-3xl font-bold text-green-900">{{ $student->results()->distinct('semester_id')->count('semester_id') }}</p>
                </div>

                <div class="bg-purple-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-purple-600 mb-1">Subjects</h4>
                    <p class="text-3xl font-bold text-purple-900">{{ $student->results()->distinct('subject_id')->count('subject_id') }}</p>
                </div>
            </div>

            @if($student->results()->count() > 0)
                <div class="mt-6">
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                        View Full Academic Records →
                    </a>
                </div>
            @else
                <div class="mt-6 text-gray-500">
                    No academic results recorded yet.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
