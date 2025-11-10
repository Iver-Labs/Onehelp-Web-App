# OneHelp - App Flow Diagrams for Video Presentation

This document provides visual flow diagrams and user journey maps to help team members understand and demonstrate the app structure.

---

## 📊 **Overall System Architecture**

```
┌─────────────────────────────────────────────────────────────┐
│                        OneHelp Platform                      │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │  Volunteers  │  │Organizations │  │    Admins    │      │
│  │              │  │              │  │              │      │
│  │ • Register   │  │ • Register   │  │ • Verify     │      │
│  │ • Browse     │  │ • Verify     │  │ • Monitor    │      │
│  │ • Apply      │  │ • Create     │  │ • Manage     │      │
│  │ • Attend     │  │ • Manage     │  │ • Analytics  │      │
│  │ • Feedback   │  │ • Track      │  │ • Reports    │      │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘      │
│         │                 │                  │              │
│         └─────────────────┴──────────────────┘              │
│                           │                                 │
│         ┌─────────────────┴──────────────────┐              │
│         │                                    │              │
│    ┌────▼─────┐  ┌──────────────┐  ┌────────▼───┐          │
│    │  Events  │  │  Messaging   │  │ Attendance │          │
│    │  System  │  │   System     │  │  Tracking  │          │
│    └────┬─────┘  └──────┬───────┘  └────────┬───┘          │
│         │                │                   │              │
│         └────────────────┴───────────────────┘              │
│                          │                                  │
│              ┌───────────▼───────────┐                      │
│              │   Laravel Backend     │                      │
│              │   • API Routes        │                      │
│              │   • Controllers       │                      │
│              │   • Models            │                      │
│              │   • Security Layer    │                      │
│              └───────────┬───────────┘                      │
│                          │                                  │
│              ┌───────────▼───────────┐                      │
│              │   Database Layer      │                      │
│              │   SQLite / MariaDB    │                      │
│              └───────────────────────┘                      │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

---

## 👤 **Volunteer User Journey**

### Complete Flow:

```
START
  │
  ├─► 1. DISCOVERY
  │   └─► Visit Homepage (/)
  │       └─► View About Page
  │           └─► Browse Public Events (/events)
  │
  ├─► 2. REGISTRATION
  │   └─► Click "Register" (/register)
  │       └─► Select "Volunteer"
  │       └─► Fill Registration Form
  │           • Email
  │           • Password
  │           • Personal Info
  │       └─► Submit → Account Created
  │
  ├─► 3. PROFILE SETUP
  │   └─► Login (/login)
  │       └─► Navigate to Profile (/volunteer/profile)
  │       └─► Add Information:
  │           • Skills (First Aid, Teaching, etc.)
  │           • Bio & Interests
  │           • Availability
  │           • Profile Picture
  │       └─► Save Profile
  │
  ├─► 4. EVENT DISCOVERY
  │   └─► Dashboard (/volunteer/dashboard)
  │       └─► View Recommended Events
  │       └─► Browse All Events (/volunteer/events)
  │       └─► Filter by:
  │           • Date
  │           • Location
  │           • Skills Required
  │           • Organization
  │
  ├─► 5. EVENT REGISTRATION
  │   └─► Click Event Details
  │       └─► Review Event Information:
  │           • Description
  │           • Date & Time
  │           • Location
  │           • Required Skills
  │           • Organization Profile
  │       └─► Click "Register"
  │       └─► Add Motivation (Optional)
  │       └─► Submit Application
  │       └─► Receive Notification
  │
  ├─► 6. COMMUNICATION
  │   └─► Check Notifications
  │       └─► Application Approved ✓
  │   └─► Messages (/volunteer/messages)
  │       └─► Communicate with Organization
  │       └─► Ask Questions
  │       └─► Get Event Updates
  │
  ├─► 7. EVENT PARTICIPATION
  │   └─► Attend Event
  │       └─► Check-in (Organization records)
  │       └─► Participate in Activities
  │       └─► Check-out (Hours logged)
  │
  ├─► 8. FEEDBACK
  │   └─► Submit Feedback
  │       └─► Rate Event (1-5 stars)
  │       └─► Write Comments
  │       └─► Submit
  │
  └─► 9. TRACKING & GROWTH
      └─► View Dashboard
          └─► Total Hours Volunteered
          └─► Events Attended
          └─► Upcoming Events
          └─► Impact Metrics
          └─► Download Certificate
