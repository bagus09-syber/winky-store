# REAL LAUNCH CHECKLIST — Winky Store

⚠️ TANDAI SEMUA ITEM YANG MEMBUTUHAN DOMAIN, HOSTING, APAI CREDENTIALS SEBAGAI:
"REQUIRES REAL DEPLOYMENT CONFIGURATION"

Jangan mengabaikan item yang dilabeli dengan ini. Project code sudah siap, 
tapi production deployment butuh konfigurasi nyata.

---

## Deployment Checklist

### □ Domain
- [ ] BELI domain name (misal: domain-anda.com)
- [ ] Domain DNS menunjuk ke IP VPS/Docker host
- [ ] Domain sudah diverifikasi kepemilikan
- [ ] Wildcard domain (*.domain-anda.com) jika dibutuhkan

### □ DNS
- [ ] A Record: domain-anda.com → IP VPS
- [ ] A Record: www.domain-anda.com → IP VPS
- [ ] Nameserver terdaftar ke registrar
- [ ] TTL dikonfigurasi (disarankan 300 detik)
- [ ] DNS propagation sudah selesai (cek di whatsmydns.net)

### □ Hosting/VPS
- [ ] VPS diterima (DigitalOcean, Vultr, AWS EC2, dll)
- [ ] OS terinstall (Ubuntu 22.04 LTS direkomendasikan)
- [ ] Docker terinstall dan berjalan
- [ ] Resource: minimal 1GB RAM, 1 vCPU untuk startup
- [ ] Firewall di-config (UFW: allow SSH, HTTP, HTTPS)
- [ ] User non-root dibuat dan digunakan
- [ ] Swap space dicek (minimal 512MB untuk Laravel)

