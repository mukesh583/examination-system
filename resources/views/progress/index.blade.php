@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Academic Progress</h1>
    </div>

    @if(isset($error))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ $error }}
        </div>
    @elseif(isset($metrics))
        <!-- Progress Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Cumulative GPA</h3>
                <p class="text-3xl font-bold text-indigo-600">{{ number_format($metrics->cgpa, 2) }}</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Total Credits Earned</h3>
                <p class="text-3xl font-bold text-green-600">{{ $metrics->totalCredits }}</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Pass Percentage</h3>
                <p class="text-3xl font-bold text-blue-600">{{ number_format($metrics->passPercentage, 1) }}%</p>
            </div>
        </div>

        <!-- Charts -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">SGPA Trend</h2>
            <div class="h-64">
                <canvas id="sgpaTrendChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Grade Distribution</h2>
                <div class="h-64">
                    <canvas id="gradeDistributionChart"></canvas>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Semester Comparison</h2>
                <div class="h-64">
                    <canvas id="semesterComparisonChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Semester Performance -->
        @if($metrics->highestSemester || $metrics->lowestSemester)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @if($metrics->highestSemester)
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-green-900 mb-2">Highest Performing Semester</h3>
                <p class="text-green-700 text-xl font-bold">{{ $metrics->highestSemester->name }}</p>
            </div>
            @endif

            @if($metrics->lowestSemester)
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-orange-900 mb-2">Lowest Performing Semester</h3>
                <p class="text-orange-700 text-xl font-bold">{{ $metrics->lowestSemester->name }}</p>
            </div>
            @endif
        </div>
        @endif
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch chart data from the API
    fetch('{{ route('progress.chart-data') }}')
        .then(response => response.json())
        .then(data => {
            // SGPA Trend Line Chart
            if (data.semesterTrends && Object.keys(data.semesterTrends).length > 0) {
                const sgpaCtx = document.getElementById('sgpaTrendChart').getContext('2d');
                const labels = Object.keys(data.semesterTrends);
                const values = Object.values(data.semesterTrends);
                
                new Chart(sgpaCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'SGPA',
                            data: values,
                            borderColor: 'rgb(79, 70, 229)',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 10,
                                title: {
                                    display: true,
                                    text: 'SGPA'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Semester'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'SGPA: ' + context.parsed.y.toFixed(2);
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Grade Distribution Pie Chart
            if (data.gradeDistribution && Object.keys(data.gradeDistribution).length > 0) {
                const gradeCtx = document.getElementById('gradeDistributionChart').getContext('2d');
                const labels = Object.keys(data.gradeDistribution);
                const values = Object.values(data.gradeDistribution);
                
                new Chart(gradeCtx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: values,
                            backgroundColor: [
                                'rgb(34, 197, 94)',   // A+ - Green
                                'rgb(59, 130, 246)',  // A - Blue
                                'rgb(168, 85, 247)',  // B - Purple
                                'rgb(251, 146, 60)',  // C - Orange
                                'rgb(251, 191, 36)',  // D - Yellow
                                'rgb(249, 115, 22)',  // E - Dark Orange
                                'rgb(239, 68, 68)'    // F - Red
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Semester Comparison Bar Chart
            if (data.semesterComparison && Object.keys(data.semesterComparison).length > 0) {
                const comparisonCtx = document.getElementById('semesterComparisonChart').getContext('2d');
                const labels = Object.keys(data.semesterComparison);
                const values = Object.values(data.semesterComparison);
                
                new Chart(comparisonCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Average Marks',
                            data: values,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                title: {
                                    display: true,
                                    text: 'Average Marks'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Semester'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Average: ' + context.parsed.y.toFixed(2);
                                    }
                                }
                            }
                        }
                    }
                });
            }
        })
        .catch(error => {
            console.error('Error loading chart data:', error);
        });
});
</script>
@endsection