END
```

### Key Screens for Member 2:
1. `/register` (Volunteer)
2. `/volunteer/profile`
3. `/events`
4. `/events/{id}` (Event Details)
5. `/volunteer/dashboard`

---

## 🏢 **Organization User Journey**

### Complete Flow:

```
START
  │
  ├─► 1. REGISTRATION
  │   └─► Visit Homepage (/)
  │       └─► Click "Register" (/register)
  │       └─► Select "Organization"
  │       └─► Fill Registration Form:
  │           • Organization Name
  │           • Email
  │           • Password
  │           • Mission Statement
  │           • Contact Information
  │           • Website
  │       └─► Submit
  │
  ├─► 2. VERIFICATION
  │   └─► Receive Email: "Pending Verification"
  │   └─► Upload Verification Documents:
  │       • Registration Certificate
  │       • Tax Documents
  │       • Proof of Mission
  │   └─► Wait for Admin Approval
  │   └─► Receive Approval Notification ✓
  │
  ├─► 3. PROFILE SETUP
  │   └─► Login (/login)
  │       └─► Complete Organization Profile
  │           • Description
  │           • Logo
  │           • Focus Areas
  │           • Contact Details
  │
  ├─► 4. EVENT CREATION
  │   └─► Dashboard (/organization/dashboard)
  │       └─► Click "Create Event" (/organization/events/create)
  │       └─► Fill Event Form:
  │           • Event Name
  │           • Description
  │           • Date & Time
  │           • Location
  │           • Max Volunteers
  │           • Required Skills
  │           • Upload Images
  │       └─► Publish Event
  │
  ├─► 5. APPLICATION MANAGEMENT
  │   └─► Receive Notification: "New Application"
  │   └─► View Applications (/organization/applications)
  │       └─► Review Volunteer Profile:
  │           • Skills Match
  │           • Experience
  │           • Motivation
  │       └─► Approve or Reject
  │       └─► Send Notification to Volunteer
  │
  ├─► 6. COMMUNICATION
  │   └─► Messages (/organization/messages)
  │       └─► Respond to Volunteer Questions
  │       └─► Send Event Updates
  │       └─► Coordinate Logistics
  │
  ├─► 7. EVENT DAY
  │   └─► Attendance Tracking
  │       └─► Check Volunteers In
  │       └─► Monitor Participation
  │       └─► Check Volunteers Out
  │       └─► Log Hours Contributed
  │
  ├─► 8. POST-EVENT
  │   └─► Review Volunteer Feedback
  │   └─► Thank Volunteers
  │   └─► Update Event Status
  │
  └─► 9. ANALYTICS & REPORTING
      └─► Dashboard (/organization/analytics)
          └─► View Metrics:
              • Total Events Created
              • Total Volunteers Engaged
              • Total Hours Contributed
              • Volunteer Retention Rate
          └─► Generate Reports:
              • Event Participation (PDF)
              • Organization Summary (PDF)
              • Export Data (Excel)
