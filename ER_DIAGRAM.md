# FixLanka Database - Entity Relationship Diagram

## Database Schema Overview

Based on the complete `fixlanka.sql` file, the FixLanka system consists of **5 main entities** with their relationships:

## Entity Relationship Diagram

```
┌─────────────────────────────────────────────────────────────────────────────────┐
│                                    FIXLANKA DATABASE                            │
└─────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────┐
│                                    USERS                                        │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: user_id (INT, AUTO_INCREMENT)                                              │
│     name (VARCHAR(100), NOT NULL)                                              │
│     email (VARCHAR(100), UNIQUE, NOT NULL)                                     │
│     mobile (VARCHAR(20))                                                       │
│     district (VARCHAR(100))                                                    │
│     password_hash (VARCHAR(255), NOT NULL)                                     │
│     profile_picture (VARCHAR(255))                                             │
│     role (ENUM: 'citizen','department','admin', DEFAULT: 'citizen')           │
│     created_at (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP)                         │
│     reset_otp (VARCHAR(10))                                                    │
│     reset_otp_expires (DATETIME)                                               │
└─────────────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ 1
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────────┐
│                                DEPARTMENTS                                      │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: dept_id (INT, AUTO_INCREMENT)                                              │
│     dept_name (VARCHAR(100), NOT NULL)                                         │
│     description (TEXT)                                                          │
│     contact_email (VARCHAR(100))                                               │
│     password (VARCHAR(255), NOT NULL)                                          │
│     logo (VARCHAR(255))                                                        │
│     status (ENUM: 'active','inactive', DEFAULT: 'active')                     │
│     created_at (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP)                         │
│     role (VARCHAR(50), DEFAULT: 'department')                                  │
└─────────────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ 1
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────────┐
│                                COMPLAINTS                                       │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: complaint_id (INT, AUTO_INCREMENT)                                         │
│ FK: user_id (INT) → users(user_id)                                             │
│ FK: dept_id (INT) → departments(dept_id)                                       │
│     title (VARCHAR(200))                                                       │
│     description (TEXT)                                                         │
│     location_lat (FLOAT)                                                       │
│     location_lng (FLOAT)                                                       │
│     media_path (VARCHAR(255))                                                  │
│     status (ENUM: 'Pending','In Progress','Resolved','Rejected')              │
│     ref_number (VARCHAR(100), UNIQUE)                                          │
│     rejection_reason (TEXT)                                                    │
│     created_at (TIMESTAMP, DEFAULT: CURRENT_TIMESTAMP)                         │
│                                                                                 │
│ TRIGGER: before_insert_ref_number - Auto-generates FL-XXXXXXXX format         │
└─────────────────────────────────────────────────────────────────────────────────┘
                                    │
                                    │ 1
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────────────────────┐
│                                  REVIEWS                                        │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: review_id (INT, AUTO_INCREMENT)                                            │
│ FK: user_id (INT) → users(user_id)                                             │
│ FK: ref_number (VARCHAR(50)) → complaints(ref_number)                          │
│     before_image (VARCHAR(255))                                                │
│     after_image (VARCHAR(255))                                                 │
│     review_text (TEXT)                                                         │
│     created_at (DATETIME, DEFAULT: CURRENT_TIMESTAMP)                          │
└─────────────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────┐
│                          PASSWORD_RESET_REQUESTS                               │
├─────────────────────────────────────────────────────────────────────────────────┤
│ PK: id (INT, AUTO_INCREMENT)                                                   │
│ FK: user_id (INT) → users(user_id)                                             │
│     username (VARCHAR(100))                                                    │
│     email (VARCHAR(255))                                                       │
│     description (TEXT)                                                         │
│     requested_password (VARCHAR(255))                                          │
│     created_at (DATETIME, DEFAULT: CURRENT_TIMESTAMP)                          │
│     read (TINYINT(1), DEFAULT: 0)                                              │
└─────────────────────────────────────────────────────────────────────────────────┘
```

## Relationship Details

### 1. USERS → COMPLAINTS (1:N)
- **Relationship**: One user can submit multiple complaints
- **Foreign Key**: `complaints.user_id` references `users.user_id`
- **Cardinality**: 1:N (One-to-Many)

### 2. DEPARTMENTS → COMPLAINTS (1:N)
- **Relationship**: One department can handle multiple complaints
- **Foreign Key**: `complaints.dept_id` references `departments.dept_id`
- **Cardinality**: 1:N (One-to-Many)

### 3. USERS → REVIEWS (1:N)
- **Relationship**: One user can submit multiple reviews
- **Foreign Key**: `reviews.user_id` references `users.user_id`
- **Cardinality**: 1:N (One-to-Many)

### 4. COMPLAINTS → REVIEWS (1:N)
- **Relationship**: One complaint can have multiple reviews
- **Foreign Key**: `reviews.ref_number` references `complaints.ref_number`
- **Cardinality**: 1:N (One-to-Many)

### 5. USERS → PASSWORD_RESET_REQUESTS (1:N)
- **Relationship**: One user can have multiple password reset requests
- **Foreign Key**: `password_reset_requests.user_id` references `users.user_id`
- **Cardinality**: 1:N (One-to-Many)

## Key Features

### Auto-Generated Reference Numbers
- **Trigger**: `before_insert_ref_number`
- **Format**: `FL-XXXXXXXX` (8-character hexadecimal)
- **Example**: `FL-4885A983`

### User Roles
- **citizen**: Regular users who submit complaints
- **department**: Government department staff
- **admin**: System administrators

### Complaint Status Workflow
1. **Pending**: Initial state when complaint is submitted
2. **In Progress**: Department is working on the issue
3. **Resolved**: Problem has been fixed
4. **Rejected**: Complaint cannot be processed (with reason)

### Department Status
- **active**: Department can receive complaints
- **inactive**: Department is temporarily disabled

## Indexes and Constraints

### Primary Keys
- `users.user_id`
- `departments.dept_id`
- `complaints.complaint_id`
- `reviews.review_id`
- `password_reset_requests.id`

### Unique Constraints
- `users.email` (UNIQUE)
- `complaints.ref_number` (UNIQUE)

### Foreign Key Constraints
- `complaints.user_id` → `users.user_id`
- `complaints.dept_id` → `departments.dept_id`
- `reviews.user_id` → `users.user_id`
- `reviews.ref_number` → `complaints.ref_number`
- `password_reset_requests.user_id` → `users.user_id`

## Sample Data Relationships

### Example Complaint Flow
```
User (Umar Nashtah) → Complaint (FL-4885A983) → Department (CEB)
                                                    ↓
                                              Status: Resolved
                                                    ↓
                                              Review (before/after images)
```

### Department Examples
- **CEB**: Ceylon Electricity Board (Power issues)
- **RDA**: Road Development Authority (Road problems)
- **Police**: Sri Lanka Police (Security issues)
- **NWSDB**: National Water Supply & Drainage Board (Water issues)
- **SLR**: Sri Lanka Railways (Railway problems)
- **DMC**: Disaster Management Center (Emergency cases)
- **MOH**: Ministry of Health (Health-related issues)

---

**ER Diagram Version**: 1.0  
**Last Updated**: December 2024  
**Based on**: fixlanka.sql database schema 