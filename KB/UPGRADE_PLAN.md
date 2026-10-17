# Laravel and PHP Upgrade Plan

**Status:** Planning  
**Working branch:** `phase4_upgrades`
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

**Source verification checkpoint (2026-10-17):** `RouteServiceProvider` loads `routes/web.php`, `routes/user.php`, `routes/controlpanel.php`, and `routes/api.php`; the 19 inventory rows group the currently mapped workflows. The active web routes expose `GET /clear-cache`, `GET /linkstorage`, `GET /cron`, and `ANY /cron/` under the `web` middleware without an authentication middleware. The scheduler registers `cron:active` every minute, `delete:old-cart-wishlist` daily, and `upgrade:user` twice daily; `/cron` invokes `cron:collaboration`, while `/cron/` invokes the daily bid-mail handler. Keep the operational endpoints blocked for owner review and do not change production access as part of this checkpoint. Host-level scheduler invocation, data effects, route access decisions, complete table mappings, and workflow approval remain open.

## Phase 2: Build a baseline regression suite and measure coverage

### Test environment

- Use a dedicated testing database with sanitized data. Never run test setup, refreshes, or seeders against the imported working database or production.
- Keep mail, queue, cache, and sessions isolated in tests. Use fakes or local test drivers; do not send real email or payment requests.
- Add or update factories/fixtures for each role and business entity without embedding real account credentials.

### Baseline test and coverage status (2026-10-17)

- Runtime: PHP 7.4.33, Laravel 5.6.40, and PHPUnit 7.5.20. PHPUnit uses the isolated MySQL test database and refuses to proceed unless the configured database name contains `_test_`.
- The test database contains the 59-table legacy schema only; source rows were not copied. Synthetic button/configuration rows support page rendering, and feature tests create their own user fixtures inside database transactions.
- `phpunit.xml` scopes coverage to `app/` and excludes nine non-runtime backup PHP files that redeclare active classes.
- On the `test-coverage-admin-workflows` branch, the current full-suite checkpoint passes: **151 tests, 705 assertions**. Coverage includes authentication/MFA/logout/reset, user profile and onboarding, offer/receive marketplace and bid responses, wallet voucher redemption/bank details/history/query/feedback/cancellation, referral and notification workflows, opportunity reservation/vouch/renewal/payment workflows, events and collaborations, partner offer/profile/order/payment workflows, admin profile/password/redemption/bid/voucher/review and user import/export/status/profile/beneficiary/bank-detail workflows, user bid creation/response/voucher activation/payment states, direct-payment initiation/status/callback workflows, marketplace cart/wishlist/checkout, and dashboard map data.
- PHP 7.4's `phpdbg` is the working coverage driver; neither Xdebug nor PCOV is loaded. The current full-suite line-coverage report is **32.34% (2,518/7,786 executable lines)**, up from the original 1.40% baseline. Classes are 13.75% and methods are 24.43%. Current coverage for the larger workflow controllers is `UserAuth\userController` 52.12% (541/1,038 lines), `UserAuth\BidController` 48.82% (372/762), `UserAuth\XsquareController` 18.96% (109/575), `Controlpanel\UserController` 34.84% (108/310), `ControlpanelAuth\UserController` 79.42% (193/243), `ControlpanelAuth\BidController` 62.74% (165/263), and `Partner\UserController` 64.12% (243/379).
- The requested **80% whole-application line-coverage target is not met**. Most partner, admin, account, payment, and integration paths remain uncovered; live external side effects have not been exercised.
- Regression tests exposed existing failures: unverified-account rejection cleared the user guard but left the shared web guard authenticated; partner-offer validation accessed validator errors incorrectly and returned HTTP 500; duplicate opportunity vouching incremented the free-vouch count and cleared the paid flag; admin and user bid payment used invalid state assignments; partner wallet payment referenced an undefined bid ID; partner payment gateway errors were not handled and could leave active unpaid offers; direct-payment initiation executed the gateway request twice and emitted debug output; user bid negotiation allowed invalid state transitions; the dashboard map passed an undefined variable to `compact()`, causing HTTP 500; the admin suspension endpoint returned a misspelled status field; and imported users were saved without valid membership-tier and verified-email values. These paths are fixed and covered by tests.

### Owner decision on coverage (2026-10-17)

- Pause work whose primary purpose is raising whole-application coverage to 80%; revisit this target later.
- This is a deferral, not a waiver or completion: retain the 80% target and the current baseline of **151 tests, 705 assertions, and 32.34% line coverage (2,518/7,786 executable lines)**. Keep the configured `app/` source set unchanged.
- Continue running the full test suite after code changes and add focused regression tests for changed security-, payment-, authorization-, and data-sensitive behavior. Coverage expansion remains an open gate and must not be described as passed.

### Test scope

