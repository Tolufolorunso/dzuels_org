# Dzuels Educational Foundation - Production Launch Runbook

This guide details the final production deployment steps, server requirements, domain configuration, SSL setup, performance optimizations, and Google Search Console submission for [dzuelsfoundation.org](https://dzuelsfoundation.org).

---

## 1. Automated Pre-Launch Verification

Run the built-in launch check tool directly from the project directory:

```bash
php artisan app:launch-check
```

This validates:
- Encryption key (`APP_KEY`)
- Storage directory write permissions
- Production Vite assets manifest (`public/build/manifest.json`)
- Database connection and essential seed records (settings including dynamic logo and Givebutter URL, 6 impact causes in page sections, ITD 2026 event, programmes, team members, albums, partners)
- Existence of refreshed MariaDB database dump (`dzuels_database.sql`)
- Presence of deployment manifest (`cpanel_changed_files.txt`)
- All 11 critical named route endpoints

---

## 2. Server Environment Setup & `.env` Optimization

### 2.1 Production `.env` Variables

Ensure the production `.env` is configured with hardened production values:

```env
APP_NAME="Dzuels Educational Foundation"
APP_ENV=production
APP_KEY=base64:... # Generate via: php artisan key:generate
APP_DEBUG=false
APP_URL=https://dzuelsfoundation.org

LOG_CHANNEL=daily
LOG_LEVEL=error

DB_CONNECTION=sqlite
# For MySQL / MariaDB on VPS:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=dzuels_org
# DB_USERNAME=dzuels_user
# DB_PASSWORD=your_strong_password

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=dzuelsfoundation.org
SESSION_SECURE_COOKIE=true

QUEUE_CONNECTION=database
CACHE_STORE=database
```

### 2.2 Storage Link & Permissions

Ensure web server (e.g. `www-data` or `nginx`) owns the storage and bootstrap cache directories:

```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 2.3 Production Optimization & Caching Commands

Before switching DNS traffic, run Laravel's cache optimization commands:

```bash
# Optimize configuration, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Compile production CSS and JS assets
npm run build
```

---

## 3. Domain Pointing (DNS Configuration)

Configure DNS records at your domain registrar for `dzuelsfoundation.org`:

| Type  | Host | Value | TTL | Note |
|-------|------|-------|-----|------|
| **A** | `@`  | `SERVER_PUBLIC_IPV4` | 3600 | Points apex domain to server IP |
| **CNAME** | `www` | `dzuelsfoundation.org.` | 3600 | Directs www traffic to apex |
| **TXT** | `@` | `"v=spf1 ..."` | 3600 | SPF record for transactional email delivery |

---

## 4. HTTPS & SSL/TLS Configuration (Let's Encrypt / Certbot)

For Nginx on Ubuntu/Debian:

```bash
sudo apt update && sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d dzuelsfoundation.org -d www.dzuelsfoundation.org
```

Certbot will automatically configure the SSL virtual host and set up auto-renewal cron tasks (`systemctl status certbot.timer`).

### Nginx Virtual Host Best Practice

```nginx
server {
    listen 80;
    server_name dzuelsfoundation.org www.dzuelsfoundation.org;
    return 301 https://dzuelsfoundation.org$request_uri;
}

server {
    listen 443 ssl http2;
    server_name www.dzuelsfoundation.org;
    ssl_certificate /etc/letsencrypt/live/dzuelsfoundation.org/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/dzuelsfoundation.org/privkey.pem;
    return 301 https://dzuelsfoundation.org$request_uri;
}

server {
    listen 443 ssl http2;
    server_name dzuelsfoundation.org;
    root /var/www/dzuels_org/public;

    ssl_certificate /etc/letsencrypt/live/dzuelsfoundation.org/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/dzuelsfoundation.org/privkey.pem;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header Referrer-Policy "strict-origin-when-cross-origin";

    index index.php;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 5. Google Search Console Submission

Once DNS and SSL are live:

1. **Add Property:**
   - Go to [Google Search Console](https://search.google.com/search-console).
   - Add property for `https://dzuelsfoundation.org`.
   - Verify ownership via DNS TXT record or HTML tag.

2. **Submit XML Sitemap:**
   - In Search Console sidebar, navigate to **Sitemaps**.
   - Enter `sitemap.xml` (full URL: `https://dzuelsfoundation.org/sitemap.xml`).
   - Click **Submit**.

3. **Verify Robots.txt:**
   - Verify `https://dzuelsfoundation.org/robots.txt` returns HTTP 200 with `Disallow: /admin` and points to the sitemap.

4. **Test Live URL Inspection:**
   - Use URL Inspection on `https://dzuelsfoundation.org/` to confirm indexing eligibility, mobile usability, and rich results (Organization & Breadcrumbs JSON-LD).

---

## 6. Post-Launch Smoke Test Checklist

- [ ] Visit `https://dzuelsfoundation.org` in light and dark mode; verify anti-flicker script.
- [ ] Test mobile viewport on real device (confirm zero horizontal overflow).
- [ ] Submit test message on `/contact` (verify admin inbox receipt).
- [ ] Submit test volunteer application on `/get-involved` (verify admin inbox receipt).
- [ ] Register test attendee on `/events/ijero-tech-day-2026` (verify admin registration receipt).
- [ ] Click Online Donation (Flutterwave) button; verify external gateway modal/page.
- [ ] Copy GTBank transfer account number; verify clipboard feedback.
- [ ] Test intentional 404 URL (e.g., `/test-missing`); verify branded error page with Home and Donate recovery paths.
- [ ] Sign in to `/admin` and verify KPI counters and edit forms.
