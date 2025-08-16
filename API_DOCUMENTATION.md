# FixLanka API Documentation

## Overview
This document provides comprehensive API documentation for the FixLanka Smart Citizen Complaint & Resolution System.

## Base URL
```
http://localhost/FixLanka/
```

## Authentication
The system uses session-based authentication. All API endpoints require a valid session except for public endpoints.

## API Endpoints

### 1. Authentication Endpoints

#### 1.1 User Login
- **URL**: `/Includes/login.php`
- **Method**: `POST`
- **Description**: Authenticate user and create session
- **Parameters**:
  ```json
  {
    "email": "user@example.com",
    "password": "userpassword"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Login successful",
    "user": {
      "user_id": 1,
      "name": "John Doe",
      "email": "user@example.com",
      "role": "citizen"
    }
  }
  ```

#### 1.2 User Registration
- **URL**: `/Includes/citizen/register.php`
- **Method**: `POST`
- **Description**: Register new citizen account
- **Parameters**:
  ```json
  {
    "name": "John Doe",
    "email": "user@example.com",
    "mobile": "+94123456789",
    "district": "Colombo",
    "password": "password123",
    "confirm_password": "password123"
  }
  ```

#### 1.3 Password Reset Request
- **URL**: `/Includes/citizen/forgot_password.php`
- **Method**: `POST`
- **Description**: Request password reset email
- **Parameters**:
  ```json
  {
    "email": "user@example.com"
  }
  ```

### 2. Complaint Management Endpoints

#### 2.1 Submit Complaint
- **URL**: `/Includes/citizen/submit_complaint.php`
- **Method**: `POST`
- **Description**: Submit new complaint with AI department prediction
- **Parameters**:
  ```json
  {
    "title": "Broken Street Light",
    "description": "Street light not working on Main Street",
    "location_lat": 6.9271,
    "location_lng": 79.8612,
    "media": "file_upload"
  }
  ```
- **Response**:
  ```json
  {
    "success": true,
    "message": "Complaint submitted successfully",
    "ref_number": "FL202412001",
    "predicted_department": "CEB"
  }
  ```

#### 2.2 Get User Complaints
- **URL**: `/Includes/citizen/my_complaints.php`
- **Method**: `GET`
- **Description**: Retrieve user's complaint history
- **Response**:
  ```json
  {
    "complaints": [
      {
        "complaint_id": 1,
        "ref_number": "FL202412001",
        "title": "Broken Street Light",
        "status": "In Progress",
        "dept_name": "CEB",
        "created_at": "2024-12-17 10:30:00"
      }
    ]
  }
  ```

#### 2.3 Track Complaint Status
- **URL**: `/home.php`
- **Method**: `POST`
- **Description**: Track complaint by reference number
- **Parameters**:
  ```json
  {
    "ref_number": "FL202412001"
  }
  ```

### 3. Department Management Endpoints

#### 3.1 Department Dashboard
- **URL**: `/Includes/department/department_dashboard.php`
- **Method**: `GET`
- **Description**: Get department's assigned complaints
- **Response**:
  ```json
  {
    "department": {
      "dept_id": 1,
      "dept_name": "CEB",
      "total_complaints": 25,
      "pending": 10,
      "in_progress": 8,
      "resolved": 7
    },
    "complaints": [...]
  }
  ```

#### 3.2 Update Complaint Status
- **URL**: `/Includes/department/department_dashboard.php`
- **Method**: `POST`
- **Description**: Update complaint status and add notes
- **Parameters**:
  ```json
  {
    "complaint_id": 1,
    "status": "Resolved",
    "notes": "Issue fixed successfully",
    "solution_image": "file_upload"
  }
  ```

### 4. Admin Management Endpoints

#### 4.1 Admin Dashboard
- **URL**: `/Includes/admin/admin_dashboard.php`
- **Method**: `GET`
- **Description**: Get system overview and statistics
- **Response**:
  ```json
  {
    "statistics": {
      "total_users": 150,
      "total_complaints": 500,
      "total_departments": 8,
      "resolved_complaints": 350
    },
    "recent_activities": [...]
  }
  ```

