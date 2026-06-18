# CLAUDE.md — PresensiKu

Panduan kerja untuk Claude Code di repo ini. Baca [PRD.md](PRD.md) untuk konteks
produk. Dokumen ini fokus pada **cara membangun** dan **design system** untuk
redesign UI yang konsisten, modern, dan profesional.

---

## 1. Tentang proyek

PresensiKu — sistem presensi/absensi enterprise dengan anti-fraud (GPS+geofence,
selfie/face recognition, device fingerprint, skor risiko). UI Bahasa Indonesia.

## 2. Tech stack

| Lapis | Teknologi |
|------|-----------|
| Backend | Laravel **13.8**, PHP **8.3+** |
| Frontend | Livewire **4.3**, Alpine.js **3**, Blade |
| Styling | TailwindCSS **4** (via `@tailwindcss/vite`), Vite **8** |
| DB | SQLite (dev) / MySQL 8 (prod) |
| RBAC & audit | spatie/laravel-permission 7, spatie/laravel-activitylog 5 |
| Realtime | Laravel Reverb 1 + laravel-echo + pusher-js |
| Dokumen | barryvdh/laravel-dompdf, maatwebsite/excel, intervention/image |
| Face recognition | Python (`face_compare.py`, `models/`) |
| Font | Geist + Geist Mono (Google Fonts) |

## 3. Perintah

```bash
# Dev (Vite + hot reload)
npm run dev
php artisan serve

# Build aset produksi
npm run build

# Database
php artisan migrate            # atau migrate --seed
php artisan migrate:fresh --seed

# Kualitas kode PHP
./vendor/bin/pint              # formatter (Laravel Pint)
php artisan test               # PHPUnit

# Deploy
./.deploy.sh                   # script deploy (PM2 via ecosystem.config.cjs)
```

> Catatan: kamera & geolokasi butuh **HTTPS** (atau `localhost`) agar
> `getUserMedia`/Geolocation aktif.

## 4. Struktur & konvensi

- **Komponen Livewire 4:** kelas di `app/Livewire/<Area>/<Name>.php`, view di
  `resources/views/livewire/<area>/<name>.blade.php` (kebab-case).
- **Layout / app shell:** `resources/views/layouts/app.blade.php` — shell
  **sidebar** (desktop, fixed `w-64`) + **drawer** (mobile, via Alpine
  `sidebarOpen`) + **topbar** (notifikasi + toggle tema). Isi nav dipakai-ulang
  dari `layouts/partials/sidebar.blade.php` (logo, nav berkelompok, kartu
  pengguna + Keluar Sesi). `guest.blade.php` untuk halaman login. Setiap view
  membungkus kontennya sendiri dengan `mx-auto max-w-7xl px-4 sm:px-6 lg:px-8`.
- **Route:** `routes/web.php` — grup `auth`+`active`, dibatasi `role:` per area.
- **Model:** `app/Models/*`. **Surat PDF:** `resources/views/letters/*`.
- **Bahasa UI:** Indonesia. Label menu: Dasbor, Ruang Absensi, Manajemen Cuti,
  Izin Kerja, Laporan & Telemetri, Panel Kontrol, Profil.
- Jangan ubah route, nama komponen, atau skema DB saat mengerjakan redesign UI.

---

# 5. DESIGN SYSTEM — "Clean Modern SaaS" (light-first)

Arah visual yang disepakati: **flat, lega, tenang, profesional** (gaya
Linear / Vercel / shadcn). **Light mode adalah default**; dark mode adalah varian
yang setara dan konsisten — bukan tambalan.

### 5.1 Prinsip

1. **Token, bukan nilai mentah.** Tidak ada warna hex hardcoded di Blade.
   Semua warna lewat token semantik (lihat §5.3).
2. **Tanpa `!important`.** Jika butuh `!important`, berarti arsitekturnya salah —
   perbaiki di token/komponen, bukan tambal override.
3. **Flat & tenang.** Hindari glassmorphism berlapis, glow neon, gradient
   ramai, dan animasi berlebih. Kedalaman = border halus + shadow tipis.
