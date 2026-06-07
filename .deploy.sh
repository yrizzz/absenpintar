#!/bin/bash

# PresensiKu Automated Deployment Script
# Usage: bash .deploy.sh
# Dijalankan di root folder project (tempat artisan berada)

# Hentikan script jika ada error yang tidak di-handle
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

# 1. Fix permission storage & cache (aman & tidak sensitif error)
echo "🔑 [1/10] Memperbaiki permission storage & cache..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || \
chown -R $(whoami):$(whoami) storage bootstrap/cache 2>/dev/null || true
echo "✅ Permission storage OK"

# 2. Maintenance mode
echo "🔧 [2/10] Mengaktifkan Maintenance Mode..."
php artisan down --no-interaction || true

# 3. Pull kode terbaru (reset file yang di-generate script agar tidak konflik)
echo "📥 [3/10] Menarik kode terbaru dari GitHub..."
git checkout -- ecosystem.config.cjs 2>/dev/null || true
git pull origin main

# 4. Update cwd di ecosystem.config.cjs sesuai path saat ini (SETELAH pull)
sed -i "s|cwd: \".*\"|cwd: \"$SCRIPT_DIR\"|g" ecosystem.config.cjs
echo "✅ ecosystem.config.cjs diupdate ke path: $SCRIPT_DIR"

# 5. Composer install (non-interactive)
echo "📦 [5/10] Menginstal dependensi PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# 6. NPM build (non-interactive)
echo "📦 [6/10] Mengompilasi Aset Frontend..."
npm install --silent --no-audit --no-fund --no-progress
npm run build

# 7. Migrasi database (non-interactive)
echo "🗄️  [7/10] Menjalankan migrasi database..."
php artisan migrate --force --no-interaction

# 8. Storage link & Livewire assets cleanup
echo "🔗 [8/10] Membuat Symlink Storage..."
php artisan storage:link --no-interaction || true
rm -rf public/vendor/livewire 2>/dev/null || true

# 9. Clear & optimize cache
echo "⚡ [9/10] Mengoptimalkan Cache Laravel..."
php artisan optimize:clear --no-interaction
php artisan optimize --no-interaction

# Re-fix permission setelah optimize agar files baru tetap writable
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || \
chown -R $(whoami):$(whoami) storage bootstrap/cache 2>/dev/null || true

# 10. PM2 restart
echo "🔄 [10/10] Me-restart service PM2..."
if command -v pm2 &> /dev/null; then
    pm2 delete presensiku-reverb presensiku-queue presensiku-scheduler 2>/dev/null || true
    pm2 start ecosystem.config.cjs
    pm2 save
    echo "✅ PM2 services berhasil dijalankan."
else
    echo "⚠️  PM2 tidak terdeteksi. Install dengan: npm install -g pm2"
    echo "    Lalu jalankan: pm2 start ecosystem.config.cjs && pm2 save"
fi

# Matikan maintenance mode
echo "🚀 Menonaktifkan Maintenance Mode..."
php artisan up --no-interaction

echo ""
echo "╔══════════════════════════════════════════╗"
echo "║     ✅  Deploy Selesai dengan Sukses!    ║"
echo "╚══════════════════════════════════════════╝"
echo ""
