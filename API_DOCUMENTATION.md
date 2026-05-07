# API Documentation - Examination Management System

This document describes the internal API endpoints used by the Examination Management System. These endpoints are currently used by the frontend and can be extended for mobile app integration in the future.

## Base URL

```
https://your-domain.com
```

## Authentication

All API endpoints require authentication using Laravel Sanctum session-based authentication.

### Authentication Headers

```
Accept: application/json
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}
```

## Endpoints

### 1. Progress Chart Data

Retrieve chart data for progress visualization.

**Endpoint:** `GET /progress/chart-data`

**Authentication:** Required

**Response:**

```json
{
  "semesterTrends": {
    "Semester 1": 8.5,
    "Semester 2": 8.7,
    "Semester 3": 9.0
  },
  "gradeDistribution": {
    "A+": 5,
    "A": 8,
    "B": 6,
    "C": 3,
    "D": 1,
    "E": 0,
    "F": 1
  },
  "semesterComparison": {
    "Semester 1": 82.5,
    "Semester 2": 85.3,
    "Semester 3": 87.8
  }
}
```

**Response Fields:**

- `semesterTrends`: Object mapping semester names to SGPA values
- `gradeDistribution`: Object mapping grade letters to count of subjects
- `semesterComparison`: Object mapping semester names to average marks

**Error Response:**

```json
{
  "error": "Unable to load chart data."
}
```

**Status Codes:**
- `200 OK`: Success
- `401 Unauthorized`: Not authenticated
- `500 Internal Server Error`: Server error

---

### 2. Search Results

Search for subjects by name or code.

**Endpoint:** `GET /results/search`

**Authentication:** Required

**Query Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| query | string | Yes | Search term for subject name or code |

**Example Request:**

```
GET /results/search?query=mathematics
```

**Response:**

```json
[
  {
    "id": 1,
    "subject_code": "MATH101",
    "subject_name": "Mathematics I",
    "marks_obtained": 85.5,
    "grade": "A",
    "is_passed": true,
    "semester_name": "Semester 1"
  },
  {
    "id": 15,
    "subject_code": "MATH201",
    "subject_name": "Mathematics II",
    "marks_obtained": 78.0,
    "grade": "B",
    "is_passed": true,
    "semester_name": "Semester 2"
  }
]
```

**Response Fields:**

- `id`: Result ID
- `subject_code`: Subject code
- `subject_name`: Subject name
- `marks_obtained`: Marks scored
- `grade`: Letter grade
- `is_passed`: Boolean indicating pass/fail
- `semester_name`: Semester name

**Error Response:**

```json
{
  "error": "Search failed. Please try again."
}
```

**Status Codes:**
- `200 OK`: Success (returns empty array if no results)
- `401 Unauthorized`: Not authenticated
- `500 Internal Server Error`: Server error

---

## Future API Endpoints

The following endpoints are planned for future mobile app integration:

### Authentication

#### Login

```
POST /api/login
```

**Request Body:**

```json
{
  "email": "student@example.com",
  "password": "password"
}
```

**Response:**

```json
{
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "student@example.com",
    "student_id": "STU001"
  }
}
```

#### Logout

```
POST /api/logout
```

**Headers:**

```
Authorization: Bearer {token}
```

---

### Dashboard

#### Get Dashboard Metrics

```
GET /api/dashboard
```

**Response:**

```json
{
  "cgpa": 8.75,
  "total_credits": 90,
  "completed_semesters": 3,
  "pass_percentage": 95.8,
  "performance_category": "Distinction",
  "top_subjects": [...],
  "bottom_subjects": [...],
  "failed_subjects": [...],
  "highest_semester": {...},
  "lowest_semester": {...}
}
```

---

### Results

#### Get All Semesters

