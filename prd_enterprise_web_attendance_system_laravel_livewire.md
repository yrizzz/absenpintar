# PRD — Enterprise Web Attendance & Workforce Presence System

## Product Name
AbsenPintar

---

# 1. Product Vision

Membangun platform absensi dan manajemen kehadiran perusahaan berbasis web modern yang:
- aman
- anti manipulasi
- scalable
- realtime
- enterprise-ready
- mendukung hybrid working
- memiliki sistem anti fraud berbasis multi-verification

Sistem tidak hanya fokus pada “absensi”, tetapi pada:
- presence verification
- attendance intelligence
- workforce monitoring
- audit transparency
- approval workflow
- geo validation
- face verification

Target utama adalah perusahaan modern yang membutuhkan:
- validasi kehadiran terpercaya
- monitoring workforce realtime
- approval management
- integrasi payroll
- anti fake GPS mechanism
- audit legal compliance

---

# 2. Problem Statement

## Masalah Umum Sistem Absensi Tradisional

### 1. Fake GPS
Karyawan memanipulasi lokasi menggunakan aplikasi fake GPS.

### 2. Titip Absen
Karyawan melakukan absensi untuk rekan kerja.

### 3. Tidak Ada Validasi Kehadiran Nyata
Sistem hanya mencatat timestamp tanpa verifikasi presence.

### 4. Sulit Monitoring Hybrid Team
HR kesulitan memonitor WFO, WFH, dan mobile workforce.

### 5. Audit Lemah
Perubahan absensi dapat dimanipulasi tanpa jejak.

### 6. Approval Berantakan
Izin, cuti, dan approval tidak terstruktur.

### 7. Data Tidak Realtime
Manajemen sulit melihat kondisi workforce realtime.

---

# 3. Product Goals

## Primary Goals

### G1 — Presence Verification
Memastikan absensi dilakukan oleh user yang benar.

### G2 — Geo Validation
Memastikan absensi dilakukan di lokasi valid.

### G3 — Anti Fraud
Mendeteksi aktivitas mencurigakan.

### G4 — Enterprise Auditability
Seluruh aktivitas tercatat dan dapat diaudit.

### G5 — Workforce Management
Menyediakan sistem lengkap untuk:
- attendance
- leave
- permission
- overtime
- shift
- approval

### G6 — Realtime Monitoring
Dashboard realtime untuk HR dan manager.

---

# 4. Product Scope

## Included

### Attendance
- check in
- check out
- break
- overtime
- shift attendance
- WFO attendance
- WFH attendance

### Verification
- selfie verification
- liveness detection
- geofence validation
- device fingerprint
- risk scoring

### HR Features
- leave request
- permission request
- sick leave
- approval workflow

### Monitoring
- realtime dashboard
- suspicious activity
- attendance analytics
- attendance map

### Administration
- role management
- branch management
- attendance policy
- shift management

---

# 5. Out of Scope (Phase 1)

- payroll calculation
- biometric hardware integration
- fingerprint machine integration
- native mobile app
- SSO enterprise
- AI predictive analytics
- offline sync

---

# 6. User Roles

## 1. Super Admin
Akses penuh seluruh sistem.

Permissions:
- manage company
- manage branches
- manage users
- manage policies
- view audit logs
- manage settings

---

## 2. HR Admin
Mengelola operasional HR.

Permissions:
- approve leave
- approve permissions
- monitor attendance
- manage shifts
- export reports

---

## 3. Manager
Mengelola tim.

Permissions:
- approve team requests
- monitor team attendance
- view team analytics

---

## 4. Employee
User utama sistem.

Permissions:
- check in
- check out
- submit leave
- submit permission
- view own attendance

---

# 7. Core Features

# 7.1 Authentication System

## Features
- login
- forgot password
- 2FA optional
- device registration
- session management

## Requirements
- secure authentication
- rate limiting
- suspicious login detection

---

# 7.2 Attendance System

## Attendance Modes

### 1. WFO
Harus berada dalam geofence kantor.

### 2. WFH
Absensi dapat dilakukan dari luar kantor.

### 3. Hybrid
Flexible berdasarkan jadwal.

### 4. Mobile Workforce
Absensi berdasarkan dynamic location.

---

## Attendance Actions

### Check In
Mencatat kehadiran masuk.

### Check Out
Mencatat pulang kerja.

### Break Start
Mencatat awal istirahat.

### Break End
Mencatat selesai istirahat.

### Overtime Start
Mulai lembur.

### Overtime End
Selesai lembur.

---

# 7.3 Selfie Verification

## Requirements
Saat absensi user wajib:
- membuka kamera
- mengambil selfie realtime
- tidak boleh upload dari galeri

## Features
- realtime camera capture
- auto compression
- face verification
- image validation
- anti spoofing

## Validation
- face detected
- single face only
- image quality sufficient
- brightness validation

---

# 7.4 Liveness Detection

## Objective
Mencegah penggunaan:
- foto
- video replay
- printed face

## Detection Strategy

### Passive Detection
- head movement
- blink detection
- facial depth estimation

