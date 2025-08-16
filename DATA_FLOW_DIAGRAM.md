# FixLanka - Data Flow Diagram (DFD)

## Overview
This document provides comprehensive Data Flow Diagrams (DFD) for the FixLanka Smart Citizen Complaint & Resolution System at different levels of abstraction.

## DFD Level 0 (Context Diagram)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           FIXLANKA SYSTEM                                   │
│                    (Context Level DFD)                                      │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────┐                                    ┌─────────────────┐
│                 │                                    │                 │
│     CITIZEN     │                                    │   DEPARTMENT    │
│                 │                                    │                 │
│ • Submit        │                                    │ • View          │
│   Complaints    │                                    │   Complaints    │
│ • Track Status  │                                    │ • Update        │
│ • Submit        │                                    │   Status        │
│   Reviews       │                                    │ • Upload        │
│ • Register/     │                                    │   Solutions     │
│   Login         │                                    │ • View          │
└─────────────────┘                                    │   Analytics     │
         │                                              └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────────────────────────────────────────────────────────────────┐
│                           FIXLANKA SYSTEM                                   │
│                                                                             │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐            │
│  │   USER MGMT     │  │  COMPLAINT      │  │   AI/ML         │            │
│  │   PROCESS       │  │  PROCESS        │  │   PROCESS       │            │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘            │
│                                                                             │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐            │
│  │  DEPARTMENT     │  │   REVIEW        │  │   ADMIN         │            │
│  │  MGMT PROCESS   │  │   PROCESS       │  │   PROCESS       │            │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘            │
└─────────────────────────────────────────────────────────────────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│                 │                                    │                 │
│   ADMIN USER    │                                    │   EXTERNAL      │
│                 │                                    │   SYSTEMS       │
│ • Manage Users  │                                    │                 │
│ • Manage Depts  │                                    │ • Email         │
│ • System Config │                                    │   Service       │
│ • Analytics     │                                    │ • SMS Gateway   │
│ • Reports       │                                    │ • AI Models     │
└─────────────────┘                                    └─────────────────┘
```

## DFD Level 1 (System Level)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           FIXLANKA SYSTEM - LEVEL 1                        │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────┐                                    ┌─────────────────┐
│     CITIZEN     │                                    │   DEPARTMENT    │
│                 │                                    │                 │
│ • User Input    │                                    │ • Login         │
│ • Complaint     │                                    │ • Dashboard     │
│   Data          │                                    │ • Status        │
│ • Review Data   │                                    │   Updates       │
│ • Profile Data  │                                    │ • Solution      │
└─────────────────┘                                    │   Uploads       │
         │                                              └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   1.0 USER      │                                    │   4.0 DEPARTMENT│
│   MANAGEMENT    │                                    │   MANAGEMENT    │
│                 │                                    │                 │
│ • Registration  │                                    │ • View          │
│ • Authentication│                                    │   Assigned      │
│ • Profile Mgmt  │                                    │   Complaints    │
│ • Password      │                                    │ • Update        │
│   Reset         │                                    │   Status        │
└─────────────────┘                                    │ • Upload        │
         │                                              │   Solutions     │
         │                                              │ • Analytics     │
         ▼                                              └─────────────────┘
┌─────────────────┐                                              │
│   2.0 COMPLAINT │                                              │
│   PROCESSING    │                                              │
│                 │                                              │
│ • Submit        │                                              │
│   Complaint     │                                              │
│ • Track Status  │                                              │
│ • File Upload   │                                              │
│ • Location      │                                              │
│   Mapping       │                                              │
└─────────────────┘                                              │
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   3.0 AI/ML     │                                    │   5.0 REVIEW    │
│   PREDICTION    │                                    │   SYSTEM        │
│                 │                                    │                 │
│ • Department    │                                    │ • Submit        │
│   Prediction    │                                    │   Reviews       │
│ • Model         │                                    │ • Before/After  │
│   Training      │                                    │   Photos        │
│ • Accuracy      │                                    │ • Rating        │
│   Metrics       │                                    │   System        │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   6.0 ADMIN     │                                    │   7.0 NOTIFICATION│
│   MANAGEMENT    │                                    │   SYSTEM        │
│                 │                                    │                 │
│ • User Mgmt     │                                    │ • Email         │
│ • Department    │                                    │   Notifications │
│   Mgmt          │                                    │ • SMS           │
│ • System Config │                                    │   Alerts        │
│ • Analytics     │                                    │ • Status        │
│ • Reports       │                                    │   Updates       │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   ADMIN USER    │                                    │   EXTERNAL      │
│                 │                                    │   SYSTEMS       │
│ • Management    │                                    │                 │
│   Interface     │                                    │ • Email         │
│ • Reports       │                                    │   Service       │
│ • Analytics     │                                    │ • SMS Gateway   │
└─────────────────┘                                    └─────────────────┘
```