1. Authentication and authorization for each of the four account types: valid/invalid credentials, MFA valid/invalid, session persistence, logout, password reset, and access to role-protected routes.
2. The core Offer, Receive, Partner, and Admin workflows identified in Phase 1, including validation and authorization boundaries.
3. Database behavior for important records, relationships, imports, and migrations using a test database.
4. Payment, CAPTCHA, email, PDF, spreadsheet, upload, scheduled-task, and queue behavior. Use sandbox/fakes where appropriate, but retain feature coverage unless a deferral is approved.
5. Regression cases for existing password hashes and imported data so existing users and records remain usable after the upgrade.

### Coverage approach

- Keep the source set and denominator stable; the target is at least **80% whole-application line coverage** for `app/`, not a narrowed subset.
- Run the full suite against the isolated test database after code changes. Refresh the line-coverage report when coverage work resumes and at framework-upgrade milestones. Do not claim the target until the suite passes and the same configured source set reports at least 80%.
- Add tests by workflow, prioritizing decision paths for authentication, authorization, payments, data changes, and the remaining user/partner/admin features. Use synthetic fixtures, fakes, and sandbox integrations; never copy production rows into test fixtures.
- Upgrade PHPUnit and the coverage driver together with the framework/PHP target; keep the same critical test cases running after each upgrade step.

**Gate:** A repeatable suite passes against the isolated database, and the stable whole-application source set reaches at least 80% line coverage. The baseline suite passes, but this coverage gate remains open and is deferred by the owner as of 2026-10-17.

## Phase 3: Remove authentication and deployment blockers

1. Replace all hard-coded MFA bypasses with a deliberate, testable MFA flow. No fixed OTP may remain in production code.
2. Stop storing a plaintext password in the partner MFA session. Use a short-lived, purpose-limited challenge/session design and test expiry, replay, invalid OTP, and session renewal.
3. Confirm production mail/MFA configuration independently from local `MAIL_DRIVER=log`.
4. Review the Composer audit output package by package, prioritize critical/high advisories and reachable application features, and record the selected fix or accepted risk.
5. Re-run tests and `composer audit` after each dependency milestone.

**Authentication implementation checkpoint (2026-10-17):** Phase 3 items 1 and 2 are implemented on `phase3_upgrades`. Active user, partner, and admin login flows use six-digit, session-bound MFA challenges with five-minute expiry, hashed codes, a five-attempt limit, and one-time consumption. Partner/admin passwords are no longer retained in the session; partner email verification requires the user to sign in rather than silently authenticating from a saved password. Control-panel login and MFA delivery management are limited to `role_id=1`, matching the `isAdmin` gate. The Control Panel Configuration page manages one shared demo inbox and an allowlist of active non-admin accounts. MFA goes to an allowlisted account's valid registered address and the shared inbox (the shared copy is BCC); unlisted users receive mail only at their own address. The allowlist is empty by default, invalid inbox settings fail explicitly, and MFA is never bypassed. Focused authentication/admin tests pass (32 tests, 140 assertions), the schema baseline tests pass (2 tests, 46 assertions), and the full PHPUnit suite passes (161 tests, 786 assertions). The additive MFA migration has been applied and verified on the local database; production migration and end-to-end mail/MFA verification remain open, as does dependency-advisory disposition.

**Gate:** Authentication has no fixed bypass or plaintext password session storage, and unresolved advisories have a documented, approved disposition before any public staging.

### Phase 3 readiness review checkpoint (2026-10-17)

- `composer audit --locked --no-dev` reports **80 advisories across 15 locked production packages**: 6 critical, 25 high, 43 medium, and 6 low. Package versions, direct/transitive status, severity counts, application touchpoints, and required upgrade/test follow-up are recorded in `KB/dependency_advisory_review.csv`.
- Critical/high findings include the Dompdf/SVG rendering chain and PhpSpreadsheet import/export chain; additional findings affect Laravel 5.6 and its Symfony/Guzzle dependencies. The inventory identifies PDF generation, spreadsheet import/export, and agent API calls as application touchpoints requiring regression tests.
- No dependency was changed in this readiness branch, no advisory was treated as an accepted risk, and no production migration or mail configuration was changed. Each finding remains open for remediation and verification during the staged framework/dependency upgrade before staging or production.
- The audit command exits nonzero because advisories are present; this is an audit finding, not a passing security gate.

## Phase 4: Upgrade PHP and Laravel on `phase4_upgrades`

1. **Complete (2026-10-17):** PHP 8.5.11 is installed from Homebrew Core alongside the runnable PHP 7.4.33 baseline. Use `/opt/homebrew/opt/php/bin/php` for PHP 8.5 and `/opt/homebrew/opt/php@7.4/bin/php` for PHP 7.4; do not change the baseline interpreter implicitly.
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

