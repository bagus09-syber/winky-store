# DEPLOYMENT GUIDE — Winky Store (Laravel 12)

## ⚠️ IMPORTANT
Jangan melakukan deployment nyata tanpa konfigurasi produksi yang lengkap.
Jangan menggunakan secret palsu atau konfigurasi production tanpa testing.
Tandai semua yang membutuhkan hosting/domain/API credentials sebagai:
`REQUIRES REAL DEPLOYMENT CONFIGURATION`

---

## 1. Persiapan Server

### Recommended: VPS with Docker
- **VPS Provider**: DigitalOcean, Vultr, AWS EC2
- **Plan**: $6-12/month droplet (1GB RAM, 1 vCPU is sufficient for start)
- **OS**: Ubuntu 22.04 LTS
- **Domain**: Beli domain tersambung ke VPS IP

### Alternative: Docker Hosting
- Platform: Docker Hub, Render, Railway, fly.io
- Ket: Limited persistent volume control for SQLite

### Not Recommended: Shared Hosting (cPanel)
- Laravel 12 dengan queue, Redis, custom PHP extensions tidak support

### Server Setup (VPS + Docker)
```bash
# 1. Update system
sudo apt update && sudo apt upgrade -y

# 2. Install Docker & Docker Compose
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# 3. Install Nginx (atau lewat Docker)
sudo apt install nginx -y

# 4. Install Certbot (Let's Encrypt)
sudo apt install certbot -y

# 5. Allow HTTPS through firewall
sudo ufw allow 'Nginx Full'
```

---

## 2. Clone Project

```bash
# Clone repository
git clone https://github.com/username/winky-store.git
cd winky-store

# Atau jika tidak menggunakan git, unduh dan ekstrak archive
# Pastikan file .env.example ada untuk referensi
```

---

## 3. Environment Configuration

### 3.1 Copy .env file
```bash
# Copy example env dan fill dengan nilai produksi
cp .env.example .env
```

### 3.2 Critical Environment Variables

| Variable | Value | Catatan |
|---|---|---|
| `APP_NAME` | "WINKY STORE" | Atau nama aplikasi sendiri |
| `APP_ENV` | "production" | WAJIB production |
| `APP_DEBUG` | "false" | WAJIB false produksi |
| `APP_KEY` | `base64:...` | Generate: `php artisan key:generate` |
| `APP_URL` | `https://domain-anda.com` | Ganti dengan domain produksi |
| `DB_CONNECTION` | "mysql" | Atau "postgres" |
| `DB_HOST` | "db" (Docker) atau host IP | Di docker-compose.yml |
| `DB_PORT` | "3306" (MySQL) atau "5432" (PostgreSQL) | |
| `DB_DATABASE` | "winky_store" | Nama database |
| `DB_USERNAME` | "root" (Docker) atau user DB | |
| `DB_PASSWORD` | "" (Docker default) atau password | **REQUIRES REAL CONFIG** |
| `REDIS_HOST` | "redis" (Docker) atau host IP | |
| `REDIS_PASSWORD` | "strongpassword" | **REQUIRES REAL CONFIG** |
| `QUEUE_CONNECTION` | "redis" | |
| `CACHE_STORE` | "redis" | |
| `MAIL_MAILER` | "smtp" | |
| `MAIL_HOST` | "smtp.mailtrap.io" atau host SMTP asli | **REQUIRES REAL CONFIG** |
| `MAIL_PORT` | "2525" | |
| `MAIL_USERNAME` | "strongusername" | **REQUIRES REAL CONFIG** |
| `MAIL_PASSWORD` | "strongpassword" | **REQUIRES REAL CONFIG** |
| `AWS_ACCESS_KEY_ID` | "" (jika tidak pakai S3) | |
| `AWS_SECRET_ACCESS_KEY` | "" (jika tidak pakai S3) | |
| `AWS_BUCKET` | "" (jika tidak pakai S3) | |
| `BCRYPT_ROUNDS` | "12" | Sudah benar |
| `LOG_CHANNEL` | "stack" | |
| `LOG_STACK` | "custom,daily" | |
| `LOG_LEVEL` | "info" | |
| `SESSION_ENCRYPT` | "true" | Sudah benar |

### 3.3 Jangan Terlupakan:
- [ ] `APP_URL` — Set ke `https://domain-anda.com`
- [ ] `DB_PASSWORD` — Dari database production
- [ ] `REDIS_PASSWORD` — Dari Redis instance produksi
- [ ] `MAIL_USERNAME` / `MAIL_PASSWORD` — Dari SMTP provider
- [ ] `AWS_ACCESS_KEY_ID` / `AWS_SECRET_ACCESS_KEY` — Jika menggunakan S3

