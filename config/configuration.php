<?php
session_start();
ob_start();

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('memory_limit', '2048M');

//DATABASE SETTINGS
$config['host'] = 'localhost';
$config['user'] = 'aleem';
$config['pass'] = 'password';
$config['dbname']  = '';

$config['link'] = mysqli_connect($config['host'], $config['user'], $config['pass']);
// Create database
$sql = "CREATE DATABASE IF NOT EXISTS portfolio";
if ($config['link']->query($sql) === TRUE) {
    $config['dbname']  = 'portfolio';
}

$config['db'] = mysqli_select_db($config['link'], $config['dbname']);
$pdo = '';

try {
    $pdo = new PDO("mysql:host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['user'], $config['pass']);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $table1 = 'CREATE TABLE IF NOT EXISTS `portfolios` (
        `id` int(11) AUTO_INCREMENT PRIMARY KEY,
        `title` varchar(255) DEFAULT NULL,
        `user_id` int(11) NOT NULL,
        `description` text,
        `picture` varchar(255) DEFAULT NULL,
        `datetime` datetime DEFAULT CURRENT_TIMESTAMP
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;';

    $table2 = 'CREATE TABLE IF NOT EXISTS `users` (
        `user_id` int(11) AUTO_INCREMENT PRIMARY KEY,
        `name` varchar(150) CHARACTER SET latin1 DEFAULT NULL,
        `password` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
        `email` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
        `isactive` tinyint(1) NOT NULL DEFAULT 1,
        `city` varchar(100) DEFAULT NULL,
        `phone` varchar(20) NOT NULL,
        `status` varchar(20) NOT NULL,
        `dp` varchar(255) DEFAULT NULL,
        `datetime` datetime DEFAULT CURRENT_TIMESTAMP,
        `logintime` datetime DEFAULT NULL
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

    $pdo->exec($table1);
    $pdo->exec($table2);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}


//HTTP REGUEST URLS FOR CSS, IMAGES AND JS
define('SITEURL', "http://" . $_SERVER['SERVER_NAME'] . "/ccproject5/");
echo SITEURL;

// For Admin
define('SOURCEROOT', $_SERVER['DOCUMENT_ROOT'] . '/ccproject5/');
define('SOURCEROOT_ADMIN', $_SERVER['DOCUMENT_ROOT'] . '/ccproject5/');
define('SITEURL_ADMIN', SITEURL . '');
define('ADMIN_ASSETS', SITEURL_ADMIN . 'assets/');

define('ADMIN_IMAGES', SITEURL_ADMIN . 'assets/Images/');

//For ADMIN panel images upload
define('ADMIN_IMAGE', SOURCEROOT . 'assets/Images/');

//For the android API
define('ADMIN_API', SITEURL_ADMIN . 'services/');

//For images upload
define('ROOT_IMAGES', SOURCEROOT . 'assets/Images/');
define('API_KEY', 'c6edce9728343e9ddfbba4dedfdf');

// Call Functions for the site
require SOURCEROOT_ADMIN . "actions/functions.php";
