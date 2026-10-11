# Dzuels Educational Foundation - cPanel & phpMyAdmin Deployment Guide

This guide provides a comprehensive, step-by-step walk-through for deploying the **Dzuels Educational Foundation** web application to standard cPanel shared hosting environments (such as Namecheap cPanel, Whogohost, Hostinger, Bluehost, or any Linux cPanel host).

---

## 1. Prerequisites & Hosting Requirements

Before beginning the migration, ensure your cPanel hosting account meets the following minimum requirements:

- **PHP Version:** PHP 8.3 or PHP 8.4 (configured via cPanel **Select PHP Version** or **MultiPHP Manager**).
- **PHP Extensions:** `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `fileinfo`, `gd` or `imagick`, `curl`, `zip`.
- **Database Engine:** MySQL 5.7+ / 8.0+ or MariaDB 10.3+.
- **SSL Certificate:** Free AutoSSL or Let's Encrypt activated for `dzuelsfoundation.org`.
- **Terminal Access / File Manager:** Access to cPanel **File Manager** and **phpMyAdmin** (SSH Terminal access is beneficial but **not required**).

---

## 2. Step 1: Export Database with the Migration Suite

The application includes an automated Artisan command that extracts all 24 database tables and seeded foundation records (programmes, ITD 2026 event and sponsors, 125 optimized gallery photos, leadership team, site settings, dynamic CMS page sections, and admin accounts) into clean, standalone MySQL/MariaDB SQL syntax:

Run the following command on your local machine:

```bash
php artisan db:export-sql
```

This generates:
- **Output File:** `database/dumps/dzuels_database.sql`
- **Dialect:** MySQL 5.7+ / 8.0+ and MariaDB 10.3+ compatible.
- **Encoding:** `utf8mb4` with `utf8mb4_unicode_ci` collation.
- **Safety Features:** Automatically wraps table statements in `SET FOREIGN_KEY_CHECKS=0;` and `START TRANSACTION;` to eliminate foreign key order errors during phpMyAdmin import.

*(Optional custom destination path: `php artisan db:export-sql --output=my_backup.sql`)*

---

## 3. Step 2: Create MySQL Database & User in cPanel

1. Log in to your hosting **cPanel Dashboard**.
2. Under the **Databases** section, click **MySQL® Database Wizard**.
3. **Step 1: Create A Database**
   - Enter a database name (e.g. `dzuels_db`).
   - Note the full database name including the cPanel account prefix (e.g., `cpaneluser_dzuels_db`).
   - Click **Next Step**.
4. **Step 2: Create Database Users**
   - Enter a username (e.g. `dzuels_user` -> full name: `cpaneluser_dzuels_user`).
   - Click **Password Generator** to create a strong, secure password (at least 16 characters).
   - **Copy and securely save this password** for your `.env` configuration.
   - Click **Create User**.
5. **Step 3: Add User to Database**
   - Check the box **ALL PRIVILEGES**.
   - Click **Make Changes**.
   - Click **Next Step** to finish.

---

## 4. Step 3: 1-Click Import into phpMyAdmin

1. Return to the cPanel home screen and click **phpMyAdmin** under **Databases**.
2. In the left sidebar, click your newly created database name (e.g. `cpaneluser_dzuels_db`).
3. Click the **Import** tab in the top navigation bar.
4. Under **File to import**, click **Choose File** (or **Browse**).
5. Select `database/dumps/dzuels_database.sql` from your computer.
6. Verify settings:
   - **Character set of the file:** `utf-8`.
   - **Format:** `SQL`.
7. Scroll down and click **Import** (or **Go**).
8. phpMyAdmin will process the dump and display a green success banner:
   > *"Import has been successfully finished, 24 tables created and 220+ queries executed."*

All tables, indexes, and foundation data are now live in your MySQL database!

---

## 5. Step 4: Package and Upload Application Code

### Recommended cPanel Directory Architecture

For maximum security on cPanel shared hosting, keep Laravel's core code **outside** the public web root:

```text
/home/cpaneluser/
├── dzuels_app/                  <-- Laravel core code (secure, not web-accessible)
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   └── artisan
└── public_html/                 <-- Public web root (only files from Laravel's public/ folder)
    ├── build/                   <-- Compiled Vite assets (manifest.json, CSS, JS)
    ├── front/                   <-- Public images, logos, gallery WebP photos
    ├── index.php                <-- Web entrypoint (pointing to ../dzuels_app/)
    ├── .htaccess                <-- Apache rewrite rules
    ├── favicon.ico
    └── robots.txt
```

### Packaging the Application Locally

1. Build production assets locally:
   ```bash
   npm run build
   ```
2. Create a clean ZIP archive of the project, **excluding** temporary local files:
   - Include: `app`, `bootstrap`, `config`, `database`, `public`, `resources`, `routes`, `storage`, `vendor`, `artisan`, `composer.json`
   - Exclude: `.git`, `node_modules`, `tests`, `database/database.sqlite`

### Uploading via cPanel File Manager

1. Open cPanel **File Manager**.
2. In your home directory (`/home/cpaneluser/`), create a new folder named `dzuels_app`.
3. Upload your ZIP archive into `dzuels_app` and click **Extract**.
4. Move the contents of `dzuels_app/public/` directly into your `public_html/` folder (or your subdomain folder).
5. Open `public_html/index.php` in the File Manager editor and update the paths to point to `dzuels_app`:

```php
// Change lines in public_html/index.php to point to ../dzuels_app/
require __DIR__.'/../dzuels_app/vendor/autoload.php';

$app = require_once __DIR__.'/../dzuels_app/bootstrap/app.php';
```

---

## 6. Step 5: Configure Production `.env`

In cPanel File Manager, ensure **Show Hidden Files (dotfiles)** is enabled in Settings.

Navigate to `/home/cpaneluser/dzuels_app/` and edit or create the `.env` file:

```dotenv
APP_NAME="Dzuels Educational Foundation"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://dzuelsfoundation.org

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Production MySQL Database Connection
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=cpaneluser_dzuels_db
DB_USERNAME=cpaneluser_dzuels_user
DB_PASSWORD=YOUR_STRONG_DATABASE_PASSWORD_HERE

# Session and Cache
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_STORE=database
CACHE_PREFIX=dzuels_cache

# Queue
QUEUE_CONNECTION=sync

# Email Configuration (SMTP)
MAIL_MAILER=smtp
MAIL_HOST=mail.dzuelsfoundation.org
MAIL_PORT=465
MAIL_USERNAME=info@dzuelsfoundation.org
MAIL_PASSWORD=YOUR_CPANEL_EMAIL_PASSWORD
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS="info@dzuelsfoundation.org"
MAIL_FROM_NAME="Dzuels Educational Foundation"

# Online Donations
FLUTTERWAVE_PAY_URL=https://flutterwave.com/pay/j0byxgjs0nma
```

---

## 7. Step 6: Configure Storage Symlink & Permissions

### Permissions
Set directory permissions via cPanel File Manager or Terminal:
- `chmod -R 755 /home/cpaneluser/dzuels_app/storage`
- `chmod -R 755 /home/cpaneluser/dzuels_app/bootstrap/cache`

### Storage Symlink
Laravel stores uploaded files in `storage/app/public` and exposes them via `public_html/storage`.

**Method A: Using cPanel Terminal (Recommended if available):**
```bash
cd /home/cpaneluser/dzuels_app
php artisan storage:link
```
If using separate `dzuels_app` and `public_html`, link directly:
```bash
ln -s /home/cpaneluser/dzuels_app/storage/app/public /home/cpaneluser/public_html/storage
```

**Method B: Via a one-time PHP script if Terminal is disabled:**
Create `public_html/symlink_setup.php` with:
```php
<?php
symlink('/home/cpaneluser/dzuels_app/storage/app/public', '/home/cpaneluser/public_html/storage');
echo 'Storage symlink created successfully!';
```
Visit `https://dzuelsfoundation.org/symlink_setup.php` once in your browser, then **immediately delete the file**.

---

## 8. Step 7: Optimization & Launch Verification

If you have cPanel Terminal access, run these optimization commands to boost performance:

```bash
cd /home/cpaneluser/dzuels_app
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan app:launch-check
```

The `app:launch-check` command will output:
```text
====================================================
 Dzuels Educational Foundation - Launch Check Suite 
====================================================
...
 Launch Readiness Status: READY FOR LAUNCH (0 failures, 0 warnings)
```

---

## 9. Step 8: Sign in to Admin Dashboard

Visit `https://dzuelsfoundation.org/admin` in your browser:

- **Super Admin Email:** `admin@dzuelsfoundation.org`
- Enter your password to sign in.
- Navigate through:
  - **Site Settings** (Impact stats, hotline, addresses, bank details)
  - **Page Content CMS** (Live home, about, and programme page text/images)
  - **Events** (ITD 2026 details and sponsors)
  - **Gallery** (5 albums and 125 photos)
  - **Staff Accounts** (Create role-based Editor or Support accounts)

---

## 10. Troubleshooting & Common Issues

| Issue | Cause | Solution |
| :--- | :--- | :--- |
| **500 Internal Server Error** | `storage/` or `bootstrap/cache/` not writable, or missing `APP_KEY` | Ensure `chmod 755` on both folders. Check `storage/logs/laravel.log` for exact error details. |
| **SQLSTATE[HY000] [2002] Connection refused** | `DB_HOST` in `.env` is incorrect | In cPanel shared hosting, try `DB_HOST=localhost` instead of `127.0.0.1`. Verify database user password. |
| **Missing CSS/JS or blank design** | Assets in `public/build/` were not copied to `public_html/build/` | Verify `public_html/build/manifest.json` exists and matches the compiled Vite bundle. |
| **Images in Gallery or Banners Not Loading** | Storage symlink is missing or public images missing | Ensure `public_html/front/images/` exists and contains the optimized gallery and banner images. |
| **Changes to `.env` not reflecting** | Config cache is active | Run `php artisan config:clear` or temporarily delete `bootstrap/cache/config.php`. |

---

*Documented for Dzuels Educational Foundation deployment. Generated by the Dzuels MySQL Migration Suite.*