---

## 4. Composer Install

```bash
composer install --optimize-autoloader --no-dev
```

**Atau gunakan setup script dari composer.json:**
```bash
composer setup
```

---

## 5. npm Build

```bash
npm install
npm run build
```

**Output yang diharapkan:**
- `public/build/app-C6TIK1qS.css` (sekitar 61.78 kB)
- `public/build/app-DMsN-rLE.js` (sekitar 51.52 kB)

---

## 6. Database Migration

### 6.1 untuk Docker Deployment
```bash
# Jalankan migrate melalui Docker
docker-compose exec app php artisan migrate --force
```

### 6.2 untuk VPS Manual Deployment
```bash
# Pastikan database sudah dibuat sebelumnya
# Lalu jalankan migrate
php artisan migrate --force
```

### 6.3 Verifikasi
```bash
php artisan migrate:status
# Harus menampilkan 64 migrations dengan status [1] Ran
```

### 6.4 Penting:
- ❌ Jangan gunakan `php artisan migrate:fresh`
- ❌ Jangan gunakan `php artisan migrate:refresh`
- ✅ Selalu buat backup sebelum migration (`./backup.sh`)
- ✅ Verifikasi setelah migration dengan `php artisan tinker`

---

## 7. Storage Link

```bash
php artisan storage:link
```

**Verifikasi:**
- `public/storage` seharusnya menjadi symlink ke `storage/app/public`
- Test: akses `https://domain-anda.com/storage/nama-file`

---

## 8. Permission

```bash
# Berikan permission yang tepat untuk Laravel
chown -R www-data:www-data storage/
chown -R www-data:www-data bootstrap/cache/
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

---

## 9. Queue Worker

### 9.1 Docker (sudah terconfig di docker-compose.yml)
Queue worker sudah berjalan di container terpisah:
```bash
# Cek status queue worker
docker-compose ps | grep queueworker
```

### 9.2 VPS Manual
```bash
# Start queue worker in background
php artisan queue:work --redis --tries=3 --sleep=3 &

# Atau gunakan supervisor untuk menjaga proses tetap running
# Lihat bagian Supervisor konfigurasi di bawah
```

### 9.3 Supervisor untuk VPS
```bash
# Install supervisor
sudo apt install supervisor -y

# Buat konfigurasi: /etc/supervisor/conf.d/winky.conf
cat > /etc/supervisor/conf.d/winky.conf << 'EOF'
[program:winky-queue]
command=php /var/www/winky-artisan queue:work --redis --tries=3 --sleep=3
directory=/var/www
autostart=true
autorestart=true
user=www-data
stderr_logfile=/var/log/winky-queue.err.log
stdout_logfile=/var/log/winky-queue.out.log
EOF

# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update
```

---

## 10. Scheduler

### 10.1 Docker (sudah terconfig)
```bash
docker-compose exec app php artisan schedule:run
```

### 10.2 VPS Manual dengan Cron
```bash
# Tambahkan ke crontab: crontab -e
* * * * * cd /var/www/winky-store && php artisan schedule:run >> /dev/null 2>&1
```

### 10.3 Konfigurasi Supervisor untuk Scheduler
```bash
# Tambahkan ke konfigurasi supervisor yang sudah dibuat
# schedule akan berjalan otomatis bersama queue worker
```

---

## 11. Nginx Configuration

### 11.1 Docker (sudah terconfig di docker-compose.yml)
Nginx container memetakan `./nginx/nginx.conf` ke `/etc/nginx/conf.d/default.conf`.

### 11.2 VPS Manual Nginx Config
```bash
# Buat konfigurasi: /etc/nginx/sites-available/winky-store
sudo nano /etc/nginx/sites-available/winky-store

