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

# 0. Check and install system dependencies if missing (OpenCV & NumPy)
echo "🔍 [0/10] Memeriksa dependensi Python (OpenCV & NumPy)..."
if ! python3 -c "import cv2, numpy" 2>/dev/null; then
    echo "⚠️ Dependensi Python tidak lengkap. Menginstal python3-opencv dan python3-numpy..."
    if [ "$EUID" -eq 0 ]; then
        apt-get update && apt-get install -y python3-opencv python3-numpy
    else
        sudo apt-get update && sudo apt-get install -y python3-opencv python3-numpy
    fi
else
    echo "✅ Dependensi Python OK"
fi

# Auto-setup .env and Laravel Reverb keys
echo "🔍 [0.5/10] Memeriksa konfigurasi .env dan Reverb..."
if [ ! -f .env ]; then
    echo "📄 File .env tidak ditemukan. Menyalin dari .env.example..."
    cp .env.example .env
fi

setup_reverb_keys() {
    local app_id=$(grep "^REVERB_APP_ID=" .env | cut -d '=' -f2- | tr -d '\r' | xargs 2>/dev/null)
    local app_key=$(grep "^REVERB_APP_KEY=" .env | cut -d '=' -f2- | tr -d '\r' | xargs 2>/dev/null)
    local app_sec=$(grep "^REVERB_APP_SECRET=" .env | cut -d '=' -f2- | tr -d '\r' | xargs 2>/dev/null)

    if [ -z "$app_id" ] || [ -z "$app_key" ] || [ -z "$app_sec" ]; then
        echo "🔑 Menghasilkan Reverb keys baru..."
        
        [ -z "$app_id" ] && app_id=$((100000 + RANDOM % 900000))
        [ -z "$app_key" ] && app_key=$(LC_ALL=C tr -dc 'a-z0-9' </dev/urandom 2>/dev/null | head -c 20)
        [ -z "$app_sec" ] && app_sec=$(LC_ALL=C tr -dc 'a-z0-9' </dev/urandom 2>/dev/null | head -c 20)

        # Pastikan variabel reverb ada di .env
        for var in REVERB_APP_ID REVERB_APP_KEY REVERB_APP_SECRET REVERB_HOST REVERB_PORT REVERB_SCHEME VITE_REVERB_APP_KEY VITE_REVERB_HOST VITE_REVERB_PORT VITE_REVERB_SCHEME; do
            if ! grep -q "^$var=" .env; then
                echo "$var=" >> .env
            fi
        done

        # Update nilai
        sed -i "s|^REVERB_APP_ID=.*|REVERB_APP_ID=$app_id|g" .env
        sed -i "s|^REVERB_APP_KEY=.*|REVERB_APP_KEY=$app_key|g" .env
        sed -i "s|^REVERB_APP_SECRET=.*|REVERB_APP_SECRET=$app_sec|g" .env
        
        if grep -q "^REVERB_HOST=\s*$" .env || ! grep -q "^REVERB_HOST=" .env; then
            sed -i 's|^REVERB_HOST=.*|REVERB_HOST="localhost"|g' .env
        fi
        if grep -q "^REVERB_PORT=\s*$" .env || ! grep -q "^REVERB_PORT=" .env; then
            sed -i "s|^REVERB_PORT=.*|REVERB_PORT=8080|g" .env
        fi
        if grep -q "^REVERB_SCHEME=\s*$" .env || ! grep -q "^REVERB_SCHEME=" .env; then
            sed -i "s|^REVERB_SCHEME=.*|REVERB_SCHEME=http|g" .env
        fi

        sed -i 's|^VITE_REVERB_APP_KEY=.*|VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"|g' .env
        sed -i 's|^VITE_REVERB_HOST=.*|VITE_REVERB_HOST="${REVERB_HOST}"|g' .env
        sed -i 's|^VITE_REVERB_PORT=.*|VITE_REVERB_PORT="${REVERB_PORT}"|g' .env
        sed -i 's|^VITE_REVERB_SCHEME=.*|VITE_REVERB_SCHEME="${REVERB_SCHEME}"|g' .env
        
        echo "✅ Reverb keys berhasil dikonfigurasi di .env."
    else
        echo "✅ Reverb keys sudah terkonfigurasi di .env."
    fi
}
setup_reverb_keys

