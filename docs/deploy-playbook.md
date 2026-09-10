# 🚀 Playbook Deploy Nutrigen ke VPS (Sumopod) + Domain (Hostinger)

> Panduan langkah-demi-langkah: dari VPS kosong sampai `https://nutrigen.id` hidup.
> Ganti semua placeholder: `IP_SERVER`, `PASSWORD_KUAT`, sesuaikan jika nama domain berbeda.
> Terakhir diperbarui: September 2026. Target: Ubuntu 24.04 LTS, Laravel, PHP 8.3, MySQL 8, Nginx.

---

## Bagian 0 — Ringkasan & Prasyarat

**Biaya bulanan (estimasi):**

| Item | Biaya |
|---|---|
| VPS Sumopod 2vCPU/4GB/60GB | Rp 90.000/bln |
| Domain `.id` di Hostinger | Rp 20–50rb/**tahun** |
| SSL Let's Encrypt | Gratis |

**Yang perlu disiapkan sebelum mulai:**
- [ ] Akun Sumopod + saldo
- [ ] Domain sudah dibeli di Hostinger (mis. `nutrigen.id`)
- [ ] Repo git Nutrigen (branch `bintangpunya` sudah di-merge ke `main`, atau sesuaikan)
- [ ] Token Fonnte siap (CATATAN: device Fonnte sedang dibatasi — deploy boleh, test kirim WA menunggu normal)
- [ ] PC Windows Anda punya Git Bash (untuk SSH)

---

## Bagian 1 — Buat VPS di Sumopod

1. Login dashboard Sumopod → **Create Server**.
2. Pilih plan **Rp 90.000** (Tencent 2 vCPU / 4 GB / 60 GB / 1.54 TB egress).
3. Pilih OS: **Ubuntu 24.04 LTS**.
4. Pilih region **Jakarta** (jika tersedia).
5. Authentication: pilih **Password** (Sumopod buat user `ubuntu` + password OS) **ATAU**
   **SSH Key** (lebih aman, tidak perlu ingat password).
   - Jika **Password**: login `ssh ubuntu@IP_SERVER` pakai password OS. Lanjut ke Bagian 2,
     lewati langkah 2.3 (rsync key) — buat key sendiri nanti (lihat "Setup key manual" di bawah 2.3).
   - Jika **SSH Key**: Sumopod menyuntikkan public key ke `~ubuntu/.ssh/`. Lanjut Bagian 2 normal.
6. Setelah server jadi, **catat IP publik** (contoh: `203.0.113.45`).

7. Jika Sumopod punya **Cloud Firewall**: buka inbound TCP **22, 80, 443**.

> **Login pertama:** image Ubuntu (Tencent) menonaktifkan login `root` via SSH.
> User default = **`ubuntu`**, password = yang diberikan Sumopod (password OS).
> Login: `ssh ubuntu@IP_SERVER` lalu naik root dengan `sudo -i` (pakai password sama).
> Jika Sumopod menawarkan pilihan "Password" vs "SSH Key" saat buat server:
> pilih **SSH Key** (lebih aman, tidak perlu ingat password) — lihat langkah 5 di atas.

---

## Bagian 2 — Setup Awal Server

```bash
# Login sebagai user default ubuntu (root tidak bisa SSH langsung di Ubuntu)
ssh ubuntu@IP_SERVER
# masukkan password OS yang diberikan Sumopod

# Naik ke root (minta password yang SAMA)
sudo -i

# 2.1 Update sistem
apt update && apt upgrade -y

# 2.2 Buat user kerja (jangan pakai root/sehari-hari)
#    Catatan: setelah `sudo -i` di atas kita sudah jadi root, jadi tanpa sudo.
#    Jika belum `sudo -i`, tambahkan `sudo` di depan tiap perintah.
adduser bintang
usermod -aG sudo bintang

# 2.3 Salin akses SSH ke user baru
#    JIKA buat server pakai SSH Key: key sudah ada di ~ubuntu/.ssh
rsync --archive --chown=bintang:bintang /home/ubuntu/.ssh /home/bintang
#    JIKA buat server pakai PASSWORD (tidak ada key): LEWATI rsync di atas,
#    lalu buat key manual dari laptop (sekali saja):
#      # di LAPTOP (Git Bash)
#      ssh-keygen -t ed25519 -C "bintang-nutrigen"   # Enter-Enter
#      ssh-copy-id -i ~/.ssh/id_ed25519.pub ubuntu@IP_SERVER   # minta password ubuntu
#      # lalu di VPS (sudo -i): rsync --archive --chown=bintang:bintang /home/ubuntu/.ssh /home/bintang

# 2.4 Buat swap 2GB (penyelamat saat RAM penuh — wajib walau 4GB)
fallocate -l 2G /swapfile
chmod 600 /swapfile
mkswap /swapfile
swapon /swapfile
echo '/swapfile none swap sw 0 0' >> /etc/fstab
# Kurangi kecenderungan swap
echo 'vm.swappiness=20' >> /etc/sysctl.conf && sysctl -p

# 2.5 Firewall
ufw allow OpenSSH
ufw allow 80/tcp
ufw allow 443/tcp
ufw enable
ufw status
```

**Kunci SSH (opsional tapi disarankan)** — setelah yakin login `ssh bintang@IP_SERVER` jalan via key:
```bash
sudo nano /etc/ssh/sshd_config
# ubah: PasswordAuthentication no
sudo systemctl restart ssh
```
> ⚠️ Jangan close sesi lama sebelum test login baru berhasil!

### 2.6 — Menambah akses VPS untuk teman tim (per-user, bisa dicabut)

Akses VPS tidak terikat laptop — siapa pun yang punya key + IP bisa SSH dari mana saja.
**Jangan** share satu user yang sama (mis. password user `bintang`); buat user terpisah
per orang supaya ada jejak siapa mengerjakan apa, dan akses bisa dicabut satu-satu.

**Langkah 1 — Teman membuat SSH key di laptopnya sendiri** (Git Bash / Terminal):
```bash
ssh-keygen -t ed25519 -C "nama-teman"
# Enter-Enter saja (default ~/.ssh/id_ed25519)
cat ~/.ssh/id_ed25519.pub     # INI yang dikirim ke kamu — aman dishare
```
> Yang dibagikan hanya **public key** (diawali `ssh-ed25519 ...`). Private key
> (`id_ed25519`, tanpa `.pub`) tidak boleh keluar dari laptop dia, kapan pun.

**Langkah 2 — Kamu pasang aksesnya dari laptopmu:**
```bash
ssh bintang@IP_SERVER

# buat user baru (ganti 'temanku' dengan nama dia)
sudo adduser temanku
# HAPUS baris di bawah jika dia tidak perlu hak admin/sudo:
sudo usermod -aG sudo temanku

# pasang public key MILIK DIA
sudo mkdir -p /home/temanku/.ssh
echo "PASTE_ISI_id_ed25519.pub_DIA_DISINI" | sudo tee -a /home/temanku/.ssh/authorized_keys
sudo chmod 700 /home/temanku/.ssh
sudo chmod 600 /home/temanku/.ssh/authorized_keys
sudo chown -R temanku:temanku /home/temanku/.ssh
```

**Langkah 3 — Teman login:** `ssh temanku@IP_SERVER` → selesai.

**Mencabut akses kapan saja:**
```bash
sudo nano /home/temanku/.ssh/authorized_keys   # hapus baris key-nya
# atau nonaktifkan seluruh user:
sudo usermod -L temanku && sudo usermod -s /usr/sbin/nologin temanku
```

**Akses untuk dirimu sendiri:** sudah dibuat di Langkah 2.2–2.3 (user `bintang` + key
kamu disalin via rsync) — cukup `ssh bintang@IP_SERVER` dari laptop mana pun dengan
private key yang sama. Simpan cadangan `~/.ssh/id_ed25519` kamu di tempat aman.

**Anti brute-force (opsional, 1 menit):**
```bash
sudo apt install -y fail2ban
sudo systemctl enable --now fail2ban
sudo fail2ban-client status sshd     # IP yang salah key berulang diblok otomatis
```

> 📌 Catatan untuk tim: akses SSH = akses PENUH ke server, termasuk `.env`
> (password DB + token Fonnte) dan seluruh data balita. Tambahkan hanya orang
> yang memang ikut deploy/maintenance. Yang sekadar ingin melihat website
> cukup dikasih URL `https://nutrigen.id` — tidak perlu SSH.

---

## Bagian 3 — Install Stack

```bash
# Login sebagai user baru
ssh bintang@IP_SERVER

# 3.1 Nginx + MySQL + PHP 8.3 + ekstensi Laravel
sudo apt install -y nginx mysql-server unzip curl git
sudo apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring \
  php8.3-xml php8.3-bcmath php8.3-curl php8.3-zip php8.3-gd php8.3-intl

# 3.2 Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version

# 3.3 Node.js 20 (untuk npm run build — WAJIB, tanpa ini halaman 500 karena manifest Vite hilang)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
node -v && npm -v

# 3.4 Aktifkan OPcache (sudah terpasang bersama php8.3-fpm, pastikan on)
php -i | grep opcache.enable   # harus: opcache.enable => On
```

---

## Bagian 4 — Database Production

```bash
# Amankan MySQL
sudo mysql_secure_installation
# jawab: validate password = MEDIUM, set password root, remove anonymous = Y,
#        disallow root remote = Y, remove test db = Y, reload = Y
```

Buat database + user khusus aplikasi:
```bash
sudo mysql
```
```sql
CREATE DATABASE nutrigen CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'nutrigen'@'localhost' IDENTIFIED BY 'PASSWORD_KUAT';
GRANT ALL PRIVILEGES ON nutrigen.* TO 'nutrigen'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

> Simpan `PASSWORD_KUAT` di password manager. Jangan pakai password yang sama dengan lokal.

---

## Bagian 5 — Deploy Kode Nutrigen

```bash
# 5.1 Clone repo ke /var/www
sudo mkdir -p /var/www
cd /var/www
sudo git clone <URL-REPO-GITHUB-ANDA> nutrigen
# jika branch produksi bukan main:
cd nutrigen && sudo git checkout main   # atau bintangpunya

# 5.2 Kepemilikan: kode milik bintang, folder runtime milik www-data
sudo chown -R bintang:bintang /var/www/nutrigen
sudo chown -R www-data:www-data /var/www/nutrigen/storage /var/www/nutrigen/bootstrap/cache

# 5.3 Install dependency
cd /var/www/nutrigen
composer install --optimize-autoloader --no-dev
npm ci && npm run build          # WAJIB — menghasilkan public/build/manifest.json

# 5.4 Buat .env produksi
cp .env.example .env
nano .env
```

Isi `.env` produksi (yang penting):
```ini
APP_NAME=Nutrigen
APP_ENV=production          # WAJIB
APP_DEBUG=false             # WAJIB — jangan pernah true di produksi
APP_URL=https://nutrigen.id # WAJIB HTTPS — signed URL Portal Ibu di-generate dari sini

APP_KEY=                    # dibiarkan kosong, di-generate langkah berikutnya

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nutrigen
DB_USERNAME=nutrigen
DB_PASSWORD=PASSWORD_KUAT

SESSION_DRIVER=database     # atau file — sesuaikan dengan migrasi yang ada
CACHE_STORE=file
QUEUE_CONNECTION=sync       # YAGNI: belum pakai queue/worker

WA_DRIVER=fonnte
FONNTE_TOKEN=TOKEN_FONNTE_ANDA
# FONNTE/Wablas token rahasia — pastikan .env TIDAK di-commit ke git!
```

```bash
# 5.5 Generate key, migrasi, storage link
php artisan key:generate
php artisan migrate --force
# ⚠️ JANGAN `db:seed` di produksi — data demo (akun demo, balita dummy) tidak boleh masuk!
php artisan storage:link

# 5.6 Kunci file .env (berisi password DB + token Fonnte)
chmod 640 .env
sudo chown bintang:www-data .env

# 5.7 Cache konfigurasi (wajib di produksi, mempercepat setiap request)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Bagian 6 — Nginx + PHP-FPM

```bash
sudo nano /etc/nginx/sites-available/nutrigen
```

```nginx
server {
    listen 80;
    server_name nutrigen.id www.nutrigen.id;
    root /var/www/nutrigen/public;     # WAJIB menunjuk ke /public
    index index.php;

    client_max_body_size 20M;

    # Cache asset build Vite agresif (file-nya ber-hash, aman di-cache)
    location /build/ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```

```bash
sudo ln -s /etc/nginx/sites-available/nutrigen /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t          # harus: syntax is ok / test is successful
sudo systemctl reload nginx
```

Sekarang buka `http://IP_SERVER` — harusnya muncul halaman Nutrigen (masih HTTP, tanpa domain pun jalan).

---

## Bagian 7 — Domain (Hostinger) → VPS

**Opsi A: DNS langsung di Hostinger (paling cepat)**
1. Login Hostinger → **Domains → nutrigen.id → DNS / Nameservers**.
2. Tambah/ubah 2 record A:
   | Type | Name | Points to | TTL |
   |---|---|---|---|
   | A | `@` | `IP_SERVER` | 3600 |
   | A | `www` | `IP_SERVER` | 3600 |
3. Hapus record lain yang tidak dipakai (parking/redirect bawaan).

**Opsi B: lewat Cloudflare (disarankan untuk produksi)**
1. Daftar Cloudflare (gratis) → **Add site** `nutrigen.id` → plan Free.
2. Cloudflare memberi 2 nameserver (mis. `vera.ns.cloudflare.com`).
3. Di Hostinger → ganti **Nameservers** ke 2 nameserver Cloudflare itu (propagasi bisa 1–24 jam).
4. Di Cloudflare → **DNS**: buat `A @ → IP_SERVER` (proxy 🟠 ON) dan `A www → IP_SERVER` (proxy ON).
5. **SSL/TLS → Overview → Full (Strict)** — WAJIB strict, bukan Flexible (Flexible bikin redirect loop).

Tes propagasi (dari PC):
```bash
nslookup nutrigen.id
# harus sudah menjawab IP_SERVER
```

---

## Bagian 8 — SSL (Let's Encrypt)

```bash
# Di VPS
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d nutrigen.id -d www.nutrigen.id
# ikuti prompt; pilih "Redirect" (2) agar HTTP otomatis ke HTTPS
```

- certbot otomatis mengubah config Nginx + memasang timer perpanjangan.
- Cek perpanjangan otomatis: `sudo certbot renew --dry-run`.
- Jika pakai **Cloudflare Full (Strict)**: sertifikat origin dari certbot ini tetap dipakai Cloudflare untuk validasi strict. ✅

Setelah HTTPS hidup, pastikan `.env` `APP_URL=https://nutrigen.id` lalu:
```bash
php artisan config:cache
```

---

## Bagian 9 — Hardening Pre-Produksi (KHUSUS Nutrigen)

Checklist ini menyambung dengan daftar pre-prod kita. **Jangan skip.**

```bash
cd /var/www/nutrigen

# 9.1 Kunci endpoint refresh-database (bisa truncate+seed DB!)
#     Cara paling aman: gate by environment. Edit routes/web.php — bungkus route
#     refresh dengan kondisi, atau tambahkan di atas definisinya:
#       if (app()->environment('production')) { abort(404); }
#     (atau hapus route-nya sama sekali di branch produksi)

# 9.2 Ganti semua kredensial demo
php artisan tinker
```
```php
// di tinker — ganti password semua akun produksi, hapus akun demo
// contoh: user puskesmas & kader dibuat ulang dengan password kuat via seeder produksi terpisah
// hapus balita dummy / pengukuran dummy jika tidak dipakai:
//   App\Models\Pengukuran::where('id','<=',N)->delete();  (sesuaikan — hati-hati!)
```
```bash
# 9.3 Verifikasi debug off & error tak bocor
curl -s http://127.0.0.1/halaman-tidak-ada | grep -i "whoops\|exception"  # harus kosong

# 9.4 Uji keamanan signed URL Portal Ibu (setelah domain hidup)
#   a. Login puskesmas, approve satu pengukuran → link WA yang di-generate HARUS https://nutrigen.id/...
#   b. Buka link dari HP → harus 200
#   c. Ubah parameter balita/orang_tua di URL → harus 403 (anti-IDOR)
#   d. Ubah/hapus signature → harus 403 InvalidSignature
#   e. Rate limit: refresh portal >20x/menit → request ke-21 harus 429

# 9.5 Pastikan URL signed TIDAK di-log plaintext (cek storage/logs setelah uji di atas)
grep -r "portal-ibu" storage/logs/ | head   # sebaiknya tidak ada URL lengkap+signature

# 9.6 TTL link portal: config/portal.php link_ttl_days — produksi disarankan <= 7 hari
```

> ⚠️ **Fonnte**: device masih dibatasi. Deploy boleh, tapi JANGAN test kirim WA via Fonnte sampai device normal. Fallback tombol "Kirim WA" (wa.me) tetap berfungsi lewat HP pengguna. Reminder terjadwal akan ter-log sebagai gagal/retry — pantau `notification_logs`.

---

## Bagian 10 — Cron: Reminder WA Terjadwal + Backup Harian

```bash
# 10.1 Scheduler Laravel (reminder WA otomatis)
sudo crontab -e -u www-data
```
```
* * * * * cd /var/www/nutrigen && php artisan schedule:run >> /dev/null 2>&1
```

```bash
# 10.2 Backup DB harian 02.00 + rotasi 7 hari
sudo mkdir -p /var/backups/nutrigen
sudo nano /usr/local/bin/backup-nutrigen.sh
```
```bash
#!/bin/bash
# Backup harian Nutrigen — rotasi 7 hari
set -e
TARGET=/var/backups/nutrigen
FILE="$TARGET/nutrigen-$(date +%F).sql.gz"
mysqldump -u nutrigen -p'PASSWORD_KUAT' --single-transaction nutrigen | gzip > "$FILE"
find "$TARGET" -name 'nutrigen-*.sql.gz' -mtime +7 -delete
```
```bash
sudo chmod 700 /usr/local/bin/backup-nutrigen.sh
sudo crontab -e
```
```
0 2 * * * /usr/local/bin/backup-nutrigen.sh
```

> 💡 Idealnya salinan backup dikirim keluar VPS (Cloudflare R2 / Google Drive / PC). Minimal: `scp` manual tiap minggu ke PC Anda.

---

## Bagian 11 — Checklist Verifikasi Akhir

Jalankan berurutan; centang semua sebelum dianggap "hidup":

- [ ] `https://nutrigen.id` membuka halaman login (gembok SSL valid 🔒)
- [ ] Login puskesmas & kader jalan (akun produksi, bukan demo)
- [ ] `https://nutrigen.id/portal-ibu` **polos → 404** (benar, karena tanpa signed URL)
- [ ] Approve pengukuran → link WA ber-format `https://nutrigen.id/portal-ibu/...?signature=...`
- [ ] Link signed dibuka di HP → dashboard ibu tampil, data hanya anaknya sendiri
- [ ] Tamper param `balita` / `orang_tua` → **403**
- [ ] Refresh portal >20x/menit → **429** pada request ke-21
- [ ] Grafik pertumbuhan tampil 7 kurva WHO + garis data anak
- [ ] Export Excel laporan kader/puskesmas jalan
- [ ] Cron jalan: `grep CRON /var/log/syslog | tail` melihat `schedule:run` tiap menit
- [ ] Backup: `ls /var/backups/nutrigen/` ada file `.sql.gz` (setelah jam 02.00)
- [ ] `storage/logs/laravel.log` tidak berisi exception saat alur normal

**Update aplikasi nanti** (simpan sebagai `/var/www/nutrigen/deploy.sh`, `chmod +x`):
```bash
#!/bin/bash
set -e
cd /var/www/nutrigen
sudo -u bintang git pull origin main
sudo -u bintang composer install --optimize-autoloader --no-dev
sudo -u bintang npm ci && sudo -u bintang npm run build
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
sudo systemctl reload php8.3-fpm
echo "✅ deploy selesai"
```
Untuk maintenance: `php artisan down` sebelum update, `php artisan up` setelahnya.

---

## Troubleshooting Cepat

| Gejala | Penyebab umum | Solusi |
|---|---|---|
| 500 Internal Server Error | permisi `storage/`, `.env` rusak | `chown -R www-data:www-data storage bootstrap/cache`; cek `tail storage/logs/laravel.log` |
| Halaman blank + log `Vite manifest not found` | lupa `npm run build` | `npm ci && npm run build` |
| 502 Bad Gateway | PHP-FPM mati/socket beda | `sudo systemctl restart php8.3-fpm`; cek path socket di Nginx |
| `419 Page Expired` saat login | domain/session mismatch | pastikan `APP_URL` benar + `php artisan config:cache` |
| Link WA "Invalid signature" | `APP_URL` beda dengan domain yang dipakai buka | samakan `APP_URL` (https) + `config:cache` |
| Redirect loop dengan Cloudflare | SSL mode **Flexible** | ubah ke **Full (Strict)** |
| DB `Connection refused` | MySQL mati / password salah | `sudo systemctl status mysql`; test `php artisan db:show` |
| 429 terus-menerus di portal | rate limit 20/menit/IP (by design) | tunggu 1 menit; kalau kader butuh lebih, naikkan di `RouteServiceProvider` |
| DNS belum resolve | propagasi | tunggu 5 menit–24 jam; cek `nslookup` |

---

## Diagram Alur Singkat

```
[Hostinger: nutrigen.id]  --DNS A record-->  [Sumopod VPS: IP_SERVER]
                                                  │
                              ┌───────────────────┼────────────────────┐
                              │ Nginx :80/:443    │ certbot SSL        │ ufw firewall
                              ▼                                        ▼
                     [PHP-FPM 8.3 → Laravel Nutrigen]          (22/80/443 saja)
                              │
              ┌───────────────┼──────────────────┐
              ▼               ▼                  ▼
        [MySQL 8]      [storage/ + build]   [cron: schedule + backup]
              │
              └── Fonnte API (WA) — device perlu normal dulu; fallback wa.me
```

*Playbook ini dibuat oleh Hermes untuk project Nutrigen — sesuaikan nilai placeholder sebelum dieksekusi.*