4. **Hierarki lewat tipografi & spacing**, bukan lewat warna mencolok.
5. **Satu warna brand.** Aksen tunggal (blue) untuk aksi primer & state aktif.
   Warna lain hanya untuk status semantik (success/warning/danger/info).
6. **Konsisten lintas halaman.** Pakai utilitas komponen yang sama di mana-mana.

### 5.2 Fondasi token (Tailwind v4 `@theme`)

Definisikan di `resources/css/app.css`. Warna semantik memakai CSS variable yang
**di-flip** antara light/dark, lalu dipetakan ke Tailwind via `@theme inline`.

```css
@import 'tailwindcss';
@source '../**/*.blade.php';

/* Dark mode via class .dark di <html> (default = light) */
@custom-variant dark (&:where(.dark, .dark *));

:root {
  /* Surfaces */
  --color-bg:            #f8fafc; /* app background  (slate-50)  */
  --color-surface:       #ffffff; /* card / panel                */
  --color-surface-muted: #f1f5f9; /* subtle fill (slate-100)     */
  --color-border:        #e2e8f0; /* hairline (slate-200)        */
  --color-border-strong: #cbd5e1; /* slate-300                   */

  /* Text */
  --color-fg:            #0f172a; /* slate-900 — judul/utama     */
  --color-fg-muted:      #475569; /* slate-600 — sekunder        */
  --color-fg-subtle:     #94a3b8; /* slate-400 — placeholder     */

  /* Brand (aksen tunggal) */
  --color-primary:        #2563eb; /* blue-600                   */
  --color-primary-hover:  #1d4ed8; /* blue-700                   */
  --color-primary-fg:     #ffffff;
  --color-ring:           #3b82f6; /* focus ring                 */

  /* Status */
  --color-success: #059669;  --color-success-soft: #ecfdf5;
  --color-warning: #d97706;  --color-warning-soft: #fffbeb;
  --color-danger:  #e11d48;  --color-danger-soft:  #fef2f2;
  --color-info:    #0284c7;  --color-info-soft:    #f0f9ff;

  /* Radius & shadow */
  --radius:     0.625rem;          /* 10px — radius default       */
  --radius-lg:  0.875rem;          /* 14px — card                 */
  --shadow-sm:  0 1px 2px rgba(15,23,42,.04);
  --shadow:     0 1px 3px rgba(15,23,42,.06), 0 1px 2px rgba(15,23,42,.04);
  --shadow-md:  0 4px 12px rgba(15,23,42,.08);
}

.dark {
  --color-bg:            #0b1120; /* slate-950-ish               */
  --color-surface:       #111827; /* slate-900                   */
  --color-surface-muted: #1e293b; /* slate-800                   */
  --color-border:        #1f2a3c;
  --color-border-strong: #334155;

  --color-fg:            #f1f5f9;
  --color-fg-muted:      #94a3b8;
  --color-fg-subtle:     #64748b;

  --color-primary:       #3b82f6;
  --color-primary-hover: #60a5fa;
  --color-primary-fg:    #0b1120;
  --color-ring:          #3b82f6;

  --color-success: #34d399; --color-success-soft: #052e23;
  --color-warning: #fbbf24; --color-warning-soft: #2a2206;
  --color-danger:  #fb7185; --color-danger-soft:  #2a0f17;
  --color-info:    #38bdf8; --color-info-soft:    #07263a;

  --shadow-sm:  0 1px 2px rgba(0,0,0,.4);
  --shadow:     0 1px 3px rgba(0,0,0,.5);
  --shadow-md:  0 8px 24px rgba(0,0,0,.5);
}

@theme inline {
  --font-sans: 'Geist', ui-sans-serif, system-ui, sans-serif;
  --font-mono: 'Geist Mono', ui-monospace, monospace;

  --color-bg: var(--color-bg);
  --color-surface: var(--color-surface);
  --color-surface-muted: var(--color-surface-muted);
  --color-border: var(--color-border);
  --color-fg: var(--color-fg);
  --color-fg-muted: var(--color-fg-muted);
  --color-fg-subtle: var(--color-fg-subtle);
  --color-primary: var(--color-primary);
  --color-success: var(--color-success);
  --color-warning: var(--color-warning);
  --color-danger:  var(--color-danger);
  --color-info:    var(--color-info);
}
```

