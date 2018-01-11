<?php

// Geral
define('IN_FRONT_END', true);
define('TRACK_USER_IP', true);
define('USER_IP', filter_input(INPUT_SERVER, 'REMOTE_ADDR'));

// Caminhos
define('MAIN_PATH', dirname(__FILE__) . '/');
define('CACHE_PATH', MAIN_PATH . 'cache/');
define('INCLUDE_PATH', MAIN_PATH . 'includes/');
define('CLASS_PATH', MAIN_PATH . 'classes/');
define('FUNCTION_PATH', MAIN_PATH . 'functions/');
define('PACKAGE_PATH', MAIN_PATH . 'packages/');
define('UPLOAD_PATH', MAIN_PATH . 'uploaded/');
define('IMAGE_CACHE_PATH', MAIN_PATH . 'uploaded/cache/');
define('UPLOAD_FOLDER', 'uploaded/');

// Banco de Dados
define('DB_DATATYPE', 'mysql');
define('DB_HOSTNAME', 'localhost');
define('DB_DATABASE', 'jnreg870_acessomedico');
define('DB_USERNAME', 'jnreg870');
define('DB_PASSWORD', 'bQO9269toc');
define('DB_DATAPORT', '3306');
define('DB_CHARSET', 'utf8');
define('DB_PREFIX', 'acessomedico_');

// Token
define('MD5_PREFIX', 'e2bfcf822acf92af27aab0d849c99bc4');

/*
|--------------------------------------------------------------------------
| Application Data
|--------------------------------------------------------------------------
|
| These constants are used globally from the application when handling data.
|
*/
define('DB_SLUG_CUSTOMER', 'customer');
define('DB_SLUG_PROVIDER', 'provider');
define('DB_SLUG_ADMIN', 'admin');
define('DB_SLUG_SECRETARY', 'secretary');

define('FILTER_TYPE_PROVIDER', 'provider');
define('FILTER_TYPE_SERVICE', 'service');

define('AJAX_SUCCESS', 'SUCCESS');
define('AJAX_FAILURE', 'FAILURE');

define('SETTINGS_SYSTEM', 'SETTINGS_SYSTEM');
define('SETTINGS_USER', 'SETTINGS_USER');

define('PRIV_VIEW', 1);
define('PRIV_ADD', 2);
define('PRIV_EDIT', 4);
define('PRIV_DELETE', 8);

define('PRIV_APPOINTMENTS', 'appointments');
define('PRIV_CUSTOMERS', 'customers');
define('PRIV_SERVICES', 'services');
define('PRIV_USERS', 'users');
define('PRIV_SYSTEM_SETTINGS', 'system_settings');
define('PRIV_USER_SETTINGS', 'user_settings');

define('DATE_FORMAT_DMY', 'DMY');
define('DATE_FORMAT_MDY', 'MDY');
define('DATE_FORMAT_YMD', 'YMD');

define('MIN_PASSWORD_LENGTH', 7);
define('ANY_PROVIDER', 'any-provider');

define('CALENDAR_VIEW_DEFAULT', 'default');
define('CALENDAR_VIEW_TABLE', 'table');

define('AVAILABILITIES_TYPE_FLEXIBLE', 'flexible');
define('AVAILABILITIES_TYPE_FIXED', 'fixed');

