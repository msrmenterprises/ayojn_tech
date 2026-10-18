# Ayojn Laravel Application

Ayojn is a multi-role web application built with Laravel 5.8, Hesto Multi Auth, and Laravel AdminLTE.

## Contents

- [Requirements](#requirements)
- [Local setup](#local-setup)
- [Database setup](#database-setup)
- [Running tests](#running-tests)
- [Local login routes](#local-login-routes)
- [Deployment notes](#deployment-notes)
- [Secrets and Git](#secrets-and-git)

## Requirements

- PHP 7.4.33 is the version currently used for local testing.
- Composer and the PHP extensions required by the application, including `curl`, `fileinfo`, `gd`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, and XML support.
- MySQL or MariaDB.

The application uses Laravel 5.8 and legacy dependencies. Production currently runs PHP 7.4.33; PHP 7.4 is end-of-life, so treat this as a legacy runtime and plan any framework/runtime upgrade as a separate, tested project. Do not change the production PHP version as part of a routine code deployment.

## Local setup

Run commands from the project root:

```sh
composer install
```

Create a private `.env` file for your local environment. This repository does not provide an `.env.example`; configure the application URL and local database connection without copying a production environment file or committing credentials.

For a new environment only, generate an application key:

```sh
php artisan key:generate
```

Do not regenerate the key for an existing environment. Clear cached configuration and start the local development server:

```sh
php artisan config:clear
php artisan serve --host=127.0.0.1 --port=8000
```

Open `http://127.0.0.1:8000`. For local development, use an appropriate non-production `APP_ENV` and keep local settings separate from staging and production.

## Database setup

Configure the local database connection privately in `.env`. For application use, import the reviewed local database schema and data required for your development workflow.

Tests must use a separate, disposable MySQL database. The test database should contain the required application schema and synthetic test fixtures, not copied production or personal records. Synthetic configuration fixtures are under `tests/fixtures/`.

Do not run `migrate:fresh`, `migrate:refresh`, or seeders against an imported or production database without a verified backup and review of their effects.

## Running tests

Run PHPUnit from the project root, where `phpunit.xml` is located. Running `vendor/bin/phpunit` from inside `vendor/bin` may show usage text instead of loading the project configuration.

Before testing:

1. Install dependencies with `composer install` if `vendor/` is missing.
2. Start the local MySQL/MariaDB service.
3. Confirm `TEST_DB_DATABASE` in `phpunit.xml` points to a dedicated, disposable test database with the required schema and synthetic fixtures.

The test bootstrap requires `_test_` in the configured database name and forces the MySQL connection. This is only a naming check; verify that the actual database is safe and contains no data you need to preserve. PHPUnit does not create the schema.

Run the receiver-bid payment regression tests:

```sh
vendor/bin/phpunit --filter ReceiverBidPaymentFailureTest
```

Run the complete unit and feature test suites:

```sh
vendor/bin/phpunit
```

On the configured macOS PHP 7.4 installation, generate a line-coverage report with:

```sh
/opt/homebrew/opt/php@7.4/bin/phpdbg -qrr vendor/bin/phpunit --coverage-text=/dev/stdout
```

The current suite is below the 80% line-coverage goal. Review the full test results and coverage report; do not treat a partial run as complete validation.

## Local login routes

| Account type | Local login URL |
|---|---|
| Offer / Influence | `http://127.0.0.1:8000/login` |
| Receive / Manage | `http://127.0.0.1:8000/login` |
| Partner | `http://127.0.0.1:8000/partner/login` |
| Admin | `http://127.0.0.1:8000/controlpanel/login` |

Provision authorized test accounts in the local or test database. No account names, passwords, or MFA codes are published in this repository. When `MAIL_DRIVER=log` is used locally, MFA messages are written to `storage/logs/laravel.log`; do not share or commit those logs.

### Production account login reference

Use the following production URLs and account email addresses:

| Account | Login email | Production login URL |
|---|---|---|
| Admin | `admin@ayojn.com` | <https://www.oksbakers.com/controlpanel/login> |
| Offer | `offer@ayojn.com` | <https://www.oksbakers.com/login> |
| Receive | `receive@ayojn.com` | <https://www.oksbakers.com/login> |
| Partner | `partner@ayojn.com` | <https://www.oksbakers.com/partner/login> |

Enter each account's password at login. Passwords are intentionally not recorded in this README; store and share them only through an approved password manager or other secure channel. The login OTP is sent to Manish Yadav. Contact him for the current OTP when prompted; never store OTP codes here.

## Deployment notes

### Hostinger production configuration

The current production deployment is:

| Setting | Value |
|---|---|
| Repository | `msrmenterprises/ayojn_tech` |
| Branch | `main` |
| Application | Laravel 5.8 |
| PHP | 7.4.33 |
| Project directory | `/home/u419449319/domains/oksbakers.com/public_html` |
| Laravel public directory | `/home/u419449319/domains/oksbakers.com/public_html/public` |
| Primary URL | `https://www.oksbakers.com/` |

The production database and mail settings are already configured in the server's `.env`. Keep that file on the server. Hostinger SMTP uses `connect@oksbakers.com`; never display, copy, or commit the mailbox password. Use `https://www.oksbakers.com/` as the canonical URL and confirm that the `www` hostname remains connected to this hosting vhost and SSL certificate.

### Deploying future code updates

1. Make and test the change locally. Review staged files before committing; never commit `.env`, database or mailbox passwords, private keys, production secrets, database dumps, logs, or test credentials.
2. Commit and push the intended changes to `main`:

   ```sh
   git add .
   git commit -m "Describe the change"
   git push origin main
   ```

3. If Hostinger Auto Deploy is enabled, confirm it deploys the new `main` commit. If it does not trigger, start deployment manually in hPanel.
4. Connect over SSH and run Composer with PHP 7.4. Use `install` to honor the committed lock file; do not run `composer update` unless intentionally changing dependency versions:

   ```sh
   cd /home/u419449319/domains/oksbakers.com/public_html

   /opt/alt/php74/usr/bin/php /usr/local/bin/composer2 install \
   --prefer-dist --no-interaction --no-dev --optimize-autoloader
   ```

   If Auto Deploy reports a Composer error, use the same command over SSH.
5. Verify the production `.env` still exists and contains the expected non-secret settings. Do not print the file or share secret values:

   ```sh
   cd /home/u419449319/domains/oksbakers.com/public_html
   test -f .env && echo ".env exists"
   ```

   Confirm `APP_ENV=production`, `APP_DEBUG=false`, and `APP_URL=https://www.oksbakers.com`. Preserve the existing `APP_KEY`; never run `php artisan key:generate` in production. Confirm the SMTP settings remain `MAIL_DRIVER=smtp`, `MAIL_HOST=smtp.hostinger.com`, `MAIL_PORT=465`, `MAIL_USERNAME=connect@oksbakers.com`, `MAIL_ENCRYPTION=ssl`, and `MAIL_FROM_ADDRESS=connect@oksbakers.com`. Do not display or share `MAIL_PASSWORD`.
6. Clear Laravel caches after each deployment, then verify the installation:

   ```sh
   cd /home/u419449319/domains/oksbakers.com/public_html

   /opt/alt/php74/usr/bin/php artisan optimize:clear

   test -f artisan && echo "artisan exists"
   test -f vendor/autoload.php && echo "Composer dependencies exist"
   /opt/alt/php74/usr/bin/php artisan --version
   ```

   The Artisan version should report Laravel 5.8. If needed, the caches can also be cleared individually with `config:clear`, `route:clear`, `view:clear`, and `cache:clear`, using the PHP 7.4 binary above.
7. If the application uses uploaded files, check the storage link and create it only if missing:

   ```sh
   ls -ld public/storage
   /opt/alt/php74/usr/bin/php artisan storage:link
   ```

### Web-server rewrite files

Keep the root `.htaccess` configured to route requests into Laravel's `public/` directory and to force the canonical HTTPS `www` hostname:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteCond %{HTTPS} off [OR]
    RewriteCond %{HTTP_HOST} !^www\.oksbakers\.com$ [NC]
    RewriteRule ^ https://www.oksbakers.com%{REQUEST_URI} [R=301,L]

    RewriteCond %{REQUEST_URI} !^/public
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

The Laravel rewrite file must be `public/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

Do not leave shell markers such as `cat >` or `EOF` in either `.htaccess` file.

### Production database migrations

Do not run migrations automatically for normal code changes. Before a migration that has been intentionally approved for production, inspect the current status:

```sh
/opt/alt/php74/usr/bin/php artisan migrate:status
```

Only when the migration is approved, apply it with:

```sh
/opt/alt/php74/usr/bin/php artisan migrate --force
```

Never run `migrate:fresh`, `db:wipe`, or `migrate:rollback` in production unless you deliberately intend to erase or roll back data.

### Post-deployment checks and troubleshooting

Test `https://www.oksbakers.com/` after deployment (use an incognito window or hard refresh). Check the home page, all production login URLs above, MFA email delivery, Login Activities, `/get-city`, main forms, uploaded files, and database-backed pages.

Inspect the Laravel log if something fails:

```sh
tail -n 100 /home/u419449319/domains/oksbakers.com/public_html/storage/logs/laravel.log
```

To monitor it live, run `tail -f` on the same log and press `Ctrl+C` to stop.

For Tinker, configure a writable PsySH environment before starting Artisan:

```sh
export XDG_RUNTIME_DIR="$HOME/.runtime"
export XDG_CONFIG_HOME="$HOME/.config"
export XDG_DATA_HOME="$HOME/.local/share"

cd /home/u419449319/domains/oksbakers.com/public_html
/opt/alt/php74/usr/bin/php artisan tinker
```

For most routine code pushes, the short sequence is:

```sh
git push origin main

cd /home/u419449319/domains/oksbakers.com/public_html
/opt/alt/php74/usr/bin/php /usr/local/bin/composer2 install \
--prefer-dist --no-interaction --no-dev --optimize-autoloader
/opt/alt/php74/usr/bin/php artisan optimize:clear
/opt/alt/php74/usr/bin/php artisan --version
```

Then test `https://www.oksbakers.com/`. The essential production safeguards are: keep PHP 7.4 for this legacy deployment, preserve `.env` and `APP_KEY`, do not run migrations blindly, and keep the canonical host at `www.oksbakers.com`.

For a new hosting setup, prefer keeping the Laravel project outside the web root and point the web server to its `public/` directory. The current Hostinger installation instead uses the documented `public_html` project directory and root rewrite file. Use a separate staging database, a private environment configuration, HTTPS, and reviewed forward migrations. Never deploy local `.env` files, test credentials, database dumps, logs, or generated development dependencies.

See [KB/UPGRADE_PLAN.md](KB/UPGRADE_PLAN.md) for the tracked upgrade and testing plan.

## Secrets and Git

- Never commit passwords, API keys, `.env` files, database dumps, logs, or local test credentials.
- Review staged changes for secrets and personal data before pushing.
- If a real credential has ever been committed or shared, rotate it; removing it from the current file does not invalidate it.
- `.gitignore` excludes `.env`, `vendor/`, `node_modules/`, and generated logs.
