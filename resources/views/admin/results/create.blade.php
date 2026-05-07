@extends('admin.layouts.app')

@section('header', 'Create Result')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.results.store') }}" method="POST">
            @csrf

            <x-admin.form-select 
                name="student_id" 
                label="Student" 
                :options="$students->pluck('name', 'id')->toArray()"
                placeholder="Select a student"
                :required="true" />

            <x-admin.form-select 
                name="semester_id" 
                label="Semester" 
                :options="$semesters->pluck('name', 'id')->toArray()"
                placeholder="Select a semester"
                :required="true" />

            <x-admin.form-select 
                name="subject_id" 
                label="Subject" 
                :options="$subjects->pluck('name', 'id')->toArray()"
                placeholder="Select a subject"
                :required="true" />

            <x-admin.form-input 
                name="marks_obtained" 
                label="Marks Obtained" 
                type="number"
                placeholder="e.g., 85"
                :required="true" />

            <div class="mb-4">
                <p class="text-sm text-gray-600">
                    <strong>Note:</strong> Grade will be calculated automatically based on the marks obtained and the subject's maximum marks.
                </p>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.results.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Create Result
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