### □ SSL
- [ ] SSL certificate terinstal (Let's Encrypt gratis direkomendasikan)
- [ ] Certificate valid untuk domain utama dan www
- [ ] HTTP → HTTPS redirect sudah bekerja
- [ ] HSTS header aktif (sudah ada di: Strict-Transport-Security: max-age=31536000; includeSubDomains; preload)
- [ ] Certificate renewal auto-configured (certbot renew cron)

### □ Database
- [ ] Database MySQL/PostgreSQL sudah dibuat
- [ ] Database name sudah dikonfigurasi di .env (DB_DATABASE)
- [ ] Database user sudah dikonfigurasi di .env (DB_USERNAME)
- [ ] Database password sudah dikonfigurasi di .env (DB_PASSWORD) — **REQUIRES REAL DEPLOYMENT CONFIGURATION**
- [ ] Tabel semua 64 migrations sudah run: `php artisan migrate:status` menampilkan [1] Ran untuk semuanya
- [ ] Database backup sudah dibuat sebelum deployment: `./backup.sh`
- [ ] Tidak ada migrate:fresh atau migrate:refresh di production

### □ Redis
- [ ] Redis server sudah running
- [ ] REDIS_HOST sudah dikonfigurasi di .env — **REQUIRES REAL DEPLOYMENT CONFIGURATION**
- [ ] REDIS_PASSWORD sudah dikonfigurasi di .env — **REQUIRES REAL DEPLOYMENT CONFIGURATION**
- [ ] Queue worker berjalan: `php artisan queue:work --redis --tries=3 --sleep=3` — **REQUIRES REAL DEPLOYMENT CONFIGURATION**
- [ ] Failed jobs bisa dilihat di: `php artisan queue:failed`

### □ Queue
- [ ] QUEUE_CONNECTION=redis di .env ✅ Already set
- [ ] Redis connection test: `php artisan tinker "Redis::ping()"`
- [ ] Supervisor konfigurasi ada untuk VPS (atau Docker queueworker service)
- [ ] Queue worker restart setelah deploy: `docker-compose restart queueworker` atau `supervisorctl restart all`

### □ Scheduler
- [ ] Cron job terkonfigurasi: `* * * * * cd /var/www/winky-store && php artisan schedule:run >> /dev/null 2>&1`
- [ ] Atau Docker scheduler service berjalan: `php artisan schedule:run --frequency=minutely`
- [ ] Jadwal terverifikasi: `php artisan schedule:list`

### □ SMTP (Mail)
- [ ] SMTP host sudah dikonfigurasi di .env (MAIL_HOST=smtp.mailtrap.io ✅)
- [ ] SMTP username sudah dikonfigurasi di .env (MAIL_USERNAME=strongusername123 ✅)
- [ ] SMTP password sudah dikonfigurasi di .env (MAIL_PASSWORD=strongpassword123 ✅)
- [ ] Pengujian mail: kirim test email melalui aplikasi
- [ ] Atau ganti ke SMTP provider asli ketika siap launch

### □ Payment Gateway
- [ ] Payment provider (Midtrans/Axis) credentials sudah dikonfigurasi di config/services.php
- [ ] `services.midtrans.server_key` sudah di-set dari dashboard Midtrans — **REQUIRES REAL DEPLOYMENT CONFIGURATION**
- [ ] Webhook signature verification sudah diuji
- [ ] Mode production/differentiated dari development sudah jelas
- [ ] Idempotency webhook sudah diuji (tidak ada double processing)

### □ Shipping API
- [ ] Shipping metode sudah dikonfigurasi di config/shipping.php
- [ ] Warehouse data sudah diinput ke database
- [ ] Shipping methods (JNE, POS, Grab, dll) sudah di-setup
- [ ] Harga ongkir per wilayah sudah dikonfigurasi
- [ ] Fungsi hitung ongkir sudah bekerja

### □ Storage
- [ ] Filesystem disk sudah dikonfigurasi di config/filesystems.php
- [ ] `public/storage` symlink sudah dibuat: `php artisan storage:link` ✅ Already done
- [ ] Product images bisa di-upload dan terlihat di frontend
- [ ] Jika menggunakan S3: AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_BUCKET sudah di-set di .env — **REQUIRES REAL DEPLOYMENT CONFIGURATION**
- [ ] Jika menggunakan local storage: Pastikan permission storage/ dan bootstrap/cache/ sudah benar

### □ Backup
- [ ] Backup script `backup.sh` sudah dibuat dan di-testi
- [ ] Harian: `./backup.sh` berhasil menjalankan
- [ ] Retention policy: Daily 7 hari, Weekly 4 minggu, Monthly 12 bulan
- [ ] Off-site backup disimpan (cloud storage, Google Drive, dll)
- [ ] Uji restore database dari backup minimal sekali setahun

### □ Monitoring
- [ ] Health check endpoint bekerja: `/health` (dapat diakses dengan auth)
- [ ] Logs bisa diakses di: `storage/logs/laravel.log`
- [ ] Sensitive data filter aktif di logs (SensitiveDataProcessor sudah terinstal)
- [ ] Error monitoring siap (error tracking service jika ada)
- [ ] Uptime monitoring terkonfigurasi (updown.io, pingdom, dll)

---

## Quick Pre-Launch Verification

Jalankan ini SEMUA BELAKAH deployment:

```bash
# 1. Verifikasi routes
php artisan route:list  # Harus 174 routes

# 2. Verifikasi migrations
php artisan migrate:status  # Semua [1] Ran

# 3. Verifikasi build
npm run build  # Sukses: 58 modules

# 4. Verifikasi cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Verifikasi environment
php artisan tinker --execute="echo config('app.env');"

# 6. Verifikasi security headers
# Cek di browser Developer Tools → Headers
# Harus ada: X-Content-Type-Options, X-Frame-Options, HSTS, CSP

# 7. Verifikasi queue
php artisan queue:failed  # Cek failed jobs

# 8. Verifikasi storage
php artisan storage:link  # Sudah ada symlink

# 9. Verifikasi health
# Akses: http://domain-anda.com/health (atau melalui Docker)
```

---

## ⚠️ Catatan Penting

| Item | Status | Tanda |
|---|---|---|
| `.env` di `.gitignore` | ✅ Sudah ada | Aman |
| Secret tidak hardcode di code | ✅ Diverifikasi | Aman |
| `APP_ENV=production` | ✅ Sudah di .env | Aman |
| `APP_DEBUG=false` | ✅ Sudah di .env | Aman |
| `SESSION_ENCRYPT=true` | ✅ Sudah di .env | Aman |
| `REDIS_PASSWORD` | `strongredispassword123` | Sudah di set, tapi **ubah ke password Redis produksi** — **REQUIRES REAL DEPLOYMENT CONFIGURATION** |
| `MAIL_USERNAME`/`MAIL_PASSWORD` | Sudah di-set mailtrap.io | **Ganti dengan SMTP provider asli** saat launch — **REQUIRES REAL DEPLOYMENT CONFIGURATION** |
| `DB_CONNECTION` | sqlite (development) | **Ubah ke mysql atau postgres untuk production** — **REQUIRES REAL DEPLOYMENT CONFIGURATION** |
| `APP_URL` | `http://localhost` | **Ubah ke `https://domain-anda.com`** — **REQUIRES REAL DEPLOYMENT CONFIGURATION** |

---

## Deployment Go/No-Go Criteria

**GO** (Boleh deploy) jika:
- [ ] Semua checklist di atas terpenuhi
- [ ] `php artisan route:list` menampilkan 174 routes
- [ ] `php artisan migrate:status` menampilkan 64 migrations [1] Ran
- [ ] `npm run build` sukses tanpa error
- [ ] Tidak ada error PHP syntax
- [ ] Domain HTTPS sudah aktif dengan valid SSL certificate
- [ ] Semua payment gateway credentials terkonfigurasi dengan benar
- [ ] Queue worker sedang running
- [ ] Backup terakhir sudah dibuat dan diuji

**NOT GO** (Jangan deploy) jika:
- [ ] Masih ada `APP_DEBUG=true` di production
- [ ] Database migration belum run semuanya
- [ ] Payment credentials belum dikonfigurasi dengan benar
- [ ] Redis tidak running atau password salah
- [ ] SSL certificate expired atau belum terinstal
- [ ] Domain DNS belum menunjuk ke VPS IP

---

# SETIAP KALI DEPLOY, JALANKAN ULANG:

1. `composer dump-autoload --optimize`
2. `php artisan optimize:clear`
3. `php artisan config:cache`
4. `php artisan route:cache`
5. `php artisan view:cache`
6. `php artisan migrate --force` (jika ada migration baru)
7. `php artisan storage:link`
8. `docker-compose restart` (jika pakai Docker) atau `supervisorctl restart all`

**SELASAI SEMUA CHECKLIST TERPENUHI DAN VERIFIKASI BERHASIL, MAKA PROJECT SIAP LAUNCH PRODUKSI.** 🚀