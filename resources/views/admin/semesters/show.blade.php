@extends('admin.layouts.app')

@section('header', 'Semester Details')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">{{ $semester->name }}</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.semesters.edit', $semester) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Edit
                    </a>
                    <a href="{{ route('admin.semesters.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Semester Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $semester->name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Academic Year</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $semester->academic_year }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $semester->start_date->format('F d, Y') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">End Date</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $semester->end_date->format('F d, Y') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        @php
                            $statusColors = [
                                'upcoming' => 'bg-blue-100 text-blue-800',
                                'current' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-gray-100 text-gray-800',
                            ];
                            $color = $statusColors[$semester->status->value] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $color }}">
                            {{ ucfirst($semester->status->value) }}
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Duration</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $semester->start_date->diffInDays($semester->end_date) }} days
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $semester->created_at->format('F d, Y g:i A') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $semester->updated_at->format('F d, Y g:i A') }}</dd>
                </div>
            </dl>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Statistics</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="bg-white p-4 rounded-md border border-gray-200">
                    <dt class="text-sm font-medium text-gray-500">Total Results</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $semester->results()->count() }}</dd>
                </div>
                <div class="bg-white p-4 rounded-md border border-gray-200">
                    <dt class="text-sm font-medium text-gray-500">Unique Students</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $semester->results()->distinct('student_id')->count('student_id') }}</dd>
                </div>
                <div class="bg-white p-4 rounded-md border border-gray-200">
                    <dt class="text-sm font-medium text-gray-500">Unique Subjects</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $semester->results()->distinct('subject_id')->count('subject_id') }}</dd>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
