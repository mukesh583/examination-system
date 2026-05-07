@extends('admin.layouts.app')

@section('header', 'Grade Scales Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <x-admin.search-bar 
            :action="route('admin.grade-scales.index')" 
            :value="request('search')"
            placeholder="Search by grade..." />
    </div>
    <a href="{{ route('admin.grade-scales.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Create New Grade Scale
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Marks</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Marks</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade Point</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($gradeScales as $gradeScale)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $gradeScale->grade }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $gradeScale->min_marks }}%
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $gradeScale->max_marks }}%
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $gradeScale->grade_point }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($gradeScale->is_passing)
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Passing
                        </span>
                    @else
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            Failing
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('admin.grade-scales.show', $gradeScale) }}" 
                       class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                    <a href="{{ route('admin.grade-scales.edit', $gradeScale) }}" 
                       class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                    <button onclick="confirmDelete({{ $gradeScale->id }})" 
                            class="text-red-600 hover:text-red-900">Delete</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    @if(request('search'))
                        No grade scales found matching "{{ request('search') }}".
                    @else
                        No grade scales found. Create your first grade scale to get started.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $gradeScales->links() }}
</div>

<!-- Delete Modal -->
<x-admin.delete-modal entity="grade scale" :route="route('admin.grade-scales.destroy', ':id')" />
@endsection
