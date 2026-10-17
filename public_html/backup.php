<?php
// Define database credentials
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'ayojn';

// Define backup directory
$backupDir = './dataBackup/';

// Create backup file name
$backupFile = $backupDir . $dbName . '-' . date('Y-m-d-H-i-s') . '.sql';

// Execute mysqldump command to create a backup
exec("mysqldump --host=$dbHost --user=$dbUser --password=$dbPass $dbName > $backupFile");

// Check if the backup was successful
if (file_exists($backupFile)) {
    echo "Backup created successfully!";
} else {
    echo "Backup failed!";
}
?>