### Active Challenge
Optional random challenge:
- blink twice
- turn head left
- smile

---

# 7.5 Geo Validation

## Features
- GPS validation
- geofence validation
- distance calculation
- location accuracy validation

## Rules

### GPS Accuracy
Jika accuracy > 100m:
- attendance flagged
- optional rejection

### Geofence
Attendance hanya valid jika:
- berada dalam radius kantor
- sesuai attendance policy

---

# 7.6 Device Fingerprinting

## Objective
Mendeteksi perubahan device.

## Collected Signals
- browser
- OS
- timezone
- language
- screen resolution
- hardware concurrency
- GPU info

## Rules

### Trusted Device
Device pertama menjadi trusted device.

### New Device
Jika device baru:
- OTP verification
- admin approval optional
- suspicious score increase

---

# 7.7 Risk Engine

## Objective
Menghitung skor risiko absensi.

## Sample Risk Factors

| Factor | Score |
|---|---|
| New device | +20 |
| VPN detected | +25 |
| GPS mismatch | +40 |
| Face mismatch | +80 |
| GPS accuracy low | +15 |
| Timezone mismatch | +20 |

## Risk Threshold

### Low Risk
0–30

### Medium Risk
31–60
Needs review.

### High Risk
61+
Possible rejection.

---

# 7.8 Attendance Watermark

## Watermark Content
- employee name
- timestamp
- branch
- latitude
- longitude
- device
- attendance type

## Objective
Mencegah manipulasi screenshot.

---

# 7.9 Leave Management

## Leave Types
- annual leave
- sick leave
- unpaid leave
- maternity leave
- custom leave

## Workflow
1. submit request
2. manager approval
3. HR approval
4. finalized

---

# 7.10 Permission System

## Permission Types
- late permission
- personal permission
- remote work request
- field assignment

---

# 7.11 Shift Management

## Features
- fixed shift
- rotating shift
- flexible shift
- custom schedule

## Shift Rules
- grace period
- overtime threshold
- late threshold

---

# 7.12 Realtime Dashboard

## Widgets
- total employees present
- late employees
- absent employees
- overtime employees
- suspicious activities
- attendance heatmap
- branch summary

---

# 7.13 Suspicious Activity Monitoring

## Events
- fake GPS suspected
- VPN usage
- multiple devices
- impossible travel
- face mismatch
- repeated failed verification

## Actions
- notify HR
- mark attendance
- request manual review

---

# 7.14 Audit Trail

## Requirements
Semua aktivitas penting harus tercatat.

## Logged Activities
- login
- attendance changes
- approvals
- policy changes
- role changes
- shift changes
- leave approval

## Rules
- immutable logs
- no hard delete
- timestamp required

---

# 8. Functional Requirements

# 8.1 Attendance Flow

## Check In Flow

1. user opens app
2. system validates authentication
3. GPS requested
4. device fingerprint collected
5. geofence validated
6. selfie captured
7. liveness validation executed
8. risk score calculated
9. attendance saved
10. watermark generated
11. audit log created
12. realtime dashboard updated

---

# 8.2 Leave Request Flow

1. employee submits leave
2. manager notified
3. manager approves/rejects
4. HR final approval
5. leave balance updated
6. audit log created

---

# 9. Non Functional Requirements

## Performance
- attendance request < 3 seconds
- dashboard realtime update
- supports 10k concurrent users

## Security
- CSRF protection
- rate limiting
- encrypted sessions
- secure image storage
- role-based access control

## Availability
- target uptime 99.5%

## Scalability
- queue-based architecture
- horizontal scaling ready

---

# 10. Technical Architecture

# 10.1 Technology Stack

## Frontend
- Laravel Blade
- Livewire v4
- Alpine.js v3
- TailwindCSS v4

## Backend
- Laravel 13
- PHP 8.4+

## Database
- MySQL 8.0+

## Queue
- Redis 7.4+
- Laravel Horizon (latest)

## Realtime
- Laravel Reverb (latest)

## AI Service
- Python 3.12+
- FastAPI 0.115+
- InsightFace (latest)
- OpenCV 4.10+

## Web Server
- Nginx (latest stable)

## Process Manager
- Supervisor (latest)

---

# 10.2 Architecture Pattern

## Main Application
Laravel handles:
- authentication
- business logic
- dashboard
- workflow
- APIs

## AI Service
Python service handles:
- face recognition
- liveness detection
- image validation

Communication:
- internal REST API

---

# 11. Database Design

# 11.1 Main Tables

## users
- id
- employee_id
- name
- email
- password
- branch_id
- role_id
- status

---

## attendance_logs
- id
- user_id
- checkin_at
- checkout_at
- latitude
- longitude
- accuracy
- ip_address
- device_id
- selfie_path
- risk_score
- attendance_status

---

## device_fingerprints
- id
- user_id
- device_hash
- browser
- os
- trusted
- last_used_at

---

## branches
- id
- name
- latitude
- longitude
- radius

---

## leave_requests
- id
- user_id
- leave_type
- start_date
- end_date
- reason
- approval_status

---

## suspicious_events
- id
- user_id
- event_type
- risk_score
- metadata
- created_at

