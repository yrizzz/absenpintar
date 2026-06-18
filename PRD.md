# PRD — PresensiKu (Enterprise Presence System)

> **Status dokumen:** v1.0 · Fondasi untuk redesign UI/UX menyeluruh
> **Pemilik produk:** Tim PresensiKu
> **Terakhir diperbarui:** 2026-06-16
> **Arah desain yang dipilih:** Clean Modern SaaS (light-first, flat, minimal — gaya Linear/Vercel/shadcn). Detail teknis lihat [CLAUDE.md](CLAUDE.md).

---

## 1. Ringkasan & Visi

PresensiKu adalah sistem presensi (absensi) dan manajemen kehadiran karyawan kelas
enterprise berbasis web, dengan fokus utama pada **pencegahan kecurangan
(anti-fraud)** absensi. Karyawan melakukan check-in/check-out melalui browser
dengan verifikasi berlapis (GPS + geofence, selfie/biometrik wajah, sidik jari
perangkat), sementara HR & manajemen memperoleh dasbor real-time, alur
persetujuan berbasis risiko, manajemen cuti/izin, laporan, dan dokumen surat resmi
(PDF).

**Masalah yang dipecahkan**
- Absensi titip ("buddy punching"), pemalsuan lokasi (fake GPS), dan manipulasi
  jam kerja.
- Proses administrasi cuti/izin yang manual dan tidak terjejak.
- Kebutuhan audit kehadiran yang lengkap, immutable, dan dapat dicetak.

**Visi redesign**
Mengubah tampilan yang saat ini tidak konsisten (CSS penuh `!important` dan warna
hardcoded) menjadi pengalaman yang **konsisten, modern, dan profesional** —
tenang, lega, mudah dibaca, dan dapat dirawat jangka panjang — tanpa mengubah
logika bisnis maupun skema data.

---

## 2. Pengguna & Peran (RBAC)

Empat peran via Spatie Permission. Label menu memakai Bahasa Indonesia.

| Peran | Cakupan akses utama |
|-------|---------------------|
| **super_admin** | Akses penuh: semua data, Panel Kontrol (Settings), kelola pengguna/cabang/shift, semua laporan. |
| **hr_admin** | Operasional HR: kelola karyawan, persetujuan cuti/izin tahap HR, Laporan & Telemetri, Settings. |
| **manager** | Persetujuan cuti/izin tim (dept head), lihat data tim, cetak surat tim. |
| **employee** | Check-in/out, riwayat pribadi, ajukan cuti/izin, profil, cetak surat sendiri. |

Aturan akses bersifat *least privilege*: karyawan biasa hanya bisa melihat &
mencetak datanya sendiri; akses ke data orang lain butuh peran admin/manager.

---

## 3. Area Fitur (Epics)

### 3.1 Autentikasi & Sesi
- Login berbasis email + password (Livewire `Auth/Login`).
- Middleware `auth` + `active` (akun nonaktif diblokir).
- Logout dengan invalidasi sesi + regenerasi token.
- Akun demo per peran tersedia di layar login (untuk dev/demo).

### 3.2 Ruang Absensi (Attendance)
- **Check-in / Check-out** dengan alur verifikasi berlapis:
  1. Izin & validasi lokasi GPS (akurasi).
  2. Validasi geofence cabang (jarak Haversine ke koordinat cabang).
  3. Pengambilan selfie real-time via kamera.
  4. Verifikasi wajah (face recognition Python — lihat §3.7).
  5. Sidik jari perangkat (device fingerprint).
  6. Skor risiko otomatis (lihat §4).
- **Riwayat (History)** — daftar log absensi pribadi/tim dengan filter
  (bulan, tipe, status) dan modal detail (foto, peta, metadata verifikasi).
- **Mode kerja:** WFO, WFH, Hybrid, Mobile Workforce.
- **Demo biometrik** (`BiometricDemo`) untuk peragaan alur verifikasi.

### 3.3 Manajemen Cuti (Leaves)
- Pengajuan cuti oleh karyawan dengan kuota cuti tahunan (`annual_leave_quota`).
- Alur persetujuan: manager → HR.
- Kartu persetujuan (approval card) untuk approver.
- Cetak **Surat Permohonan Cuti** (PDF).

### 3.4 Izin Kerja (Permissions / Dispensasi)
- Pengajuan izin/dispensasi kerja oleh karyawan.
- Persetujuan oleh dept head & HR.
- Cetak **Surat Izin Kerja / Dispensasi** (PDF).

### 3.5 Laporan & Telemetri (Reports — hr_admin, super_admin)
Empat jenis laporan, dapat difilter (pengguna, cabang, rentang tanggal) atau
dipilih baris tertentu, lalu dicetak (view `reports.printable`) / ekspor:
- `presence_summary` — ringkasan kehadiran.
- `coordinates_log` — log koordinat/lokasi absensi.
- `leaves_audit` — audit cuti.
- `device_fingerprints` — daftar perangkat terdaftar.
- Ekspor Excel (Maatwebsite) & PDF (DomPDF).

### 3.6 Panel Kontrol (Settings — hr_admin, super_admin)
- Pengaturan sistem (tabel `settings`): parameter geofence, ambang risiko,
  konfigurasi organisasi/cabang/shift, dsb.

### 3.7 Verifikasi Wajah (Face Recognition)
- Skrip Python (`face_compare.py`, `models/`) membandingkan selfie absensi
  dengan foto referensi karyawan.