END
```

### Key Screens for Member 3:
1. `/register` (Organization)
2. `/organization/events/create`
3. `/organization/applications`
4. `/organization/analytics`
5. `/organization/dashboard`

---

## 🛡️ **Admin User Journey**

### Complete Flow:

```
START
  │
  ├─► 1. DASHBOARD OVERVIEW
  │   └─► Login as Admin
  │       └─► View Dashboard (/admin/dashboard)
  │           └─► Key Metrics:
  │               • Total Users
  │               • Active Volunteers
  │               • Verified Organizations
  │               • Active Events
  │               • Pending Verifications
  │               • Total Volunteer Hours
  │
  ├─► 2. USER MANAGEMENT
  │   └─► Users List (/admin/users)
  │       └─► Filter & Search Users
  │       └─► View User Details
  │       └─► Actions:
  │           • Toggle Active/Inactive Status
  │           • Delete User Account
  │           • View User Activity
  │           • Send Notifications
  │
  ├─► 3. ORGANIZATION VERIFICATION
  │   └─► Verifications (/admin/verifications)
  │       └─► View Pending Requests
  │       └─► Review Each Request:
  │           • Organization Details
  │           • Mission Statement
  │           • Verification Documents
  │       └─► Decision:
  │           ├─► APPROVE:
  │           │   • Add Admin Notes
  │           │   • Approve
  │           │   • Organization Notified
  │           └─► REJECT:
  │               • Add Reason
  │               • Reject
  │               • Organization Notified
  │
  ├─► 4. EVENT OVERSIGHT
  │   └─► Events List (/admin/events)
  │       └─► View All Platform Events
  │       └─► Monitor Event Quality
  │       └─► Remove Inappropriate Events
  │
  ├─► 5. SYSTEM ANALYTICS
  │   └─► Analytics Dashboard (/admin/analytics)
  │       └─► Platform Metrics:
  │           • User Growth Over Time
  │           • Event Distribution
  │           • Volunteer Engagement
  │           • Organization Activity
  │           • Popular Event Categories
  │           • Geographic Distribution
  │
  ├─► 6. SECURITY MONITORING
  │   └─► Security Logs
  │       └─► Review Access Logs
  │       └─► Monitor Suspicious Activity
  │       └─► Respond to Security Incidents
  │
  └─► 7. REPORTING
      └─► Generate System Reports
          └─► System Summary Report (PDF)
          └─► Export User Data (Excel)
          └─► Export Event Data (Excel)
END
```

### Key Screens for Member 4:
1. `/admin/dashboard`
2. `/admin/users`
3. `/admin/verifications`
4. `/admin/events`
5. `/admin/analytics`

---

## 🔄 **Event Lifecycle**

```
┌─────────────────────────────────────────────────────────────┐
│                     EVENT LIFECYCLE                          │
└─────────────────────────────────────────────────────────────┘

1. EVENT CREATION (Organization)
   │
   ├─► Organization creates event
   │   └─► Fills all details
   │   └─► Uploads images
   │   └─► Sets capacity
   │   └─► Publishes
   │
   ▼
2. EVENT PUBLISHED
   │
   ├─► Event appears in listings
   │   └─► Volunteers can browse
   │   └─► Details publicly visible
   │
   ▼
3. REGISTRATION PHASE
   │
   ├─► Volunteers discover event
   │   └─► Apply to participate
   │   └─► Applications sent to org
   │
   ▼
4. APPLICATION REVIEW (Organization)
   │
   ├─► Organization reviews applications
   │   ├─► APPROVE → Volunteer notified
   │   └─► REJECT → Volunteer notified
   │
   ▼
5. PRE-EVENT COMMUNICATION
   │
   ├─► Organization sends updates
   │   └─► Logistics details
   │   └─► What to bring
   │   └─► Meeting point
   │
   ▼
6. EVENT DAY
   │
   ├─► Check-in process
   │   └─► Attendance recorded
   │   └─► Volunteers participate
   │   └─► Check-out process
   │   └─► Hours logged
   │
   ▼
7. POST-EVENT FEEDBACK
   │
   ├─► Volunteers submit feedback
   │   └─► Rate experience
   │   └─► Provide comments
   │
   ▼
8. COMPLETION & REPORTING
   │
   └─► Event marked complete
       └─► Hours added to volunteer profile
       └─► Organization reviews feedback
       └─► Certificates generated
```

---

## 💬 **Messaging System Flow**

```
┌─────────────────────────────────────────────────────────────┐
│                   MESSAGING SYSTEM                           │
└─────────────────────────────────────────────────────────────┘

