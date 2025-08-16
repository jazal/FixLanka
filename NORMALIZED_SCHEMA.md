# FixLanka Database - Normalized Relation Schema

## Current Database Analysis

Based on the `fixlanka.sql` file, the current database is already well-normalized. However, I'll provide a detailed analysis and suggest some normalization improvements.

## Current Schema (Already Normalized)

### 1. USERS Table (3NF)
```sql
USERS (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mobile VARCHAR(20),
    district VARCHAR(100),
    password_hash VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255),
    role ENUM('citizen','department','admin') DEFAULT 'citizen',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reset_otp VARCHAR(10),
    reset_otp_expires DATETIME
)
```

### 2. DEPARTMENTS Table (3NF)
```sql
DEPARTMENTS (
    dept_id INT PRIMARY KEY AUTO_INCREMENT,
    dept_name VARCHAR(100) NOT NULL,
    description TEXT,
    contact_email VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    logo VARCHAR(255),
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    role VARCHAR(50) DEFAULT 'department'
)
```

### 3. COMPLAINTS Table (3NF)
```sql
COMPLAINTS (
    complaint_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    dept_id INT,
    title VARCHAR(200),
    description TEXT,
    location_lat FLOAT,
    location_lng FLOAT,
    media_path VARCHAR(255),
    status ENUM('Pending','In Progress','Resolved','Rejected') DEFAULT 'Pending',
    ref_number VARCHAR(100) UNIQUE,
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id),
    FOREIGN KEY (dept_id) REFERENCES DEPARTMENTS(dept_id)
)
```

### 4. REVIEWS Table (3NF)
```sql
REVIEWS (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    ref_number VARCHAR(50) NOT NULL,
    before_image VARCHAR(255),
    after_image VARCHAR(255),
    review_text TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id),
    FOREIGN KEY (ref_number) REFERENCES COMPLAINTS(ref_number)
)
```

### 5. PASSWORD_RESET_REQUESTS Table (3NF)
```sql
PASSWORD_RESET_REQUESTS (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    username VARCHAR(100),
    email VARCHAR(255),
    description TEXT,
    requested_password VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    read TINYINT(1) DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id)
)
```

## Normalization Analysis

### First Normal Form (1NF) ✅
All tables satisfy 1NF:
- No repeating groups
- All attributes are atomic
- Primary keys are defined
- No duplicate rows

### Second Normal Form (2NF) ✅
All tables satisfy 2NF:
- All non-key attributes are fully dependent on the primary key
- No partial dependencies

### Third Normal Form (3NF) ✅
All tables satisfy 3NF:
- No transitive dependencies
- All attributes depend only on the primary key

## Suggested Normalization Improvements

### 1. DISTRICT Normalization
**Current Issue**: District is stored as VARCHAR in USERS table
**Improvement**: Create a separate DISTRICTS table

```sql
-- New DISTRICTS table
DISTRICTS (
    district_id INT PRIMARY KEY AUTO_INCREMENT,
    district_name VARCHAR(100) UNIQUE NOT NULL,
    province VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)

-- Modified USERS table
USERS (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mobile VARCHAR(20),
    district_id INT,  -- Changed from district VARCHAR
    password_hash VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255),
    role ENUM('citizen','department','admin') DEFAULT 'citizen',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reset_otp VARCHAR(10),
    reset_otp_expires DATETIME,
    FOREIGN KEY (district_id) REFERENCES DISTRICTS(district_id)
)
```

### 2. COMPLAINT_STATUS Normalization
**Current Issue**: Status is stored as ENUM in COMPLAINTS table
**Improvement**: Create a separate COMPLAINT_STATUSES table

```sql
-- New COMPLAINT_STATUSES table
COMPLAINT_STATUSES (
    status_id INT PRIMARY KEY AUTO_INCREMENT,
    status_name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    color_code VARCHAR(7),  -- For UI display
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)

-- Modified COMPLAINTS table
COMPLAINTS (
    complaint_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    dept_id INT,
    title VARCHAR(200),
    description TEXT,
    location_lat FLOAT,
    location_lng FLOAT,
    media_path VARCHAR(255),
    status_id INT DEFAULT 1,  -- Changed from status ENUM
    ref_number VARCHAR(100) UNIQUE,
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id),
    FOREIGN KEY (dept_id) REFERENCES DEPARTMENTS(dept_id),
    FOREIGN KEY (status_id) REFERENCES COMPLAINT_STATUSES(status_id)
)
```

