# Requirements Document

## Introduction

This document specifies the requirements for a comprehensive CRUD (Create, Read, Update, Delete) management system for professors and administrators to manage the examination system. The system enables authorized users with admin role to perform full lifecycle management of semesters, subjects, students, results, and grade scales through a dedicated admin interface. The system ensures data integrity, provides search and filtering capabilities, handles large datasets with pagination, and maintains consistency with the existing Laravel-based examination management system's architecture and UI design patterns.

## Glossary

- **Admin_User**: A user account with 'admin' role (professor or administrator) authorized to manage examination system data
- **Student_User**: A user account with 'student' role who takes examinations and views results
- **CRUD_System**: The Create, Read, Update, Delete management interface for examination system entities
- **Semester_Entity**: An academic semester with name, academic year, start date, end date, and status
- **Subject_Entity**: An academic subject/course with code, name, credits, max marks, and department
- **Student_Entity**: A student account with name, email, student_id, enrollment_year, and program
- **Result_Entity**: An examination result linking student, semester, subject, marks, and grade
- **GradeScale_Entity**: A grading scale entry mapping marks range to grade letter and grade points
- **Admin_Dashboard**: The main navigation interface for accessing all CRUD management sections
- **Validation_System**: The input validation mechanism ensuring data integrity and business rules
- **Notification_System**: The feedback mechanism displaying success and error messages to users
- **Search_Filter_System**: The capability to search and filter records based on specific criteria
- **Pagination_System**: The mechanism to display large datasets across multiple pages
- **Authentication_System**: The existing Laravel authentication system verifying user identity
- **Authorization_System**: The middleware system enforcing admin role requirements
- **UI_Framework**: Tailwind CSS framework used for consistent styling
- **Database**: SQLite database storing all examination system data

## Requirements

### Requirement 1: Admin Authentication and Authorization

**User Story:** As a system administrator, I want only authorized admin users to access the CRUD management system, so that sensitive examination data remains secure and protected from unauthorized modifications.

#### Acceptance Criteria

1. WHEN an unauthenticated user attempts to access any CRUD management page, THE Authentication_System SHALL redirect them to the login page
2. WHEN an authenticated Student_User attempts to access any CRUD management page, THE Authorization_System SHALL return a 403 Forbidden error
3. WHEN an authenticated Admin_User accesses any CRUD management page, THE Authorization_System SHALL grant access and display the requested page
4. THE CRUD_System SHALL apply the admin middleware to all CRUD management routes
5. THE CRUD_System SHALL verify the user's role is 'admin' before processing any CRUD operation

### Requirement 2: Admin Dashboard Navigation

**User Story:** As an admin user, I want a centralized dashboard with clear navigation to all management sections, so that I can efficiently access different parts of the CRUD system.

#### Acceptance Criteria

1. THE CRUD_System SHALL display an Admin_Dashboard with navigation links to all five management sections (Semesters, Subjects, Students, Results, Grade Scales)
2. THE Admin_Dashboard SHALL display summary statistics for total counts of each entity type
3. THE Admin_Dashboard SHALL highlight the currently active navigation section
4. THE Admin_Dashboard SHALL use the UI_Framework for consistent styling with the existing application
5. WHEN an Admin_User clicks a navigation link, THE CRUD_System SHALL navigate to the corresponding management section

### Requirement 3: Semesters CRUD Operations

**User Story:** As an admin user, I want to create, read, update, and delete semesters, so that I can manage the academic calendar and semester lifecycle.

#### Acceptance Criteria

1. THE CRUD_System SHALL display a list of all Semester_Entity records with name, academic_year, start_date, end_date, and status
2. WHEN an Admin_User submits a create semester form, THE CRUD_System SHALL validate all required fields (name, academic_year, start_date, end_date, status) and create a new Semester_Entity
3. WHEN an Admin_User submits an update semester form, THE CRUD_System SHALL validate all fields and update the existing Semester_Entity
4. WHEN an Admin_User requests to delete a Semester_Entity, THE CRUD_System SHALL display a confirmation dialog before deletion
5. THE CRUD_System SHALL prevent deletion of a Semester_Entity that has associated Result_Entity records
6. THE Validation_System SHALL ensure start_date is before end_date
7. THE Validation_System SHALL ensure academic_year follows the format "YYYY-YYYY" (e.g., "2024-2025")
8. THE Validation_System SHALL ensure status is one of the valid SemesterStatusEnum values
9. THE Notification_System SHALL display success messages after successful create, update, or delete operations
10. THE Notification_System SHALL display error messages when validation fails or operations encounter errors

