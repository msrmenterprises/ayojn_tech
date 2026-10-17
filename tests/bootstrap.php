<?php

$databasePath = tempnam(sys_get_temp_dir(), 'ayojn-phpunit-');

if ($databasePath === false) {
    throw new RuntimeException('Unable to create an isolated PHPUnit database.');
}

register_shutdown_function(static function () use ($databasePath) {
    if (is_file($databasePath) && !unlink($databasePath)) {
        error_log('Unable to remove the temporary PHPUnit database: ' . $databasePath);
    }
});

putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=' . $databasePath);
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = $databasePath;
$_SERVER['DB_CONNECTION'] = 'sqlite';
$_SERVER['DB_DATABASE'] = $databasePath;

$database = new PDO('sqlite:' . $databasePath);
$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$database->exec(
    'CREATE TABLE buttons_setting (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        page_id TEXT NOT NULL,
        button_name TEXT NOT NULL,
        button_icon TEXT NOT NULL,
        class_name TEXT NOT NULL,
        button_hover TEXT NOT NULL
    )'
);
$database->exec(
    "INSERT INTO buttons_setting (page_id, button_name, button_icon, class_name, button_hover)
    VALUES ('home_page', 'Test action', 'test-icon', 'test-action', 'Test action')"
);
$database->exec(
    "INSERT INTO buttons_setting (page_id, button_name, button_icon, class_name, button_hover)
    VALUES
        ('header', 'Header action 1', 'test-icon', 'test-action', 'Header action 1'),
        ('header', 'Header action 2', 'test-icon', 'test-action', 'Header action 2'),
        ('header', 'Header action 3', 'test-icon', 'test-action', 'Header action 3')"
);
$database->exec(
    'CREATE TABLE countries (
        id INTEGER PRIMARY KEY AUTOINCREMENT
    )'
);
$database->exec(
    'CREATE TABLE industries (
        id INTEGER PRIMARY KEY AUTOINCREMENT
    )'
);
$database->exec(
    'CREATE TABLE users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT,
        password TEXT
    )'
);

require __DIR__ . '/../vendor/autoload.php';
