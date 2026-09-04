# Winky Store Security Documentation

## Overview
This document describes the security measures implemented in the Winky Store application (Laravel 12).

## Environment Security

### .env Configuration
- `APP_ENV=production` - Ensures production error handling
- `APP_DEBUG=false` - Prevents exposing stack traces to end users
- `SESSION_ENCRYPT=true` - Encrypts session data stored in cookies
- `LOG_LEVEL=info` - Appropriate level for production (not debug)
- `LOG_CHANNEL=stack` - Uses stack channel with custom and daily channels

### Sensitive Data Protection
- **SensitiveDataFilter** processor in `config/logging.php` automatically masks:
  - passwords, API keys, tokens, credit card numbers
  - CVV, SSN, social security numbers
  - Credit card numbers and expiration dates
  - Any keys containing: `password`, `secret`, `api_key`, `token`, `credit_card`, `card_number`, `cvv`, `ssn`

## HTTP Security Headers

The `SetSecurityHeaders` middleware adds the following headers to all production responses:

| Header | Value | Purpose |
|---|---|---|
| `X-Content-Type-Options` | `nosniff` | Prevents MIME-type sniffing |
| `X-Frame-Options` | `SAMEORIGIN` | Prevents clickjacking attacks |
| `X-XSS-Protection` | `1; mode=block` | Enables XSS filter in older browsers |
| `Strict-Transport-Security` | `max-age=31536000; includeSubDomains; preload` | Enforces HTTPS |
| `Content-Security-Policy` | `default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; connect-src 'self'; font-src 'self'; frame-src 'self';` | Restricts resource loading |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Controls referrer information |
| `Permissions-Policy` | `camera=(), microphone=(), geolocation=(), payment=()` | Disables unnecessary browser features |

## Authentication & Authorization

### Gates & Policies
- User model has `isAdmin()` method: returns `$this->is_admin === true`
- User model has `isSeller()` method: returns store status is 'active'
- Admin middleware: `['auth', 'admin']` prefix in routes
- Seller middleware: `['auth', 'seller']` prefix in routes
- Admin middleware checks `Auth::user()->isAdmin()`

### CSRF Protection
- `VerifyCsrfToken` middleware registered in web middleware group
- CSRF tokens automatically included in all forms
- API endpoints use token-based authentication where applicable

## Database Security

### Parameterized Queries
- Laravel Eloquent/Query Builder uses parameterized queries
- Protects against SQL injection attacks

### Migrations
- All 64 migrations run successfully
- Database indexes on all new tables (loyalty_accounts, referral_codes, promoters, etc.)
- Foreign key constraints enabled

## Payment Security

### Webhook Signature Verification
- Midtrans webhook: SHA-512 hash of `orderId + statusCode + grossAmount + server_key`
- Uses `hash_equals()` for timing attack protection
- Idempotency: checks for existing processed webhooks
- Duplicate webhook protection: returns true early if already processed

### Sensitive Data Filtering
- Webhook payloads filtered before saving to database
- Masked: card_number, cvv, expiry, billing_address, full_name, phone, email, amount, secret, key, api_key, api_secret

### Server-Side Payment Amount
- Payment amount sourced from `$order->total`, not client-specified
- Prevents payment amount tampering

## File Security

### Upload Validation
- Verify file type and size in controllers
- Store uploaded files outside web root when possible
- Use signed URLs for private file access

### Log Sanitization
- SensitiveDataFilter processor runs on all log entries
- Prevents passwords, keys, and sensitive data from appearing in logs

## Security Recommendations

### Production Checklist
1. ✅ `APP_ENV=production` set in .env
2. ✅ `APP_DEBUG=false` set in .env
3. ✅ Security headers middleware registered
4. ✅ SensitiveDataFilter processor active
5. ✅ HSTS header enforced
6. ⚠️ Custom SSL certificate installed for production domain
7. ⚠️ Custom domain pointed to production server
8. ⚠️ Payment gateway credentials configured (Midtrans/Axis)
9. ⚠️ SMTP mail server credentials configured
10. ⚠️ Redis password configured with actual Redis instance
11. ⚠️ AWS S3 credentials configured if using S3 storage
12. ⚠️ Custom domain and DNS settings updated

### Security Headers Note
The Content-Security-Policy includes `unsafe-inline` for scripts and styles to maintain compatibility with Laravel's default asset loading. For stricter security, consider extracting inline styles/scripts to separate files and removing `'unsafe-inline'`.

## Backup & Recovery

### Backup Schedule
- Daily: Automatic backup via `backup.sh` script (7-day retention)
- Weekly: Manual export (4-week retention)
- Monthly: Manual export (12-month retention)

### Recovery Procedure
1. Stop application if using MySQL/PostgreSQL
2. Restore from backup:
   - MySQL: `mysql -u user -p database < backup.sql`
   - PostgreSQL: `psql -U user -d database -f backup.sql`
   - SQLite: `cp backup/database.sqlite database/database.sqlite`
3. Verify migration status: `php artisan migrate:status`
4. Restart application
5. Test critical functions (login, orders, payments)

### Important Notes
- ⚠️ Never run `php artisan migrate:fresh` in production (drops all data)
- ⚠️ Never hardcode passwords or secrets in scripts
- ⚠️ Test restore periodically (monthly recommended)
- 📍 Keep backups off-site or in cloud storage
- 📍 Use environment variables for database credentials, not hardcoded values