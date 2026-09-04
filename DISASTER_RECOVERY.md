# DISASTER RECOVERY — Winky Store

## ⚠️ IMPORTANT
Jangan pernah menjalankan `php artisan migrate:fresh` atau `migrate:refresh` di production tanpa backup penuh!
Setiap kali recover, selalu verifikasi integritas data setelah restore.

---

## 1. Database Backup & Restore

### 1.1 Backup Procedures

#### SQLite (Development/Simple Deployment)
```bash
# Harian: jalankan backup script
./backup.sh

# Atau manual:
copy database/database.sqlite backup/database_%date:~-4%%date:~7,2%%date:~10,2%_%time:~0,2%%time:~3,2%.sqlite
gzip backup/database_%date:~-4%%date:~7,2%%date:~10,2%_%time:~0,2%%time:~3,2%.sqlite

# Clean up backups older than 7 days
forfiles /p backup /s /m "*.sqlite.gz" /d -7 /c "cmd /c del @path"
```

#### MySQL/PostgreSQL (Production)
```bash
# Full database backup (MySQL)
mysqldump -u DB_USERNAME -pDB_PASSWORD DB_DATABASE > backup/db_YYYYMMDD_HHMMSS.sql

# With compression
mysqldump -u DB_USERNAME -pDB_PASSWORD DB_DATABASE | gzip > backup/db_YYYYMMDD_HHMMSS.sql.gz

# Single transaction for consistency (MySQL)
mysqldump -u DB_USERNAME -pDB_PASSWORD DB_DATABASE --single-transaction > backup/db_YYYYMMDD_HHMMSS.sql
```

### 1.2 Restore Procedures

#### SQLite Restore
```bash
# Decompress backup
gzip -d backup/database_YYYYMMDD_HHMMSS.sql.gz
# Atau: gunzip backup/database_YYYYMMDD_HHMMSS.sql.gz

# Restore to active database
copy backup/database_YYYYMMDD_HHMMSS.sqlite database/database.sqlite

# Atau overwrite dengan command copy:
copy backup/database_YYYYMMDD_HHMMSS.sqlite database/database.sqlite

# Verify
php artisan migrate:status
```

#### MySQL/PostgreSQL Restore
```bash
# MySQL restore
mysql -u DB_USERNAME -pDB_PASSWORD DB_DATABASE < backup/db_YYYYMMDD_HHMMSS.sql

# PostgreSQL restore
psql -U DB_USERNAME -d DB_DATABASE -f backup/db_YYYYMMDD_HHMMSS.sql
```

### 1.3 Post-Restore Verification

```bash
# Check migration status
php artisan migrate:status

# Test critical functions
php artisan tinker --execute="App\Models\User::count(); App\Models\Order::count();"

# Verify key data integrity
php artisan tinker --execute="App\Models\Product::count(); App\Models\Product::where('is_active', true)->count();"
```

---

## 2. File Recovery

### 2.1 Storage Recovery

```bash
# Recreate storage symlink if lost
php artisan storage:link

# Verify symlink
ls -la public/storage  # Should be link to storage/app/public

# Check public folder files
dir public/storage
```

### 2.2 Configuration Recovery

```bash
# Reset config cache to force re-read .env
php artisan config:clear
php artisan config:cache

# Reset route cache
php artisan route:clear
php artisan route:cache

# Reset view cache
php artisan view:clear
php artisan view:cache
```

### 2.3 Environment Recovery

```bash
# Copy from .env.example if .env is corrupted
copy .env.example .env

# Atau dari backup terbaru
copy .env.backup .env

# Re-generate application key jika perlu
php artisan key:generate --force
```

---

## 3. Server Failure Recovery

### 3.1 Docker-Based Recovery

```bash
# Rebuild containers from scratch
docker-compose down

# Atau rebuild images
docker-compose build

# Bring up all services
docker-compose up -d

# Wait for services to be healthy
docker-compose ps

# Verify application health
docker exec winky-store-app php artisan migrate:status
```

### 3.2 VPS Manual Recovery

```bash
# Re-clone project from git
git clone https://github.com/username/winky-store.git
cd winky-store

# Restore environment
copy .env.backup .env

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migrations
php artisan migrate --force

# Recreate storage link
php artisan storage:link

# Restart services
# Jika pakai supervisor:
supervisorctl restart all

# Jika pakai Docker:
docker-compose restart
```

---

## 4. Rollback Deployment

### 4.1 Rollback to Previous Version

```bash
# Using git
git checkout HEAD~1  # Kembali 1 commit

# Atau ke commit tertentu
git checkout <commit-hash>

# Redeploy
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4.2 Database Rollback

```bash
# Rollback last migration batch
php artisan migrate:rollback

# Rollback specific steps
php artisan migrate:rollback --step=1

# Atau restore dari backup
# Lihat section 1.2 Restore Procedures
```

---

## 5. Payment Webhook Recovery

### 5.1 Failed Webhook Recovery

```bash
# Cek webhook yang gagal
php artisan tinker --execute="App\Models\PaymentWebhook::where('status', 'failed')->count();"

# Ulangi proses webhook yang gagal
# (Ini bergantung pada implementasi masing-masing payment gateway)

