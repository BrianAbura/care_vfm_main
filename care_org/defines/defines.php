<?php
/**
 * Systems Definition Script
 * Protected
 * DB Connection details to be changed
 */
date_default_timezone_set('Africa/Nairobi');
require_once('meekrodb.2.3.class.php');
require_once('Logger.php');
//HOSTS
define( 'DB_HOST', 'localhost' );
define( 'DB_USER', 'vms_admin' );
define( 'DB_PASS', 'admin@Carepass23' );
define( 'DB_NAME', 'carevfm_prod' );
//EMAIL
define( 'EMAIL_HOST', 'smtp.gmail.com' );
define( 'EMAIL_USER', 'caug.procurement@gmail.com' );
define( 'EMAIL_PASS', 'unfdaejddqhkzflb' );
define( 'EMAIL_PORT', '465' );
define( 'EMAIL_SECURE', 'ssl' );
//LOG FILE
define( 'LOG_FILE', '/var/log/vfm_logs/vfm_logs.log');

DB::$user = DB_USER;
DB::$password = DB_PASS;
DB::$dbName = DB_NAME;
DB::$host = DB_HOST;
?>
