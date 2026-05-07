@extends('admin.layouts.app')

@section('header', 'Grade Scale Details')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Grade {{ $gradeScale->grade }}</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.grade-scales.edit', $gradeScale) }}" 
                       class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Edit
                    </a>
                    <form action="{{ route('admin.grade-scales.destroy', $gradeScale) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this grade scale entry?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                            Delete
                        </button>
                    </form>
                    <a href="{{ route('admin.grade-scales.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Grade</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $gradeScale->grade }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        @if($gradeScale->is_passing)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Passing
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Failing
                            </span>
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Minimum Marks</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $gradeScale->min_marks }}%</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Maximum Marks</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $gradeScale->max_marks }}%</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Grade Point</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($gradeScale->grade_point, 1) }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Marks Range</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $gradeScale->min_marks }}% - {{ $gradeScale->max_marks }}%</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $gradeScale->created_at->format('F d, Y g:i A') }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $gradeScale->updated_at->format('F d, Y g:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
