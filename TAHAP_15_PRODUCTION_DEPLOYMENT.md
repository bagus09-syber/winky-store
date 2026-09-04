# TAHAP 15 — PRODUCTION DEPLOYMENT DOCUMENTATION

## Winky Store Production Launch Readiness

### Environment Configuration

#### .env Production Requirements

| Variable | Development | Production Required | Default |
|----------|-------------|---------------------|---------|
| APP_ENV | local | **production** | local |
| APP_DEBUG | true | **false** | true |
| APP_URL | http://localhost | **production domain** | http://localhost |
| SESSION_ENCRYPT | false | **true** | false |
| LOG_LEVEL | debug | **info** | debug |
| LOG_CHANNEL | dailyfile | **dailyfile** | stack |
| QUEUE_CONNECTION | sync | **redis** | sync |
| CACHE_STORE | file | **redis** | file |
| DB_CONNECTION | sqlite | **mysql/postgres** | sqlite |
| REDIS_HOST | 127.0.0.1 | **with password** | 127.0.0.1 |
| REDIS_PASSWORD | null | **required** | null |
| MAIL_MAILER | log | **smtp** | log |
| MAIL_HOST | 127.0.0.1 | **smtp host** | 127.0.0.1 |
| MAIL_PORT | 2525 | **smtp port** | 2525 |
| MAIL_USERNAME | null | **required** | null |
| MAIL_PASSWORD | null | **required** | null |

#### Critical Security Notes

1. **APP_DEBUG** must be `false` in production - enabling it exposes stack traces, application details, and potentially sensitive information to end users.

2. **APP_ENV** must be set to `production` - this affects:
   - Error handling behavior
   - Cache configuration
   - Session configuration
   - Visible features

3. **SESSION_ENCRYPT** must be `true` - encrypts session data stored in cookies. Prevents session hijacking.

4. **Database credentials** - never hardcode in codebase. Use environment variables only.

5. **Mail credentials** - MAIL_USERNAME and MAIL_PASSWORD must be set for production transactional emails (order confirmations, password resets, etc.).

6. **REDIS_PASSWORD** - required for production Redis instance. Do not leave as `null`.

7. **AWS credentials** - if using S3 storage, AWS_ACCESS_KEY_ID and AWS_SECRET_ACCESS_KEY must be configured.

8. **Payment gateway credentials** - Midtrans server_key, etc. must be set from dashboard.

### Deployment Commands

#### Local Development Setup

```bash
# 1. Copy example environment file
cp .env.example .env

# 2. Generate application key
php artisan key:generate

# 3. Install dependencies
composer install
npm install

# 4. Build assets
npm run dev

# 5. Run migrations
php artisan migrate

# 6. Start development server
php artisan serve
```

#### Production Deployment

```bash
# 1. Prepare environment
cp .env.example .env
# - Edit .env with production values
# - Set APP_ENV=production
# - Set APP_DEBUG=false
# - Configure all production credentials

# 2. Install production dependencies
composer install --optimize-autoloader --no-dev

# 3. Build assets for production
npm run build

# 4. Run migrations (DO NOT use migrate:fresh)
php artisan migrate

# 5. Clear and cache configuration
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Verify all systems
php artisan route:list    # Should show 174 routes
php artisan migrate:status # Verify all migrations run

# 7. Start production server
#   - Using PHP-FPM with Nginx
#   - Or using the provided Docker setup
```

#### Docker Deployment

```bash
# Using docker-compose
docker-compose build
docker-compose up -d

# Or build and run individually
docker build -t winky-store .
docker run -d \
  -p 80:80 \
  -p 443:443 \
  -p 3306:3306 \
  -p 6379:6379 \
  -v .:/var/www \
  --env-file .env \
  winky-store
```

### Database Migration Management

#### Safe Migration Commands

| Command | Purpose | Safety |
|---------|---------|--------|
| `php artisan migrate` | Run pending migrations | ✅ Safe |
| `php artisan migrate:status` | Check migration status | ✅ Safe |
| `php artisan migrate:refresh` | Rollback and re-run | ⚠️ Data loss risk |
| `php artisan migrate:reset` | Rollback all migrations | ⚠️ Data loss risk |
| `php artisan migrate:fresh` | Drop all tables and re-migrate | ❌ **NEVER use in production** |
| `php artisan schema:dump` | Update database schema | ✅ Safe |

#### Important - NEVER USE IN PRODUCTION

```bash
# ❌ DANGER - DO NOT RUN IN PRODUCTION
php artisan migrate:fresh
# This will drop ALL tables and re-run migrations,
# resulting in complete data loss!

# ❌ DANGER - DO NOT RUN IN PRODUCTION  
php artisan migrate:refresh
# This will rollback all migrations and re-run them,
# losing all existing data!
```