```
GET /api/semesters
```

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "name": "Semester 1",
      "academic_year": "2023-2024",
      "status": "completed",
      "sgpa": 8.5,
      "subject_count": 8,
      "total_credits": 24,
      "passed_count": 8,
      "failed_count": 0
    }
  ]
}
```

#### Get Semester Results

```
GET /api/semesters/{id}/results
```

**Query Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| search | string | No | Search term |
| status | string | No | Filter by status (all, passed, failed) |
| sort_by | string | No | Sort field (subject_name, marks, grade) |
| sort_order | string | No | Sort order (asc, desc) |

**Response:**

```json
{
  "semester": {...},
  "sgpa": 8.5,
  "results": [
    {
      "id": 1,
      "subject": {
        "code": "CS101",
        "name": "Computer Science I",
        "credits": 3,
        "max_marks": 100
      },
      "marks_obtained": 85.5,
      "grade": "A",
      "is_passed": true,
      "percentage": 85.5
    }
  ]
}
```

---

### Progress

#### Get Progress Metrics

```
GET /api/progress
```

**Response:**

```json
{
  "cgpa": 8.75,
  "total_credits": 90,
  "completed_semesters": 3,
  "pass_percentage": 95.8,
  "highest_semester": {...},
  "lowest_semester": {...},
  "failed_subjects": [...]
}
```

#### Get Chart Data

```
GET /api/progress/charts
```

**Response:**

```json
{
  "semester_trends": {...},
  "grade_distribution": {...},
  "semester_comparison": {...}
}
```

---

### Export

#### Export to PDF

```
GET /api/export/pdf/{semester_id}
```

**Response:** Binary PDF file

**Headers:**

```
Content-Type: application/pdf
Content-Disposition: attachment; filename="results_semester_1.pdf"
```

#### Export to CSV

```
GET /api/export/csv/{semester_id}
```

**Response:** CSV file

**Headers:**

```
Content-Type: text/csv
Content-Disposition: attachment; filename="results_semester_1.csv"
```

---

## Error Handling

All API endpoints follow a consistent error response format:

### Error Response Format

```json
{
  "error": "Error type",
  "message": "Detailed error message",
  "details": {
    "field": "Additional error details"
  }
}
```

### Common Error Codes

| Status Code | Description |
|-------------|-------------|
| 400 | Bad Request - Invalid input |
| 401 | Unauthorized - Authentication required |
| 403 | Forbidden - Insufficient permissions |
| 404 | Not Found - Resource doesn't exist |
| 422 | Unprocessable Entity - Validation failed |
| 429 | Too Many Requests - Rate limit exceeded |
| 500 | Internal Server Error - Server error |

### Validation Errors

```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ],
    "password": [
      "The password must be at least 8 characters."
    ]
  }
}
```

---

## Rate Limiting

API endpoints are rate-limited to prevent abuse:

- **Authenticated requests**: 60 requests per minute
- **Login attempts**: 5 attempts per minute

Rate limit headers are included in responses:

```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1640000000
```

---

## Data Types

### Semester Object

```json
{
  "id": 1,
  "name": "Semester 1",
  "academic_year": "2023-2024",
  "start_date": "2023-08-01",
  "end_date": "2023-12-31",
  "status": "completed"
}
```

### Subject Object

```json
{
  "id": 1,
  "code": "CS101",
  "name": "Computer Science I",
  "credits": 3,
  "max_marks": 100,
  "department": "Computer Science"
}
```

### Result Object

```json
{
  "id": 1,
  "student_id": 1,
  "semester_id": 1,
  "subject_id": 1,
  "marks_obtained": 85.5,
  "grade": "A",
  "is_passed": true,
  "created_at": "2023-12-31T10:00:00Z",
  "updated_at": "2023-12-31T10:00:00Z"
}
```

### Performance Metrics Object

```json
{
  "cgpa": 8.75,
  "total_credits": 90,
  "completed_semesters": 3,
  "pass_percentage": 95.8,
  "highest_semester": {...},
  "lowest_semester": {...},
  "failed_subjects": [...]
}
```

---

## Pagination

List endpoints support pagination:

**Query Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| page | integer | 1 | Page number |
| per_page | integer | 20 | Items per page |

**Response Format:**

```json
{
  "data": [...],
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "per_page": 20,
    "to": 20,
    "total": 100
  },
  "links": {
    "first": "https://api.example.com/results?page=1",
    "last": "https://api.example.com/results?page=5",
    "prev": null,
    "next": "https://api.example.com/results?page=2"
  }
}
```

---

## Versioning

The API currently uses URL versioning:

```
/api/v1/endpoint
```

Future versions will be released as:

```
/api/v2/endpoint
```

Older versions will be maintained for backward compatibility.

---

## Testing

### Using cURL

```bash
# Login
curl -X POST https://your-domain.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"student@example.com","password":"password"}'

# Get chart data (with session)
curl -X GET https://your-domain.com/progress/chart-data \
  -H "Accept: application/json" \
  -b cookies.txt

# Search results
curl -X GET "https://your-domain.com/results/search?query=math" \
  -H "Accept: application/json" \
  -b cookies.txt
```

### Using Postman

1. Import the API collection (if available)
2. Set up environment variables
3. Authenticate using login endpoint
4. Use session cookies for subsequent requests

---

## Security

### Best Practices

1. **Always use HTTPS** in production
2. **Never expose API tokens** in client-side code
3. **Validate all input** on the server side
4. **Use CSRF protection** for session-based auth
5. **Implement rate limiting** to prevent abuse
6. **Log all API access** for security auditing

### CORS Configuration

For mobile app integration, configure CORS in `config/cors.php`:

```php
'paths' => ['api/*'],
'allowed_origins' => ['https://your-mobile-app.com'],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

---

## Support

For API-related questions:
- Email: api-support@example.com
- Documentation: https://docs.example.com
- GitHub Issues: https://github.com/example/repo/issues

---

## Changelog

### Version 1.0.0 (2024)
- Initial API implementation
- Progress chart data endpoint
- Search results endpoint
- Session-based authentication

### Future Versions
- RESTful API for mobile apps
- Token-based authentication (Sanctum)
- Webhook support for result notifications
- GraphQL endpoint (planned)

---

**Note:** This API is currently for internal use. Public API access for mobile apps will be available in a future release.
