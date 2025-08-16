# FixLanka - Pseudo Code Documentation

## Overview
This document provides comprehensive pseudo code for the FixLanka Smart Citizen Complaint & Resolution System, excluding AI prediction functionality.

## 1. User Management Pseudo Code

### 1.1 User Registration
```
FUNCTION registerUser(name, email, mobile, district, password, confirmPassword)
    BEGIN
        // Validate input data
        IF validateInput(name, email, mobile, district, password) == FALSE THEN
            RETURN error("Invalid input data")
        END IF
        
        // Check if email already exists
        IF checkEmailExists(email) == TRUE THEN
            RETURN error("Email already registered")
        END IF
        
        // Validate password confirmation
        IF password != confirmPassword THEN
            RETURN error("Passwords do not match")
        END IF
        
        // Hash password
        hashedPassword = hashPassword(password)
        
        // Generate verification token
        verificationToken = generateRandomToken()
        
        // Create user account
        userData = {
            name: name,
            email: email,
            mobile: mobile,
            district: district,
            password_hash: hashedPassword,
            role: "citizen",
            verification_token: verificationToken,
            status: "pending",
            created_at: currentTimestamp()
        }
        
        // Save user to database
        userId = insertUser(userData)
        
        // Send verification email
        sendVerificationEmail(email, verificationToken)
        
        RETURN success("Registration successful. Please check your email for verification.")
    END FUNCTION
```

### 1.2 User Login
```
FUNCTION loginUser(email, password)
    BEGIN
        // Validate input
        IF isEmpty(email) OR isEmpty(password) THEN
            RETURN error("Email and password are required")
        END IF
        
        // Find user by email
        user = findUserByEmail(email)
        
        IF user == NULL THEN
            RETURN error("Invalid email or password")
        END IF
        
        // Verify password
        IF verifyPassword(password, user.password_hash) == FALSE THEN
            RETURN error("Invalid email or password")
        END IF
        
        // Check if account is verified
        IF user.status == "pending" THEN
            RETURN error("Please verify your email before logging in")
        END IF
        
        // Check if account is active
        IF user.status == "inactive" THEN
            RETURN error("Account is deactivated. Contact administrator.")
        END IF
        
        // Create session
        sessionData = {
            user_id: user.user_id,
            name: user.name,
            email: user.email,
            role: user.role,
            login_time: currentTimestamp()
        }
        
        sessionId = createSession(sessionData)
        
        // Update last login time
        updateLastLogin(user.user_id)
        
        RETURN success("Login successful", {session_id: sessionId, user: sessionData})
    END FUNCTION
```

### 1.3 Password Reset
```
FUNCTION requestPasswordReset(email)
    BEGIN
        // Find user by email
        user = findUserByEmail(email)
        
        IF user == NULL THEN
            RETURN error("Email not found")
        END IF
        
        // Generate reset token
        resetToken = generateRandomToken()
        expiryTime = currentTimestamp() + 3600 // 1 hour
        
        // Save reset request
        resetData = {
            user_id: user.user_id,
            email: email,
            reset_token: resetToken,
            expiry_time: expiryTime,
            created_at: currentTimestamp()
        }
        
        insertPasswordReset(resetData)
        
        // Send reset email
        sendPasswordResetEmail(email, resetToken)
        
        RETURN success("Password reset link sent to your email")
    END FUNCTION

FUNCTION resetPassword(token, newPassword, confirmPassword)
    BEGIN
        // Validate input
        IF newPassword != confirmPassword THEN
            RETURN error("Passwords do not match")
        END IF
        
        // Find reset request
        resetRequest = findPasswordResetByToken(token)
        
        IF resetRequest == NULL THEN
            RETURN error("Invalid reset token")
        END IF
        
        // Check if token is expired
        IF currentTimestamp() > resetRequest.expiry_time THEN
            RETURN error("Reset token has expired")
        END IF
        
        // Hash new password
        hashedPassword = hashPassword(newPassword)
        
        // Update user password
        updateUserPassword(resetRequest.user_id, hashedPassword)
        
        // Delete reset request
        deletePasswordReset(token)
        
        RETURN success("Password reset successful")
    END FUNCTION
```

## 2. Complaint Management Pseudo Code