#### 4.2 Manage Users
- **URL**: `/Includes/admin/manage_accounts.php`
- **Method**: `GET/POST`
- **Description**: View and manage user accounts
- **Parameters** (for updates):
  ```json
  {
    "user_id": 1,
    "action": "activate|deactivate|delete",
    "role": "citizen|department|admin"
  }
  ```

#### 4.3 Add Department
- **URL**: `/Includes/admin/add_department.php`
- **Method**: `POST`
- **Description**: Add new government department
- **Parameters**:
  ```json
  {
    "dept_name": "New Department",
    "description": "Department description",
    "contact_email": "dept@example.com"
  }
  ```

#### 4.4 Reassign Complaint
- **URL**: `/Includes/admin/reassign_complaint.php`
- **Method**: `POST`
- **Description**: Reassign complaint to different department
- **Parameters**:
  ```json
  {
    "complaint_id": 1,
    "new_dept_id": 2,
    "reason": "Better suited for this department"
  }
  ```

### 5. Review System Endpoints

#### 5.1 Submit Review
- **URL**: `/Includes/citizen/review.php`
- **Method**: `POST`
- **Description**: Submit review for resolved complaint
- **Parameters**:
  ```json
  {
    "complaint_id": 1,
    "message": "Great service! Issue resolved quickly.",
    "before_image": "file_upload",
    "after_image": "file_upload"
  }
  ```

#### 5.2 Get Reviews
- **URL**: `/home.php` (Reviews section)
- **Method**: `GET`
- **Description**: Get public reviews for homepage display
- **Response**:
  ```json
  {
    "reviews": [
      {
        "review_id": 1,
        "user_name": "John Doe",
        "message": "Great service!",
        "before_image": "uploads/reviews/before.jpg",
        "after_image": "uploads/reviews/after.jpg",
        "created_at": "2024-12-17 15:30:00"
      }
    ]
  }
  ```

### 6. AI Prediction Endpoints

#### 6.1 Predict Department
- **URL**: `/Includes/citizen/predict_department.php`
- **Method**: `POST`
- **Description**: Predict appropriate department for complaint
- **Parameters**:
  ```json
  {
    "description": "Street light not working"
  }
  ```
- **Response**:
  ```json
  {
    "predicted_department": "CEB",
    "confidence": 0.85
  }
  ```

## Error Responses

### Standard Error Format
```json
{
  "success": false,
  "error": "Error message",
  "error_code": "ERROR_CODE"
}
```

### Common Error Codes
- `AUTH_REQUIRED`: Authentication required
- `INVALID_CREDENTIALS`: Invalid login credentials
- `EMAIL_EXISTS`: Email already registered
- `INVALID_INPUT`: Invalid input data
- `FILE_UPLOAD_ERROR`: File upload failed
- `PERMISSION_DENIED`: Insufficient permissions
- `NOT_FOUND`: Resource not found
- `SERVER_ERROR`: Internal server error

## File Upload Guidelines

### Supported File Types
- **Images**: JPG, JPEG, PNG, GIF
- **Maximum Size**: 5MB per file
- **Upload Directory**: `/uploads/`

### File Upload Response
```json
{
  "success": true,
  "file_path": "uploads/complaints/image_123.jpg",
  "file_name": "image_123.jpg"
}
```

## Rate Limiting
- **Authentication**: 5 attempts per 15 minutes
- **Complaint Submission**: 10 per hour per user
- **File Uploads**: 20 per hour per user

## Security Headers
```
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
X-XSS-Protection: 1; mode=block
Content-Security-Policy: default-src 'self'
```

## Testing

### Test Environment
- **Base URL**: `http://localhost/FixLanka/`
- **Test Database**: `fixlanka_test`
- **Test Email**: `test@fixlanka.com`

### Sample Test Data
```json
{
  "test_user": {
    "email": "test@example.com",
    "password": "test123",
    "role": "citizen"
  },
  "test_department": {
    "dept_name": "Test Department",
    "contact_email": "test@dept.com"
  }
}
```

---

**API Version**: 1.0  
**Last Updated**: December 2024  
**Maintained By**: FixLanka Development Team 