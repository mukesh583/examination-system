@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $semester->name }}</h1>
            <p class="text-gray-600 mt-1">{{ $semester->academic_year }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('export.pdf', $semester) }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
                Export PDF
            </a>
            <a href="{{ route('export.csv', $semester) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded">
                Export CSV
            </a>
        </div>
    </div>

    <!-- Filters with Alpine.js -->
    <div class="bg-white shadow rounded-lg p-4" x-data="resultFilters()">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search with real-time filtering -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input 
                    type="text" 
                    x-model="search"
                    @input.debounce.500ms="filterResults()"
                    placeholder="Subject name or code"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    aria-label="Search subjects by name or code"
                >
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select 
                    x-model="status"
                    @change="filterResults()"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    aria-label="Filter by pass/fail status"
                >
                    <option value="all">All</option>
                    <option value="passed">Passed</option>
                    <option value="failed">Failed</option>
                </select>
            </div>

            <!-- Sort By -->
            <div>
                <label for="sort_by" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                <select 
                    x-model="sortBy"
                    @change="filterResults()"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    aria-label="Sort results by field"
                >
                    <option value="">Default</option>
                    <option value="subject_name">Subject Name</option>
                    <option value="marks">Marks</option>
                    <option value="grade">Grade</option>
                </select>
            </div>

            <!-- Sort Order -->
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
                <select 
                    x-model="sortOrder"
                    @change="filterResults()"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    aria-label="Sort order"
                >
                    <option value="asc">Ascending</option>
                    <option value="desc">Descending</option>
                </select>
            </div>
        </div>

        <!-- Loading indicator -->
        <div x-show="loading" class="mt-4 text-center">
            <span class="text-sm text-gray-600">Loading...</span>
        </div>
    </div>

    <!-- SGPA Display -->
    @if(isset($sgpa) && $sgpa > 0)
    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
        <div class="flex justify-between items-center">
            <span class="text-lg font-semibold text-indigo-900">Semester GPA (SGPA)</span>
            <span class="text-3xl font-bold text-indigo-600">{{ number_format($sgpa, 2) }}</span>
        </div>
    </div>
    @endif

    <!-- Results Table -->
    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded" role="alert">
            {{ $error }}
        </div>
    @elseif(isset($results) && $results->isNotEmpty())
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject Code</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Credits</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marks</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="results-tbody">
                        @foreach($results as $result)
                        <tr class="hover:bg-gray-50 result-row" 
                            data-subject-code="{{ $result->subject->code }}"
                            data-subject-name="{{ $result->subject->name }}"
                            data-marks="{{ $result->marks_obtained }}"
                            data-grade="{{ $result->grade->value }}"
                            data-status="{{ $result->is_passed ? 'passed' : 'failed' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $result->subject->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $result->subject->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $result->subject->credits }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($result->marks_obtained, 2) }} / {{ $result->subject->max_marks }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $result->grade->value }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($result->is_passed)
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Passed</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div class="md:hidden space-y-4 p-4" id="results-cards">
                @foreach($results as $result)
                <div class="border border-gray-200 rounded-lg p-4 result-card"
                     data-subject-code="{{ $result->subject->code }}"
                     data-subject-name="{{ $result->subject->name }}"
                     data-marks="{{ $result->marks_obtained }}"
                     data-grade="{{ $result->grade->value }}"
                     data-status="{{ $result->is_passed ? 'passed' : 'failed' }}">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900 text-base">{{ $result->subject->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $result->subject->code }}</p>
                        </div>
                        @if($result->is_passed)
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Passed</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                        @endif
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-600">Credits:</span>
                            <span class="font-medium text-gray-900">{{ $result->subject->credits }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Grade:</span>
                            <span class="font-semibold text-gray-900">{{ $result->grade->value }}</span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-600">Marks:</span>
                            <span class="font-medium text-gray-900">{{ number_format($result->marks_obtained, 2) }} / {{ $result->subject->max_marks }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <p class="text-gray-600">No results available for this semester.</p>
        </div>
    @endif
</div>

<script>
function resultFilters() {
    return {
        search: '{{ request('search') }}',
        status: '{{ request('status', 'all') }}',
        sortBy: '{{ request('sort_by') }}',
        sortOrder: '{{ request('sort_order', 'asc') }}',
        loading: false,

        filterResults() {
            this.loading = true;
            
            // Get all result rows and cards
            const rows = document.querySelectorAll('.result-row');
            const cards = document.querySelectorAll('.result-card');
            
            // Filter logic
            const searchLower = this.search.toLowerCase();
            
            [...rows, ...cards].forEach(element => {
                const subjectCode = element.dataset.subjectCode.toLowerCase();
                const subjectName = element.dataset.subjectName.toLowerCase();
                const resultStatus = element.dataset.status;
                
                // Check search match
                const matchesSearch = !this.search || 
                    subjectCode.includes(searchLower) || 
                    subjectName.includes(searchLower);
                
                // Check status filter
                const matchesStatus = this.status === 'all' || resultStatus === this.status;
                
                // Show/hide element
                if (matchesSearch && matchesStatus) {
                    element.style.display = '';
                } else {
                    element.style.display = 'none';
                }
            });
            
            // Apply sorting
            if (this.sortBy) {
                this.sortResults(rows, cards);
            }
            
            this.loading = false;
        },

        sortResults(rows, cards) {
            const sortRows = (elements, container) => {
                const elementsArray = Array.from(elements);
                
                elementsArray.sort((a, b) => {
                    let aValue, bValue;
                    
                    switch(this.sortBy) {
                        case 'subject_name':
                            aValue = a.dataset.subjectName;
                            bValue = b.dataset.subjectName;
                            break;
                        case 'marks':
                            aValue = parseFloat(a.dataset.marks);
                            bValue = parseFloat(b.dataset.marks);
                            break;
                        case 'grade':
                            aValue = a.dataset.grade;
                            bValue = b.dataset.grade;
                            break;
                        default:
                            return 0;
                    }
                    
                    if (typeof aValue === 'string') {
                        return this.sortOrder === 'asc' 
                            ? aValue.localeCompare(bValue)
                            : bValue.localeCompare(aValue);
                    } else {
                        return this.sortOrder === 'asc' 
                            ? aValue - bValue
                            : bValue - aValue;
                    }
                });
                
                elementsArray.forEach(element => container.appendChild(element));
            };
            
            if (rows.length > 0) {
                sortRows(rows, document.getElementById('results-tbody'));
            }
            if (cards.length > 0) {
                sortRows(cards, document.getElementById('results-cards'));
            }
        }
    };
}
</script>
@endsection