### 2.1 Submit Complaint
```
FUNCTION submitComplaint(userId, title, description, locationLat, locationLng, mediaFiles)
    BEGIN
        // Validate input
        IF isEmpty(title) OR isEmpty(description) THEN
            RETURN error("Title and description are required")
        END IF
        
        // Validate location
        IF locationLat == 0 AND locationLng == 0 THEN
            RETURN error("Please select a location on the map")
        END IF
        
        // Generate reference number
        refNumber = generateReferenceNumber()
        
        // Process uploaded files
        uploadedFiles = []
        IF mediaFiles != NULL THEN
            FOR EACH file IN mediaFiles
                IF validateFile(file) == TRUE THEN
                    filePath = uploadFile(file)
                    uploadedFiles.push(filePath)
                END IF
            END FOR
        END IF
        
        // Create complaint data
        complaintData = {
            user_id: userId,
            title: title,
            description: description,
            location_lat: locationLat,
            location_lng: locationLng,
            media_path: join(uploadedFiles, ","),
            status: "Pending",
            ref_number: refNumber,
            created_at: currentTimestamp()
        }
        
        // Save complaint to database
        complaintId = insertComplaint(complaintData)
        
        // Send notification to admin for department assignment
        sendAdminNotification("New complaint submitted", complaintData)
        
        RETURN success("Complaint submitted successfully", {ref_number: refNumber})
    END FUNCTION
```

### 2.2 Track Complaint Status
```
FUNCTION trackComplaint(refNumber)
    BEGIN
        // Find complaint by reference number
        complaint = findComplaintByRefNumber(refNumber)
        
        IF complaint == NULL THEN
            RETURN error("Complaint not found")
        END IF
        
        // Get department information
        department = findDepartmentById(complaint.dept_id)
        
        // Get user information
        user = findUserById(complaint.user_id)
        
        // Prepare response data
        responseData = {
            ref_number: complaint.ref_number,
            title: complaint.title,
            status: complaint.status,
            description: complaint.description,
            location: {
                lat: complaint.location_lat,
                lng: complaint.location_lng
            },
            department: department ? department.dept_name : "Not assigned",
            created_at: complaint.created_at,
            submitted_by: user.name
        }
        
        RETURN success("Complaint found", responseData)
    END FUNCTION
```

### 2.3 Get User Complaints
```
FUNCTION getUserComplaints(userId, page = 1, limit = 10)
    BEGIN
        // Calculate offset
        offset = (page - 1) * limit
        
        // Get complaints for user
        complaints = findComplaintsByUserId(userId, limit, offset)
        
        // Get total count
        totalCount = countComplaintsByUserId(userId)
        
        // Prepare response
        responseData = {
            complaints: complaints,
            pagination: {
                current_page: page,
                total_pages: ceil(totalCount / limit),
                total_count: totalCount,
                limit: limit
            }
        }
        
        RETURN success("Complaints retrieved", responseData)
    END FUNCTION
```

## 3. Department Management Pseudo Code

### 3.1 Department Login
```
FUNCTION departmentLogin(email, password)
    BEGIN
        // Find department by email
        department = findDepartmentByEmail(email)
        
        IF department == NULL THEN
            RETURN error("Invalid credentials")
        END IF
        
        // Verify password
        IF verifyPassword(password, department.password) == FALSE THEN
            RETURN error("Invalid credentials")
        END IF
        
        // Check if department is active
        IF department.status == "inactive" THEN
            RETURN error("Department account is inactive")
        END IF
        
        // Create session
        sessionData = {
            dept_id: department.dept_id,
            dept_name: department.dept_name,
            email: department.email,
            role: "department",
            login_time: currentTimestamp()
        }
        
        sessionId = createSession(sessionData)
        
        RETURN success("Login successful", {session_id: sessionId, department: sessionData})
    END FUNCTION
```

### 3.2 Get Department Dashboard
```
FUNCTION getDepartmentDashboard(deptId)
    BEGIN
        // Get department information
        department = findDepartmentById(deptId)
        
        // Get complaint statistics
        stats = {
            total: countComplaintsByDepartment(deptId),
            pending: countComplaintsByStatus(deptId, "Pending"),
            in_progress: countComplaintsByStatus(deptId, "In Progress"),
            resolved: countComplaintsByStatus(deptId, "Resolved"),
            rejected: countComplaintsByStatus(deptId, "Rejected")
        }
        
        // Get recent complaints
        recentComplaints = findRecentComplaintsByDepartment(deptId, 10)
        
        // Prepare response
        responseData = {
            department: department,
            statistics: stats,
            recent_complaints: recentComplaints
        }
        
        RETURN success("Dashboard data retrieved", responseData)
    END FUNCTION
```

