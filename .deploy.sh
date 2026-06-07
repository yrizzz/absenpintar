#!/bin/bash

# PresensiKu Automated Deployment Script
# Digunakan untuk melakukan pull dan setup otomatis versi terbaru dari GitHub

# Hentikan script jika ada error
set -e

echo "=== Memulai Proses Deploy PresensiKu ==="

# 1. Masuk ke maintenance mode
echo "🔧 Mengaktifkan Maintenance Mode..."
php artisan down || true

# 2. Pull kode terbaru dari GitHub (branch main)
echo "📥 Menarik kode terbaru dari GitHub..."
git pull origin main

# 3. Install/Update dependensi PHP (mode produksi)
echo "📦 Menginstal dependensi PHP..."
composer install --no-dev --optimize-autoloader

# 4. Kompilasi aset frontend
echo "📦 Mengompilasi Aset Frontend..."
npm install
npm run build

# 5. Jalankan migrasi database
echo "🗄️ Menjalankan migrasi database..."
php artisan migrate --force

# 6. Buat symlink storage
echo "🔗 Membuat Symlink Storage..."
php artisan storage:link || true

# 7. Bersihkan dan optimalkan cache Laravel
echo "⚡ Mengoptimalkan Cache Laravel..."
php artisan optimize:clear
php artisan optimize

# 8. Restart service PM2 (Reverb + Queue + Scheduler)
if command -v pm2 &> /dev/null
then
    echo "🔄 Menghentikan service lama di PM2..."
    pm2 delete presensiku-reverb presensiku-queue presensiku-scheduler || true
    echo "🚀 Memulai ulang service PM2..."
    pm2 start ecosystem.config.cjs
else
    echo "⚠️ PM2 tidak terdeteksi. Silakan restart Reverb/Queue secara manual."
fi

# 9. Matikan maintenance mode
echo "🚀 Menonaktifkan Maintenance Mode (Web Online)..."
php artisan up

echo "=== Proses Deploy PresensiKu Selesai! ==="
