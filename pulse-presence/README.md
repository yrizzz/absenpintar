# AbsenPintar - Enterprise Attendance System

![Laravel](https://img.shields.io/badge/Laravel-13-red)
![Livewire](https://img.shields.io/badge/Livewire-4-purple)
![PHP](https://img.shields.io/badge/PHP-8.4-blue)
![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4-cyan)

Enterprise-grade web attendance and workforce presence system with advanced anti-fraud mechanisms.

## 🚀 Features

### Core Features
- ✅ **Multi-layer Fraud Detection** - 7 verification layers
- ✅ **GPS & Geofence Validation** - Location-based attendance
- ✅ **Selfie Verification** - Real-time camera capture
- ✅ **Device Fingerprinting** - Browser/device tracking
- ✅ **Risk Scoring Engine** - Automatic fraud detection
- ✅ **Role-based Access Control** - 4 roles with 40+ permissions
- ✅ **Attendance History** - Complete audit trail
- ✅ **Dashboard Analytics** - Real-time stats

### Anti-Fraud System
- GPS accuracy validation
- Geofence validation (Haversine formula)
- Device fingerprint tracking
- Impossible travel detection
- VPN detection (ready for integration)
- Timezone mismatch detection
- Risk-based approval workflow

### Work Modes
- WFO (Work From Office)
- WFH (Work From Home)
- Hybrid
- Mobile Workforce

## 🛠️ Tech Stack

### Backend
- **Laravel 13** - PHP Framework
- **PHP 8.4+** - Programming Language
- **SQLite** (dev) / **MySQL 8.0+** (prod) - Database
- **Spatie Permission** - Role & Permission Management
- **Spatie Activity Log** - Activity Tracking

### Frontend
- **Livewire 4** - Full-stack Framework
- **Alpine.js 3** - JavaScript Framework
- **TailwindCSS 4** - CSS Framework

### Additional Packages
- Intervention Image - Image Processing
- Maatwebsite Excel - Excel Export
- Barryvdh DomPDF - PDF Generation

## 📦 Installation

### Requirements
- PHP 8.4 or higher
- Composer
- Node.js & NPM
- SQLite (dev) or MySQL 8.0+ (prod)

### Setup Steps

1. **Clone the repository**
```bash
cd pulse-presence
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Environment setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database setup**
```bash
# For SQLite (development)
touch database/database.sqlite

# For MySQL (production)
mysql -u root -p -e "CREATE DATABASE pulse_presence CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

5. **Configure .env**
```env
# For SQLite
DB_CONNECTION=sqlite

# For MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pulse_presence
DB_USERNAME=root
DB_PASSWORD=your_password
```

6. **Run migrations and seeders**
```bash
php artisan migrate --seed
```

7. **Build assets**
```bash
npm run build
# or for development
npm run dev
```

8. **Start the server**
```bash
php artisan serve
```

9. **Access the application**
```
http://localhost:8000
```

## 👥 Default Users

After seeding, you can login with these credentials:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@AbsenPintar.com | password |
| HR Admin | hr@AbsenPintar.com | password |
| Manager | manager@AbsenPintar.com | password |
| Employee | employee4@AbsenPintar.com | password |

## 📱 Usage

### For Employees

1. **Check In**
   - Navigate to Attendance → Check In
   - Allow location access
   - Allow camera access
   - Take selfie
   - Confirm check-in

2. **Check Out**
   - Navigate to Attendance → Check Out
   - Follow same steps as check-in

3. **View History**
   - Navigate to Attendance → History
   - Filter by month, type, or status

### For HR/Managers

1. **Dashboard**
   - View real-time attendance stats
   - Monitor suspicious activities
   - Review flagged attendances

2. **Reports**
   - Generate attendance reports
   - Export to Excel/PDF
   - Custom date ranges

## 🔒 Security Features

### Multi-layer Verification
1. **GPS Validation** - Accuracy threshold checking
2. **Geofence Validation** - Distance calculation
3. **Device Fingerprinting** - Browser/device tracking
4. **Selfie Verification** - Real-time camera capture
5. **Liveness Detection** - Anti-spoofing (ready for AI integration)
6. **IP Intelligence** - VPN detection
7. **Behavior Analysis** - Impossible travel detection

### Risk Scoring
- **Low Risk (0-30)**: Auto-approved
- **Medium Risk (31-60)**: Requires review
- **High Risk (61+)**: Flagged for investigation

### Audit Trail
- All activities logged
- Immutable records
- Complete change history
- IP address tracking

## 📊 Database Schema

### Core Tables
- `users` - User accounts with roles
- `branches` - Office locations with geofence
- `attendance_logs` - Complete attendance records
- `device_fingerprints` - Device tracking
- `suspicious_events` - Fraud detection logs
- `leave_requests` - Leave management
- `permission_requests` - Permission requests
- `shifts` - Shift management
- `audit_logs` - Activity audit trail

## 🎯 Roadmap

### Phase 1 - MVP ✅
- [x] Authentication system
- [x] Attendance check-in/out
- [x] Selfie capture
- [x] GPS validation
- [x] Device fingerprinting
- [x] Risk scoring
- [x] Dashboard

### Phase 2 - Enhancement
- [ ] Leave management
- [ ] Permission requests
- [ ] Shift management
- [ ] Suspicious activity review
- [ ] Advanced reporting

### Phase 3 - Advanced
- [ ] AI face recognition
- [ ] Liveness detection
- [ ] Real-time notifications (Laravel Reverb)
- [ ] Mobile app
- [ ] Payroll integration

## 🤝 Contributing

This is a private enterprise project. For contributions, please contact the development team.

## 📄 License

Proprietary - All rights reserved

## 👨‍💻 Development Team

Developed by enowX Labs AI

## 📞 Support

For support and inquiries:
- Email: support@AbsenPintar.com
- Documentation: See IMPLEMENTATION_PROGRESS.md

## 🙏 Acknowledgments

- Laravel Framework
- Livewire
- Spatie Packages
- TailwindCSS
- Alpine.js

---

**Note**: This system requires HTTPS in production for camera and geolocation access.