### Requirement 4: Subjects CRUD Operations

**User Story:** As an admin user, I want to create, read, update, and delete subjects, so that I can maintain the course catalog and subject information.

#### Acceptance Criteria

1. THE CRUD_System SHALL display a list of all Subject_Entity records with code, name, credits, max_marks, and department
2. WHEN an Admin_User submits a create subject form, THE CRUD_System SHALL validate all required fields (code, name, credits, max_marks, department) and create a new Subject_Entity
3. WHEN an Admin_User submits an update subject form, THE CRUD_System SHALL validate all fields and update the existing Subject_Entity
4. WHEN an Admin_User requests to delete a Subject_Entity, THE CRUD_System SHALL display a confirmation dialog before deletion
5. THE CRUD_System SHALL prevent deletion of a Subject_Entity that has associated Result_Entity records
6. THE Validation_System SHALL ensure subject code is unique across all subjects
7. THE Validation_System SHALL ensure credits is a positive integer between 1 and 10
8. THE Validation_System SHALL ensure max_marks is a positive integer between 1 and 1000
9. THE Notification_System SHALL display success messages after successful create, update, or delete operations
10. THE Notification_System SHALL display error messages when validation fails or operations encounter errors

### Requirement 5: Students CRUD Operations

**User Story:** As an admin user, I want to create, read, update, and delete student accounts, so that I can manage student enrollment and maintain accurate student records.

#### Acceptance Criteria

1. THE CRUD_System SHALL display a list of all Student_User records with name, email, student_id, enrollment_year, and program
2. WHEN an Admin_User submits a create student form, THE CRUD_System SHALL validate all required fields (name, email, student_id, enrollment_year, program, password) and create a new Student_User with role 'student'
3. WHEN an Admin_User submits an update student form, THE CRUD_System SHALL validate all fields and update the existing Student_User
4. WHEN an Admin_User requests to delete a Student_User, THE CRUD_System SHALL display a confirmation dialog before deletion
5. THE CRUD_System SHALL prevent deletion of a Student_User that has associated Result_Entity records
6. THE Validation_System SHALL ensure email is unique and follows valid email format
7. THE Validation_System SHALL ensure student_id is unique across all students
8. THE Validation_System SHALL ensure enrollment_year is a valid four-digit year between 1900 and current year plus 10
9. THE Validation_System SHALL ensure password meets minimum security requirements (at least 8 characters) when creating new students
10. THE CRUD_System SHALL hash passwords using Laravel's bcrypt before storing in the Database
11. THE Notification_System SHALL display success messages after successful create, update, or delete operations
12. THE Notification_System SHALL display error messages when validation fails or operations encounter errors

### Requirement 6: Results CRUD Operations

**User Story:** As an admin user, I want to create, read, update, and delete examination results, so that I can record and maintain student performance data.

#### Acceptance Criteria

1. THE CRUD_System SHALL display a list of all Result_Entity records with student name, semester name, subject name, marks_obtained, and grade
2. WHEN an Admin_User submits a create result form, THE CRUD_System SHALL validate all required fields (student_id, semester_id, subject_id, marks_obtained) and create a new Result_Entity
3. WHEN an Admin_User submits an update result form, THE CRUD_System SHALL validate all fields and update the existing Result_Entity
4. WHEN an Admin_User requests to delete a Result_Entity, THE CRUD_System SHALL display a confirmation dialog before deletion
5. THE CRUD_System SHALL automatically calculate and assign the grade based on marks_obtained using the GradeScale_Entity
6. THE CRUD_System SHALL automatically set is_passed flag based on whether the calculated grade is passing
7. THE Validation_System SHALL ensure the combination of student_id, semester_id, and subject_id is unique (no duplicate results)
8. THE Validation_System SHALL ensure marks_obtained is between 0 and the subject's max_marks value
9. THE Validation_System SHALL ensure student_id references an existing Student_User
10. THE Validation_System SHALL ensure semester_id references an existing Semester_Entity
11. THE Validation_System SHALL ensure subject_id references an existing Subject_Entity
12. THE Notification_System SHALL display success messages after successful create, update, or delete operations
13. THE Notification_System SHALL display error messages when validation fails or operations encounter errors