## DFD Level 2 (Process Level)

### 1.0 USER MANAGEMENT PROCESS

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           1.0 USER MANAGEMENT                               │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────┐                                    ┌─────────────────┐
│     CITIZEN     │                                    │   REGISTRATION  │
│                 │                                    │   DATA          │
│ • Name          │                                    │                 │
│ • Email         │                                    │ • Name          │
│ • Mobile        │                                    │ • Email         │
│ • District      │                                    │ • Mobile        │
│ • Password      │                                    │ • District      │
└─────────────────┘                                    │ • Password      │
         │                                              └─────────────────┘
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   1.1 USER      │                                    │   1.2 EMAIL     │
│   REGISTRATION  │                                    │   VERIFICATION  │
│                 │                                    │                 │
│ • Validate      │                                    │ • Send          │
│   Input Data    │                                    │   Verification  │
│ • Hash Password │                                    │   Email         │
│ • Create User   │                                    │ • Verify        │
│   Account       │                                    │   Token         │
│ • Generate      │                                    │ • Activate      │
│   Ref Number    │                                    │   Account       │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   1.3 USER      │                                    │   1.4 PASSWORD  │
│   AUTHENTICATION│                                    │   RESET         │
│                 │                                    │                 │
│ • Login         │                                    │ • Generate      │
│   Validation    │                                    │   Reset Token   │
│ • Session       │                                    │ • Send Reset    │
│   Management    │                                    │   Email         │
│ • Role-based    │                                    │ • Update        │
│   Access        │                                    │   Password      │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   USER DATA     │                                    │   VERIFICATION  │
│   STORE         │                                    │   EMAILS        │
│                 │                                    │                 │
│ • User          │                                    │ • Email         │
│   Profiles      │                                    │   Templates     │
│ • Session       │                                    │ • SMTP          │
│   Data          │                                    │   Configuration │
│ • Password      │                                    │ • Delivery      │
│   Hashes        │                                    │   Status        │
└─────────────────┘                                    └─────────────────┘
```

### 2.0 COMPLAINT PROCESSING

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           2.0 COMPLAINT PROCESSING                         │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────┐                                    ┌─────────────────┐
│     CITIZEN     │                                    │   COMPLAINT     │
│                 │                                    │   DATA          │
│ • Title         │                                    │                 │
│ • Description   │                                    │ • Title         │
│ • Location      │                                    │ • Description   │
│ • Photos        │                                    │ • Coordinates   │
│ • Category      │                                    │ • Media Files   │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   2.1 COMPLAINT │                                    │   2.2 FILE      │
│   VALIDATION    │                                    │   UPLOAD        │
│                 │                                    │                 │
│ • Input         │                                    │ • File          │
│   Validation    │                                    │   Validation    │
│ • Location      │                                    │ • Image         │
│   Verification  │                                    │   Processing    │
│ • Category      │                                    │ • Storage       │
│   Assignment    │                                    │ • Path          │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   2.3 AI        │                                    │   2.4 COMPLAINT │
│   PREDICTION    │                                    │   STORAGE       │
│                 │                                    │                 │
│ • Text          │                                    │ • Database      │
│   Analysis      │                                    │   Storage       │
│ • Department    │                                    │ • Reference     │
│   Prediction    │                                    │   Number        │
│ • Confidence    │                                    │   Generation    │
│   Score         │                                    │ • Status        │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   2.5 STATUS    │                                    │   COMPLAINT     │
│   TRACKING      │                                    │   DATABASE      │
│                 │                                    │                 │
│ • Status        │                                    │ • Complaint     │
│   Updates       │                                    │   Records       │
│ • Department    │                                    │ • Status        │
│   Assignment    │                                    │   History       │
│ • Timeline      │                                    │ • File          │
│   Tracking      │                                    │   References    │
└─────────────────┘                                    └─────────────────┘
```