Pemakaian di Blade: `bg-surface`, `text-fg`, `text-fg-muted`, `border-border`,
`bg-primary text-primary-fg`, `text-success`, dst — **bukan** `bg-[#121d33]`,
`text-white`, `text-slate-400`.

> Penting: hentikan pola lama `html.light .bg-[#...] { ... !important }`. Default
> sekarang light; dark di-handle variabel `.dark`, bukan ratusan override.

### 5.3 Skala tipografi

Font: **Geist**. Default body `text-fg`, ukuran `text-sm`/`text-base`.

| Token | Kelas | Pakai untuk |
|------|-------|-------------|
| Display | `text-2xl/3xl font-semibold tracking-tight text-fg` | judul halaman |
| Heading | `text-lg font-semibold text-fg` | judul kartu/section |
| Body | `text-sm text-fg` | teks utama |
| Muted | `text-sm text-fg-muted` | keterangan/sekunder |
| Label | `text-xs font-medium text-fg-muted uppercase tracking-wide` | label form/tabel |
| Metric | `text-3xl font-semibold tabular-nums text-fg` | angka dasbor |

Aturan berat font: maksimal `font-semibold` untuk judul (hindari `font-black`/
`font-extrabold` yang dipakai versi lama — terlalu "tebal/ramai"). Angka pakai
`tabular-nums`.

### 5.4 Spacing, radius, density

- Skala spacing Tailwind default (4px grid). Padding kartu: `p-5`/`p-6`.
- Radius: input/button `rounded-lg`, card `rounded-xl`, pill/badge `rounded-full`.
  (Hentikan `rounded-2xl` masif versi lama.)
- Container halaman: `mx-auto max-w-7xl px-4 sm:px-6 lg:px-8`.
- Gap antar section: `space-y-6`. Tabel: density nyaman, baris `py-3`.

### 5.5 Komponen (utility classes)

Definisikan sebagai `@utility`/`@layer components` di `app.css`. Spesifikasi:

**Button**
- `.btn` — basis: `inline-flex items-center justify-center gap-2 rounded-lg
  text-sm font-medium px-4 h-10 transition-colors focus-visible:ring-2
  ring-[--color-ring] disabled:opacity-50 disabled:pointer-events-none`.
- `.btn-primary` — `bg-primary text-primary-fg hover:bg-[--color-primary-hover]`.
- `.btn-secondary` — `bg-surface border border-border text-fg hover:bg-surface-muted`.
- `.btn-ghost` — transparan, `hover:bg-surface-muted`.
- `.btn-danger` — `bg-danger text-white hover:opacity-90`.
- Ukuran: `.btn-sm` (h-8 px-3 text-xs), `.btn-lg` (h-11 px-5).
- **Tanpa gradient, tanpa `scale` hover, tanpa glow.** Transisi hanya warna.

**Card / panel**
- `.card` — `bg-surface border border-border rounded-xl shadow-sm`.
- Header kartu: judul `text-lg font-semibold` + deskripsi `text-sm text-fg-muted`,
  body `p-6`, pemisah `border-border`.

**Badge** (status semantik, soft fill)
- `.badge` basis: `inline-flex items-center rounded-full px-2.5 py-0.5 text-xs
  font-medium`.
- Varian: `.badge-success` (`bg-success-soft text-success`), `-warning`,
  `-danger`, `-info`, `-neutral` (`bg-surface-muted text-fg-muted`).

**Input / select / textarea**
- `.input` — `w-full h-10 rounded-lg bg-surface border border-border px-3
  text-sm text-fg placeholder:text-fg-subtle focus-visible:ring-2
  ring-[--color-ring] focus:border-primary`.
