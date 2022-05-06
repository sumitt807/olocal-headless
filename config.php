<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('ABSPATH')) {
    if ($_SERVER['SERVER_ADDR'] == '104.144.219.2') {
        define('SITE_URL', 'https://headless.olocal.com');
    } else {
        define('SITE_URL', 'http://localhost/olocal-headless');
    }
    define('ABSPATH', dirname(__FILE__) . '/');
    define('TEMPLATE', ABSPATH . "/template//");
    define('PARTIALTEMPLATE', ABSPATH . "/partial-template/");
    define('ASSETS', SITE_URL . "/assets/");
    define('IMG', SITE_URL . "/assets/images/");
    define('CSS', SITE_URL . "/assets/css/");
    define('JS', SITE_URL . "/assets/js/");
    define('INC', ABSPATH . "/inc/");
    define('TABLE_PREFIX', "wptk_");
}

require INC . 'db.php';
require INC . 'helper.php';