### 3.0 AI/ML PREDICTION PROCESS

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           3.0 AI/ML PREDICTION                             │
└─────────────────────────────────────────────────────────────────────────────┐

┌─────────────────┐                                    ┌─────────────────┐
│   COMPLAINT     │                                    │   TRAINING      │
│   TEXT          │                                    │   DATA          │
│                 │                                    │                 │
│ • Description   │                                    │ • Historical    │
│ • Title         │                                    │   Complaints    │
│ • Keywords      │                                    │ • Department    │
│ • Context       │                                    │   Labels        │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   3.1 TEXT      │                                    │   3.2 MODEL     │
│   PREPROCESSING │                                    │   TRAINING      │
│                 │                                    │                 │
│ • Tokenization  │                                    │ • TF-IDF        │
│ • Cleaning      │                                    │   Vectorization │
│ • Normalization │                                    │ • Naive Bayes   │
│ • Feature       │                                    │   Training      │
│   Extraction    │                                    │ • Model         │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   3.3 PREDICTION│                                    │   3.4 MODEL     │
│   GENERATION    │                                    │   STORAGE       │
│                 │                                    │                 │
│ • Department    │                                    │ • Pickle Files  │
│   Classification│                                    │ • Vectorizer    │
│ • Confidence    │                                    │ • Model         │
│   Score         │                                    │ • Version       │
│ • Alternative   │                                    │ • Control       │
│   Suggestions   │                                    │   Metadata      │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   PREDICTION    │                                    │   MODEL         │
│   RESULTS       │                                    │   FILES         │
│                 │                                    │                 │
│ • Department    │                                    │ • complaint_    │
│   ID            │                                    │   model.pkl     │
│ • Confidence    │                                    │ • vectorizer.   │
│   Level         │                                    │   pkl           │
│ • Suggestions   │                                    │ • training_     │
│ • Timestamp     │                                    │   data.csv      │
└─────────────────┘                                    └─────────────────┘
```

### 4.0 DEPARTMENT MANAGEMENT

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           4.0 DEPARTMENT MANAGEMENT                        │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────┐                                    ┌─────────────────┐
│   DEPARTMENT    │                                    │   COMPLAINT     │
│   STAFF         │                                    │   ASSIGNMENTS   │
│                 │                                    │                 │
│ • Login         │                                    │ • Department    │
│ • Dashboard     │                                    │   ID            │
│ • Status        │                                    │ • Complaint     │
│   Updates       │                                    │   ID            │
│ • Solution      │                                    │ • Assignment    │
│   Uploads       │                                    │   Date          │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   4.1 COMPLAINT │                                    │   4.2 STATUS    │
│   ASSIGNMENT    │                                    │   MANAGEMENT    │
│                 │                                    │                 │
│ • View          │                                    │ • Update        │
│   Assigned      │                                    │   Status        │
│   Complaints    │                                    │ • Add Notes     │
│ • Filter by     │                                    │ • Upload        │
│   Status        │                                    │   Solutions     │
│ • Sort by       │                                    │ • Timeline      │
│   Priority      │                                    │   Tracking      │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   4.3 SOLUTION  │                                    │   4.4 ANALYTICS │
│   UPLOAD        │                                    │   GENERATION    │
│                 │                                    │                 │
│ • Before        │                                    │ • Performance   │
│   Photos        │                                    │   Metrics       │
│ • After Photos  │                                    │ • Response      │
│ • Description   │                                    │   Times         │
│ • Completion    │                                    │ • Success       │
│   Notes         │                                    │   Rates         │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   SOLUTION      │                                    │   ANALYTICS     │
│   FILES         │                                    │   DATA          │
│                 │                                    │                 │
│ • Image Files   │                                    │ • Performance   │
│ • Documentation │                                    │   Reports       │
│ • Completion    │                                    │ • Statistics    │
│   Reports       │                                    │ • Charts        │
└─────────────────┘                                    └─────────────────┘
```