VOLUNTEER SIDE:                    ORGANIZATION SIDE:
     │                                    │
     ├─► Messages Dashboard               ├─► Messages Dashboard
     │   (/volunteer/messages)           │   (/organization/messages)
     │                                    │
     ├─► View Conversations               ├─► View Conversations
     │   • List of organizations         │   • List of volunteers
     │   • Unread count                  │   • Unread count
     │                                    │
     ├─► Select Conversation              ├─► Select Conversation
     │   └─► View Message History         │   └─► View Message History
     │                                    │
     ├─► Send Message ◄──────────────────►├─► Send Message
     │   • Type message                   │   • Type message
     │   • Click Send                     │   • Click Send
     │   • Real-time delivery             │   • Real-time delivery
     │                                    │
     └─► Receive Notification             └─► Receive Notification
         • New message alert                  • New message alert
```

---

## 📊 **Data Flow Diagram**

```
┌─────────────────────────────────────────────────────────────┐
│                      DATA FLOW                               │
└─────────────────────────────────────────────────────────────┘

┌──────────┐         ┌──────────────┐         ┌──────────┐
│  Browser │ ◄─────► │   Laravel    │ ◄─────► │ Database │
│   (UI)   │  HTTP   │   Backend    │   SQL   │  SQLite  │
└────┬─────┘         └──────┬───────┘         └──────────┘
     │                      │
     │                      ├─► Validation Layer
     │                      │   • Input sanitization
     │                      │   • XSS prevention
     │                      │   • CSRF protection
     │                      │
     │                      ├─► Authorization Layer
     │                      │   • Role-based access
     │                      │   • Permission checks
     │                      │
     │                      ├─► Business Logic
     │                      │   • Controllers
     │                      │   • Models
     │                      │   • Services
     │                      │
     │                      └─► Response Layer
     │                          • JSON API
     │                          • Blade Views
     │                          • Error handling
     │
     └─► User Experience
         • Responsive design
         • Real-time updates
         • Notifications
```

---

## 🔐 **Security Flow**

```
┌─────────────────────────────────────────────────────────────┐
│                    SECURITY LAYERS                           │
└─────────────────────────────────────────────────────────────┘

REQUEST
  │
  ├─► 1. CSRF Protection
  │   └─► Verify CSRF token
  │       ├─► Valid → Continue
  │       └─► Invalid → 419 Error
  │
  ├─► 2. Authentication Check
  │   └─► Check user session
  │       ├─► Authenticated → Continue
  │       └─► Not authenticated → Redirect to login
  │
  ├─► 3. Authorization Check
  │   └─► Verify user permissions
  │       ├─► Authorized → Continue
  │       └─► Unauthorized → 403 Error
  │
  ├─► 4. Input Validation
  │   └─► Validate all inputs
  │       ├─► Valid → Continue
  │       └─► Invalid → 422 Error with details
  │
  ├─► 5. XSS Prevention
  │   └─► Sanitize user input
  │       └─► Strip malicious scripts
  │
  ├─► 6. SQL Injection Prevention
  │   └─► Use parameterized queries
  │       └─► Eloquent ORM protection
  │
  ├─► 7. Rate Limiting
  │   └─► Check request rate
  │       ├─► Under limit → Continue
  │       └─► Over limit → 429 Error
  │
  └─► 8. Response with Security Headers
      └─► Add headers:
          • X-Content-Type-Options
          • X-Frame-Options
          • X-XSS-Protection
          • Content-Security-Policy
RESPONSE
```

---

## 📱 **Responsive Design Flow**

```
┌─────────────────────────────────────────────────────────────┐
│                  RESPONSIVE DESIGN                           │
└─────────────────────────────────────────────────────────────┘

DEVICE TYPES:
┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│   Mobile    │  │   Tablet    │  │   Desktop   │
│  < 768px    │  │ 768-1024px  │  │  > 1024px   │
└─────────────┘  └─────────────┘  └─────────────┘
      │                │                │
      └────────────────┴────────────────┘
                       │
             ┌─────────▼─────────┐
             │  Tailwind CSS     │
             │  Breakpoints      │
             └─────────┬─────────┘
                       │
        ┌──────────────┼──────────────┐
        │              │              │
    ┌───▼───┐     ┌────▼────┐    ┌───▼────┐
    │ Stack │     │  Grid   │    │ Multi- │
    │Layout │     │ Layout  │    │ Column │
    └───────┘     └─────────┘    └────────┘
        │              │              │
        └──────────────┴──────────────┘
                       │
                   ┌───▼────┐
                   │ Render │
                   └────────┘
