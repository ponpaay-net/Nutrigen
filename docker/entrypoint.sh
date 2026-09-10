#!/bin/sh
set -e

echo "==> Menyiapkan aplikasi NutriGen..."

# 1. Pastikan APP_KEY tersedia (kalau kosong, generate agar app tidak 500).
#    Untuk produksi, sebaiknya set APP_KEY tetap lewat environment variable.
if [ -z "$APP_KEY" ]; then
    echo "==> APP_KEY kosong — membuat kunci baru (disarankan set APP_KEY permanen di server)."
    php artisan key:generate --force || true
fi

# 2. Migrasi struktur database (idempotent).
echo "==> Menjalankan migrasi..."
php artisan migrate --force --no-interaction || true

# 3. Isi data demo HANYA bila database masih kosong (aman diulang).
echo "==> Memastikan data demo terisi..."
php artisan nutrigen:seed-if-empty || true

# 4. Tautkan storage (abaikan bila sudah ada).
php artisan storage:link || true

# 5. Bersihkan cache lama agar konfigurasi terbaru dipakai.
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "==> Menjalankan server pada port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
