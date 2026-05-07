@extends('admin.layouts.app')

@section('header', 'Edit Semester')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.semesters.update', $semester) }}" method="POST">
            @csrf
            @method('PUT')

            <x-admin.form-input 
                name="name" 
                label="Semester Name" 
                :value="$semester->name"
                placeholder="e.g., Fall 2024, Spring 2025"
                :required="true" />

            <x-admin.form-input 
                name="academic_year" 
                label="Academic Year" 
                :value="$semester->academic_year"
                placeholder="e.g., 2024-2025"
                :required="true" />

            <x-admin.form-input 
                name="start_date" 
                label="Start Date" 
                type="date"
                :value="$semester->start_date->format('Y-m-d')"
                :required="true" />

            <x-admin.form-input 
                name="end_date" 
                label="End Date" 
                type="date"
                :value="$semester->end_date->format('Y-m-d')"
                :required="true" />

            <x-admin.form-select 
                name="status" 
                label="Status"
                :options="[
                    'upcoming' => 'Upcoming',
                    'current' => 'Current',
                    'completed' => 'Completed'
                ]"
                :selected="$semester->status->value"
                :required="true"
                placeholder="Select status" />

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.semesters.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update Semester
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