- Dipanggil dari backend Laravel; hasil (match/score) memengaruhi skor risiko.
- Catatan: liveness/anti-spoofing disiapkan untuk integrasi lanjutan.

### 3.8 Dokumen Surat Resmi (PDF)
Route `letters.*` dengan otorisasi pemilik/admin:
- Surat Permohonan Cuti · Surat Izin Kerja/Dispensasi · Slip Absen (per record)
  · Surat Keterangan Kehadiran (rentang tanggal).

### 3.9 Profil & Notifikasi
- Profil karyawan (`ProfileIndex`): data diri, tanggal lahir, tanggal bergabung,
  cabang, peran.
- Notifikasi in-app real-time (`NotificationBell`, tabel `notifications`,
  Laravel Reverb untuk broadcast).

### 3.10 Dasbor (Dashboard)
- Statistik kehadiran real-time (hadir, telat, tidak hadir, dll).
- Sorotan aktivitas mencurigakan / log ber-risiko untuk admin.
- Ringkasan status absensi hari ini untuk karyawan.

---

## 4. Sistem Anti-Fraud & Skor Risiko

**Tujuh lapis verifikasi** (sesuai README):
1. Validasi akurasi GPS.
2. Validasi geofence (jarak Haversine).
3. Device fingerprinting (pelacakan browser/perangkat).
4. Verifikasi selfie real-time.
5. Liveness detection (siap integrasi AI).
6. IP intelligence (deteksi VPN — siap integrasi).
7. Analisis perilaku (deteksi *impossible travel*, mismatch zona waktu).

**Skala skor risiko**
- **0–30 (rendah):** auto-approved.
- **31–60 (sedang):** butuh tinjauan.
- **61+ (tinggi):** ditandai untuk investigasi.

Setiap kejadian mencurigakan dicatat di `suspicious_events`; seluruh perubahan
penting tercatat di `audit_logs` + Spatie Activitylog (immutable, ber-IP).

---

## 5. Model Data (ringkas)

| Tabel | Peran |
|-------|-------|
| `users` | Akun + peran, cabang, kuota cuti, tgl lahir/bergabung. |
| `branches` | Lokasi kantor + koordinat geofence. |
| `shifts` | Definisi shift kerja. |
| `attendance_logs` | Record absensi lengkap (timestamp, lokasi, foto, skor risiko). |
| `device_fingerprints` | Perangkat terdaftar per user (hash unik). |
| `suspicious_events` | Log deteksi kecurangan. |
| `leave_requests` | Pengajuan & status cuti. |
| `permission_requests` | Pengajuan & status izin kerja. |
| `settings` | Konfigurasi sistem (key-value). |
| `notifications` | Notifikasi in-app. |
| `audit_logs` + activity_log | Jejak audit. |

---

## 6. Kebutuhan Non-Fungsional

- **Keamanan:** HTTPS wajib di produksi (akses kamera & geolokasi). RBAC ketat,
  otorisasi per-record pada cetak surat. Audit immutable.
- **Performa:** dasbor & daftar responsif; query laporan ter-filter.
- **Aksesibilitas:** kontras teks memenuhi WCAG AA, target sentuh ≥ 40px,
  fokus keyboard terlihat.
- **Responsif:** desktop (nav atas) + mobile (bottom dock). Mobile-first.
- **i18n:** UI Bahasa Indonesia.
- **Kompatibilitas:** browser modern dengan dukungan `getUserMedia` & Geolocation.

---

## 7. Tujuan & Lingkup Redesign (UI/UX)

Redesign ini **murni lapisan presentasi** — tidak mengubah route, logika
Livewire, model, atau skema DB.

**Sasaran**
1. Mengganti `resources/css/app.css` (1369 baris, penuh `!important` & warna
   hardcoded) dengan **design system berbasis token** yang bersih.
2. Tampilan **light-first**, flat, lega, profesional; dark mode sebagai varian
   opsional yang konsisten (bukan ditambal override).
3. Komponen konsisten (button, card, badge, input, table, modal, nav) di semua
   halaman.
4. Tipografi & spacing yang dapat dibaca dan rapi pada mobile maupun desktop.

**Kriteria sukses**
- 0 penggunaan `!important` baru dan 0 warna hex hardcoded di markup; semua via
  token. (Lihat aturan di [CLAUDE.md](CLAUDE.md).)
- Semua 12 view Livewire + 4 surat + layout konsisten dengan design system.
- Kontras teks lulus WCAG AA pada light & dark.
- Tidak ada regresi fungsional (check-in/out, persetujuan, cetak tetap jalan).

**Di luar lingkup (untuk sekarang)**
- Perubahan fitur/logika bisnis baru.
- Migrasi framework atau perubahan skema database.
- Aplikasi mobile native, integrasi payroll, AI liveness (lihat Roadmap).

---

## 8. Roadmap singkat

- **Fase 1 (selesai):** auth, check-in/out, selfie, GPS, fingerprint, skor risiko,
  dasbor.
- **Fase 2 (berjalan):** cuti, izin, shift, tinjauan aktivitas mencurigakan,
  laporan lanjutan.
- **Fase 3 (mendatang):** AI face recognition penuh, liveness detection,
  notifikasi real-time (Reverb), aplikasi mobile, integrasi payroll.
- **Redesign UI (paralel):** lihat §7 dan rencana migrasi di [CLAUDE.md](CLAUDE.md).
