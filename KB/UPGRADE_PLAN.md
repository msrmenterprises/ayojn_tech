# Laravel and PHP Upgrade Plan

**Status:** Planning  
**Working branch:** `app_upgrade`  
**Plan date:** 2026-10-17

This plan keeps all existing features in scope by default. Do not remove, bypass, or disable a feature without documenting the blocker, explaining the user impact, and getting the owner's approval.

## Goal

Move the application from Laravel 5.6/PHP 7.x to a maintained Laravel and PHP release, preserve the current business workflows and data, establish regression tests and coverage, and deploy repeatably to Hostinger with a tested rollback path.

## Recommended target

- Target **Laravel 13 on PHP 8.5**, subject to a dependency compatibility inventory and local tests.
- Hostinger currently offers PHP 8.5. Laravel 13 supports PHP 8.3-8.5 and lists security fixes through March 17, 2028. Laravel 12's security support ends February 24, 2027, so Laravel 13 is the preferable target in the current planning window.
- Follow Laravel's major-version upgrade guides in sequence. Do not try to jump by changing only the PHP or Laravel version constraints.
- Official references:
  - [Laravel 13 release and support policy](https://laravel.com/docs/13.x/releases)
  - [Laravel 13 upgrade guide](https://laravel.com/docs/13.x/upgrade)
  - [PHP supported versions](https://www.php.net/supported-versions.php)

## Verified starting point

- Current framework: Laravel 5.6.40. `composer.json` requires PHP `^7.1.3`.
- Local app runtime used for setup: PHP 7.4.33.
- Hostinger web runtime is currently selected as PHP 7.4. The account also has PHP 8.0-8.5 options.
- Hostinger default SSH/Composer PHP is 8.1.34. PHP 7.4 CLI is available at `/opt/alt/php74/usr/bin/php`, and Composer runs under that binary.
- PHP 7.4 reached end of life in 2022. It may be used only as a compatibility bridge for isolated tests, not as the long-term public runtime.
- The Hostinger PHP 7.4 extension screenshots show PDO/PDO MySQL, mbstring, cURL, OpenSSL, tokenizer, XML, XMLReader, XMLWriter, DOM, fileinfo, GD, and ZIP enabled.
- `composer audit --no-dev` reported 80 advisories across 15 production packages (6 critical, 25 high, 43 medium, 6 low). These are advisory/version matches, not proof that every issue is reachable, but they must be reviewed before public deployment.
- The code has fixed MFA fallbacks in the user, partner, and admin login flows. The partner login also puts the submitted password in the session during MFA. Review and replace both patterns before public deployment.
- The current test suite contains only Laravel example tests in `tests/Unit/ExampleTest.php` and `tests/Feature/ExampleTest.php`; there are no business-feature regression tests yet. PHPUnit 7.5.20 is locked as a development dependency.
- The repository is on `app_upgrade`. At plan creation, one runtime session file under `storage/framework/sessions/` was modified. It may contain authentication state; do not stage, publish, or inspect it as ordinary source.
- The local README contains local account credentials and is intentionally git-ignored. Do not force-add or upload it.

## Phase 0: Preserve a clean recovery point

1. Confirm the GitHub commit used as the upgrade baseline and that the local branch is `app_upgrade`.
2. Keep the modified session file and all runtime files, `.env`, database dumps, logs, `vendor/`, and `node_modules/` out of commits.
3. Take a local database backup and verify it can be restored into a separate test database.
4. Keep an untouched copy of the current code and lockfile for rollback/reference. Do not run `composer update` on the baseline.

**Gate:** Baseline code, lockfile, and database can be restored without using the production database.

## Phase 1: Inventory all features and dependencies

1. Map each user-visible workflow to its routes, controllers, models, tables, scheduled commands, and external services.
2. Cover all four login types: Offer, Receive, Partner, and Admin.
3. Create a dependency matrix for every direct Composer package: installed version, feature using it, target-compatible version, replacement candidate, and test required.
4. Include multi-auth, AdminLTE, DataTables, CAPTCHA, PDF, spreadsheet import/export, PayPal, mail/MFA, uploads, scheduled tasks, and queues in the inventory.
5. Classify feature decisions as:
   - **Keep:** required for launch and must pass tests.
   - **Blocker:** incompatible or unsafe; document the exact failing workflow and options.
   - **Proposed deferral:** not removed until the owner approves it.
6. Build the route inventory from the files actually loaded by `RouteServiceProvider`: `routes/web.php`, `routes/user.php`, `routes/controlpanel.php`, and `routes/api.php`. Do not treat backup route files as active unless they are explicitly re-enabled.
7. Trace each workflow through its controller to the Eloquent models and query-builder tables it reads or changes. Record unresolved table/guard mappings as verification items instead of inferring them from naming conventions; compare the inventory with a sanitized schema because the checked-in migrations do not describe every legacy table used by the application.
8. Record scheduled Artisan commands and HTTP-triggered cron paths separately, including cadence, command registration, affected data, and operational dependencies.
9. Record non-package integrations and side effects alongside Composer packages: mail/MFA, PayPal/IPN, Xsquare payments, CAPTCHA, PDF generation, spreadsheet import/export, uploads/storage, cURL/API calls, and queue use.
10. For every workflow, capture its user/role, entry routes, controllers, data entities, external effects, authorization/validation boundaries, regression cases, evidence files, and inventory status.

No feature is considered optional solely because its dependency is old or difficult to upgrade. Mark whether each row is an initial route map or has been traced through data and integrations, and list remaining verification work explicitly.

**Gate:** Every active route and scheduled/cron entry point is accounted for; each workflow has either verified data/integration mappings or explicit open verification items; all direct Composer dependencies have an installed-version record; and the owner approves the inventory and any proposed deferrals before code paths are disabled.

**Current inventory status:** `KB/workflow_inventory.csv` has been expanded from the initial four authentication rows into a route/controller map of the principal public, account, marketplace, payment, partner, support, administrative, API, and scheduled workflows. This is not yet owner-approved or a complete schema trace: exact guard/role boundaries, legacy table mappings, deployment-level cron invocation, and several integration details remain marked for verification.

## Phase 2: Build a baseline regression suite and measure coverage

### Test environment

- Use a dedicated testing database with sanitized data. Never run test setup, refreshes, or seeders against the imported working database or production.
- Keep mail, queue, cache, and sessions isolated in tests. Use fakes or local test drivers; do not send real email or payment requests.
- Add or update factories/fixtures for each role and business entity without embedding real account credentials.

### Baseline test and coverage status (2026-10-17)

- Runtime: PHP 7.4.33, Laravel 5.6.40, and PHPUnit 7.5.20. PHPUnit uses the isolated MySQL test database and refuses to proceed unless the configured database name contains `_test_`.
- The test database contains the 59-table legacy schema only; source rows were not copied. Synthetic button/configuration rows support page rendering, and feature tests create their own user fixtures inside database transactions.
- `phpunit.xml` scopes coverage to `app/` and excludes nine non-runtime backup PHP files that redeclare active classes.
- On the `test-coverage-80` branch, the current full-suite checkpoint passes: **125 tests, 536 assertions**. Coverage includes authentication/MFA/logout/reset, user profile and onboarding, offer/receive marketplace and bid responses, wallet voucher redemption/bank details/history/query/feedback/cancellation, referral and notification workflows, opportunity reservation/vouch/renewal/payment workflows, events and collaborations, partner offer/profile/order workflows, admin profile/password/redemption/bid/voucher/review workflows, user bid creation/response/voucher activation/payment states, marketplace cart/wishlist/checkout, and dashboard map data.
- PHP 7.4's `phpdbg` is the working coverage driver; neither Xdebug nor PCOV is loaded. The current full-suite line-coverage report is **26.99% (2,089/7,741 executable lines)**, up from the original 1.40% baseline. Classes are 10.26% and methods are 21.69%. Current coverage for the larger workflow controllers is `UserAuth\userController` 52.12% (541/1,038 lines), `UserAuth\BidController` 48.82% (372/762), `ControlpanelAuth\UserController` 41.88% (98/234), `ControlpanelAuth\BidController` 62.74% (165/263), and `Partner\UserController` 39.03% (137/351).
- The requested **80% whole-application line-coverage target is not met**. Most partner, admin, account, payment, and integration paths remain uncovered; external side effects have not been exercised.
- Regression tests exposed existing failures: unverified-account rejection cleared the user guard but left the shared web guard authenticated; partner-offer validation accessed validator errors incorrectly and returned HTTP 500; duplicate opportunity vouching incremented the free-vouch count and cleared the paid flag; admin and user bid payment used invalid state assignments; user bid negotiation allowed invalid state transitions; and the dashboard map passed an undefined variable to `compact()`, causing HTTP 500. These paths are fixed and covered by tests.

### Test scope

1. Authentication and authorization for each of the four account types: valid/invalid credentials, MFA valid/invalid, session persistence, logout, password reset, and access to role-protected routes.
2. The core Offer, Receive, Partner, and Admin workflows identified in Phase 1, including validation and authorization boundaries.
3. Database behavior for important records, relationships, imports, and migrations using a test database.
4. Payment, CAPTCHA, email, PDF, spreadsheet, upload, scheduled-task, and queue behavior. Use sandbox/fakes where appropriate, but retain feature coverage unless a deferral is approved.
5. Regression cases for existing password hashes and imported data so existing users and records remain usable after the upgrade.

### Coverage approach

- Keep the source set and denominator stable; the target is at least **80% whole-application line coverage** for `app/`, not a narrowed subset.
- Run the full suite against the isolated test database and retain a line-coverage report at each checkpoint. Do not claim the target until the suite passes and the same configured source set reports at least 80%.
- Add tests by workflow, prioritizing decision paths for authentication, authorization, payments, data changes, and the remaining user/partner/admin features. Use synthetic fixtures, fakes, and sandbox integrations; never copy production rows into test fixtures.
- Upgrade PHPUnit and the coverage driver together with the framework/PHP target; keep the same critical test cases running after each upgrade step.

**Gate:** A repeatable suite passes against the isolated database, and the stable whole-application source set reaches at least 80% line coverage. The baseline suite passes, but this coverage gate remains open.

## Phase 3: Remove authentication and deployment blockers

1. Replace all hard-coded MFA bypasses with a deliberate, testable MFA flow. No fixed OTP may remain in production code.
2. Stop storing a plaintext password in the partner MFA session. Use a short-lived, purpose-limited challenge/session design and test expiry, replay, invalid OTP, and session renewal.
3. Confirm production mail/MFA configuration independently from local `MAIL_DRIVER=log`.
4. Review the Composer audit output package by package, prioritize critical/high advisories and reachable application features, and record the selected fix or accepted risk.
5. Re-run tests and `composer audit` after each dependency milestone.

**Gate:** Authentication has no fixed bypass or plaintext password session storage, and unresolved advisories have a documented, approved disposition before any public staging.

## Phase 4: Upgrade PHP and Laravel on `app_upgrade`

1. Install/configure PHP 8.5 locally alongside PHP 7.4 so the old baseline can still be run when needed.
2. Upgrade one Laravel major-version step at a time, following the official guide for each step. At every milestone:
   - update only the required framework/dependency constraints;
   - regenerate `composer.lock` in the local upgrade environment;
   - resolve deprecated/abandoned packages or get approval to defer the feature that blocks;
   - run the full test suite, coverage report, static checks available to the project, and Composer platform checks;
   - commit a clean checkpoint before moving to the next major.
3. Test the current direct integrations against the target framework. Replace unsupported packages with maintained equivalents only after their current workflows have regression coverage.
4. Keep the database schema and business behavior stable during framework upgrades; handle database-version changes in Phase 5.
5. Do not edit only `composer.json` to claim PHP 8.5 support. Composer requirements, locked dependencies, runtime behavior, and tests must all agree.

**Gate:** The application installs and runs under the same PHP 8.5 runtime selected for staging, all approved features pass, and platform/audit checks have been reviewed.

## Phase 5: Database compatibility

1. Identify the Hostinger database product/version and supported collations before importing.
2. Restore a backup into a separate staging database and compare table counts, schema, and representative data.
3. Test forward migrations and rollback/recovery procedures on copies. Use reviewed forward migrations only; never use destructive refresh/fresh commands on real data.
4. Do not combine a major database-server upgrade with the Laravel/PHP upgrade. Schedule and test it as a separate change.

**Gate:** The app passes regression tests against a restored copy of the intended staging database.

## Phase 6: Protected Hostinger staging

1. Create a separate staging database and private application directory outside the existing `public_html/`.
2. Back up the existing site before changing document-root settings. Configure the domain document root to the upgraded Laravel `public/` directory.
3. Restrict staging with authentication or IP allowlisting. Use sanitized data, a staging-specific `.env`, a unique stable `APP_KEY`, `APP_DEBUG=false`, secure HTTPS cookies, and test-only integrations.
4. Confirm the PHP web runtime and CLI/Composer use the same target version and required extensions.
5. Deploy a known tested Git commit, install from `composer.lock` with production dependencies, build pinned frontend assets, and apply only reviewed migrations.
6. Smoke-test all four account types and every owner-approved feature. Confirm logs contain no secrets and rollback restores the previous code/database.

**Gate:** Staging passes the test matrix, security review, and restore/rollback rehearsal before production cutover.

## Phase 7: Production cutover and maintenance

1. Schedule a maintenance window and take verified pre-deployment file/database backups.
2. Deploy the exact staging-tested commit and reviewed migrations. Keep production credentials and keys separate from local/staging.
3. Smoke-test authentication, authorization, business-critical flows, mail/MFA, payments, uploads, logs, and background jobs.
4. Monitor errors and database behavior; roll back promptly if an acceptance check fails.
5. Schedule ongoing dependency audits, PHP/Laravel support-window reviews, backups, and restore tests.

## Immediate next actions

1. Confirm the modified session file is excluded from the upgrade commit; do not stage its contents.
2. Inventory all current features and direct Composer dependencies.
3. Continue expanding database-backed regression coverage through remaining Admin user/import/export, Partner payment, user payment, and account/dashboard workflows; use the unchanged `app/` coverage denominator and reach at least 80% line coverage.
4. Set up local PHP 8.5 and begin the Laravel 13 compatibility work only after the baseline is recorded.
5. Review and fix MFA/session handling and Composer advisories before public staging.

## Explicitly out of scope until approved

- Removing or bypassing a feature because it is inconvenient to upgrade.
- Running migrations or seeders against production or the imported working database.
- Changing the current Hostinger site's PHP version or replacing files under `public_html/`.
- Committing passwords, `.env`, logs, session files, SQL dumps, or local credential documentation.
