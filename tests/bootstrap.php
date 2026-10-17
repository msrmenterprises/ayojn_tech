<?php

$testDatabase = getenv('TEST_DB_DATABASE');

if ($testDatabase === false || strpos($testDatabase, '_test_') === false) {
    throw new RuntimeException('TEST_DB_DATABASE must name a dedicated isolated test database.');
}

putenv('DB_CONNECTION=mysql');
putenv('DB_DATABASE=' . $testDatabase);
$_ENV['DB_CONNECTION'] = 'mysql';
$_ENV['DB_DATABASE'] = $testDatabase;
$_SERVER['DB_CONNECTION'] = 'mysql';
$_SERVER['DB_DATABASE'] = $testDatabase;

require __DIR__ . '/../vendor/autoload.php';
