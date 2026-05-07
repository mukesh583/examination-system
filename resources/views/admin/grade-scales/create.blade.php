@extends('admin.layouts.app')

@section('header', 'Create Grade Scale')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.grade-scales.store') }}" method="POST">
            @csrf

            <x-admin.form-input 
                name="grade" 
                label="Grade" 
                placeholder="e.g., A+, B, F"
                :required="true" />

            <x-admin.form-input 
                name="min_marks" 
                label="Minimum Marks" 
                type="number"
                placeholder="e.g., 80"
                :required="true"
                min="0"
                max="100"
                step="0.01" />

            <x-admin.form-input 
                name="max_marks" 
                label="Maximum Marks" 
                type="number"
                placeholder="e.g., 100"
                :required="true"
                min="0"
                max="100"
                step="0.01" />

            <x-admin.form-input 
                name="grade_point" 
                label="Grade Point" 
                type="number"
                placeholder="e.g., 4.00"
                :required="true"
                min="0"
                max="10"
                step="0.01" />

            <div class="mb-4">
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="is_passing" 
                        id="is_passing"
                        value="1"
                        {{ old('is_passing') ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Is Passing Grade</span>
                </label>
                @error('is_passing')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.grade-scales.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Create Grade Scale
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
