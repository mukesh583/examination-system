@extends('admin.layouts.app')

@section('header', 'Subjects Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-4 items-center">
        <x-admin.search-bar 
            :action="route('admin.subjects.index')" 
            :value="request('search')"
            placeholder="Search by code, name, or department..." />
        
        <!-- Department Filter -->
        <form method="GET" action="{{ route('admin.subjects.index') }}" class="flex gap-2 items-center">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            
            <select name="department" 
                    onchange="this.form.submit()"
                    class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>
                        {{ $dept }}
                    </option>
                @endforeach
            </select>
            
            @if(request('department') || request('search'))
                <a href="{{ route('admin.subjects.index') }}" 
                   class="text-sm text-gray-600 hover:text-gray-900 underline">
                    Clear Filters
                </a>
            @endif
        </form>
    </div>
    <a href="{{ route('admin.subjects.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Create Subject
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credits</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Marks</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($subjects as $subject)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $subject->code }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $subject->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $subject->credits }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $subject->max_marks }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $subject->department }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('admin.subjects.show', $subject) }}" 
                       class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                    <a href="{{ route('admin.subjects.edit', $subject) }}" 
                       class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                    <button onclick="confirmDelete({{ $subject->id }})" 
                            class="text-red-600 hover:text-red-900">Delete</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    @if(request('search'))
                        No subjects found matching "{{ request('search') }}".
                    @else
                        No subjects found. Create your first subject to get started.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $subjects->links() }}
</div>

<!-- Delete Modal -->
<x-admin.delete-modal entity="subject" :route="route('admin.subjects.destroy', ':id')" />
@endsection
