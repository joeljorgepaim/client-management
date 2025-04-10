<?php
ob_start();
session_start();


error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'config.php';
require_once 'helpers.php';
require_once 'classes/Database.php';
require_once 'classes/Client.php';
require_once 'classes/Contact.php';


function autoloader($class) {
    $file = 'classes/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('autoloader');


Database::getInstance();
