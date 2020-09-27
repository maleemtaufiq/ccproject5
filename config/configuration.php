<?php
session_start();
ob_start();

error_reporting(0);
ini_set('display_errors', 0);
ini_set('memory_limit', '2048M');

// ini_set('session.cookie_domain', '.http://ba.easertechnologies.com');
ini_set("date.timezone", "Asia/Karachi");
date_default_timezone_set("Asia/Karachi");

//DATABASE SETTINGS
$config['host'] = 'easertechnologiescom.ipagemysql.com';
$config['user'] = 'cloud_5';
$config['pass'] = 'cloud_5';
$config['dbname']  = 'portfolio';

$config['link']         = mysqli_connect($config['host'], $config['user'], $config['pass']);
$config['db']             = mysqli_select_db($config['link'], $config['dbname']);

$pdo = '';

try {
    $pdo = new PDO("mysql:host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['user'], $config['pass']);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}


//HTTP REGUEST URLS FOR CSS, IMAGES AND JS
define('SITEURL', "http://" . $_SERVER['SERVER_NAME'] . "/");

// For Admin
define('SOURCEROOT', $_SERVER['DOCUMENT_ROOT'] . '/');
define('SOURCEROOT_ADMIN', $_SERVER['DOCUMENT_ROOT'] . '/');
define('SITEURL_ADMIN', SITEURL . '');
define('ADMIN_ASSETS', SITEURL_ADMIN . 'assets/');
define('ADMIN_GLB', SITEURL_ADMIN . 'assets/global/');
define('ADMIN_LIB', SITEURL_ADMIN . 'assets/libraries/');
define('ADMIN_GLB_PLUGINS', SITEURL_ADMIN . 'assets/global/plugins/');

define('ADMIN_IMAGES', SITEURL_ADMIN . 'assets/Images/');
define('ERROR_IMAGES', SITEURL_ADMIN . 'assets/error/');
define('ADMIN_REPORTS', SITEURL_ADMIN . 'assets/reports/');
define('SIGNATURE_IMG', ADMIN_IMAGES . 'signature/');

//For ADMIN panel images upload
define('ADMIN_IMAGE', SOURCEROOT . 'assets/Images/');
define('ERROR_IMAGE', SOURCEROOT . 'assets/error/');
define('ADMIN_REPORT', SOURCEROOT . 'assets/reports/');

//For admin Internal Pages
define('ADMIN_PAGES', SITEURL_ADMIN . 'pages/');
define('ADMIN_P_DASHBOARD', SITEURL_ADMIN . 'pages/dashboard/');
define('ADMIN_P_USERS', SITEURL_ADMIN . 'pages/brandambassador/');
define('ADMIN_P_REPORTS', SITEURL_ADMIN . 'pages/reports/');
define('ADMIN_P_UPDATES', SITEURL_ADMIN . 'pages/updates/');

//For the android API
define('ADMIN_API', SITEURL_ADMIN . 'android/');

//For images upload
define('ROOT_IMAGES', SOURCEROOT . 'assets/Images/');
define('API_KEY', 'c6edce9728343e9ddfbba4dedfdf');

// Call Functions for the site
require SOURCEROOT_ADMIN . "actions/functions.php";