### Requirement 7: Grade Scales CRUD Operations

**User Story:** As an admin user, I want to create, read, update, and delete grade scale entries, so that I can configure and maintain the grading system used for result calculations.

#### Acceptance Criteria

1. THE CRUD_System SHALL display a list of all GradeScale_Entity records with grade, min_marks, max_marks, grade_point, and is_passing status
2. WHEN an Admin_User submits a create grade scale form, THE CRUD_System SHALL validate all required fields (grade, min_marks, max_marks, grade_point, is_passing) and create a new GradeScale_Entity
3. WHEN an Admin_User submits an update grade scale form, THE CRUD_System SHALL validate all fields and update the existing GradeScale_Entity
4. WHEN an Admin_User requests to delete a GradeScale_Entity, THE CRUD_System SHALL display a confirmation dialog before deletion
5. THE Validation_System SHALL ensure grade is unique across all grade scale entries
6. THE Validation_System SHALL ensure min_marks is less than or equal to max_marks
7. THE Validation_System SHALL ensure min_marks and max_marks are between 0 and 100 (percentage values)
8. THE Validation_System SHALL ensure grade_point is between 0.0 and 10.0
9. THE Validation_System SHALL ensure marks ranges do not overlap with existing GradeScale_Entity entries
10. THE Notification_System SHALL display success messages after successful create, update, or delete operations
11. THE Notification_System SHALL display error messages when validation fails or operations encounter errors

### Requirement 8: Search and Filter Capabilities

**User Story:** As an admin user, I want to search and filter records in each management section, so that I can quickly find specific entries in large datasets.

#### Acceptance Criteria

1. THE Search_Filter_System SHALL provide a search input field on each CRUD listing page
2. WHEN an Admin_User enters a search term for semesters, THE Search_Filter_System SHALL filter results by name or academic_year
3. WHEN an Admin_User enters a search term for subjects, THE Search_Filter_System SHALL filter results by code, name, or department
4. WHEN an Admin_User enters a search term for students, THE Search_Filter_System SHALL filter results by name, email, or student_id
5. WHEN an Admin_User enters a search term for results, THE Search_Filter_System SHALL filter results by student name, subject name, or semester name
6. WHEN an Admin_User enters a search term for grade scales, THE Search_Filter_System SHALL filter results by grade
7. THE Search_Filter_System SHALL perform case-insensitive partial matching on search terms
8. THE Search_Filter_System SHALL display "No results found" message when search returns zero records
9. WHERE a filter dropdown is provided, THE Search_Filter_System SHALL allow filtering by specific field values (e.g., semester status, department)
10. THE Search_Filter_System SHALL preserve search and filter parameters when navigating between pages

### Requirement 9: Pagination for Large Datasets

**User Story:** As an admin user, I want large lists of records to be paginated, so that pages load quickly and I can navigate through data efficiently.

#### Acceptance Criteria

1. THE Pagination_System SHALL display a maximum of 15 records per page on all CRUD listing pages
2. THE Pagination_System SHALL display pagination controls showing current page, total pages, and navigation links
3. WHEN an Admin_User clicks a page number, THE Pagination_System SHALL load and display the corresponding page of records
4. THE Pagination_System SHALL display "Previous" and "Next" navigation buttons
5. THE Pagination_System SHALL disable "Previous" button on the first page
6. THE Pagination_System SHALL disable "Next" button on the last page
7. THE Pagination_System SHALL display page numbers with ellipsis for large page counts (e.g., "1 2 3 ... 45 46")
8. THE Pagination_System SHALL preserve search and filter parameters when navigating between pages
9. THE Pagination_System SHALL display total record count on each listing page