# Pastikan idempotency sudah work
# Cek di database PaymentWebhook tabel untuk status 'processed'
```

### 5.2 Duplicate Webhook Handling

```bash
# Jika webhook sudah diproses (status 'processed'), 
# sistem sudah otomatis mengabaikan duplicate (lihat PaymentService::processWebhook)

# Cek di database:
SELECT transaction_id, status, processed_at FROM payment_webhooks 
WHERE status = 'processed' 
ORDER BY processed_at DESC 
LIMIT 5;
```

---

## 6. Queue Recovery

### 6.1 Failed Jobs Recovery

```bash
# Cek jumlah failed jobs
php artisan tinker --execute="App\Jobs\Failed::count();"

# Atau melalui artisan
php artisan queue:failed-table  # Pastikan tabel sudah ada

# Restart failed jobs yang ingin direset
# Gunakan perintah: php artisan queue:work --redis --tries=3 --sleep=3

# Atau melalui dashboard/interface jika sudah terinstall
```

### 6.2 Queue Worker Recovery

```bash
# Restart queue worker
# Docker:
docker exec winky-store-queue php artisan queue:work --redis --tries=3 --sleep=3

# VPS dengan supervisor:
supervisorctl restart all

# VPS manual:
php artisan queue:work --redis --tries=3 --sleep=3 &
```

---

## 7. Emergency Maintenance Mode

### 7.1 Activate Maintenance Mode

```bash
# Activate
php artisan down --message="Sistem sedang perbaikan, akan kembali dalam beberapa menit."

# Deactivate
php artisan up
```

### 7.2 Maintenance Mode dengan Database Maintenance

```bash
# Saat perlu database maintenance
php artisan down --message="Sistem dalam maintenance untuk perbaikan database. Akan kembali pada HH:MM WIB."

# Lakukan maintenance
# ... perbaikan database ...

# Kembali ke normal
php artisan up
```

---

## 8. Disaster Recovery Checklist

### Pre-Disaster Prevention

- [ ] Backup database harian sudah dibuat lewat `./backup.sh`
- [ ] Backup disimpan off-site (cloud storage atau lokasi berbeda)
- [ ] `.env` tidak ikut commit ke repository (sudah di `.gitignore`)
- [ ] `.env.backup` disiapkan untuk emergency recovery
- [ ] `php artisan key:generate` sudah dijalankan setelah key hilang
- [ ] SSL certificate renewed sebelum expired (Let's Encrypt auto-renewal)
- [ ] Health check endpoint sudah diaktifkan dan dipantau

### Post-Disaster Recovery Steps

- [ ] Identifikasi masalah (apakah database, server, file, atau kode?)
- [ ] Ambil snapshot kondisi saat ini sebelum melakukan apa pun
- [ ] Jalankan backup restore sesuai tipe disaster (lihat section 1)
- [ ] Restore file dan konfigurasi (lihat section 2)
- [ ] Jalankan rollback deployment jika terkait deploy baru (lihat section 4)
- [ ] Verifikasi semua fungsi kritis (login, order, payment, dashboard)
- [ ] Pantau sistem selama 24 jam setelah recovery
- [ ] Update dokumentasi jika ada perubahan proses

### Recovery Time Objectives (RTO)

| Emergency Type | Target Recovery Time |
|---|---|
| Database restore | 30 menit |
| File recovery | 15 menit |
| Server failure (Docker) | 20 menit |
| Full system restore | 2 jam |
| Payment webhook recovery | 1 jam |

### Backup Retention Policy

| Frequency | Retention | Location |
|---|---|---|
| Daily | 7 days | `./backup/` folder |
| Weekly | 4 weeks | `./backup/archives/` folder |
| Monthly | 12 months | Cloud storage / external drive |

---
*Disaster Recovery documentation conducted as Part 7 of Tahap 17*
*Date: September 2026*
---
Now let me continue to **Part 8 — Final Verification**:



Now let me continue to **Part 8 — Final Verification**:


<tool_call>
<function=todowrite>
<parameter=todos>
[{"content": "Part 1: Final Project Audit ✅ Sudah selesai", "priority": "high", "status": "completed"}, {"content": "Part 2: Environment Variables Audit ✅ Sudah selesai", "priority": "high", "status": "completed"}, {"content": "Part 3: Real Deployment Checklist / REAL_LAUNCH_CHECKLIST.md ✅ Sudah dibuat", "priority": "high", "status": "completed"}, {"content": "Part 4: Final Security Audit ✅ Sudah selesai dan dibuat laporan", "priority": "high", "status": "completed"}, {"content": "Part 5: Production Performance Audit ✅ Sudah selesai dan dibuat laporan", "priority": "high", "status": "completed"}, {"content": "Part 6: Final Health Check ✅ Sudah diverifikasi semua sistem", "priority": "high", "status": "completed"}, {"content": "Part 7: Disaster Recovery / DISASTER_RECOVERY.md ✅ Sudah dibuat", "priority": "high", "status": "completed"}, {"content": "Part 8: Final Verification - run all commands", "priority": "high", "status": "in_progress"}, {"content": "Final Report Tahap 17", "priority": "high", "status": "pending"}]