### 3. FILE_UPLOADS Normalization
**Current Issue**: File paths are stored in multiple tables
**Improvement**: Create a centralized FILE_UPLOADS table

```sql
-- New FILE_UPLOADS table
FILE_UPLOADS (
    file_id INT PRIMARY KEY AUTO_INCREMENT,
    original_name VARCHAR(255) NOT NULL,
    stored_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_type VARCHAR(50),
    file_size INT,
    upload_type ENUM('complaint','review_before','review_after','profile','logo') NOT NULL,
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (uploaded_by) REFERENCES USERS(user_id)
)

-- Modified COMPLAINTS table
COMPLAINTS (
    complaint_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    dept_id INT,
    title VARCHAR(200),
    description TEXT,
    location_lat FLOAT,
    location_lng FLOAT,
    file_id INT,  -- Changed from media_path VARCHAR
    status_id INT DEFAULT 1,
    ref_number VARCHAR(100) UNIQUE,
    rejection_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id),
    FOREIGN KEY (dept_id) REFERENCES DEPARMENTS(dept_id),
    FOREIGN KEY (status_id) REFERENCES COMPLAINT_STATUSES(status_id),
    FOREIGN KEY (file_id) REFERENCES FILE_UPLOADS(file_id)
)

-- Modified REVIEWS table
REVIEWS (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    ref_number VARCHAR(50) NOT NULL,
    before_file_id INT,  -- Changed from before_image VARCHAR
    after_file_id INT,   -- Changed from after_image VARCHAR
    review_text TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES USERS(user_id),
    FOREIGN KEY (ref_number) REFERENCES COMPLAINTS(ref_number),
    FOREIGN KEY (before_file_id) REFERENCES FILE_UPLOADS(file_id),
    FOREIGN KEY (after_file_id) REFERENCES FILE_UPLOADS(file_id)
)
```

### 4. DEPARTMENT_CATEGORIES Normalization
**Current Issue**: Department types are hardcoded
**Improvement**: Create a DEPARTMENT_CATEGORIES table

```sql
-- New DEPARTMENT_CATEGORIES table
DEPARTMENT_CATEGORIES (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    icon_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)

-- Modified DEPARTMENTS table
DEPARTMENTS (
    dept_id INT PRIMARY KEY AUTO_INCREMENT,
    dept_name VARCHAR(100) NOT NULL,
    category_id INT,  -- Added category reference
    description TEXT,
    contact_email VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    logo VARCHAR(255),
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    role VARCHAR(50) DEFAULT 'department',
    FOREIGN KEY (category_id) REFERENCES DEPARTMENT_CATEGORIES(category_id)
)
```

## Complete Normalized Schema (Improved)

### Core Tables
```sql
-- 1. DISTRICTS
DISTRICTS (district_id, district_name, province, created_at)

-- 2. DEPARTMENT_CATEGORIES  
DEPARTMENT_CATEGORIES (category_id, category_name, description, icon_path, created_at)

-- 3. COMPLAINT_STATUSES
COMPLAINT_STATUSES (status_id, status_name, description, color_code, created_at)

-- 4. USERS
USERS (user_id, name, email, mobile, district_id, password_hash, profile_picture, role, created_at, reset_otp, reset_otp_expires)
FK: district_id → DISTRICTS(district_id)

-- 5. DEPARTMENTS
DEPARTMENTS (dept_id, dept_name, category_id, description, contact_email, password, logo, status, created_at, role)
FK: category_id → DEPARTMENT_CATEGORIES(category_id)

-- 6. FILE_UPLOADS
FILE_UPLOADS (file_id, original_name, stored_name, file_path, file_type, file_size, upload_type, uploaded_by, created_at)
FK: uploaded_by → USERS(user_id)

-- 7. COMPLAINTS
COMPLAINTS (complaint_id, user_id, dept_id, title, description, location_lat, location_lng, file_id, status_id, ref_number, rejection_reason, created_at)
FK: user_id → USERS(user_id)
FK: dept_id → DEPARTMENTS(dept_id)
FK: file_id → FILE_UPLOADS(file_id)
FK: status_id → COMPLAINT_STATUSES(status_id)

-- 8. REVIEWS
REVIEWS (review_id, user_id, ref_number, before_file_id, after_file_id, review_text, created_at)
FK: user_id → USERS(user_id)
FK: ref_number → COMPLAINTS(ref_number)
FK: before_file_id → FILE_UPLOADS(file_id)
FK: after_file_id → FILE_UPLOADS(file_id)

-- 9. PASSWORD_RESET_REQUESTS
PASSWORD_RESET_REQUESTS (id, user_id, username, email, description, requested_password, created_at, read)
FK: user_id → USERS(user_id)
```

