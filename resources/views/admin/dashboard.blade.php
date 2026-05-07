@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')
@section('header', 'Dashboard Overview')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
    <!-- Total Students -->
    <a href="{{ route('admin.students.index') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 mr-4">
                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-800">{{ $totalStudents }}</p>
                <p class="text-gray-600 text-sm">Students</p>
            </div>
        </div>
    </a>

    <!-- Total Subjects -->
    <a href="{{ route('admin.subjects.index') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 mr-4">
                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-800">{{ $totalSubjects }}</p>
                <p class="text-gray-600 text-sm">Subjects</p>
            </div>
        </div>
    </a>

    <!-- Total Semesters -->
    <a href="{{ route('admin.semesters.index') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 mr-4">
                <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-800">{{ $totalSemesters }}</p>
                <p class="text-gray-600 text-sm">Semesters</p>
            </div>
        </div>
    </a>

    <!-- Total Results -->
    <a href="{{ route('admin.results.index') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 mr-4">
                <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-800">{{ $totalResults }}</p>
                <p class="text-gray-600 text-sm">Results</p>
            </div>
        </div>
    </a>

    <!-- Total Grade Scales -->
    <a href="{{ route('admin.grade-scales.index') }}" class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 mr-4">
                <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                </svg>
            </div>
            <div>
                <p class="text-3xl font-bold text-gray-800">{{ $totalGradeScales }}</p>
                <p class="text-gray-600 text-sm">Grade Scales</p>
            </div>
        </div>
    </a>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center mb-4">
        <svg class="w-6 h-6 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h2 class="text-xl font-bold text-gray-800">Recent Results</h2>
    </div>
    
    @if($recentResults->isEmpty())
        <p class="text-gray-400 text-center py-8 italic">No recent results</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Semester</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Marks</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($recentResults as $result)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $result->student->name }}</td>
                        <td class="px-6 py-4">{{ $result->subject->name }}</td>
                        <td class="px-6 py-4">{{ $result->semester->name }}</td>
                        <td class="px-6 py-4">{{ $result->marks_obtained }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded {{ $result->is_passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $result->grade->value }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $result->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
