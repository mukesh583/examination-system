# User Guide - Examination Management System

Welcome to the Examination Management System! This guide will help you navigate and use all features of the application.

## Table of Contents

1. [Getting Started](#getting-started)
2. [Dashboard](#dashboard)
3. [Viewing Results](#viewing-results)
4. [Tracking Progress](#tracking-progress)
5. [Exporting Results](#exporting-results)
6. [Understanding Your Performance](#understanding-your-performance)
7. [Frequently Asked Questions](#frequently-asked-questions)

## Getting Started

### Logging In

1. Navigate to the application URL in your web browser
2. Enter your email address and password
3. Click the "Login" button
4. You will be redirected to your dashboard

### First Time Login

If this is your first time logging in:
- Your email address is typically your student email
- Your initial password may have been provided by your institution
- You should change your password after first login (if password change feature is enabled)

## Dashboard

The dashboard is your home page and provides a quick overview of your academic performance.

### Key Metrics

**CGPA (Cumulative Grade Point Average)**
- Your overall GPA across all completed semesters
- Calculated on a 10-point scale
- Updates automatically when new results are added

**Total Credits Earned**
- Sum of credits from all passed subjects
- Does not include credits from failed subjects
- Tracks your progress toward degree completion

**Semesters Completed**
- Number of semesters you have completed
- Helps track your academic timeline

**Pass Rate**
- Percentage of subjects you have passed
- Calculated as: (Passed Subjects / Total Subjects) × 100

### Performance Category

Your performance category is determined by your CGPA:

| Category | CGPA Range |
|----------|------------|
| Distinction | 7.5 - 10.0 |
| First Class | 6.0 - 7.49 |
| Second Class | 5.0 - 5.99 |
| Pass | 4.0 - 4.99 |

### Top and Bottom Subjects

- **Top Performing Subjects**: Your 3 highest-scoring subjects
- **Areas for Improvement**: Your 3 lowest-scoring subjects
- Use this information to identify strengths and weaknesses

### Outstanding Failed Subjects

If you have any failed subjects, they will be listed here with:
- Subject name and code
- Semester in which you failed
- These subjects need to be cleared

### Best and Worst Semesters

- **Best Semester**: The semester with your highest SGPA
- **Needs Improvement**: The semester with your lowest SGPA

## Viewing Results

### Semester List

Click "Results" in the navigation menu to see all your semesters.

Each semester card shows:
- Semester name (e.g., "Semester 1", "Semester 2")
- Academic year
- Current status (Current, Completed, or Upcoming)
- Number of subjects
- Total credits
- SGPA (Semester GPA)
- Number of passed and failed subjects

Click "View Results" on any semester card to see detailed results.

### Detailed Semester Results

When viewing a specific semester, you'll see:

**Semester Information**
- Semester name and academic year
- SGPA for the semester

**Results Table** (Desktop View)

The table displays:
- **Subject Code**: Unique identifier for the subject
- **Subject Name**: Full name of the subject
- **Credits**: Credit hours for the subject
- **Marks**: Your marks out of maximum marks
- **Grade**: Letter grade (A+, A, B, C, D, E, or F)
- **Status**: Passed or Failed

**Results Cards** (Mobile View)

On mobile devices, results are displayed as cards for better readability. Each card shows the same information as the table.

### Filtering and Searching

**Search Box**
- Type subject name or code to filter results in real-time
- Results update as you type (within 500ms)
- Search is case-insensitive

**Status Filter**
- **All**: Show all subjects
- **Passed**: Show only passed subjects
- **Failed**: Show only failed subjects

**Sort Options**
- **Subject Name**: Alphabetical order
- **Marks**: Numerical order by marks obtained
- **Grade**: Order by grade (A+ to F)

**Sort Order**
- **Ascending**: Low to high (A to Z, 0 to 100)
- **Descending**: High to low (Z to A, 100 to 0)

All filters work together and update results instantly.

## Tracking Progress

Click "Progress" in the navigation menu to access detailed analytics.

### Progress Summary

Three key metrics are displayed at the top:
- **Cumulative GPA**: Your overall CGPA
- **Total Credits Earned**: Credits from passed subjects
- **Pass Percentage**: Overall pass rate

### SGPA Trend Chart

A line chart showing your SGPA across all semesters:
- X-axis: Semester names
- Y-axis: SGPA (0-10 scale)
- Hover over points to see exact SGPA values
- Helps visualize your academic trajectory

### Grade Distribution Chart

A pie chart showing the distribution of your grades:
- Each slice represents a grade (A+, A, B, C, D, E, F)
- Percentages show the proportion of each grade
- Colors help distinguish between grades
- Hover to see exact counts and percentages

### Semester Comparison Chart

A bar chart comparing average marks across semesters:
- X-axis: Semester names
- Y-axis: Average marks (0-100 scale)
- Hover over bars to see exact averages
- Helps identify performance trends

### Best and Worst Semesters

Highlighted boxes show:
- **Highest Performing Semester**: Your best semester by SGPA
- **Lowest Performing Semester**: Your weakest semester by SGPA

## Exporting Results

You can export your semester results in two formats:

### PDF Export

1. Navigate to the semester you want to export
2. Click the "Export PDF" button (red button)
3. A PDF file will be generated and downloaded
4. The PDF includes:
   - Your student information
   - Semester details
   - Complete results table
   - SGPA and summary statistics
   - Generation date and time

**Use Cases for PDF:**
- Printing physical copies
- Sharing with family or advisors
- Official documentation
- Archival purposes

### CSV Export

1. Navigate to the semester you want to export
2. Click the "Export CSV" button (green button)
3. A CSV file will be downloaded
4. The CSV includes all result data in spreadsheet format

**Use Cases for CSV:**
- Opening in Excel or Google Sheets
- Data analysis
- Creating custom reports
- Importing into other systems

## Understanding Your Performance

### Grading System

The system uses a 10-point GPA scale:

| Grade | Marks Range | Grade Point | Status |
|-------|-------------|-------------|--------|
| A+ | 90-100 | 10.0 | Pass |
| A | 80-89 | 9.0 | Pass |
| B | 70-79 | 8.0 | Pass |
| C | 60-69 | 7.0 | Pass |
| D | 50-59 | 6.0 | Pass |
| E | 40-49 | 5.0 | Pass |
| F | 0-39 | 0.0 | Fail |

### GPA Calculation

**SGPA (Semester GPA)**

SGPA is calculated using a weighted average:

```
SGPA = (Sum of (Grade Point × Credits)) / (Total Credits)
```

Example:
- Subject 1: Grade A (9.0), Credits 3 → 9.0 × 3 = 27
- Subject 2: Grade B (8.0), Credits 4 → 8.0 × 4 = 32
- Subject 3: Grade A+ (10.0), Credits 3 → 10.0 × 3 = 30

SGPA = (27 + 32 + 30) / (3 + 4 + 3) = 89 / 10 = 8.9

**CGPA (Cumulative GPA)**

CGPA is calculated the same way but includes all semesters:

```
CGPA = (Sum of all (Grade Point × Credits)) / (Total Credits from all semesters)
```

**Important Notes:**
- Failed subjects (Grade F) are included with 0 grade points
- Only passed subjects contribute to earned credits
- GPA values are rounded to 2 decimal places

### Performance Indicators

**Green Indicators** (Good Performance)
- Passed status
- High grades (A+, A)
- CGPA above 7.5 (Distinction)

**Orange/Yellow Indicators** (Needs Attention)
- Average grades (D, E)
- CGPA between 5.0-6.0
- Declining SGPA trend

**Red Indicators** (Requires Improvement)
- Failed status
- Grade F
- CGPA below 5.0
- Multiple failed subjects

## Frequently Asked Questions

### General Questions

**Q: How often are results updated?**
A: Results are updated by your institution. Check with your academic office for result publication schedules.

**Q: Can I view results from previous years?**
A: Yes, all your historical results are available in the system.

**Q: Why can't I see another student's results?**
A: For privacy and security, you can only view your own results.

### Technical Questions

**Q: The page is loading slowly. What should I do?**
A: 
- Check your internet connection
- Clear your browser cache
- Try refreshing the page
- Contact support if the issue persists

**Q: Charts are not displaying. How do I fix this?**
A:
- Ensure JavaScript is enabled in your browser
- Try a different browser (Chrome, Firefox, Safari)
- Clear browser cache and reload

**Q: I'm offline. Can I still view my results?**
A:
- The system will display cached results when offline
- A yellow banner will indicate offline mode
- Some features may be limited offline
- Reconnect to internet for full functionality

**Q: The PDF export is not working. What should I do?**
A:
- Check if pop-ups are blocked in your browser
- Ensure you have enough storage space
- Try a different browser
- Contact support if the issue continues

### Account Questions

**Q: I forgot my password. How do I reset it?**
A: Contact your institution's IT support or academic office for password reset.

**Q: My email address has changed. How do I update it?**
A: Contact your institution's academic office to update your contact information.

**Q: Can I access the system from my mobile phone?**
A: Yes! The system is fully responsive and works on mobile phones, tablets, and computers.

### Results Questions

**Q: I think there's an error in my results. What should I do?**
A: Contact your academic office or examination department to report the discrepancy.

**Q: When will my current semester results be available?**
A: Results are published by your institution according to their academic calendar.

**Q: What does SGPA and CGPA mean?**
A:
- **SGPA**: Semester Grade Point Average - your GPA for one semester
- **CGPA**: Cumulative Grade Point Average - your overall GPA across all semesters

**Q: How is my pass percentage calculated?**
A: Pass Percentage = (Number of Passed Subjects / Total Subjects) × 100

**Q: Do failed subjects affect my CGPA?**
A: Yes, failed subjects are included in CGPA calculation with 0 grade points, which lowers your CGPA.

**Q: How many credits do I need to graduate?**
A: Check with your academic advisor or refer to your program's curriculum requirements.

### Export Questions

**Q: Can I export results for multiple semesters at once?**
A: Currently, you can export one semester at a time. Export each semester separately if needed.

**Q: What's the difference between PDF and CSV export?**
A:
- **PDF**: Formatted document, good for printing and official use
- **CSV**: Spreadsheet format, good for data analysis in Excel

**Q: Can I customize the PDF format?**
A: The PDF format is standardized. Contact your institution if you need a different format.

## Tips for Success

1. **Check Regularly**: Log in regularly to stay updated on your academic progress

2. **Use Filters**: Use search and filter features to quickly find specific subjects

3. **Monitor Trends**: Pay attention to your SGPA trend chart to identify patterns

4. **Address Failures**: Focus on clearing any failed subjects as soon as possible

5. **Set Goals**: Use your current CGPA to set realistic improvement goals

6. **Export Records**: Regularly export and save your results for your records

7. **Seek Help**: If your performance is declining, seek help from advisors or tutors

8. **Mobile Access**: Use the mobile app for quick checks on the go

## Accessibility Features

The system includes several accessibility features:

- **Keyboard Navigation**: Navigate using Tab, Enter, and Arrow keys
- **Screen Reader Support**: Compatible with NVDA, JAWS, and other screen readers
- **High Contrast**: Text and backgrounds meet WCAG contrast requirements
- **Responsive Text**: Text sizes adjust for readability on all devices
- **ARIA Labels**: All interactive elements have descriptive labels

## Getting Help

If you need assistance:

1. **Technical Issues**: Contact IT support at support@example.com
2. **Result Queries**: Contact the examination department
3. **Account Issues**: Contact the academic office
4. **General Questions**: Refer to this user guide

## System Requirements

**Supported Browsers:**
- Google Chrome (recommended)
- Mozilla Firefox
- Safari
- Microsoft Edge

**Minimum Requirements:**
- Internet connection
- JavaScript enabled
- Cookies enabled
- Modern browser (updated within last 2 years)

**Recommended:**
- Broadband internet connection
- Desktop or laptop for best experience
- Updated browser version

---

Thank you for using the Examination Management System! We hope this guide helps you make the most of the application.
