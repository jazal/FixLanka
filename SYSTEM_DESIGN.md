# FixLanka - System Design Documentation

## 1. System Overview

### 1.1 Project Description
FixLanka is a Smart Citizen Complaint & Resolution System designed to facilitate efficient reporting and resolution of public problems in Sri Lanka. The system enables citizens to report issues to relevant government departments through a user-friendly web interface with AI-powered department prediction.

### 1.2 System Architecture
The system follows a **3-tier architecture**:
- **Presentation Layer**: PHP-based web interface with HTML, CSS, and JavaScript
- **Business Logic Layer**: PHP backend with AI integration
- **Data Layer**: MySQL database

### 1.3 Technology Stack
- **Frontend**: HTML5, CSS3, JavaScript, Swiper.js, Font Awesome
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **AI/ML**: Python, scikit-learn, TF-IDF Vectorization, Naive Bayes
- **Email**: PHPMailer
- **Server**: Apache/XAMPP

## 2. System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                       │
├─────────────────────────────────────────────────────────────┤
│  Citizen Portal    │  Department Portal   │  Admin Portal   │
│  - Submit Complaints│  - View Assigned     │  - Manage Users │
│  - Track Status    │  - Update Status     │  - Manage Depts │
│  - View Reviews    │  - Upload Solutions  │  - Analytics    │
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                   BUSINESS LOGIC LAYER                      │
├─────────────────────────────────────────────────────────────┤
│  Authentication    │  Complaint Mgmt      │  AI Prediction  │
│  - Login/Register  │  - CRUD Operations   │  - Dept Routing │
│  - Password Reset  │  - Status Updates    │  - ML Model     │
│  - Role Management │  - File Upload       │  - TF-IDF       │
└─────────────────────────────────────────────────────────────┘
                              │
┌─────────────────────────────────────────────────────────────┐
│                      DATA LAYER                            │
├─────────────────────────────────────────────────────────────┤
│  MySQL Database                                            │
│  - users          │  - complaints        │  - reviews      │
│  - departments    │  - password_requests │  - uploads      │
└─────────────────────────────────────────────────────────────┘
```

## 3. Database Design

### 3.1 Entity Relationship Diagram

```
┌─────────────┐    ┌──────────────┐    ┌─────────────┐
│    users    │    │ departments  │    │ complaints  │
├─────────────┤    ├──────────────┤    ├─────────────┤
│ user_id (PK)│    │ dept_id (PK) │    │ complaint_id│
│ name        │    │ dept_name    │    │ user_id (FK)│
│ email       │    │ description  │    │ dept_id (FK)│
│ mobile      │    │ contact_email│    │ title       │
│ district    │    │ status       │    │ description │
│ password    │    │ created_at   │    │ location    │
│ profile_pic │    └──────────────┘    │ media_path  │
│ role        │                        │ status      │
│ created_at  │                        │ ref_number  │
└─────────────┘                        │ created_at  │
       │                               └─────────────┘
       │                                       │
       └───────────────────────────────────────┘
                                               │
                                       ┌─────────────┐
                                       │   reviews   │
                                       ├─────────────┤
                                       │ review_id   │
                                       │ user_id (FK)│
                                       │ complaint_id│
                                       │ message     │
                                       │ image_1     │
                                       │ image_2     │
                                       │ created_at  │
                                       └─────────────┘
```

### 3.2 Database Schema Details

#### Users Table
- **Purpose**: Store user account information
- **Key Fields**: user_id, email, role (citizen/department/admin)
- **Relationships**: One-to-Many with complaints and reviews

#### Departments Table
- **Purpose**: Store government department information
- **Key Fields**: dept_id, dept_name, status (active/inactive)
- **Relationships**: One-to-Many with complaints

#### Complaints Table
- **Purpose**: Store citizen complaints and their status
- **Key Fields**: complaint_id, ref_number (unique), status, location coordinates
- **Relationships**: Many-to-One with users and departments

#### Reviews Table
- **Purpose**: Store before/after images and feedback for resolved complaints
- **Key Fields**: review_id, before/after images, user feedback
- **Relationships**: Many-to-One with users and complaints

## 4. User Roles and Permissions

### 4.1 Citizen Role
- **Permissions**:
  - Register and manage profile
  - Submit complaints with location and media
  - Track complaint status
  - Submit reviews for resolved complaints
  - Change password and reset password
- **Access**: Citizen portal (`Includes/citizen/`)

### 4.2 Department Role
- **Permissions**:
  - View assigned complaints
  - Update complaint status
  - Upload solution images
  - View department statistics
- **Access**: Department portal (`Includes/department/`)

### 4.3 Admin Role
- **Permissions**:
  - Manage all user accounts
  - Add/remove departments
  - Reassign complaints between departments
  - View system analytics
  - Manage rejected complaints
  - Handle password reset requests
- **Access**: Admin portal (`Includes/admin/`)

## 5. Core System Modules

### 5.1 Authentication Module
**Files**: `Includes/login.php`, `Includes/citizen/register.php`, `Includes/logout.php`
- **Features**:
  - Multi-role login system
  - Password hashing and verification
  - Session management
  - Password reset via email

### 5.2 Complaint Management Module
**Files**: `Includes/citizen/submit_complaint.php`, `Includes/citizen/my_complaints.php`
- **Features**:
  - Complaint submission with location mapping
  - Media upload (images)
  - Status tracking
  - Reference number generation

### 5.3 AI Department Prediction Module
**Files**: `AI/train_model.py`, `AI/predict.py`, `AI/complaint_model.pkl`
- **Features**:
  - TF-IDF vectorization of complaint text
  - Naive Bayes classification
  - Automatic department routing
  - Model training and prediction

### 5.4 Review System Module
**Files**: `Includes/citizen/review.php`
- **Features**:
  - Before/after image upload
  - User feedback collection
  - Review display on homepage

### 5.5 Admin Management Module
**Files**: `Includes/admin/admin_dashboard.php`, `Includes/admin/manage_accounts.php`
- **Features**:
  - User account management
  - Department management
  - Complaint reassignment
  - System analytics

## 6. System Workflow

### 6.1 Complaint Submission Workflow
```
1. Citizen Login → 2. Submit Complaint → 3. AI Prediction → 4. Department Assignment
                                                              ↓
