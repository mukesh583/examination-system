<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Examination Results - {{ $semester->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #4F46E5;
        }
        .header h1 {
            color: #4F46E5;
            font-size: 24px;
            margin: 0 0 10px 0;
        }
        .header h2 {
            color: #666;
            font-size: 16px;
            margin: 0;
            font-weight: normal;
        }
        .student-info {
            margin-bottom: 20px;
            background-color: #F3F4F6;
            padding: 15px;
            border-radius: 5px;
        }
        .student-info table {
            width: 100%;
        }
        .student-info td {
            padding: 5px 0;
        }
        .student-info td:first-child {
            font-weight: bold;
            width: 150px;
        }
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .results-table th {
            background-color: #4F46E5;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        .results-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #E5E7EB;
        }
        .results-table tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        .status-passed {
            color: #059669;
            font-weight: bold;
        }
        .status-failed {
            color: #DC2626;
            font-weight: bold;
        }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #EEF2FF;
            border-left: 4px solid #4F46E5;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            color: #4F46E5;
        }
        .summary-item {
            display: inline-block;
            margin-right: 30px;
            margin-bottom: 10px;
        }
        .summary-label {
            font-weight: bold;
            color: #666;
        }
        .summary-value {
            font-size: 18px;
            color: #4F46E5;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 10px;
            color: #9CA3AF;
        }
        .grade-excellent {
            color: #059669;
            font-weight: bold;
        }
        .grade-good {
            color: #3B82F6;
            font-weight: bold;
        }
        .grade-average {
            color: #F59E0B;
            font-weight: bold;
        }
        .grade-poor {
            color: #DC2626;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>Examination Management System</h1>
        <h2>Academic Results Report</h2>
    </div>

    <!-- Student Information -->
    <div class="student-info">
        <table>
            <tr>
                <td>Student Name:</td>
                <td>{{ $student->name }}</td>
            </tr>
            <tr>
                <td>Student ID:</td>
                <td>{{ $student->student_id }}</td>
            </tr>
            <tr>
                <td>Program:</td>
                <td>{{ $student->program }}</td>
            </tr>
            <tr>
                <td>Semester:</td>
                <td>{{ $semester->name }}</td>
            </tr>
            <tr>
                <td>Academic Year:</td>
                <td>{{ $semester->academic_year }}</td>
            </tr>
        </table>
    </div>

    <!-- Results Table -->
    <table class="results-table">
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Credits</th>
                <th>Marks Obtained</th>
                <th>Max Marks</th>
                <th>Percentage</th>
                <th>Grade</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $result)
            <tr>
                <td>{{ $result->subject->code }}</td>
                <td>{{ $result->subject->name }}</td>
                <td>{{ $result->subject->credits }}</td>
                <td>{{ number_format($result->marks_obtained, 2) }}</td>
                <td>{{ $result->subject->max_marks }}</td>
                <td>{{ number_format(($result->marks_obtained / $result->subject->max_marks) * 100, 2) }}%</td>
                <td class="@if($result->grade->value === 'A+' || $result->grade->value === 'A') grade-excellent @elseif($result->grade->value === 'B' || $result->grade->value === 'C') grade-good @elseif($result->grade->value === 'D' || $result->grade->value === 'E') grade-average @else grade-poor @endif">
                    {{ $result->grade->value }}
                </td>
                <td class="@if($result->is_passed) status-passed @else status-failed @endif">
                    {{ $result->is_passed ? 'PASSED' : 'FAILED' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary -->
    <div class="summary">
        <h3>Semester Summary</h3>
        <div class="summary-item">
            <span class="summary-label">SGPA:</span>
            <span class="summary-value">{{ number_format($sgpa, 2) }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Subjects:</span>
            <span class="summary-value">{{ $results->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Passed:</span>
            <span class="summary-value" style="color: #059669;">{{ $results->where('is_passed', true)->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Failed:</span>
            <span class="summary-value" style="color: #DC2626;">{{ $results->where('is_passed', false)->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Credits:</span>
            <span class="summary-value">{{ $results->sum(fn($r) => $r->subject->credits) }}</span>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Generated on: {{ $generatedAt->format('F d, Y \a\t h:i A') }}</p>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>&copy; {{ date('Y') }} Examination Management System. All rights reserved.</p>
    </div>
</body>
</html>
