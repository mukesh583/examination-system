@extends('admin.layouts.app')

@section('header', 'Create Subject')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.subjects.store') }}" method="POST">
            @csrf

            <x-admin.form-input 
                name="code" 
                label="Subject Code" 
                placeholder="e.g., CS101, MATH201"
                :required="true" />

            <x-admin.form-input 
                name="name" 
                label="Subject Name" 
                placeholder="e.g., Introduction to Computer Science"
                :required="true" />

            <x-admin.form-input 
                name="credits" 
                label="Credits" 
                type="number"
                placeholder="e.g., 3"
                :required="true"
                min="1"
                max="10" />

            <x-admin.form-input 
                name="max_marks" 
                label="Maximum Marks" 
                type="number"
                placeholder="e.g., 100"
                :required="true"
                min="1"
                max="1000" />

            <x-admin.form-input 
                name="department" 
                label="Department" 
                placeholder="e.g., Computer Science, Mathematics"
                :required="true" />

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.subjects.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Create Subject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
