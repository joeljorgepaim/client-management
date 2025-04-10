<?php
ob_start();
session_start();

// Set error reporting in development mode
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files
require_once 'config.php';
require_once 'helpers.php';
require_once 'classes/Database.php';
require_once 'classes/Client.php';
require_once 'classes/Contact.php';

// Autoloader function (if needed for future expansion)
function autoloader($class) {
    $file = 'classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('autoloader');

// Initialize database connection
Database::getInstance();