**Phase 4 checkpoint (2026-10-17):** Work is on `phase4_upgrades`, based on `main` at `4f8c202`. The first framework increment is complete locally: Laravel 5.6.40 → 5.7.29, using the official [Laravel 5.7 upgrade guide](https://laravel.com/docs/5.7/upgrade). The custom user password-reset handler now passes the request to Laravel's updated reset-response method, and the Laravel 5.7 cache `.gitignore` layout is in place. The full PHP 7.4.33 suite passes (**161 tests, 786 assertions**) and the updated lock passes PHP 7.4 platform checks.

- The PHP 8.5.11 runtime and required extensions are present, but the 5.7.29 lock correctly rejected PHP 8.5 because that Laravel version requires PHP `^7.1.3`. The 5.8.38 lock has the same PHP constraint. Do not claim PHP 8.5 app compatibility until a later Laravel step supports it; keep running the app suite with the explicit PHP 7.4 binary in the meantime.
- The Laravel 5.7 dependency resolution required Composer's `--no-blocking` flag for this local update because the framework requires advisory-flagged Carbon 1. The flag was used only for that command; no persistent advisory bypass was configured and no risk was accepted. Re-audit before each checkpoint and keep all unresolved findings blocked from staging/production.
- At the 5.7 checkpoint, the lock reported 78 advisories across 13 production packages (7 critical, 24 high, 42 medium, 5 low). Laravel 5.7 also required Guzzle 6, so Guzzle moved from 7.9.2 to 6.5.8; the agent API integration still needs a focused regression check.

**Second Phase 4 checkpoint (2026-10-17):** Laravel 5.7.29 → 5.8.38 and Yajra Laravel DataTables 8.13.7 → 9.21.2, following the official [Laravel 5.8 upgrade guide](https://laravel.com/docs/5.8/upgrade). Yajra 9 is required because version 8 only supports Illuminate 5.4–5.7. Password registration and reset now use the owner-approved eight-character minimum; tests cover rejection of short passwords, acceptance of exactly eight characters at the registration validation boundary, the exact eight-character user-reset boundary, and rejection of short admin resets. The authenticated DataTables JSON endpoint tests also pass under Yajra 9. The full suite passes (**167 tests, 806 assertions**), `composer validate` passes, platform requirements pass on PHP 7.4.33, and Artisan boots as Laravel 5.8.38.

- The PHP 8.5.11 lock check passes all required extensions but fails the PHP requirement because Laravel 5.8.38 still requires `^7.1.3`. Continue using PHP 7.4.33 for the app until a later framework version supports PHP 8.5.
- `composer audit --locked --no-dev` now reports **77 advisories across 13 production packages** (7 critical, 23 high, 42 medium, 5 low); the advisory matrix records the current lock. Composer's `--no-blocking` was used only for the local dependency resolution because Carbon 1 remains advisory-flagged; no persistent bypass or risk acceptance was added.
- Carbon remains at 1.39.1 to avoid combining the Carbon 2 migration with this Laravel step. Cache TTL/lock API searches found no application call sites requiring changes for the 5.8 cache behavior update.
- **Follow-up complete in the working tree (2026-10-17):** the signup foreign-key failure was caused by active user, half-registration, and partner registration paths omitting `user_type`. They now assign the lowest configured user type, matching the existing admin-import behavior, and return an explicit error if none is configured. Regression tests cover successful creation and the assigned type across all three flows. The full suite now passes (**169 tests, 814 assertions**).

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

1. Keep the 80% coverage target deferred, not passed; run the full PHPUnit suite and focused regression tests for changed code while coverage expansion is paused.
2. **Complete:** Addressed the Phase 3 fixed-MFA and plaintext-password session blockers with short-lived challenges and focused authentication regression tests. Local schema and application regression checks pass; production mail/MFA verification remains outstanding.
3. Before production validation, apply the additive migration through the approved deployment process and configure the production mail transport and admin-managed demo inbox/account list. The critical/high Composer advisories have been inventoried package by package in `KB/dependency_advisory_review.csv`; remediation or explicit approved-risk decisions remain outstanding.
4. Finish and obtain approval for the workflow inventory, including remaining guard/role boundaries, legacy table mappings, payment integrations, scheduled tasks, and the public operational/storage endpoints.
5. **In progress:** Local framework upgrades have reached Laravel 5.8 on PHP 7.4. Continue one version step at a time and preserve the PHP 7.4 baseline until the framework and dependency graph support PHP 8.5; inventory approval, advisory disposition, and production checks remain gates before staging.

## Explicitly out of scope until approved

- Removing or bypassing a feature because it is inconvenient to upgrade.
- Running migrations or seeders against production or the imported working database.
- Changing the current Hostinger site's PHP version or replacing files under `public_html/`.
- Committing passwords, `.env`, logs, session files, SQL dumps, or local credential documentation.
