@extends('admin.layouts.app')

@section('header', 'Subject Details')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">{{ $subject->code }} - {{ $subject->name }}</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.subjects.edit', $subject) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Edit
                    </a>
                    <a href="{{ route('admin.subjects.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Subject Code</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $subject->code }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Subject Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $subject->name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Credits</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $subject->credits }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Maximum Marks</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $subject->max_marks }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Department</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $subject->department }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $subject->created_at->format('F d, Y g:i A') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $subject->updated_at->format('F d, Y g:i A') }}</dd>
                </div>
            </dl>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Statistics</h3>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="bg-white p-4 rounded-md border border-gray-200">
                    <dt class="text-sm font-medium text-gray-500">Total Results</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $subject->results()->count() }}</dd>
                </div>
                <div class="bg-white p-4 rounded-md border border-gray-200">
                    <dt class="text-sm font-medium text-gray-500">Unique Students</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">{{ $subject->results()->distinct('student_id')->count('student_id') }}</dd>
                </div>
                <div class="bg-white p-4 rounded-md border border-gray-200">
                    <dt class="text-sm font-medium text-gray-500">Average Marks</dt>
                    <dd class="mt-1 text-2xl font-semibold text-gray-900">
                        {{ $subject->results()->count() > 0 ? number_format($subject->results()->avg('marks_obtained'), 2) : 'N/A' }}
                    </dd>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
