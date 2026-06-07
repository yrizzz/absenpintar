#!/bin/bash

# PresensiKu Automated Deployment Script
# Usage: bash .deploy.sh
# Dijalankan di root folder project (tempat artisan berada)

set -e

# Deteksi path project otomatis dari lokasi script ini
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$SCRIPT_DIR"

echo ""
echo "╔══════════════════════════════════════════╗"
echo "║     PresensiKu — Deployment Script      ║"
echo "╚══════════════════════════════════════════╝"
echo "📁 Project path: $SCRIPT_DIR"
echo ""

# Update cwd di ecosystem.config.cjs sesuai path saat ini
sed -i "s|cwd: \".*\"|cwd: \"$SCRIPT_DIR\"|g" ecosystem.config.cjs
echo "✅ ecosystem.config.cjs diupdate ke path: $SCRIPT_DIR"

# 1. Maintenance mode
echo ""
echo "🔧 [1/9] Mengaktifkan Maintenance Mode..."
php artisan down || true

# 2. Pull kode terbaru
echo "📥 [2/9] Menarik kode terbaru dari GitHub..."
git pull origin main

# 3. Composer install
echo "📦 [3/9] Menginstal dependensi PHP..."
composer install --no-dev --optimize-autoloader

# 4. NPM build
echo "📦 [4/9] Mengompilasi Aset Frontend..."
npm install --silent
npm run build

# 5. Migrasi database
echo "🗄️  [5/9] Menjalankan migrasi database..."
php artisan migrate --force

# 6. Storage link
echo "🔗 [6/9] Membuat Symlink Storage..."
php artisan storage:link || true

# 7. Clear & optimize cache
echo "⚡ [7/9] Mengoptimalkan Cache Laravel..."
php artisan optimize:clear
php artisan optimize

# 8. PM2 restart
echo "🔄 [8/9] Me-restart service PM2..."
if command -v pm2 &> /dev/null; then
    pm2 delete presensiku-reverb presensiku-queue presensiku-scheduler 2>/dev/null || true
    pm2 start ecosystem.config.cjs
    pm2 save
    echo "✅ PM2 services berhasil dijalankan."
else
    echo "⚠️  PM2 tidak terdeteksi. Install dengan: npm install -g pm2"
    echo "    Lalu jalankan: pm2 start ecosystem.config.cjs && pm2 save"
fi

# 9. Matikan maintenance mode
echo "🚀 [9/9] Menonaktifkan Maintenance Mode..."
php artisan up

echo ""
echo "╔══════════════════════════════════════════╗"
echo "║     ✅  Deploy Selesai dengan Sukses!    ║"
echo "╚══════════════════════════════════════════╝"
echo ""