8. Review Submission ← 7. Status Update ← 6. Department Action ← 5. Notification
```

### 6.2 User Registration Workflow
```
1. Registration Form → 2. Email Verification → 3. Account Activation → 4. Login Access
```

### 6.3 Password Reset Workflow
```
1. Forgot Password → 2. Email Verification → 3. Reset Link → 4. New Password → 5. Login
```

## 7. Security Features

### 7.1 Authentication Security
- Password hashing using PHP's password_hash()
- Session-based authentication
- Role-based access control
- SQL injection prevention with prepared statements

### 7.2 Data Security
- Input validation and sanitization
- File upload restrictions
- XSS prevention
- CSRF protection

### 7.3 File Security
- Upload directory protection
- File type validation
- File size limitations
- Secure file naming

## 8. AI/ML Integration

### 8.1 Machine Learning Model
- **Algorithm**: Multinomial Naive Bayes
- **Feature Extraction**: TF-IDF Vectorization
- **Training Data**: Historical complaint descriptions with department labels
- **Prediction**: Automatic department assignment based on complaint text

### 8.2 Model Training Process
```python
# Load training data
data = pd.read_csv("complaints_data.csv")

# Feature extraction
vectorizer = TfidfVectorizer()
X = vectorizer.fit_transform(data['description'])

# Model training
model = MultinomialNB()
model.fit(X, data['category'])

# Save model
joblib.dump(model, "complaint_model.pkl")
```

### 8.3 Prediction Process
```python
# Load model and predict
model = joblib.load("complaint_model.pkl")
vectorizer = joblib.load("vectorizer.pkl")

X = vectorizer.transform([complaint_text])
prediction = model.predict(X)
```

## 9. File Structure

```
FixLanka/
├── home.php                 # Main landing page
├── home.css                 # Main stylesheet
├── fixlanka_database.sql    # Database schema
├── Includes/                # Core application files
│   ├── dbconnect.php       # Database connection
│   ├── login.php           # Authentication
│   ├── header.php          # Common header
│   ├── citizen/            # Citizen portal
│   ├── admin/              # Admin portal
│   └── department/         # Department portal
├── AI/                     # Machine learning components
│   ├── train_model.py      # Model training script
│   ├── predict.py          # Prediction script
│   ├── complaint_model.pkl # Trained model
│   └── vectorizer.pkl      # TF-IDF vectorizer
├── uploads/                # File storage
│   ├── logos/              # Department logos
│   ├── reviews/            # Review images
│   └── icons/              # UI icons
├── js/                     # JavaScript files
│   └── map.js              # Location mapping
└── phpmailer/              # Email functionality
```

## 10. Performance Considerations

### 10.1 Database Optimization
- Indexed primary and foreign keys
- Optimized queries with prepared statements
- Connection pooling

### 10.2 File Management
- Organized upload directory structure
- Image compression for storage efficiency
- Regular cleanup of temporary files

### 10.3 Caching Strategy
- Session-based caching for user data
- Static asset caching
- Database query result caching

## 11. Scalability and Maintenance

### 11.1 Scalability Features
- Modular code structure for easy expansion
- Role-based access control for multi-tenant support
- Configurable department management

### 11.2 Maintenance Considerations
- Regular database backups
- Log monitoring and error tracking
- Model retraining for improved AI accuracy
- Security updates and patches

## 12. Future Enhancements

### 12.1 Planned Features
- Mobile application development
- Real-time notifications
- Advanced analytics dashboard
- Multi-language support
- API development for third-party integration

### 12.2 Technical Improvements
- Migration to modern PHP framework
- Implementation of microservices architecture
- Enhanced AI model with deep learning
- Real-time chat support
- Blockchain integration for transparency

## 13. Deployment Requirements

### 13.1 Server Requirements
- **Web Server**: Apache 2.4+ or Nginx
- **PHP**: 7.4+ with required extensions
- **Database**: MySQL 5.7+ or MariaDB 10.2+
- **Python**: 3.7+ (for AI components)
- **Storage**: Minimum 10GB for uploads

### 13.2 Required PHP Extensions
- mysqli
- gd (for image processing)
- mbstring
- openssl
- zip

### 13.3 Python Dependencies
- pandas
- scikit-learn
- joblib
- numpy

## 14. Testing Strategy

### 14.1 Unit Testing
- Individual module testing
- Database operation testing
- AI model accuracy testing

### 14.2 Integration Testing
- End-to-end workflow testing
- Cross-browser compatibility
- Mobile responsiveness testing

### 14.3 Security Testing
- Penetration testing
- SQL injection testing
- XSS vulnerability testing
- File upload security testing

---

**Document Version**: 1.0  
**Last Updated**: December 2024  
**Maintained By**: FixLanka Development Team 