## Sample Data for New Tables

### DISTRICTS
```sql
INSERT INTO DISTRICTS (district_name, province) VALUES
('Colombo', 'Western'),
('Gampaha', 'Western'),
('Kalutara', 'Western'),
('Kandy', 'Central'),
('Matale', 'Central'),
('Nuwara Eliya', 'Central'),
('Galle', 'Southern'),
('Matara', 'Southern'),
('Hambantota', 'Southern'),
('Jaffna', 'Northern'),
('Kilinochchi', 'Northern'),
('Mullaitivu', 'Northern'),
('Vavuniya', 'Northern'),
('Mannar', 'Northern'),
('Puttalam', 'North Western'),
('Kurunegala', 'North Western'),
('Anuradhapura', 'North Central'),
('Polonnaruwa', 'North Central'),
('Badulla', 'Uva'),
('Monaragala', 'Uva'),
('Ratnapura', 'Sabaragamuwa'),
('Kegalle', 'Sabaragamuwa'),
('Trincomalee', 'Eastern'),
('Batticaloa', 'Eastern'),
('Ampara', 'Eastern');
```

### COMPLAINT_STATUSES
```sql
INSERT INTO COMPLAINT_STATUSES (status_name, description, color_code) VALUES
('Pending', 'Complaint received, awaiting department review', '#FFA500'),
('In Progress', 'Department is working on the issue', '#0066CC'),
('Resolved', 'Problem has been fixed successfully', '#00CC00'),
('Rejected', 'Complaint cannot be processed', '#CC0000');
```

### DEPARTMENT_CATEGORIES
```sql
INSERT INTO DEPARTMENT_CATEGORIES (category_name, description) VALUES
('Utilities', 'Electricity, water, and gas services'),
('Transportation', 'Roads, railways, and public transport'),
('Public Safety', 'Police and emergency services'),
('Health & Sanitation', 'Healthcare and environmental services'),
('Infrastructure', 'Construction and maintenance services'),
('Emergency Services', 'Disaster management and emergency response');
```

## Benefits of Normalized Schema

### 1. **Data Integrity**
- Referential integrity through foreign keys
- Consistent district names and status values
- Centralized file management

### 2. **Maintainability**
- Easy to add new districts, statuses, or categories
- Consistent data across the system
- Reduced data redundancy

### 3. **Scalability**
- Efficient queries with proper indexing
- Easy to extend with new features
- Better performance with normalized structure

### 4. **Flexibility**
- Dynamic status management
- Configurable department categories
- Centralized file upload handling

## Migration Strategy

### Phase 1: Create New Tables
1. Create DISTRICTS table and populate with data
2. Create COMPLAINT_STATUSES table and populate with data
3. Create DEPARTMENT_CATEGORIES table and populate with data

### Phase 2: Modify Existing Tables
1. Add foreign key columns to existing tables
2. Update existing data to reference new tables
3. Remove old columns after data migration

### Phase 3: Implement File Management
1. Create FILE_UPLOADS table
2. Migrate existing file references
3. Update application code to use new structure

---

**Normalized Schema Version**: 2.0  
**Last Updated**: December 2024  
**Based on**: fixlanka.sql database schema 