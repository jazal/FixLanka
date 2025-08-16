# FixLanka - Sequence Diagrams

This document contains all major sequence diagrams for the FixLanka system, using Mermaid syntax for easy visualization.

---

## 1. User Registration
```mermaid
sequenceDiagram
    participant User
    participant WebApp
    participant DB
    User->>WebApp: Fill registration form
    WebApp->>WebApp: Validate input
    WebApp->>DB: Check if email exists
    DB-->>WebApp: Email exists?
    alt Email not exists
        WebApp->>WebApp: Hash password
        WebApp->>DB: Insert user record (pending)
        DB-->>WebApp: Success
        WebApp->>User: Show success, send verification email
    else Email exists
        WebApp->>User: Show error (already registered)
    end
```

---

## 2. User Login
```mermaid
sequenceDiagram
    participant User
    participant WebApp
    participant DB
    User->>WebApp: Enter email & password
    WebApp->>DB: Fetch user by email
    DB-->>WebApp: User record
    WebApp->>WebApp: Verify password
    alt Valid credentials
        WebApp->>User: Login success, create session
    else Invalid credentials
        WebApp->>User: Show error
    end
```

---

## 3. Submit Complaint
```mermaid
sequenceDiagram
    participant Citizen
    participant WebApp
    participant DB
    Citizen->>WebApp: Fill complaint form (details, location, files)
    WebApp->>WebApp: Validate input & files
    WebApp->>DB: Insert complaint record
    DB-->>WebApp: Complaint ID
    WebApp->>DB: Save uploaded files
    WebApp->>Citizen: Show reference number, success message
    WebApp->>Admin: Notify new complaint (internal)
```

---

## 4. Track Complaint Status
```mermaid
sequenceDiagram
    participant User
    participant WebApp
    participant DB
    User->>WebApp: Enter reference number
    WebApp->>DB: Fetch complaint by ref number
    DB-->>WebApp: Complaint details
    alt Found
        WebApp->>User: Show status, details
    else Not found
        WebApp->>User: Show error
    end
```

---

## 5. Department Login
```mermaid
sequenceDiagram
    participant Department
    participant WebApp
    participant DB
    Department->>WebApp: Enter email & password
    WebApp->>DB: Fetch department by email
    DB-->>WebApp: Department record
    WebApp->>WebApp: Verify password
    alt Valid
        WebApp->>Department: Login success, dashboard
    else Invalid
        WebApp->>Department: Show error
    end
```

---

## 6. Department Updates Complaint Status
```mermaid
sequenceDiagram
    participant Department
    participant WebApp
    participant DB
    Department->>WebApp: Select complaint, update status
    WebApp->>WebApp: Validate action
    WebApp->>DB: Update complaint status
    DB-->>WebApp: Success
    WebApp->>Citizen: Notify status update (email/notification)
    WebApp->>Department: Show confirmation
```

---

## 7. Admin Reassigns Complaint
```mermaid
sequenceDiagram
    participant Admin
    participant WebApp
    participant DB
    Admin->>WebApp: Select complaint, choose new department
    WebApp->>DB: Update complaint dept_id, log reason
    DB-->>WebApp: Success
    WebApp->>Department: Notify new assignment
    WebApp->>Citizen: Notify reassignment
    WebApp->>Admin: Show confirmation
```

---

## 8. Submit Review
```mermaid
sequenceDiagram
    participant Citizen
    participant WebApp
    participant DB
    Citizen->>WebApp: Fill review form (text, images)
    WebApp->>WebApp: Validate input
    WebApp->>DB: Insert review record
    DB-->>WebApp: Success
    WebApp->>DB: Update complaint review status
    WebApp->>Citizen: Show success
```

---

## 9. Password Reset
```mermaid
sequenceDiagram
    participant User
    participant WebApp
    participant DB
    User->>WebApp: Request password reset
    WebApp->>DB: Find user by email
    DB-->>WebApp: User exists?
    alt Exists
        WebApp->>WebApp: Generate token
        WebApp->>DB: Save token
        WebApp->>User: Send reset email
    else Not found
        WebApp->>User: Show error
    end
    User->>WebApp: Open reset link, enter new password
    WebApp->>DB: Validate token
    alt Valid
        WebApp->>DB: Update password
        WebApp->>User: Show success
    else Invalid/Expired
        WebApp->>User: Show error
    end
```

---

## 10. Logout
```mermaid
sequenceDiagram
    participant User
    participant WebApp
    User->>WebApp: Click logout
    WebApp->>WebApp: Destroy session
    WebApp->>User: Redirect to home/login
```

---

**Note:** These diagrams can be rendered using Mermaid.js or compatible Markdown viewers. They provide a clear, step-by-step visualization of the main interactions in the FixLanka system. 