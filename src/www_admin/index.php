<?php

$debug = $_ENV['DEBUG'] ?? '1';
$debug = $debug === '1';

if($debug) {
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
}

define('BASEPATH', dirname(__DIR__));
define('APPPATH', BASEPATH.DIRECTORY_SEPARATOR.'app');
define('BASEURL', $_ENV['ADMIN_BASEURL'] ?? '/');

require_once APPPATH.'/core/application.php';
new Application(APPPATH.'/router/admin.php');