### 5.0 REVIEW SYSTEM

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           5.0 REVIEW SYSTEM                                 │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────┐                                    ┌─────────────────┐
│     CITIZEN     │                                    │   RESOLVED      │
│                 │                                    │   COMPLAINTS    │
│ • Before Photos │                                    │                 │
│ • After Photos  │                                    │ • Complaint     │
│ • Review Text   │                                    │   ID            │
│ • Rating        │                                    │ • Resolution    │
│ • Feedback      │                                    │   Date          │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   5.1 REVIEW    │                                    │   5.2 PHOTO     │
│   VALIDATION    │                                    │   PROCESSING    │
│                 │                                    │                 │
│ • Complaint     │                                    │ • Image         │
│   Verification  │                                    │   Validation    │
│ • Status Check  │                                    │ • Compression   │
│ • User          │                                    │ • Storage       │
│   Verification  │                                    │ • Path          │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   5.3 REVIEW    │                                    │   5.4 RATING    │
│   STORAGE       │                                    │   CALCULATION   │
│                 │                                    │                 │
│ • Database      │                                    │ • Average       │
│   Storage       │                                    │   Rating        │
│ • Photo         │                                    │ • Department    │
│   References    │                                    │   Performance   │
│ • Text          │                                    │ • User          │
│   Storage       │                                    │   Satisfaction  │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   REVIEW        │                                    │   RATING        │
│   DATABASE      │                                    │   STATISTICS    │
│                 │                                    │                 │
│ • Review        │                                    │ • Department    │
│   Records       │                                    │   Ratings       │
│ • Photo Files   │                                    │ • Average       │
│ • User          │                                    │   Scores        │
│   Feedback      │                                    │ • Performance   │
└─────────────────┘                                    └─────────────────┘
```

### 6.0 ADMIN MANAGEMENT

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           6.0 ADMIN MANAGEMENT                              │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────┐                                    ┌─────────────────┐
│   ADMIN USER    │                                    │   SYSTEM        │
│                 │                                    │   DATA          │
│ • User Mgmt     │                                    │                 │
│ • Department    │                                    │ • User          │
│   Mgmt          │                                    │   Data          │
│ • System Config │                                    │ • Department    │
│ • Analytics     │                                    │   Data          │
│ • Reports       │                                    │ • Complaint     │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   6.1 USER      │                                    │   6.2 DEPARTMENT│
│   MANAGEMENT    │                                    │   MANAGEMENT    │
│                 │                                    │                 │
│ • View Users    │                                    │ • Add           │
│ • Edit Users    │                                    │   Departments   │
│ • Delete Users  │                                    │ • Edit          │
│ • Role          │                                    │   Departments   │
│   Management    │                                    │ • Delete        │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   6.3 SYSTEM    │                                    │   6.4 ANALYTICS │
│   CONFIGURATION │                                    │   GENERATION    │
│                 │                                    │                 │
│ • System        │                                    │ • Performance   │
│   Settings      │                                    │   Reports       │
│ • Email Config  │                                    │ • User          │
│ • Security      │                                    │   Statistics    │
│   Settings      │                                    │ • Department    │
└─────────────────┘                                    └─────────────────┘
         │                                                       │
         │                                                       │
         ▼                                                       ▼
┌─────────────────┐                                    ┌─────────────────┐
│   CONFIGURATION │                                    │   ANALYTICS     │
│   DATA          │                                    │   REPORTS       │
│                 │                                    │                 │
│ • System        │                                    │ • Performance   │
│   Parameters    │                                    │   Metrics       │
│ • Email         │                                    │ • User          │
│   Settings      │                                    │   Growth        │
│ • Security      │                                    │ • Department    │
│   Config        │                                    │   Performance   │
└─────────────────┘                                    └─────────────────┘
```

## Data Stores