- Label: `.label` = `text-sm font-medium text-fg`. Pesan error: `text-danger`.

**Table**
- Wrapper `.card overflow-hidden`. `thead`: `bg-surface-muted text-fg-muted
  text-xs uppercase tracking-wide`. Baris: `border-b border-border`,
  `hover:bg-surface-muted/60`. Sel: `px-4 py-3 text-sm`.

**Modal**
- Overlay: `bg-slate-900/40 backdrop-blur-sm`. Panel: `.card max-w-lg shadow-md`.
  Konsisten light & dark (jangan panel putih flat di dark).

**Nav (top) & bottom dock**
- Nav: `bg-surface/80 backdrop-blur border-b border-border`. Item aktif:
  `text-primary` + indikator garis bawah `border-primary`; nonaktif:
  `text-fg-muted hover:text-fg`. Hindari 5 warna berbeda untuk 5 tab — pakai satu
  warna aktif (`primary`).

**Alert / flash**
- Pakai pasangan `*-soft` + `text-*` + ikon, border `border-*`. Tanpa glow.

### 5.6 Ikon & ilustrasi

- Ikon: outline stroke (heroicons-style) konsisten, `w-4 h-4`/`w-5 h-5`,
  `stroke-width:1.5`. Warna ikut `currentColor`.
- Logo brand: shield + wordmark "Presensi**Ku**" (Ku = `text-primary`).

### 5.7 Motion

- Transisi 150–200ms, `ease-out`, hanya untuk `colors`, `opacity`, kecil
  `translate`. **Hapus** animasi `float`, `pulse-subtle`, `scan-glow`, dan
  `hover:scale` dari sistem lama. Hormati `prefers-reduced-motion`.

### 5.8 Aksesibilitas

- Kontras teks ≥ WCAG AA (4.5:1). Token sudah dipilih untuk lulus pada `--color-bg`.
- `focus-visible` ring jelas di semua elemen interaktif.
- Target sentuh ≥ 40px (`h-10`). Label form selalu terkait input.

---

## 6. Rencana migrasi redesign

Kerjakan bertahap agar tidak ada regresi. **Jangan** sentuh logika Livewire/route.

1. **Fondasi:** tulis ulang `resources/css/app.css` dengan token §5.2 + komponen
   §5.5. Set default light, dark via `.dark`. Update toggle tema agar memakai
   `.dark` (bukan `.light`) — sesuaikan `layouts/app.blade.php` baris init theme.
2. **Layout shell:** `layouts/app.blade.php` & `guest.blade.php` → token + nav/
   dock baru (satu warna aktif, hapus glow/gradient).
3. **Per view** (satu per satu, verifikasi tiap selesai): `auth/login` →
   `dashboard` → `attendance/*` → `leaves/*` → `permissions/*` → `reports/*` →
   `settings/*` → `profile` → `notification-bell` → `components/attendance/detail-modal`.
4. **Surat PDF** (`letters/*`): rapikan agar profesional & cetak-friendly
   (warna aman untuk print, hindari background gelap).
5. **Bersih-bersih:** hapus semua hex hardcoded (`bg-[#121d33]`, `bg-[#0d1527]`,
   dst), `text-white`, dan utilitas mati. Pastikan 0 `!important`.

**Definition of done per view**
- Tidak ada hex/`!important` baru; semua via token.
- Konsisten dengan komponen §5.5; light & dark sama-sama rapi.
- Fungsionalitas tidak berubah (manual cek alur utama).

## 7. Larangan (untuk menjaga konsistensi)

- ❌ Warna hex hardcoded di Blade (`bg-[#...]`, `text-[#...]`).
- ❌ `!important` (kecuali benar-benar tak terhindarkan & dikomentari alasannya).
- ❌ Glassmorphism berlapis, glow neon, gradient ramai, `hover:scale`,
  `font-black`.
- ❌ Override per-selector rapuh (`button[onclick*="..."]`).
- ❌ Mengubah route, nama komponen, atau skema DB saat redesign UI.
- ✅ Token semantik, komponen reusable, light-first, flat & tenang.