### Backup & Recovery

#### Backup Schedule

| Frequency | Command | Retention |
|-----------|---------|-----------|
| Daily | `./backup.sh` | 7 days |
| Weekly | Manual export | 4 weeks |
| Monthly | Manual export | 12 months |

#### Restore Procedure

1. Stop application if using MySQL/PostgreSQL
2. Restore from backup:
   - MySQL: `mysql -u user -p database < backup.sql`
   - PostgreSQL: `psql -U user -d database -f backup.sql`
   - SQLite: `cp backup.sqlite database/database.sqlite`
3. Verify migration status: `php artisan migrate:status`
4. Restart application
5. Test critical functions (login, orders, payments)

#### Backup Script Example

```bash
#!/bin/bash
# database backup script for Winky Store

BACKUP_DIR="./backup"
DATE=$(date +%Y%m%d_%H%M%S)
DB_PATH="database/database.sqlite"

mkdir -p "$BACKUP_DIR"

if [ -f "$DB_PATH" ]; then
    cp "$DB_PATH" "$BACKUP_DIR/database_$DATE.sqlite"
    gzip "$BACKUP_DIR/database_$DATE.sqlite"
    echo "Backup created: $BACKUP_DIR/database_$DATE.sqlite.gz"
    
    # Clean up backups older than 7 days
    find "$BACKUP_DIR" -name "database_*.sqlite.gz" -mtime +7 -delete
    
    echo "Old backups cleaned (older than 7 days)"
else
    echo "Error: Database file not found"
    exit 1
fi
```

### Security Hardening

#### Security Headers (already implemented)

The following security headers are automatically added to all production responses via the `SetSecurityHeaders` middleware:

- `X-Content-Type-Options: nosniff` - Prevents MIME-type sniffing
- `X-Frame-Options: SAMEORIGIN` - Prevents clickjacking attacks
- `X-XSS-Protection: 1; mode=block` - Enables XSS filter in older browsers
- `Strict-Transport-Security: max-age=31536000; includeSubDomains; preload` - Enforces HTTPS
- `Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; connect-src 'self'; font-src 'self'; frame-src 'self';` - Restricts resource loading
- `Referrer-Policy: strict-origin-when-cross-origin` - Controls referrer information
- `Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()` - Disables unnecessary browser features

#### Rate Limiting

Laravel's rate limiter is configured for:
- API endpoints: 100 requests per minute per IP
- Auth endpoints: 10 requests per minute per IP
- Payment webhooks: 20 requests per minute (stricter to prevent abuse)

#### CSRF Protection

- CSRF tokens are automatically included in all forms
- API endpoints use token-based authentication where applicable
- Webhook endpoints verify request signatures (see Payment Service)

#### HTTPS Enforcement

- Strict-Transport-Security header enforced (1 year, includes subdomains)
- Ensure valid SSL certificate is installed for production domain
- Redirect all HTTP traffic to HTTPS

### Performance Optimization

#### Caching Strategy

After deployment, run these caching commands:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### Asset Optimization

- Vite build produces minified CSS and JS
- Gzip compression enabled for appropriate content types
- Image optimization recommended via build scripts
- Browser caching headers configured in nginx

#### Queue Workers

For production, ensure queue workers are running:

```bash
# Start queue worker in Docker
docker exec winky-store-queue php artisan queue:work --redis --tries=3 --sleep=3

# Or via systemd
php artisan queue:work --redis --tries=3 --sleep=3
```

### Monitoring & Logging

#### Health Check Endpoint

The health check service can be exposed at `/health` endpoint (recommended to protect with auth):

```php
// Add to routes/api.php or routes/web.php with auth middleware
use App\Health\HealthCheckService;

Route::get('/health', function () {
    $checks = (new HealthCheckService())->createDefaultChecks();
    $result = (new HealthCheckService())->evaluate();
    return response()->json($result);
});
```

#### Log Monitoring

- Daily log files rotated (keep 14 days by default)
- Sensitive data filtered via SensitiveDataProcessor
- Security events logged to `custom` channel
- Payment webhook events logged with filtered payloads
- Admin audit logs stored separately

#### Error Tracking

- Critical errors logged to `emergency` channel
- All exceptions captured with full context (excluding secrets)
- Payment errors tracked separately for fraud analysis
- Queue failed jobs monitored and alerted

### Rollback Procedure

#### If Deployment Fails

1. **Immediate rollback**:
   ```bash
   # Revert to previous .env if needed
   cp .env.backup .env
   
   # Clear caches and re-cache previous config
   php artisan optimize:clear
   php artisan config:cache
   php artisan route:cache
   ```