### Primary Data Stores
```
┌─────────────────────────────────────────────────────────────────────────────┐
│                              DATA STORES                                     │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐
│   USERS         │ │  COMPLAINTS     │ │  DEPARTMENTS    │ │   REVIEWS       │
│                 │ │                 │ │                 │ │                 │
│ • user_id (PK)  │ │ • complaint_id  │ │ • dept_id (PK)  │ │ • review_id     │
│ • name          │ │   (PK)          │ │ • dept_name     │ │   (PK)          │
│ • email         │ │ • user_id (FK)  │ │ • description   │ │ • user_id (FK)  │
│ • mobile        │ │ • dept_id (FK)  │ │ • contact_email │ │ • ref_number    │
│ • district      │ │ • title         │ │ • password      │ │   (FK)          │
│ • password_hash │ │ • description   │ │ • logo          │ │ • before_image  │
│ • profile_pic   │ │ • location_lat  │ │ • status        │ │ • after_image   │
│ • role          │ │ • location_lng  │ │ • created_at    │ │ • review_text   │
│ • created_at    │ │ • media_path    │ │ • role          │ │ • created_at    │
│ • reset_otp     │ │ • status        │ └─────────────────┘ └─────────────────┘
│ • reset_otp_exp │ │ • ref_number    │
└─────────────────┘ │ • rejection_reason│ ┌─────────────────┐ ┌─────────────────┐
                    │ • created_at     │ │ PASSWORD_RESET  │ │   FILE_UPLOADS  │
                    └─────────────────┘ │   REQUESTS       │ │                 │
                                        │                 │ │ • file_id (PK)  │
                                        │ • id (PK)       │ │ • original_name │
                                        │ • user_id (FK)  │ │ • stored_name   │
                                        │ • username      │ │ • file_path     │
                                        │ • email         │ │ • file_type     │
                                        │ • description   │ │ • file_size     │
                                        │ • requested_pwd │ │ • upload_type   │
                                        │ • created_at    │ │ • uploaded_by   │
                                        │ • read          │ │ • created_at    │
                                        └─────────────────┘ └─────────────────┘
```

## External Entities

### External Systems
```
┌─────────────────────────────────────────────────────────────────────────────┐
│                            EXTERNAL ENTITIES                                │
└─────────────────────────────────────────────────────────────────────────────┘

┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐
│   EMAIL         │ │   SMS GATEWAY   │ │   AI/ML         │ │   FILE STORAGE  │
│   SERVICE       │ │                 │ │   MODELS        │ │                 │
│                 │ │                 │ │                 │ │                 │
│ • SMTP Server   │ │ • SMS Provider  │ │ • Python        │ │ • Local Storage │
│ • Email         │ │ • API Endpoints │ │   Scripts       │ │ • Cloud Storage │
│   Templates     │ │ • Delivery      │ │ • Scikit-learn  │ │ • CDN           │
│ • Delivery      │ │   Reports       │ │ • Model Files   │ │ • Backup        │
│   Status        │ │ • Error         │ │ • Training Data │ │   Systems       │
└─────────────────┘ │   Handling      │ └─────────────────┘ └─────────────────┘
                    └─────────────────┘
```

## Data Flow Summary

### Input Flows
1. **Citizen Registration**: Personal data → User Management → User Database
2. **Complaint Submission**: Complaint data + files → Complaint Processing → Complaint Database
3. **Department Login**: Credentials → Authentication → Department Dashboard
4. **Status Updates**: Status data → Department Management → Complaint Database
5. **Review Submission**: Review data + photos → Review System → Review Database

### Output Flows
1. **Email Notifications**: System events → Notification System → Email Service
2. **SMS Alerts**: Status updates → Notification System → SMS Gateway
3. **Analytics Reports**: System data → Admin Management → Analytics Reports
4. **AI Predictions**: Complaint text → AI/ML Process → Department Assignment

### Internal Flows
1. **User Authentication**: Login data → User Management → Session Management
2. **Complaint Tracking**: Reference number → Complaint Processing → Status Display
3. **File Management**: Upload requests → File Processing → Storage System
4. **Data Analytics**: System data → Analytics Generation → Performance Reports

---

**DFD Version**: 1.0  
**Last Updated**: December 2024  
**Levels**: Context (0), System (1), Process (2) 