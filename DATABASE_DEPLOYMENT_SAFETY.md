# Database Deployment Safety — Winky Store

## ⚠️ Critical Rules

| Rule | Status |
|---|---|
| ❌ **JANGAN pernah menjalankan `php artisan migrate:fresh` di production** | Akan menghapus semua data! |
| ❌ **JANGAN pernah menjalankan `php artisan migrate:refresh` di production** | Akan rollback semua migration dan re-run, kehilangan data! |
| ✅ **Gunakan `php artisan migrate --force` untuk menjalankan migration pending** | ✅ Already verified working |
| ✅ **Selalu buat backup sebelum migration produksi** | ✅ `backup.sh` sudah dibuat |
| ✅ **Verifikasi status migration setelah deployment** | ✅ `php artisan migrate:status` |

## Safe Migration Commands

| Command | Purpose | Safety |
|---|---|---|
| `php artisan migrate` | Run pending migrations | ✅ Safe |
| `php artisan migrate:status` | Check migration status | ✅ Safe |
| `php artisan migrate:refresh --step=1` | Rollback specific step only | ⚠️ Use with caution |
| `php artisan migrate:reset` | Rollback all migrations | ❌ DANGER - data loss risk |

## Production Deployment Procedure

### 1. Pre-Deployment Backup

```bash
# Run backup script before deployment
chmod +x backup.sh
./backup.sh
# Atau di Windows: backup.bat
```

### 2. Run Migrations

```bash
# Run pending migrations with force (production)
php artisan migrate --force

# Verify all migrations ran
php artisan migrate:status
```

### 3. Post-Deployment Verification

```bash
# Check critical tables exist
php artisan tinker --execute="App\Models\User::count()"

# Check key relationships
php artisan tinker --execute="App\Models\Order::with('user')->first()"
```

## Rollback Procedure (Emergency Only)

### If Migration Causes Issue:

```bash
# Rollback last batch only
php artisan migrate:rollback

# Or rollback specific number of steps
php artisan migrate:rollback --step=1

# Verify database integrity
php artisan tinker --execute="DB::connection()->getPdo();"
```

### Restore from Backup

```bash
# SQLite restore
cp backup/database_YYYYMMDD_HHMMSS.sqlite.gz .
gzip -d backup/database_YYYYMMDD_HHMMSS.sqlite
cp database/database.sqlite database/database.sqlite.bak
cp database/database.sqlite.bak database/database.sqlite
# Atau: cp backup/database_YYYYMMDD_HHMMSS.sqlite database/database.sqlite

# Verify
php artisan migrate:status
```

## Important Safety Notes

| Warning | Consequence |
|---|---|
| `php artisan migrate:fresh` | Drops ALL tables, complete data loss |
| `php artisan migrate:refresh` | Rolls back all migrations, data loss |
| Running migrate without `--force` in production | May prompt for confirmation, safer |
| Deploying without backup | No recovery path if something goes wrong |
| Changing DB structure manually | Breaks Eloquent models, potential data corruption |

## Pre-Deployment Checklist

- [ ] Backup database menggunakan `backup.sh`
- [ ] Verify `php artisan migrate:status` shows current state
- [ ] Confirm `.env` has correct DB credentials (production MySQL/PostgreSQL)
- [ ] Test `php artisan migrate --force` in staging first
- [ ] Verify all environment variables are set (DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD)
- [ ] Check Redis is running (for cache/queue)
- [ ] Run `php artisan optimize:clear`
- [ ] Run `php artisan config:cache` after migration

## Production Database Requirements

| Setting | Development | Production Required |
|---|---|---|
| `DB_CONNECTION` | sqlite | mysql atau postgres dengan konfigurasi penuh |
| `DB_HOST` | (tidak perlu) | Harus di-set (misal: localhost atau db host) |
| `DB_PORT` | (default sqlite) | 3306 (MySQL) atau 5432 (PostgreSQL) |
| `DB_DATABASE` | database.sqlite | Nama database produksi |
| `DB_USERNAME` | (tidak perlu untuk sqlite) | Username database |
| `DB_PASSWORD` | kosong untuk sqlite | Password database yang kuat |

**SETIAP PERUBAHAN YANG MENJADI PRODUCTION HARUS DILAKUKAN DENGAN HIERASIS DAN TESTING SEBELUM DEPLOYMENT.**

Jangan menjalankan `migrate:fresh` atau `migrate:refresh` di production tanpa seizin penuh dan backup penuh.