### Requirement 10: Form Validation and User Feedback

**User Story:** As an admin user, I want clear validation messages and feedback for all form submissions, so that I understand what data is required and can correct errors easily.

#### Acceptance Criteria

1. THE Validation_System SHALL display inline error messages next to form fields that fail validation
2. THE Validation_System SHALL highlight invalid form fields with red borders or error styling
3. THE Validation_System SHALL preserve valid form input values when validation fails, requiring only correction of invalid fields
4. THE Notification_System SHALL display a success notification banner after successful create operations
5. THE Notification_System SHALL display a success notification banner after successful update operations
6. THE Notification_System SHALL display a success notification banner after successful delete operations
7. THE Notification_System SHALL display an error notification banner when operations fail due to database constraints
8. THE Notification_System SHALL display an error notification banner when operations fail due to system errors
9. THE Notification_System SHALL auto-dismiss success notifications after 5 seconds
10. THE Notification_System SHALL require manual dismissal of error notifications
11. THE Validation_System SHALL perform server-side validation for all form submissions
12. WHERE appropriate, THE Validation_System SHALL provide client-side validation for immediate feedback

### Requirement 11: Data Integrity and Referential Constraints

**User Story:** As a system administrator, I want the system to enforce referential integrity and prevent data inconsistencies, so that the examination database remains accurate and reliable.

#### Acceptance Criteria

1. THE CRUD_System SHALL prevent deletion of Semester_Entity records that have associated Result_Entity records
2. THE CRUD_System SHALL prevent deletion of Subject_Entity records that have associated Result_Entity records
3. THE CRUD_System SHALL prevent deletion of Student_User records that have associated Result_Entity records
4. WHEN an Admin_User attempts to delete a record with dependencies, THE Notification_System SHALL display an error message explaining the constraint violation
5. THE CRUD_System SHALL enforce unique constraints on email addresses in Student_User records
6. THE CRUD_System SHALL enforce unique constraints on student_id in Student_User records
7. THE CRUD_System SHALL enforce unique constraints on subject code in Subject_Entity records
8. THE CRUD_System SHALL enforce unique constraints on grade in GradeScale_Entity records
9. THE CRUD_System SHALL enforce unique constraints on the combination of student_id, semester_id, and subject_id in Result_Entity records
10. THE Validation_System SHALL verify foreign key references exist before creating or updating Result_Entity records

### Requirement 12: UI Consistency and Responsive Design

**User Story:** As an admin user, I want the CRUD interface to match the existing application's design and work on different screen sizes, so that I have a consistent and accessible user experience.

#### Acceptance Criteria

1. THE CRUD_System SHALL use the UI_Framework (Tailwind CSS) for all styling
2. THE CRUD_System SHALL use the same layout structure as existing application pages (header, navigation, content area)
3. THE CRUD_System SHALL use consistent button styles matching the existing application (primary, secondary, danger)
4. THE CRUD_System SHALL use consistent form input styles matching the existing application
5. THE CRUD_System SHALL use consistent table styles matching the existing application
6. THE CRUD_System SHALL be responsive and functional on desktop screen sizes (1024px and above)
7. THE CRUD_System SHALL be responsive and functional on tablet screen sizes (768px to 1023px)
8. THE CRUD_System SHALL be responsive and functional on mobile screen sizes (below 768px)
9. THE CRUD_System SHALL use consistent color scheme matching the existing application
10. THE CRUD_System SHALL use consistent typography matching the existing application

### Requirement 13: Bulk Operations and Data Management

**User Story:** As an admin user, I want efficient ways to manage multiple records, so that I can perform administrative tasks quickly when dealing with large datasets.

#### Acceptance Criteria