### 3.3 Update Complaint Status
```
FUNCTION updateComplaintStatus(complaintId, deptId, newStatus, notes, solutionFiles)
    BEGIN
        // Validate complaint belongs to department
        complaint = findComplaintById(complaintId)
        
        IF complaint.dept_id != deptId THEN
            RETURN error("Unauthorized access")
        END IF
        
        // Validate status transition
        IF isValidStatusTransition(complaint.status, newStatus) == FALSE THEN
            RETURN error("Invalid status transition")
        END IF
        
        // Process solution files if status is resolved
        uploadedFiles = []
        IF newStatus == "Resolved" AND solutionFiles != NULL THEN
            FOR EACH file IN solutionFiles
                IF validateFile(file) == TRUE THEN
                    filePath = uploadFile(file)
                    uploadedFiles.push(filePath)
                END IF
            END FOR
        END IF
        
        // Update complaint
        updateData = {
            status: newStatus,
            notes: notes,
            solution_files: join(uploadedFiles, ","),
            updated_at: currentTimestamp()
        }
        
        updateComplaint(complaintId, updateData)
        
        // Send notification to user
        user = findUserById(complaint.user_id)
        sendUserNotification(user.email, "Complaint status updated", {
            ref_number: complaint.ref_number,
            new_status: newStatus,
            notes: notes
        })
        
        RETURN success("Status updated successfully")
    END FUNCTION
```

## 4. Review System Pseudo Code

### 4.1 Submit Review
```
FUNCTION submitReview(userId, refNumber, reviewText, beforeImage, afterImage)
    BEGIN
        // Validate complaint exists and belongs to user
        complaint = findComplaintByRefNumber(refNumber)
        
        IF complaint == NULL THEN
            RETURN error("Complaint not found")
        END IF
        
        IF complaint.user_id != userId THEN
            RETURN error("Unauthorized access")
        END IF
        
        // Check if complaint is resolved
        IF complaint.status != "Resolved" THEN
            RETURN error("Can only review resolved complaints")
        END IF
        
        // Check if review already exists
        existingReview = findReviewByRefNumber(refNumber)
        IF existingReview != NULL THEN
            RETURN error("Review already submitted for this complaint")
        END IF
        
        // Process images
        beforeImagePath = NULL
        afterImagePath = NULL
        
        IF beforeImage != NULL THEN
            IF validateImage(beforeImage) == TRUE THEN
                beforeImagePath = uploadImage(beforeImage)
            END IF
        END IF
        
        IF afterImage != NULL THEN
            IF validateImage(afterImage) == TRUE THEN
                afterImagePath = uploadImage(afterImage)
            END IF
        END IF
        
        // Create review data
        reviewData = {
            user_id: userId,
            ref_number: refNumber,
            review_text: reviewText,
            before_image: beforeImagePath,
            after_image: afterImagePath,
            created_at: currentTimestamp()
        }
        
        // Save review
        reviewId = insertReview(reviewData)
        
        // Update complaint review status
        updateComplaintReviewStatus(refNumber, "reviewed")
        
        RETURN success("Review submitted successfully")
    END FUNCTION
```

### 4.2 Get Reviews
```
FUNCTION getReviews(page = 1, limit = 10)
    BEGIN
        // Calculate offset
        offset = (page - 1) * limit
        
        // Get reviews with user information
        reviews = findReviewsWithUserInfo(limit, offset)
        
        // Get total count
        totalCount = countReviews()
        
        // Prepare response
        responseData = {
            reviews: reviews,
            pagination: {
                current_page: page,
                total_pages: ceil(totalCount / limit),
                total_count: totalCount,
                limit: limit
            }
        }
        
        RETURN success("Reviews retrieved", responseData)
    END FUNCTION
```

## 5. Admin Management Pseudo Code

### 5.1 Admin Login
```
FUNCTION adminLogin(email, password)
    BEGIN
        // Find admin user
        admin = findUserByEmailAndRole(email, "admin")
        
        IF admin == NULL THEN
            RETURN error("Invalid credentials")
        END IF
        
        // Verify password
        IF verifyPassword(password, admin.password_hash) == FALSE THEN
            RETURN error("Invalid credentials")
        END IF
        
        // Create session
        sessionData = {
            user_id: admin.user_id,
            name: admin.name,
            email: admin.email,
            role: "admin",
            login_time: currentTimestamp()
        }
        
        sessionId = createSession(sessionData)
        
        RETURN success("Login successful", {session_id: sessionId, admin: sessionData})
    END FUNCTION
```

