@extends('admin.layouts.app')

@section('header', 'Result Details')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Result Details</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.results.edit', $result) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Edit
                    </a>
                    <form action="{{ route('admin.results.destroy', $result) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this result?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Delete
                        </button>
                    </form>
                    <a href="{{ route('admin.results.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Student Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $result->student->name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Student ID</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $result->student->student_id }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Semester</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $result->semester->name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Academic Year</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $result->semester->academic_year }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Subject</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $result->subject->name }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Subject Code</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $result->subject->code }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Marks Obtained</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        {{ $result->marks_obtained }} / {{ $result->subject->max_marks }}
                        <span class="text-gray-500">({{ number_format($result->percentage, 2) }}%)</span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Grade</dt>
                    <dd class="mt-1">
                        @php
                            $gradeColor = $result->is_passed 
                                ? 'bg-green-100 text-green-800' 
                                : 'bg-red-100 text-red-800';
                        @endphp
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $gradeColor }}">
                            {{ $result->grade->value }}
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        @if($result->is_passed)
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Passed
                            </span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Failed
                            </span>
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Credits</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $result->subject->credits }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $result->created_at->format('F d, Y g:i A') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $result->updated_at->format('F d, Y g:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