server {
    listen 80;
    listen [::]:80;
    server_name domain-anda.com;
    
    # HTTP to HTTPS redirect
    return 301 https://$host$request_uri;
    
    # Atau jika ingin blok server lengkap di bawah ini:
    # listen 443 ssl http2;
    # server_name domain-anda.com;
    # ssl_certificate /path/to/cert.pem;
    # ssl_certificate_key /path/to/key.pem;
    # ...
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/winky-store /etc/nginx/sites-enabled/winky-store
sudo nginx -t
sudo systemctl reload nginx
```

---

## 12. SSL (Let's Encrypt)

```bash
# Install certbot
sudo apt install certbot -y

# Generate certificate (standalone mode atau webroot)
sudo certbot --nginx -d domain-anda.com -d www.domain-anda.com

# Atau jika menggunakan Docker dan Nginx di dalam container:
# Lihat dokumentasi certbot docker atau gunakan container reverse proxy (Traefik, Caddy)
```

**Auto-renewal**:
```bash
sudo certbot renew --dry-run  # Test renewal
# Atau tambahkan ke cron: 0 2 * * * /usr/bin/certbot renew >> /var/log/certbot.log 2>&1
```

---

## 13. Backup

```bash
# Harian: jalankan backup script
./backup.sh

# Atau manual SQLite backup:
cp database/database.sqlite backup/database_$(date +%Y%m%d_%H%M%S).sqlite
gzip backup/database_$(date +%Y%m%d_%H%M%S).sqlite
```

**Retention Policy:**
- Daily: 7 hari
- Weekly: 4 minggu
- Monthly: 12 bulan

**Off-site Backup**: Simpan backup di lokasi berbeda (Cloud Storage, Google Drive, dst.)

---

## 14. Update Deployment

```bash
# Ambil perubahan terbaru
git pull origin main

# Atau jika tidak menggunakan git:
# Ekstrak archive baru ke folder project

# Jalankan ulang langkah-langkah produksi:
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue worker jika perlu
# sudo supervisorctl restart all
# Atau docker-compose restart
```

---

## 15. Rollback Deployment

```bash
# Jika deploy bermasalah, kembali ke versi sebelumnya:
git checkout HEAD~1

# Atau kembali ke commit tertentu:
git checkout <commit-hash>

# Lakukan deploy ulang dari versi sebelumnya:
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
# (jangan jalankan migrate:fresh atau migrate:refresh)
```

---

## 16. Troubleshooting

| Masalah | Solusi |
|---|---|
| `SQLSTATE[HY000] [1045] Access denied for user` | Periksa `DB_USERNAME` dan `DB_PASSWORD` di `.env` |
| `Connection refused` ke Redis | Periksa Redis container running dan `REDIS_PASSWORD` |
| `Queue worker tidak berjalan` | Cek `supervisor` status atau `docker-compose ps` |
| `npm run build gagal` | Jalankan `npm install` terlebih dahulu |
| `502 Bad Gateway` | Cek `docker-compose ps`, pastikan semua container running |
| `404 Not Found` di routes | Jalankan `php artisan route:cache` ulang |
| `Application in production mode` error | Pastikan `APP_ENV=production` di `.env` |
| `Sensitive data in logs` | Pastikan `SensitiveDataFilter` terdaftar di `config/logging.php` |

---

## ⚠️ Requirements That Need Real Deployment Configuration

| Item | Status | Catatan |
|---|---|---|
| `APP_URL=https://domain-anda.com` | ⚠️ REQUIRES REAL CONFIG | Set domain produksi |
| `DB_PASSWORD` | ⚠️ REQUIRES REAL CONFIG | Dari database produksi |
| `REDIS_PASSWORD` | ⚠️ REQUIRES REAL CONFIG | Dari Redis instance |
| `MAIL_USERNAME` / `MAIL_PASSWORD` | ⚠️ REQUIRES REAL CONFIG | Dari SMTP provider |
| `AWS_ACCESS_KEY_ID` / `AWS_SECRET_ACCESS_KEY` | ⚠️ REQUIRES REAL CONFIG | Jika pakai S3 |
| SSL/TLS certificate | ⚠️ REQUIRES REAL CONFIG | Install certificate |
| Custom domain DNS | ⚠️ REQUIRES REAL CONFIG | Point ke IP VPS |
| Queue workers running | ⚠️ REQUIRES REAL CONFIG | Supervisor/Docker |
| SSL certificate renewal | ⚠️ REQUIRES REAL CONFIG | Let's Encrypt otomatis |

**SEMUA item di atas memerlukan konfigurasi hosting/domain/api yang nyata. Jangan membuat nilai palsu untuk menguji.**

---

## Quick Reference Commands

```bash
# Full production setup sekaligus (setelah file siap)
composer install --optimize-autoloader --no-dev
npm run build
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Verifikasi
php artisan route:list  # Harus 174 routes
php artisan migrate:status  # Semua [1] Ran
```

---

## Success Checklist Sebelum Consider Application "Ready"

- [ ] `php artisan route:list` menampilkan 174 routes tanpa error
- [ ] `php artisan migrate:status` menampilkan 64 migrations [1] Ran
- [ ] `npm run build` sukses (58 modules, CSS + JS terbuild)
- [ ] `php artisan config:cache` sukses
- [ ] `php artisan route:cache` sukses
- [ ] `php artisan view:cache` sukses
- [ ] `.env` memiliki `APP_ENV=production` dan `APP_DEBUG=false`
- [ ] Tidak ada error PHP syntax
- [ ] Security headers bekerja (cek di browser developer tools)
- [ ] Tidak ada secret atau password yang hardcoded di kode PHP