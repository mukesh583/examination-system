@props([
    'action',
    'value' => '',
    'placeholder' => 'Search...'
])

<form method="GET" action="{{ $action }}" class="flex items-center">
    <input 
        type="text" 
        name="search" 
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-r-md hover:bg-gray-700 transition">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
        </svg>
    </button>
</form>
