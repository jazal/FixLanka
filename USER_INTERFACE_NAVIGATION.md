# FixLanka - User Interface & Navigation Documentation

## Table of Contents
1. [Overview](#overview)
2. [Design System](#design-system)
3. [Navigation Structure](#navigation-structure)
4. [User Interface Components](#user-interface-components)
5. [Responsive Design](#responsive-design)
6. [User Experience Flow](#user-experience-flow)
7. [Accessibility Features](#accessibility-features)
8. [Interactive Elements](#interactive-elements)
9. [Mobile Navigation](#mobile-navigation)
10. [Dashboard Interfaces](#dashboard-interfaces)

## 1. Overview

FixLanka employs a modern, user-friendly interface designed for accessibility and ease of use across all user types: citizens, department staff, and administrators.

### Key Design Principles
- **Simplicity**: Clean, uncluttered interface
- **Accessibility**: High contrast, readable fonts
- **Responsiveness**: Mobile-first design approach
- **Consistency**: Unified design language across all pages
- **Intuitiveness**: Clear navigation and user flows

## 2. Design System

### Color Palette
```css
Primary Colors:
- Primary Blue: #00bfff (Main brand color)
- Dark Blue: #0099cc (Hover states)
- Light Blue: #e6f0f9 (Background sections)

Neutral Colors:
- White: #ffffff
- Light Gray: #f9faff (Body background)
- Dark Gray: #333333 (Text)
- Border Gray: #e0e0e0

Status Colors:
- Success: #4CAF50
- Warning: #FF9800
- Error: #F44336
- Info: #2196F3
```

### Typography
```css
Font Family: 'Roboto', sans-serif
- Regular: 400
- Bold: 700

Font Sizes:
- Main Title: 3.8rem (60px)
- Section Headers: 2rem (32px)
- Body Text: 1rem (16px)
- Small Text: 0.875rem (14px)
- Navigation: 1.0625rem (17px)
```

### Spacing System
```css
Padding/Margin Scale:
- xs: 8px
- sm: 16px
- md: 24px
- lg: 32px
- xl: 48px
- xxl: 64px
```

## 3. Navigation Structure

### 3.1 Main Navigation (Homepage)
```html
Navigation Structure:
├── Logo (FixLanka)
├── Home
├── How it Works
├── Departments
├── Reviews
├── My Complaints (Logged in users)
├── Profile Picture (Logged in users)
├── Login (Guest users)
└── Register (Guest users)
```

### 3.2 Role-Based Navigation

#### Citizen Navigation
```html
Citizen Menu:
├── Home
├── Submit Complaint
├── My Complaints
├── Profile Settings
└── Logout
```

#### Department Navigation
```html
Department Menu:
├── Dashboard
├── Pending Complaints
├── In Progress Complaints
├── Completed Complaints
├── Profile Settings
└── Logout
```

#### Admin Navigation
```html
Admin Menu:
├── Dashboard
├── Manage Accounts
├── Manage Departments
├── System Reports
├── Settings
└── Logout
```

## 4. User Interface Components

### 4.1 Header/Navigation Bar
```css
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-color: white;
  padding: 10px 20px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  position: sticky;
  top: 0;
  z-index: 1000;
  height: 100px;
}
```

**Features:**
- Sticky positioning
- Responsive hamburger menu
- User profile picture integration
- Active state indicators
- Smooth hover transitions

### 4.2 Hero Section
```css
.hero {
  background-size: cover;
  background-position: center;
  padding: 100px 20px 80px;
  text-align: center;
  color: #000;
  position: relative;
}
```

**Components:**
- Main title with large typography
- Call-to-action buttons
- Status checker form
- Background overlay for readability

### 4.3 Button System
```css
.btn {
  background-color: #00bfff;
  color: white;
  padding: 12px 28px;
  border-radius: 30px;
  font-weight: 600;
  font-size: 1.1rem;
  display: inline-block;
  transition: background-color 0.3s ease;
}
```

**Button Types:**
- Primary buttons (blue background)
- Secondary buttons (outline style)
- Login/Register buttons (special styling)
- Form submit buttons

### 4.4 Form Components
```css
.ref-check input[type="text"] {
  padding: 10px 15px;
  font-size: 1rem;
  border: 2px solid #00bfff;
  border-radius: 30px 0 0 30px;
  width: 250px;
  outline: none;
}
```

**Form Features:**
- Rounded input fields
- Focus states with blue border
- Integrated submit buttons
- Validation feedback

### 4.5 Card Components
```css
.review-card {
  background: white;
  border-radius: 15px;
  padding: 20px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
}
```

**Card Types:**
- Review cards with before/after images
- Department cards with logos
- Complaint status cards
- Dashboard stat cards

## 5. Responsive Design

### 5.1 Breakpoints
```css
Mobile: max-width: 480px
Tablet: max-width: 768px
Desktop: min-width: 769px
```

### 5.2 Mobile Navigation
```css
.hamburger {
  display: none;
  flex-direction: column;
  cursor: pointer;
}

@media (max-width: 768px) {
  .hamburger {
    display: flex;
  }
  
  .nav-links {
    display: none;
    position: absolute;
    top: 100px;
    left: 0;
    width: 100%;
    background: white;
    flex-direction: column;
    padding: 20px;
  }
  
  .nav-links.active {
    display: flex;
  }
}
```

### 5.3 Responsive Typography
```css
@media (max-width: 768px) {
  .main-title {
    font-size: 2.5rem;
  }
  
  .main-subtitle {
    font-size: 1.1rem;
  }
}

@media (max-width: 480px) {
  .main-title {
    font-size: 2rem;
  }
}
```

## 6. User Experience Flow

### 6.1 Guest User Journey
```
1. Landing Page
   ├── View hero section
   ├── Check complaint status
   ├── Browse departments
   ├── View reviews
   └── Register/Login

2. Registration Flow
   ├── Fill registration form
   ├── Email verification
   ├── Complete profile
   └── Access citizen features

3. Login Flow
   ├── Enter credentials
   ├── Authentication
   └── Redirect to dashboard
```

### 6.2 Citizen User Journey
```
1. Dashboard Access
   ├── View complaint status
   ├── Submit new complaint
   ├── Track existing complaints
   └── Update profile

2. Complaint Submission
   ├── Select location on map
   ├── Upload photos
   ├── Fill complaint details
   └── Submit and get reference

3. Complaint Tracking
   ├── Enter reference number
   ├── View status updates
   ├── Receive notifications
   └── Submit review (when resolved)
```

### 6.3 Department User Journey
```
1. Department Dashboard
   ├── View assigned complaints
   ├── Update complaint status
   ├── Upload solution files
   └── Communicate with citizens

2. Complaint Management
   ├── Review new complaints
   ├── Update progress
   ├── Upload solution evidence
   └── Mark as resolved
```

## 7. Accessibility Features

### 7.1 Keyboard Navigation
```css
/* Focus states for all interactive elements */
.nav-links li a:focus,
.btn:focus,
input:focus {
  outline: 2px solid #00bfff;
  outline-offset: 2px;
}
```

### 7.2 Screen Reader Support
```html
<!-- Semantic HTML structure -->
<nav role="navigation" aria-label="Main navigation">
<main role="main">
<aside role="complementary">
```

### 7.3 Color Contrast
- Minimum contrast ratio: 4.5:1
- High contrast mode support
- Color-blind friendly palette

### 7.4 Alt Text and Labels
```html
<img src="logo.png" alt="FixLanka Logo">
<label for="ref_number">Reference Number</label>
<input id="ref_number" type="text" aria-describedby="ref_help">
```

## 8. Interactive Elements

### 8.1 Carousel/Slider Components
```javascript
// Department carousel
const departmentSwiper = new Swiper('.department-swiper', {
  slidesPerView: 4,
  spaceBetween: 30,
  pagination: {
    el: '.swiper-pagination',
    clickable: true,
  },
  breakpoints: {
    768: {
      slidesPerView: 2,
    },
    480: {
      slidesPerView: 1,
    }
  }
});
```

### 8.2 Map Integration
```javascript
// Location picker for complaints
function initMap() {
  const map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: 6.9271, lng: 79.8612 }, // Colombo
    zoom: 12
  });
  
  map.addListener('click', function(e) {
    placeMarker(e.latLng);
  });
}
```

### 8.3 File Upload Interface
```html
<div class="file-upload">
  <input type="file" id="complaint-images" multiple accept="image/*">
  <label for="complaint-images">
    <i class="fas fa-camera"></i>
    Upload Images
  </label>
  <div class="preview-container"></div>
</div>
```

## 9. Mobile Navigation

### 9.1 Hamburger Menu
```css
.hamburger {
  display: none;
  flex-direction: column;
  cursor: pointer;
  padding: 10px;
}

.hamburger span {
  width: 25px;
  height: 3px;
  background-color: #333;
  margin: 3px 0;
  transition: 0.3s;
}
```

### 9.2 Mobile-Specific Features
- Touch-friendly button sizes (minimum 44px)
- Swipe gestures for carousels
- Optimized form inputs for mobile keyboards
- Reduced navigation complexity

### 9.3 Mobile Menu States
```css
/* Hamburger animation */
.hamburger.active span:nth-child(1) {
  transform: rotate(-45deg) translate(-5px, 6px);
}

.hamburger.active span:nth-child(2) {
  opacity: 0;
}

.hamburger.active span:nth-child(3) {
  transform: rotate(45deg) translate(-5px, -6px);
}
```

## 10. Dashboard Interfaces

### 10.1 Citizen Dashboard
```html
Citizen Dashboard Layout:
├── Header with profile
├── Quick Actions
│   ├── Submit New Complaint
│   └── Check Status
├── Recent Complaints
│   ├── Status cards
│   ├── Progress indicators
│   └── Action buttons
└── Statistics
    ├── Total complaints
    ├── Resolved count
    └── Pending count
```

### 10.2 Department Dashboard
```html
Department Dashboard Layout:
├── Header with department info
├── Statistics Overview
│   ├── Pending complaints
│   ├── In progress
│   ├── Completed today
│   └── Performance metrics
├── Complaint Queue
│   ├── Priority sorting
│   ├── Status filters
│   └── Quick actions
└── Recent Activity
    ├── Status updates
    ├── New assignments
    └── User interactions
```

### 10.3 Admin Dashboard
```html
Admin Dashboard Layout:
├── Header with admin controls
├── System Overview
│   ├── Total users
│   ├── Total complaints
│   ├── Department performance
│   └── System health
├── Management Tools
│   ├── User management
│   ├── Department management
│   ├── Complaint reassignment
│   └── System settings
└── Analytics
    ├── Complaint trends
    ├── User activity
    ├── Department efficiency
    └── Resolution times
```

## 11. Component Library

### 11.1 Status Indicators
```css
.status-pending {
  background-color: #FF9800;
  color: white;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 0.875rem;
}

.status-resolved {
  background-color: #4CAF50;
  color: white;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 0.875rem;
}
```

### 11.2 Loading States
```css
.loading-spinner {
  border: 3px solid #f3f3f3;
  border-top: 3px solid #00bfff;
  border-radius: 50%;
  width: 30px;
  height: 30px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
```

### 11.3 Notification System
```css
.notification {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 15px 20px;
  border-radius: 8px;
  color: white;
  z-index: 1000;
  animation: slideIn 0.3s ease;
}

.notification.success {
  background-color: #4CAF50;
}

.notification.error {
  background-color: #F44336;
}
```

## 12. Performance Optimization

### 12.1 Image Optimization
- WebP format support
- Lazy loading for images
- Responsive image sizes
- Compression for faster loading

### 12.2 CSS Optimization
- Minified CSS files
- Critical CSS inlining
- Unused CSS removal
- Efficient selectors

### 12.3 JavaScript Optimization
- Code splitting
- Lazy loading for non-critical scripts
- Event delegation
- Debounced user interactions

## 13. Browser Compatibility

### 13.1 Supported Browsers
- Chrome (latest 2 versions)
- Firefox (latest 2 versions)
- Safari (latest 2 versions)
- Edge (latest 2 versions)
- Mobile browsers (iOS Safari, Chrome Mobile)

### 13.2 Progressive Enhancement
- Core functionality works without JavaScript
- Enhanced features with modern browsers
- Graceful degradation for older browsers

---

**Document Version**: 1.0  
**Last Updated**: December 2024  
**UI Framework**: Custom CSS with Bootstrap components  
**Design System**: FixLanka Brand Guidelines 