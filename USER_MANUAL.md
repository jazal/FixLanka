# FixLanka User Manual

## Table of Contents
1. [Introduction](#introduction)
2. [Getting Started](#getting-started)
3. [Citizen Portal](#citizen-portal)
4. [Department Portal](#department-portal)
5. [Admin Portal](#admin-portal)
6. [Troubleshooting](#troubleshooting)
7. [FAQ](#faq)

## Introduction

### What is FixLanka?
FixLanka is a Smart Citizen Complaint & Resolution System designed to help citizens report public problems and get them resolved efficiently. The system uses AI technology to automatically route complaints to the appropriate government departments.

### Key Features
- **Easy Complaint Submission**: Submit complaints with photos and location
- **AI-Powered Routing**: Automatic department assignment
- **Real-time Tracking**: Track complaint status with reference numbers
- **Review System**: Share before/after photos and feedback
- **Multi-role Access**: Separate portals for citizens, departments, and administrators

### Supported Departments
- **CEB**: Ceylon Electricity Board (Power issues)
- **RDA**: Road Development Authority (Road problems)
- **SLTB**: Sri Lanka Transport Board (Transport issues)
- **NWSDB**: National Water Supply and Drainage Board (Water problems)
- **Police**: Sri Lanka Police (Security issues)
- **DMC**: Disaster Management Centre (Emergency situations)
- **MOH**: Ministry of Health (Health-related issues)
- **SLR**: Sri Lanka Railways (Railway problems)

## Getting Started

### System Requirements
- **Web Browser**: Chrome, Firefox, Safari, or Edge (latest versions)
- **Internet Connection**: Required for accessing the system
- **Device**: Computer, tablet, or smartphone
- **Camera**: For taking photos of issues (optional but recommended)

### Accessing the System
1. Open your web browser
2. Navigate to: `http://localhost/FixLanka/` (for local installation)
3. You'll see the FixLanka homepage

### How It Works
```
1. CLICK - Register or login to your account
2. SNAP - Take photos of the problem
3. SUBMIT - Submit your complaint with details
4. TRACK - Monitor the status of your complaint
```

## Citizen Portal

### Registration

#### Step 1: Create Account
1. Click "Register" on the homepage
2. Fill in your details:
   - **Full Name**: Your complete name
   - **Email**: Valid email address
   - **Mobile**: Phone number with country code
   - **District**: Your district of residence
   - **Password**: Strong password (8+ characters)
   - **Confirm Password**: Re-enter your password
3. Click "Register" to create your account

#### Step 2: Email Verification
1. Check your email for verification link
2. Click the verification link to activate your account
3. Return to FixLanka and login

### Login
1. Click "Login" on the homepage
2. Enter your email and password
3. Click "Login" to access your account

### Submitting a Complaint

#### Step 1: Access Complaint Form
1. Login to your account
2. Click "Report an Issue" button
3. You'll be redirected to the complaint submission form

#### Step 2: Fill Complaint Details
1. **Title**: Brief description of the problem (e.g., "Broken Street Light")
2. **Description**: Detailed explanation of the issue
   - Include location details
   - Describe the problem clearly
   - Mention any safety concerns
3. **Location**: 
   - Click on the map to set exact location
   - Or enter coordinates manually
   - Provide address details

#### Step 3: Upload Photos
1. **Take Photos**: Use your device camera to capture the problem
2. **Photo Guidelines**:
   - Take clear, well-lit photos
   - Include multiple angles if possible
   - Show the scale of the problem
   - Avoid blurry or dark images
3. **File Requirements**:
   - Supported formats: JPG, JPEG, PNG, GIF
   - Maximum size: 5MB per photo
   - Maximum photos: 3 per complaint

#### Step 4: Submit Complaint
1. Review all information
2. Click "Submit Complaint"
3. Note your reference number for tracking

### Tracking Complaints

#### Method 1: My Complaints Page
1. Login to your account
2. Click "My Complaints" in the navigation
3. View all your submitted complaints
4. Check status updates and department responses

#### Method 2: Reference Number Tracking
1. On the homepage, find the tracking section
2. Enter your reference number
3. Click "Check Status"
4. View current status and details

### Complaint Statuses
- **Pending**: Complaint received, awaiting department review
- **In Progress**: Department is working on the issue
- **Resolved**: Problem has been fixed
- **Rejected**: Complaint cannot be processed (with reason)

### Submitting Reviews

#### When to Submit a Review
- After your complaint is marked as "Resolved"
- When you want to provide feedback on the solution
- To share before/after photos

#### How to Submit a Review
1. Go to "My Complaints" page
2. Find the resolved complaint
3. Click "Submit Review"
4. Upload before and after photos
5. Write your feedback message
6. Submit the review

#### Review Guidelines
- **Before Photos**: Show the original problem
- **After Photos**: Show the solution/repair
- **Feedback**: Be constructive and specific
- **Privacy**: Don't include personal information

### Profile Management

#### Update Profile
1. Click your profile picture in the navigation
2. Update your information:
   - Name
   - Mobile number
   - District
   - Profile picture
3. Click "Update Profile"

#### Change Password
1. Go to "Change Password" page
2. Enter current password
3. Enter new password
4. Confirm new password
5. Click "Change Password"

#### Forgot Password
1. Click "Forgot Password" on login page
2. Enter your email address
3. Check email for reset link
4. Click the link and set new password

## Department Portal

### Login
1. Navigate to the login page
2. Enter department credentials
3. Select "Department" role
4. Click "Login"

### Dashboard Overview
The department dashboard shows:
- **Total Complaints**: All assigned complaints
- **Pending**: Complaints awaiting action
- **In Progress**: Complaints being worked on
- **Resolved**: Completed complaints
- **Recent Activity**: Latest updates

### Managing Complaints

#### View Assigned Complaints
1. Login to department portal
2. View complaint list on dashboard
3. Filter by status if needed
4. Click on complaint to view details

#### Update Complaint Status
1. Select a complaint from the list
2. Click "Update Status"
3. Choose new status:
   - **In Progress**: Work has started
   - **Resolved**: Problem fixed
   - **Rejected**: Cannot be processed
4. Add notes explaining the update
5. Upload solution photos (if resolved)
6. Click "Update"

#### Upload Solution Photos
1. When marking complaint as resolved
2. Click "Upload Photos"
3. Take photos of the solution
4. Add captions if needed
5. Submit photos with status update

### Department Statistics
- **Monthly Reports**: View complaint statistics
- **Response Times**: Track average resolution time
- **Success Rate**: Percentage of resolved complaints
- **Popular Issues**: Most common problems

## Admin Portal

### Login
1. Navigate to the login page
2. Enter admin credentials
3. Select "Admin" role
4. Click "Login"

### Dashboard Overview
The admin dashboard displays:
- **System Statistics**: Total users, complaints, departments
- **Recent Activities**: Latest system events
- **Quick Actions**: Common admin tasks
- **Performance Metrics**: System performance data

### User Management

#### View All Users
1. Click "Manage Accounts" in admin menu
2. View list of all registered users
3. Filter by role, status, or date
4. Search for specific users

#### Manage User Accounts
1. Select a user from the list
2. Choose action:
   - **Activate**: Enable user account
   - **Deactivate**: Disable user account
   - **Delete**: Remove user account
   - **Change Role**: Modify user permissions
3. Confirm the action

#### Handle Password Requests
1. Go to "Password Requests" page
2. View pending password reset requests
3. Approve or reject requests
4. Send reset emails to approved users

### Department Management

#### Add New Department
1. Click "Add Department" in admin menu
2. Fill department details:
   - **Department Name**: Official name
   - **Description**: Brief description
   - **Contact Email**: Department email
3. Click "Add Department"

#### Manage Existing Departments
1. View list of all departments
2. Edit department information
3. Activate/deactivate departments
4. Update contact information

### Complaint Management

#### View All Complaints
1. Access complaint management section
2. View complaints from all departments
3. Filter by status, department, or date
4. Search by reference number

#### Reassign Complaints
1. Select a complaint
2. Click "Reassign"
3. Choose new department
4. Add reason for reassignment
5. Confirm reassignment

#### Manage Rejected Complaints
1. Go to "Rejected Complaints" page
2. Review rejection reasons
3. Reassign if appropriate
4. Update status if needed

### System Analytics
- **User Growth**: New user registrations
- **Complaint Trends**: Popular issues over time
- **Department Performance**: Resolution rates by department
- **Response Times**: Average time to resolution

## Troubleshooting

### Common Issues

#### Login Problems
**Problem**: Cannot login to account
**Solutions**:
1. Check email and password spelling
2. Ensure Caps Lock is off
3. Try "Forgot Password" option
4. Contact admin if account is locked

#### Photo Upload Issues
**Problem**: Photos not uploading
**Solutions**:
1. Check file size (max 5MB)
2. Ensure file format is supported (JPG, PNG, GIF)
3. Check internet connection
4. Try refreshing the page

#### Complaint Not Showing
**Problem**: Submitted complaint not appearing
**Solutions**:
1. Check "My Complaints" page
2. Verify submission was successful
3. Check for confirmation message
4. Contact support if issue persists

#### AI Prediction Issues
**Problem**: Wrong department assigned
**Solutions**:
1. Provide more detailed description
2. Use specific keywords related to the issue
3. Contact admin to request reassignment
4. The system learns from feedback

### Error Messages

#### "Email Already Exists"
- Use different email address
- Try "Forgot Password" if you own the account
- Contact support for assistance

#### "Invalid File Type"
- Use supported formats: JPG, JPEG, PNG, GIF
- Check file extension
- Convert file if needed

#### "File Too Large"
- Compress image before uploading
- Use lower resolution photo
- Maximum size is 5MB

#### "Location Required"
- Click on map to set location
- Enter coordinates manually
- Provide address details

### Performance Issues

#### Slow Loading
1. Check internet connection
2. Clear browser cache
3. Try different browser
4. Contact support if persistent

#### Page Not Loading
1. Check URL is correct
2. Try refreshing page
3. Clear browser cache
4. Check if system is under maintenance

## FAQ

### General Questions

**Q: Is FixLanka free to use?**
A: Yes, FixLanka is completely free for citizens to report problems.

**Q: Can I submit complaints anonymously?**
A: No, registration is required to submit complaints for accountability and tracking.

**Q: How long does it take to resolve complaints?**
A: Resolution time varies by issue complexity and department workload. Simple issues may be resolved in days, while complex projects may take weeks.

**Q: Can I submit multiple complaints for the same issue?**
A: It's recommended to submit one detailed complaint rather than multiple similar ones.

### Technical Questions

**Q: What browsers are supported?**
A: Chrome, Firefox, Safari, and Edge (latest versions).

**Q: Can I use FixLanka on mobile?**
A: Yes, the system is mobile-responsive and works on smartphones and tablets.

**Q: How do I enable location services?**
A: Allow location access when prompted by your browser for accurate complaint mapping.

**Q: What if I don't have a camera?**
A: Photos are recommended but not required. You can still submit complaints with detailed descriptions.

### Privacy and Security

**Q: Is my personal information secure?**
A: Yes, we use industry-standard security measures to protect your data.

**Q: Who can see my complaints?**
A: Only you, the assigned department, and system administrators can view your complaints.

**Q: Can I delete my account?**
A: Contact the administrator to request account deletion.

**Q: How is my location data used?**
A: Location data is only used to route complaints to the correct department and for mapping purposes.

### Department-Specific Questions

**Q: How do I know which department to contact?**
A: The AI system automatically routes complaints to the appropriate department based on your description.

**Q: What if the wrong department is assigned?**
A: Contact the administrator to request reassignment, or the department can reassign internally.

**Q: Can I track complaints from multiple departments?**
A: Yes, you can track all your complaints regardless of which department they're assigned to.

### Support and Contact

**Q: How do I contact support?**
A: Use the contact form on the website or email support@fixlanka.com

**Q: What information should I include when contacting support?**
A: Include your reference number, description of the issue, and any error messages.

**Q: How quickly will I get a response?**
A: We aim to respond to support requests within 24 hours.

---

**User Manual Version**: 1.0  
**Last Updated**: December 2024  
**Maintained By**: FixLanka Development Team 