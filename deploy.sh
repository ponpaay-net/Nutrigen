#!/usr/bin/env bash
#
# NutriGen — Safe Deploy Script
# =============================================================================
# Dipakai untuk mempersiapkan environment target (Antigravity / server / fresh
# clone) setelah code di-pull.
#
# AMAN: menjalankan HANYA migrasi upgrade yang tidak menghapus data
# (soft-delete, validator-tracking, notification_logs) — MENGHINDARI
# 'reseed_production_database' yang destructive (truncate + reseed).
#
# Cara pakai:
#   bash deploy.sh
#   # atau:  chmod +x deploy.sh && ./deploy.sh
# =============================================================================
set -euo pipefail

echo "=========================================="
echo " NutriGen — Safe Deploy"
echo "=========================================="

# --- 0) Cek prasyarat -------------------------------------------------------
if [ ! -f vendor/autoload.php ]; then
    echo "!! vendor/ tidak ditemukan. Jalankan: composer install"
    exit 1
fi

# --- 1) Migrasi upgrade aman (skip reseed destructive) ----------------------
echo
echo "[1/4] Migrasi upgrade aman (migrate:upgrade)..."
php artisan migrate:upgrade

# --- 2) Bersihkan cache agar tidak ada yang basi ----------------------------
echo
echo "[2/4] Bersihkan cache (config/route/cache/view)..."
php artisan config:clear || true
php artisan route:clear || true
php artisan cache:clear || true
php artisan view:clear || true

# --- 3) Rebuild frontend (public/build gitignored, wajib build) --------------
echo
echo "[3/4] Build aset frontend (npm run build)..."
if [ -d node_modules ]; then
    npm run build
else
    echo "!! node_modules tidak ditemukan. Jalankan: npm install"
    exit 1
fi

# --- 4) Verifikasi cepat ----------------------------------------------------
echo
echo "[4/4] Verifikasi cepat..."
php artisan route:list >/dev/null 2>&1 && echo "   ok: router memuat" || echo "   !! router error"

echo
echo "=========================================="
echo " Selesai — deploy aman sukses."
echo " Jalankan: php artisan serve  (untuk dev local)"
echo "=========================================="