2. **Database rollback** (if migration caused issue):
   ```bash
   php artisan migrate:rollback  # Rollback last batch
   # Or specific step:
   php artisan migrate:rollback --step=1
   ```

3. **Asset rollback**:
   ```bash
   # Revert to previous asset build
   npm run build  # Or use git to revert public/build assets
   ```

4. **Full rollback using Docker**:
   ```bash
   docker-compose down
   # Restore previous container image or volume
   docker-compose up -d
   ```

#### Emergency Contacts

- System Administrator: [your-contact]
- Database Administrator: [your-contact]
- Payment Gateway Support: [midtrans-support]
- Hosting Provider: [your-hosting-contact]

### File Summary - Changes Made in Phase 15

| File | Type | Description |
|------|------|-------------|
| `.env` | Modified | Updated APP_DEBUG=false, SESSION_ENCRYPT=true, QUEUE_CONNECTION=redis, LOG_LEVEL=info, LOG_CHANNEL=dailyfile |
| `app/Http/Middleware/SetSecurityHeaders.php` | New | Security headers middleware |
| `app/Http/Kernel.php` | Modified | Registered SetSecurityHeaders middleware |
| `app/Health/HealthCheckService.php` | New | Health check service for production monitoring |
| `app/Health/HealthCheckFacade.php` | New | Health check facade |
| `app/Services/Payment/PaymentService.php` | Modified | Added webhook payload filtering for sensitive data |
| `app/Services/Payment/Providers/DevelopmentPaymentProvider.php` | Modified | Updated verifyWebhook with development mode notice |
| `app/Services/Payment/Providers/MidtransPaymentProvider.php` | Existing | Already had webhook signature verification |
| `config/logging.php` | Modified | Updated LOG_LEVEL, added dailyfile channel config |
| `nginx/nginx.conf` | New | Production Nginx configuration with security headers |
| `docker-compose.yml` | New | Production Docker compose setup |
| `Dockerfile` | New | Production Dockerfile |
| `init_db.sql` | New | Database initialization script |
| `TAHAP_15_*.md` | New | Multiple documentation files |
| `routes/web.php` | Modified | Fixed duplicate route name 'login' → 'home' |

### Pre-Launch Checklist

#### ✅ Required Before Launch

- [ ] APP_ENV=production set in .env
- [ ] APP_DEBUG=false set in .env
- [ ] All payment gateway credentials configured
- [ ] Redis configured with password
- [ ] Database migrated and verified
- [ ] npm run build successful (no errors)
- [ ] php artisan route:list shows 174 routes without errors
- [ ] php artisan migrate:status shows all migrations run
- [ ] Security headers tested (visit site with browser devtools)
- [ ] Health check endpoint returns healthy status
- [ ] Backup system tested (create and restore backup)
- [ ] Queue workers configured and running
- [ ] SSL certificate installed for production domain
- [ ] Domain DNS pointed to production server
- [ ] Monitoring/alerting configured
- [ ] Rollback procedure documented and tested

#### ⚠️ Items Requiring Real Production Configuration

| Item | Status | Action Required |
|------|--------|-----------------|
| SMTP mail server credentials | ⚠️ REQUIRES REAL CONFIG | Set MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD from actual SMTP provider |
| Redis connection with password | ⚠️ REQUIRES REAL CONFIG | Set REDIS_PASSWORD from actual Redis instance |
| Database MySQL/PostgreSQL connection | ⚠️ REQUIRES REAL CONFIG | Update DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD |
| Payment gateway (Midtrans/Axis) credentials | ⚠️ REQUIRES REAL CONFIG | Set config/services.midtrans.* from dashboard |
| AWS S3 bucket credentials | ⚠️ REQUIRES REAL CONFIG | Set AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_BUCKET |
| Custom domain name | ⚠️ REQUIRES REAL CONFIG | Update APP_URL and DNS settings |
| SSL/TLS certificate | ⚠️ REQUIRES REAL CONFIG | Install valid certificate for production domain |

### Version Information

- **Phase**: 15 (Production & Global Launch Readiness)
- **Winky Store Version**: 1.0.0 (Post Phase 1-14 completion)
- **Laravel Version**: 10.x
- **PHP Version**: 8.2+
- **Date**: September 2026

### Next Steps After This Documentation

1. Complete real production environment configuration
2. Test all critical user flows (login, order, payment, seller center)
3. Conduct security penetration testing
4. Perform load testing
5. Execute rollback procedure in staging environment
6. Deploy to production with monitoring
7. Schedule follow-up security audit after 30 days