# 1. Fix permission storage & cache (Gunakan 777 agar web server & CLI bebas menulis cache tanpa tabrakan permission)

echo "🔑 [1/10] Memperbaiki permission storage & cache..."
chmod -R 777 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || \
chown -R www:www storage bootstrap/cache 2>/dev/null || \
chown -R nginx:nginx storage bootstrap/cache 2>/dev/null || \
chown -R apache:apache storage bootstrap/cache 2>/dev/null || \
chown -R $(whoami):$(whoami) storage bootstrap/cache 2>/dev/null || true
echo "✅ Permission storage OK"

# 2. Maintenance mode
echo "🔧 [2/10] Mengaktifkan Maintenance Mode..."
php artisan down --no-interaction || true

# 3. Pull kode terbaru (reset file tracked yang berubah lokal agar tidak konflik merge)
echo "📥 [3/10] Menarik kode terbaru dari GitHub..."
git checkout -- . 2>/dev/null || true
git pull origin main

# 4. Update cwd di ecosystem.config.cjs sesuai path saat ini (SETELAH pull)
sed -i "s|cwd: \".*\"|cwd: \"$SCRIPT_DIR\"|g" ecosystem.config.cjs
echo "✅ ecosystem.config.cjs diupdate ke path: $SCRIPT_DIR"

# 5. Composer install (non-interactive)
echo "📦 [5/10] Menginstal dependensi PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# Generate APP_KEY jika kosong atau tidak ada di .env
if [ -f .env ] && { grep -q "^APP_KEY=$" .env || grep -q "^APP_KEY=\s*$" .env || ! grep -q "^APP_KEY=" .env; }; then
    echo "🔑 Menghasilkan APP_KEY..."
    php artisan key:generate --force --no-interaction
fi


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

# Re-fix permission setelah optimize agar files baru yang di-generate root tetap writable oleh web server
chmod -R 777 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || \
chown -R www:www storage bootstrap/cache 2>/dev/null || \
chown -R nginx:nginx storage bootstrap/cache 2>/dev/null || \
chown -R apache:apache storage bootstrap/cache 2>/dev/null || \
chown -R $(whoami):$(whoami) storage bootstrap/cache 2>/dev/null || true

# 10. PM2 restart
echo "🔄 [10/10] Me-restart service PM2..."
if command -v pm2 &> /dev/null; then
    pm2 delete presensiku-reverb presensiku-queue presensiku-scheduler 2>/dev/null || true
    
    # Membebaskan port Reverb secara otomatis berdasarkan konfigurasi .env
    if [ -f .env ]; then
        REVERB_PORT_TO_KILL=$(grep "^REVERB_SERVER_PORT=" .env | cut -d '=' -f2 | tr -d '\r')
        if [ -z "$REVERB_PORT_TO_KILL" ]; then
            REVERB_PORT_TO_KILL=$(grep "^REVERB_PORT=" .env | cut -d '=' -f2 | tr -d '\r')
        fi
        if [ ! -z "$REVERB_PORT_TO_KILL" ]; then
            echo "🧹 Membebaskan port Reverb $REVERB_PORT_TO_KILL..."
            fuser -k $REVERB_PORT_TO_KILL/tcp 2>/dev/null || true
        fi
    fi

    echo "🧹 Membersihkan sisa-sisa proses artisan yang menggantung di folder ini..."
    PROJECT_DIR=$(pwd)
    for pid in $(pgrep -f "artisan"); do
        if [ "$(readlink -f /proc/$pid/cwd 2>/dev/null)" = "$PROJECT_DIR" ]; then
            echo "Killing process $pid in $PROJECT_DIR..."
            kill -9 $pid 2>/dev/null || true
        fi
    done
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