### 5.2 Get Admin Dashboard
```
FUNCTION getAdminDashboard()
    BEGIN
        // Get system statistics
        stats = {
            total_users: countUsers(),
            total_complaints: countComplaints(),
            total_departments: countDepartments(),
            pending_complaints: countComplaintsByStatus(NULL, "Pending"),
            resolved_complaints: countComplaintsByStatus(NULL, "Resolved"),
            total_reviews: countReviews()
        }
        
        // Get recent activities
        recentActivities = findRecentActivities(20)
        
        // Get department performance
        departmentPerformance = getDepartmentPerformance()
        
        // Prepare response
        responseData = {
            statistics: stats,
            recent_activities: recentActivities,
            department_performance: departmentPerformance
        }
        
        RETURN success("Dashboard data retrieved", responseData)
    END FUNCTION
```

### 5.3 Manage Users
```
FUNCTION manageUsers(action, userId, userData)
    BEGIN
        SWITCH action
            CASE "view":
                users = findUsersWithPagination(userData.page, userData.limit)
                RETURN success("Users retrieved", users)
                
            CASE "update":
                IF validateUserData(userData) == FALSE THEN
                    RETURN error("Invalid user data")
                END IF
                
                updateUser(userId, userData)
                RETURN success("User updated successfully")
                
            CASE "delete":
                // Check if user has active complaints
                activeComplaints = countActiveComplaintsByUser(userId)
                IF activeComplaints > 0 THEN
                    RETURN error("Cannot delete user with active complaints")
                END IF
                
                deleteUser(userId)
                RETURN success("User deleted successfully")
                
            CASE "activate":
                updateUserStatus(userId, "active")
                RETURN success("User activated successfully")
                
            CASE "deactivate":
                updateUserStatus(userId, "inactive")
                RETURN success("User deactivated successfully")
                
            DEFAULT:
                RETURN error("Invalid action")
        END SWITCH
    END FUNCTION
```

### 5.4 Manage Departments
```
FUNCTION manageDepartments(action, deptId, deptData)
    BEGIN
        SWITCH action
            CASE "add":
                IF validateDepartmentData(deptData) == FALSE THEN
                    RETURN error("Invalid department data")
                END IF
                
                // Hash password
                deptData.password = hashPassword(deptData.password)
                
                insertDepartment(deptData)
                RETURN success("Department added successfully")
                
            CASE "update":
                IF validateDepartmentData(deptData) == FALSE THEN
                    RETURN error("Invalid department data")
                END IF
                
                updateDepartment(deptId, deptData)
                RETURN success("Department updated successfully")
                
            CASE "delete":
                // Check if department has active complaints
                activeComplaints = countActiveComplaintsByDepartment(deptId)
                IF activeComplaints > 0 THEN
                    RETURN error("Cannot delete department with active complaints")
                END IF
                
                deleteDepartment(deptId)
                RETURN success("Department deleted successfully")
                
            CASE "activate":
                updateDepartmentStatus(deptId, "active")
                RETURN success("Department activated successfully")
                
            CASE "deactivate":
                updateDepartmentStatus(deptId, "inactive")
                RETURN success("Department deactivated successfully")
                
            DEFAULT:
                RETURN error("Invalid action")
        END SWITCH
    END FUNCTION
```

### 5.5 Reassign Complaint
```
FUNCTION reassignComplaint(complaintId, newDeptId, reason)
    BEGIN
        // Get complaint
        complaint = findComplaintById(complaintId)
        
        IF complaint == NULL THEN
            RETURN error("Complaint not found")
        END IF
        
        // Get new department
        newDepartment = findDepartmentById(newDeptId)
        
        IF newDepartment == NULL THEN
            RETURN error("Department not found")
        END IF
        
        // Update complaint
        updateData = {
            dept_id: newDeptId,
            reassignment_reason: reason,
            reassigned_at: currentTimestamp()
        }
        
        updateComplaint(complaintId, updateData)
        
        // Notify new department
        sendDepartmentNotification(newDepartment.email, "New complaint assigned", {
            complaint_id: complaintId,
            title: complaint.title,
            reason: reason
        })
        
        // Notify user
        user = findUserById(complaint.user_id)
        sendUserNotification(user.email, "Complaint reassigned", {
            ref_number: complaint.ref_number,
            new_department: newDepartment.dept_name,
            reason: reason
        })
        
        RETURN success("Complaint reassigned successfully")
    END FUNCTION
```