---

## audit_logs
- id
- user_id
- action
- model_type
- model_id
- metadata
- ip_address
- created_at

---

# 12. Security Architecture

# 12.1 Anti Fake GPS Strategy

## Important Principle
Fake GPS cannot be prevented 100% in web browser.

System strategy:
- detection
- risk scoring
- multi verification

## Multi Verification Layers

### Layer 1
GPS validation

### Layer 2
Geofence validation

### Layer 3
Device fingerprint

### Layer 4
Face verification

### Layer 5
Liveness detection

### Layer 6
IP intelligence

### Layer 7
Behavior analysis

---

# 12.2 Security Features

## Features
- HTTPS only
- signed URLs
- image access control
- rate limiting
- queue isolation
- secure headers
- encrypted storage

---

# 13. API Design

# 13.1 Attendance APIs

## POST /api/attendance/checkin
Create attendance check in.

## POST /api/attendance/checkout
Create attendance check out.

## POST /api/attendance/break/start
Start break.

## POST /api/attendance/break/end
End break.

---

# 13.2 Leave APIs

## POST /api/leaves
Submit leave request.

## POST /api/leaves/{id}/approve
Approve leave.

## POST /api/leaves/{id}/reject
Reject leave.

---

# 14. Frontend Pages

# Employee Pages

## Authentication
- login
- forgot password

## Attendance
- dashboard
- check in page
- attendance history
- leave requests
- profile

## Monitoring
- attendance calendar
- shift schedule
- notifications

---

# HR Pages

## Monitoring
- realtime attendance dashboard
- suspicious activity dashboard
- attendance analytics
- branch monitoring

## Management
- employee management
- shift management
- leave approval
- permission approval
- attendance correction

---

# 15. Realtime Features

## Features
- live attendance updates
- live employee count
- live suspicious detection
- live notifications

Technology:
- Laravel Reverb
- WebSocket

---

# 16. Queue Jobs

## Jobs
- image compression
- watermark generation
- AI verification
- notification sending
- report generation
- suspicious analysis

---

# 17. File Storage Strategy

## Selfie Images
Store:
- original image
- compressed image
- watermarked image

## Rules
- private storage
- signed access URLs
- retention policy

---

# 18. Reporting System

## Reports
- attendance report
- lateness report
- overtime report
- leave report
- suspicious activity report

## Export Formats
- Excel
- CSV
- PDF

---

# 19. Notifications

## Channels
- in-app
- email
- WhatsApp optional

## Notification Types
- attendance reminder
- leave approval
- suspicious login
- shift reminder

---

# 20. Deployment Architecture

# Infrastructure

## Server
Ubuntu 24.04 LTS

## Services
- Nginx (latest stable)
- PHP-FPM 8.4
- Redis 7.4+
- MySQL 8.0+
- Supervisor (latest)

## Processes
- horizon
- reverb
- scheduler
- AI service

---

# 21. Monitoring & Logging

## Monitoring
- server health
- queue health
- response time
- failed jobs

## Logging
- application logs
- security logs
- attendance logs
- suspicious events

---

# 22. Development Phases

# Phase 1 — MVP

## Features
- authentication
- attendance
- selfie capture
- geofence
- leave management
- approval workflow
- watermark
- dashboard

Timeline:
6–8 weeks

---

# Phase 2 — Security Enhancement

## Features
- face verification
- liveness detection
- risk engine
- suspicious dashboard
- realtime monitoring

Timeline:
4–6 weeks

---

# Phase 3 — Enterprise Features

## Features
- payroll export
- advanced analytics
- branch analytics
- attendance heatmap
- AI anomaly detection

Timeline:
4–8 weeks

---

# 23. Success Metrics

## Technical Metrics
- attendance success rate
- average response time
- uptime
- failed attendance rate

## Business Metrics
- reduction in attendance fraud
- approval processing time
- HR operational efficiency

---

# 24. Future Roadmap

## Future Features
- native mobile app
- offline attendance sync
- payroll integration
- SSO integration
- AI attendance analytics
- predictive absenteeism
- employee productivity insights

---

# 25. Recommended Laravel Packages

## Backend
- spatie/laravel-permission (latest)
- spatie/laravel-activitylog (latest)
- spatie/laravel-medialibrary (latest)
- laravel/horizon (latest)
- laravel/reverb (latest)

## Frontend
- livewire/livewire ^4.0
- alpinejs ^3.0
- tailwindcss ^4.0

## Security
- fingerprintjs/fingerprintjs-pro (latest)
- laravel/sanctum (latest)

## Image
- intervention/image ^3.0

## Geospatial
- matanyadaev/laravel-eloquent-spatial (latest)

## Export
- maatwebsite/excel (latest)
- barryvdh/laravel-dompdf (latest)

---

# 26. Final Product Positioning

AbsenPintar diposisikan sebagai:

“Enterprise Workforce Presence Verification Platform”

Bukan sekadar aplikasi absensi.

Fokus utama:
- trust
- verification
- transparency
- anti manipulation
- realtime workforce monitoring
- scalable HR operations

