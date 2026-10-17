# Ayojn Laravel Application

Ayojn is a multi-role web application built with Laravel 5.6, Hesto Multi Auth, and Laravel AdminLTE.

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

The application currently uses Laravel 5.6.40 and legacy dependencies. PHP 7.4 is end-of-life; do not expose this stack publicly until a supported runtime and compatible framework/dependencies have been selected and tested.

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

## Deployment notes

No production deployment should proceed until the application has been reviewed and tested on a supported PHP/Laravel dependency set. Resolve dependency-audit findings, remove hard-coded MFA fallbacks, and validate authentication, authorization, payments, mail, uploads, and other critical workflows before public exposure.

For deployment, keep the Laravel project outside the web root and point the web server to the `public/` directory. Use a separate staging database, a private environment configuration, HTTPS, and reviewed forward migrations. Never deploy local `.env` files, test credentials, database dumps, logs, or generated development dependencies.

See [KB/UPGRADE_PLAN.md](KB/UPGRADE_PLAN.md) for the tracked upgrade and testing plan.

## Secrets and Git

- Never commit passwords, API keys, `.env` files, database dumps, logs, or local test credentials.
- Review staged changes for secrets and personal data before pushing.
- If a real credential has ever been committed or shared, rotate it; removing it from the current file does not invalidate it.
- `.gitignore` excludes `.env`, `vendor/`, `node_modules/`, and generated logs.
