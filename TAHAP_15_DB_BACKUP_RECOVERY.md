# TAHAP 15 — PART 4: DATABASE BACKUP & RECOVERY ARCHITECTURE

## Backup & Recovery Architecture for Winky Store

### Current Database Configuration
- Development: SQLite at `C:/WINKY/winky-store/database/database.sqlite`
- Production: Akan migrasi ke MySQL/PostgreSQL

### Backup Strategy

#### SQLite Backup (Development/Simple Deployment)
Untuk SQLite, backup adalah file kopi mati (static file copy).

**Backup Command:**
```bash
# Copy database file ke lokasi backup
copy database/database.sqlite backup/database_$(date +%Y%m%d_%H%M%S).sqlite

# Atau menggunakan tar
tar -czf backup/database_$(date +%Y%m%d_%H%M%S).sql.gz database/database.sqlite
```

#### MySQL/PostgreSQL Backup (Production)
Untuk database berbasis server, gunakan dump utility.

**MySQL Backup Command:**
```bash
# Full database backup
mysqldump -u ${DB_USERNAME} -p${DB_PASSWORD} ${DB_DATABASE} > backup/db_$(date +%Y%m%d_%H%M%S).sql

# With compression
mysqldump -u ${DB_USERNAME} -p${DB_PASSWORD} ${DB_DATABASE} | gzip > backup/db_$(date +%Y%m%d_%H%M%S).sql.gz

# With single transaction for consistency
mysqldump -u ${DB_USERNAME} -p${DB_PASSWORD} --single-transaction ${DB_DATABASE} > backup/db_$(date +%Y%m%d_%H%M%S).sql
```

**PostgreSQL Backup Command:**
```bash
pg_dump -U ${DB_USERNAME} ${DB_DATABASE} > backup/db_$(date +%Y%m%d_%H%M%S).sql
```

### Backup Retention Policy

| Frequency | Retention | Location |
|-----------|-----------|----------|
| Daily | 7 days | ./backup/ |
| Weekly | 4 weeks | ./backup/archives/ |
| Monthly | 12 months | ./backup/archives/ |

### Recovery Procedure

#### Step 1: Stop Application
Jangan melakukan restore saat application sedang running (jika menggunakan database).

#### Step 2: Restore MySQL
```bash
mysql -u ${DB_USERNAME} -p${DB_PASSWORD} ${DB_DATABASE} < backup/db_20260115_120000.sql
```

#### Step 3: Restore PostgreSQL
```bash
psql -U ${DB_USERNAME} -d ${DB_DATABASE} -f backup/db_20260115_120000.sql
```

#### Step 4: Restore SQLite
```bash
copy backup/database_20260115_120000.sqlite database/database.sqlite
# Atau untuk overwrite:
cp backup/database_20260115_120000.sqlite database/database.sqlite
```

#### Step 5: Verify Migration
```bash
php artisan migrate:status
php artisan db:show-tables  # atau perintah sesuai migration
```

### Automated Backup Script (Development)

Buat file `backup.sh` di root project:

```bash
#!/bin/bash
# Database Backup Script for Winky Store

BACKUP_DIR="./backup"
DATE=$(date +%Y%m%d_%H%M%S)
DB_PATH="database/database.sqlite"

# Create backup directory if not exists
mkdir -p "$BACKUP_DIR"

# Check if database exists
if [ -f "$DB_PATH" ]; then
    # Copy SQLite database
    cp "$DB_PATH" "$BACKUP_DIR/database_$DATE.sqlite"
    
    # Compress backup
    gzip "$BACKUP_DIR/database_$DATE.sqlite"
    
    echo "Backup created: $BACKUP_DIR/database_$DATE.sqlite.gz"
    
    # Remove backups older than 7 days
    find "$BACKUP_DIR" -name "database_*.sqlite.gz" -mtime +7 -delete
    
    echo "Old backups cleaned up (older than 7 days)"
else
    echo "Error: Database file not found at $DB_PATH"
    exit 1
fi
```

**Usage:**
```bash
chmod +x backup.sh
./backup.sh
```

### Important Notes - JANGAN LALU-LALU

1. **Jangan pernah menjalankan migrate:fresh di production** - Ini menghapus semua data.

2. **Jangan hardcode password di script backup** - Gunakan environment variables atau file yang terenkripsi.

3. **Test restore secara berkala** - Buat schedule uji recovery setiap bulanan.

4. **Backup separation** - Simpan backup di lokasi berbeda dari server utama (off-site atau cloud).

5. **Database credential di environment** - Gunakan `.env` file yang aman, JANGAN commit password ke repository.

6. **WAL (Write-Ahead Logging)** - Untuk SQLite pastikan journal mode benar untuk consistency.

### Recovery Documentation

**File:** `BACKUP_RECOVERY.md` (sudah dibuat di Tahap 15 Part 1)

**Isi dokumentasi:**
1. Nama file backup terakhir
2. Timestamp backup
3. Checksum (MD5/SHA256) untuk verifikasi integritas
4. Langkah-langkah restore
5. Kontak person yang bertanggung jawab
6. Emergency contact list

### Verification

Setiap bulan sekali, lakukan:
1. Restore ke environment testing
2. Verify data integrity
3. Verify application functionality
4. Update dokumentasi jika ada perubahan

### Current Status

| Component | Status |
|-----------|--------|
| Backup script | ⚠️ Dibutuhkan konfigurasi produksi |
| Retention policy | ⚠️ Harus diimplementasikan |
| Recovery procedure | ✅ Terdocumentasi |
| Automated scheduling | ⚠️ Harus di-setup via cron/job scheduler |
| Off-site backup | ⚠️ Harus diimplementasikan |

**TAMBAHAN: Buat file backup script dan test restore sebelum production deployment.**