## 6. Utility Functions Pseudo Code

### 6.1 Input Validation
```
FUNCTION validateInput(name, email, mobile, district, password)
    BEGIN
        // Validate name
        IF length(name) < 2 OR length(name) > 100 THEN
            RETURN FALSE
        END IF
        
        // Validate email
        IF isValidEmail(email) == FALSE THEN
            RETURN FALSE
        END IF
        
        // Validate mobile
        IF isValidMobile(mobile) == FALSE THEN
            RETURN FALSE
        END IF
        
        // Validate district
        IF isEmpty(district) THEN
            RETURN FALSE
        END IF
        
        // Validate password
        IF length(password) < 8 THEN
            RETURN FALSE
        END IF
        
        RETURN TRUE
    END FUNCTION
```

### 6.2 File Upload
```
FUNCTION uploadFile(file)
    BEGIN
        // Validate file type
        allowedTypes = ["jpg", "jpeg", "png", "gif"]
        fileExtension = getFileExtension(file.name)
        
        IF fileExtension NOT IN allowedTypes THEN
            RETURN error("Invalid file type")
        END IF
        
        // Validate file size (5MB limit)
        IF file.size > 5242880 THEN
            RETURN error("File too large")
        END IF
        
        // Generate unique filename
        timestamp = currentTimestamp()
        randomString = generateRandomString(8)
        filename = timestamp + "_" + randomString + "." + fileExtension
        
        // Create upload path
        uploadPath = "uploads/" + filename
        
        // Save file
        saveFile(file, uploadPath)
        
        RETURN uploadPath
    END FUNCTION
```

### 6.3 Reference Number Generation
```
FUNCTION generateReferenceNumber()
    BEGIN
        prefix = "FL"
        timestamp = currentTimestamp()
        randomString = generateRandomString(8)
        
        refNumber = prefix + "-" + randomString
        
        // Check if reference number already exists
        WHILE checkRefNumberExists(refNumber) == TRUE
            randomString = generateRandomString(8)
            refNumber = prefix + "-" + randomString
        END WHILE
        
        RETURN refNumber
    END FUNCTION
```

### 6.4 Email Notification
```
FUNCTION sendEmail(to, subject, message, template = NULL)
    BEGIN
        // Prepare email data
        emailData = {
            to: to,
            subject: subject,
            message: message,
            template: template,
            sent_at: currentTimestamp()
        }
        
        // Send email using PHPMailer
        mailer = new PHPMailer()
        mailer.setFrom("noreply@fixlanka.com", "FixLanka System")
        mailer.addAddress(to)
        mailer.setSubject(subject)
        mailer.setBody(message)
        
        // Send email
        IF mailer.send() == TRUE THEN
            // Log successful email
            logEmail(emailData, "sent")
            RETURN TRUE
        ELSE
            // Log failed email
            logEmail(emailData, "failed")
            RETURN FALSE
        END IF
    END FUNCTION
```

## 7. Database Operations Pseudo Code

### 7.1 Database Connection
```
FUNCTION connectDatabase()
    BEGIN
        config = {
            host: "localhost",
            username: "root",
            password: "",
            database: "fixlanka"
        }
        
        connection = new mysqli(config.host, config.username, config.password, config.database)
        
        IF connection.connect_error THEN
            RETURN error("Database connection failed")
        END IF
        
        RETURN connection
    END FUNCTION
```

### 7.2 Query Execution
```
FUNCTION executeQuery(query, params = NULL)
    BEGIN
        connection = connectDatabase()
        
        IF params != NULL THEN
            statement = connection.prepare(query)
            statement.bind_param(params)
            result = statement.execute()
        ELSE
            result = connection.query(query)
        END IF
        
        IF result == FALSE THEN
            RETURN error("Query execution failed")
        END IF
        
        RETURN result
    END FUNCTION
```

---

**Pseudo Code Version**: 1.0  
**Last Updated**: December 2024  
**Note**: AI prediction functionality has been removed from all pseudo code 