1. THE CRUD_System SHALL display action buttons (Edit, Delete) for each record in listing tables
2. THE CRUD_System SHALL provide a "Create New" button prominently displayed on each listing page
3. WHEN an Admin_User clicks Edit, THE CRUD_System SHALL load the edit form pre-populated with current values
4. WHEN an Admin_User clicks Delete, THE CRUD_System SHALL display a confirmation modal with record details
5. THE CRUD_System SHALL display record counts for each entity type on the Admin_Dashboard
6. THE CRUD_System SHALL sort listing tables by a default column (e.g., created_at descending or name ascending)
7. WHERE appropriate, THE CRUD_System SHALL provide column sorting capability on listing tables
8. THE CRUD_System SHALL display timestamps (created_at, updated_at) on detail and edit views
9. THE CRUD_System SHALL provide breadcrumb navigation showing current location in the admin interface
10. THE CRUD_System SHALL provide a "Cancel" button on all create and edit forms that returns to the listing page

### Requirement 14: Grade Calculation Integration

**User Story:** As an admin user, I want results to automatically calculate grades based on the grade scale, so that grading is consistent and I don't need to manually assign grades.

#### Acceptance Criteria

1. WHEN an Admin_User creates a Result_Entity with marks_obtained, THE CRUD_System SHALL query the GradeScale_Entity to find the matching grade
2. WHEN an Admin_User updates a Result_Entity's marks_obtained, THE CRUD_System SHALL recalculate and update the grade
3. THE CRUD_System SHALL set the grade field to the grade value from the matching GradeScale_Entity where min_marks <= marks_obtained <= max_marks
4. THE CRUD_System SHALL set the is_passed field to true if the matching GradeScale_Entity has is_passing = true
5. THE CRUD_System SHALL set the is_passed field to false if the matching GradeScale_Entity has is_passing = false
6. IF no matching GradeScale_Entity is found for the marks_obtained, THE CRUD_System SHALL display an error message and prevent result creation or update
7. THE CRUD_System SHALL display the calculated grade as read-only information on the result form
8. THE CRUD_System SHALL recalculate all affected Result_Entity grades when a GradeScale_Entity is updated
9. THE CRUD_System SHALL prevent deletion of GradeScale_Entity records that would leave Result_Entity records without valid grade mappings

### Requirement 15: Audit Trail and Activity Logging

**User Story:** As a system administrator, I want to track when records are created and modified, so that I can maintain accountability and troubleshoot data issues.

#### Acceptance Criteria

1. THE Database SHALL automatically record created_at timestamp when any entity is created
2. THE Database SHALL automatically record updated_at timestamp when any entity is modified
3. THE CRUD_System SHALL display created_at and updated_at timestamps on detail and edit views
4. THE CRUD_System SHALL format timestamps in a human-readable format (e.g., "Jan 15, 2024 10:30 AM")
5. WHERE a listing table includes timestamps, THE CRUD_System SHALL display them in a consistent format
6. THE CRUD_System SHALL use Laravel's built-in timestamp functionality for all entities
7. THE CRUD_System SHALL preserve timestamp accuracy across all CRUD operations

## Notes

### Implementation Considerations

- The system builds upon the existing Laravel examination management system
- All models (User, Semester, Subject, Result, GradeScale) already exist with appropriate relationships
- The EnsureUserIsAdmin middleware already exists and should be applied to all admin routes
- The system should follow Laravel best practices including Request validation classes, Resource Controllers, and Blade components
- Form submissions should use POST for create, PUT/PATCH for update, and DELETE for delete operations
- CSRF protection should be enabled for all forms
- The system should leverage Laravel's built-in pagination, validation, and flash messaging features

### Security Considerations

- All admin routes must be protected by authentication and admin authorization middleware
- Passwords must be hashed using bcrypt before storage
- SQL injection protection is provided by Laravel's Eloquent ORM
- CSRF tokens must be included in all forms
- Input validation must be performed server-side for all user inputs
- Error messages should not expose sensitive system information

### Performance Considerations

- Pagination should be used to limit query result sizes
- Eager loading should be used for relationships to avoid N+1 query problems
- Database indexes should exist on frequently queried columns (email, student_id, subject code)
- Search queries should use appropriate database indexes for performance

### Future Enhancements (Out of Scope)

- Bulk import/export functionality for CSV or Excel files
- Advanced reporting and analytics dashboards
- Email notifications for CRUD operations
- Audit log table tracking all changes with user attribution
- Soft deletes for data recovery
- Role-based permissions beyond admin/student (e.g., department admin, super admin)
- API endpoints for programmatic access to CRUD operations