```

---

## 🎯 **User Role Permissions**

```
┌─────────────────────────────────────────────────────────────┐
│                   ROLE PERMISSIONS                           │
└─────────────────────────────────────────────────────────────┘

FEATURE                  VOLUNTEER    ORGANIZATION    ADMIN
─────────────────────────────────────────────────────────────
Browse Events            ✓            ✓               ✓
Register for Events      ✓            ✗               ✗
Create Events            ✗            ✓               ✗
Manage Applications      ✗            ✓               ✗
Track Attendance         ✗            ✓               ✓
Submit Feedback          ✓            ✗               ✗
View Own Profile         ✓            ✓               ✓
Edit Own Profile         ✓            ✓               ✓
Send Messages            ✓            ✓               ✓
View Own Messages        ✓            ✓               ✓
Verify Organizations     ✗            ✗               ✓
Manage Users             ✗            ✗               ✓
View All Events          ✗            ✗               ✓
System Analytics         ✗            ✗               ✓
Delete Users             ✗            ✗               ✓
Modify Skills            ✗            ✗               ✓
Generate Reports         ✓            ✓               ✓
Export Data              ✗            ✓               ✓
```

---

## 📈 **Demo Scenario Timeline**

```
┌─────────────────────────────────────────────────────────────┐
│                  COMPLETE DEMO SCENARIO                      │
└─────────────────────────────────────────────────────────────┘

TIME    MEMBER   ACTION
─────────────────────────────────────────────────────────────
0:00    M1       Welcome, introduce OneHelp
0:30    M1       Explain problem statement
1:00    M1       Show technology stack
1:30    M1       Describe user types
2:00    M1       → Handoff to M2
        ─────────────────────────────────────────────────────
2:00    M2       Sarah registers as volunteer
2:30    M2       Sarah completes profile
3:00    M2       Sarah browses events
3:30    M2       Sarah registers for Beach Cleanup
4:00    M2       Show confirmation and dashboard
4:30    M2       → Handoff to M3
        ─────────────────────────────────────────────────────
4:30    M3       Green Earth registers
5:00    M3       Admin approves verification
5:30    M3       Green Earth creates event
6:00    M3       Review applications
6:30    M3       Show analytics dashboard
7:00    M3       → Handoff to M4
        ─────────────────────────────────────────────────────
7:00    M4       Admin dashboard overview
7:30    M4       User management demo
8:00    M4       Verification workflow
8:30    M4       System analytics
9:00    M4       → Handoff to M5
        ─────────────────────────────────────────────────────
9:00    M5       Messaging system demo
9:30    M5       Notifications & attendance
10:00   M5       Feedback system
10:30   M5       Report generation
11:00   M5       API & testing
11:30   M5       Mobile responsiveness
12:00   M5       Closing remarks
        ─────────────────────────────────────────────────────
12:00   END      Thank you!
```

---

## 🎬 **Recommended Camera Shots**

### For Screen Recording:

1. **Full Screen:** Default view for navigation and forms
2. **Zoom In:** When showing specific UI elements or buttons
3. **Split Screen:** When comparing before/after or different roles
4. **Picture-in-Picture:** Optional team member video in corner

### Cursor Movement:
- Move deliberately, not erratically
- Pause on important elements
- Use highlighting (if recording software supports it)

---

## 📋 **Checklist for Each Member**

```
BEFORE YOUR SEGMENT:
□ Review your section in main guide
□ Practice your script at least once
□ Verify demo data is ready
□ Test all URLs you'll visit
□ Ensure you know the handoff line

DURING YOUR SEGMENT:
□ Speak clearly and at moderate pace
□ Follow your planned screen flow
□ Show each key feature
□ Avoid rushing or dragging
□ End with handoff line

AFTER YOUR SEGMENT:
□ Verify recording captured everything
□ Note any issues for re-recording
□ Prepare for next segment (if applicable)
```

---

**Use these diagrams as reference during your presentation to understand the flow and context of what you're demonstrating!**
