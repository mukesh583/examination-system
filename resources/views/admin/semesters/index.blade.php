@extends('admin.layouts.app')

@section('header', 'Semesters Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-4 items-center">
        <x-admin.search-bar 
            :action="route('admin.semesters.index')" 
            :value="request('search')"
            placeholder="Search by name or academic year..." />
        
        <!-- Status Filter -->
        <form method="GET" action="{{ route('admin.semesters.index') }}" class="flex gap-2 items-center">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            
            <select name="status" 
                    onchange="this.form.submit()"
                    class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">All Statuses</option>
                <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="current" {{ request('status') === 'current' ? 'selected' : '' }}>Current</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            
            @if(request('status') || request('search'))
                <a href="{{ route('admin.semesters.index') }}" 
                   class="text-sm text-gray-600 hover:text-gray-900 underline">
                    Clear Filters
                </a>
            @endif
        </form>
    </div>
    <a href="{{ route('admin.semesters.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Create Semester
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($semesters as $semester)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $semester->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $semester->academic_year }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $semester->start_date->format('M d, Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    {{ $semester->end_date->format('M d, Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
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
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="{{ route('admin.semesters.show', $semester) }}" 
                       class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                    <a href="{{ route('admin.semesters.edit', $semester) }}" 
                       class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                    <button onclick="confirmDelete({{ $semester->id }})" 
                            class="text-red-600 hover:text-red-900">Delete</button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    @if(request('search'))
                        No semesters found matching "{{ request('search') }}".
                    @else
                        No semesters found. Create your first semester to get started.
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $semesters->links() }}
</div>

<!-- Delete Modal -->
<x-admin.delete-modal entity="semester" :route="route('admin.semesters.destroy', ':id')" />
@endsection
