# 🚀 Quick Start Guide - AbsenPintar

## ✅ Setup Complete!

Database sudah di-migrate dan di-seed dengan data demo.

## 🌐 Akses Aplikasi

**URL:** http://localhost:8000

## 👤 Login Credentials

| Role | Email | Password |
|------|-------|----------|
| **Super Admin** | admin@AbsenPintar.com | password |
| **HR Admin** | hr@AbsenPintar.com | password |
| **Manager** | manager@AbsenPintar.com | password |
| **Employee** | employee4@AbsenPintar.com | password |

## 📱 Testing Flow

### 1. Login sebagai Employee
```
Email: employee4@AbsenPintar.com
Password: password
```

### 2. Check In
1. Klik menu **"Attendance"** atau tombol **"Check In Now"** di dashboard
2. **Step 1 - Location:**
   - Klik "Get My Location"
   - Allow location access di browser
   - GPS akan otomatis captured
3. **Step 2 - Selfie:**
   - Allow camera access di browser
   - Klik "Capture Photo"
   - Review foto, klik "Use This Photo"
4. **Step 3 - Confirm:**
   - Review data (location, accuracy, time, branch)
   - Klik "Confirm Check-In"
5. Lihat hasil di Dashboard

### 3. View Dashboard
- Total attendance bulan ini
- On-time count
- Late count
- Suspicious events
- Today's attendance detail

### 4. View History
- Klik menu "Attendance"
- Filter by month, type, status
- Lihat risk score dan status

### 5. Check Out
- Klik "Check Out" di dashboard
- Ulangi proses seperti check-in

## 🔐 Security Features yang Berjalan

### Automatic Validation
Setiap check-in/out akan otomatis:
1. ✅ Validate GPS accuracy
2. ✅ Check geofence (apakah dalam radius branch)
3. ✅ Collect device fingerprint
4. ✅ Calculate risk score
5. ✅ Log suspicious events (jika ada)
6. ✅ Set attendance status (approved/pending/flagged)

### Risk Scoring
- **Low (0-30)**: Auto-approved ✅
- **Medium (31-60)**: Pending review ⚠️
- **High (61+)**: Flagged 🚨

### Device Fingerprint
Otomatis collect:
- Browser type
- Operating System
- Platform
- Timezone
- Language
- Screen resolution
- Hardware info

## 🎯 Demo Scenarios

### Scenario 1: Normal Check-In (Low Risk)
- Login sebagai employee4
- Check-in dari lokasi yang dekat dengan branch
- GPS accuracy bagus (< 100m)
- Device yang sama
- **Result:** Auto-approved, Low risk

### Scenario 2: Suspicious Check-In (High Risk)
- Check-in dari lokasi jauh dari branch
- GPS accuracy buruk (> 100m)
- Device baru
- **Result:** Flagged, High risk

### Scenario 3: View as HR Admin
- Login sebagai hr@AbsenPintar.com
- Lihat semua attendance
- Review suspicious events
- Approve/reject flagged attendance

## 📊 Database Info

**Location:** `database/database.sqlite`

**Tables:**
- 10 users (berbagai roles)
- 3 branches (Jakarta, Surabaya, Bandung)
- 4 shifts (Regular, Morning, Afternoon, Night)
- 4 roles dengan 40+ permissions

## 🛠️ Development Commands

```bash
# Start server
php artisan serve

# Build assets (development)
npm run dev

# Build assets (production)
npm run build

# Reset database
php artisan migrate:fresh --seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## 🐛 Troubleshooting

### Camera tidak muncul
- Pastikan browser support getUserMedia API
- Allow camera permission di browser
- Gunakan HTTPS di production
- **Fixed:** Alpine.js script updated to use `$wire` instead of `@this`

### Location tidak terdeteksi
- Allow location permission di browser
- Pastikan GPS aktif di device
- Gunakan HTTPS di production

### Migration error
```bash
php artisan migrate:fresh --seed
```
- **Fixed:** Migration order corrected (cache → branches → users → shifts → attendance_logs)

### Alpine.js errors
- **Fixed:** Updated to use Livewire 4 `@script` directive
- **Fixed:** Changed from `@this.call()` to `$wire.call()`
- Clear browser cache and hard refresh (Ctrl + Shift + R)

### Detailed troubleshooting
See `TROUBLESHOOTING.md` for complete troubleshooting guide.

## 📝 Notes

- **HTes

- **HTTPS Required:** Camera dan GPS hanya berfungsi di HTTPS (production)
- **Browser Support:** Chrome, Firefox, Safari (latest versions)
- **Mobile Friendly:** Responsive design, bisa diakses dari mobile
- **Real-time:** Semua data langsung tersimpan dan tervalidasi

## 🎉 Features Implemented

✅ Authentication with device fingerprint
✅ GPS location capture
✅ Real-time camera selfie
✅ Geofence validation
✅ Risk scoring engine
✅ Suspicious event logging
✅ Role-based access control
✅ Attendance history
✅ Dashboard analytics
✅ Multi-layer fraud detection

## 📞 Support

Jika ada pertanyaan atau issue:
1. Check `IMPLEMENTATION_PROGRESS.md` untuk detail teknis
2. Check `README.md` untuk dokumentasi lengkap
3. Check migration files untuk struktur database

---

**Happy Testing! 🚀**
