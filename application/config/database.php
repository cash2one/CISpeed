<?php if (! defined ( 'BASEPATH' )) exit ( 'No direct script access allowed' );
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
		     mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|		     to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|				- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'speedtest';
$active_record = FALSE;

$db ['default'] ['hostname'] = '10.99.82.8';
$db ['default'] ['username'] = 'root';
$db ['default'] ['password'] = 'Et@123';
$db ['default'] ['database'] = 'speed';
$db ['default'] ['dbdriver'] = 'mysqli';
$db ['default'] ['dbprefix'] = '';
$db ['default'] ['pconnect'] = TRUE;
$db ['default'] ['db_debug'] = TRUE;
$db ['default'] ['cache_on'] = FALSE;
$db ['default'] ['cachedir'] = '';
$db ['default'] ['char_set'] = 'utf8';
$db ['default'] ['dbcollat'] = 'utf8_general_ci';
$db ['default'] ['swap_pre'] = '';
$db ['default'] ['autoinit'] = TRUE;
$db ['default'] ['stricton'] = FALSE;

$db ['speedtest'] ['hostname'] = 'localhost';
$db ['speedtest'] ['username'] = 'root';
$db ['speedtest'] ['password'] = '841129hxd';
$db ['speedtest'] ['database'] = 'speed';
$db ['speedtest'] ['dbdriver'] = 'mysqli';
$db ['speedtest'] ['dbprefix'] = '';
$db ['speedtest'] ['pconnect'] = TRUE;
$db ['speedtest'] ['db_debug'] = TRUE;
$db ['speedtest'] ['cache_on'] = FALSE;
$db ['speedtest'] ['cachedir'] = '';
$db ['speedtest'] ['char_set'] = 'utf8';
$db ['speedtest'] ['dbcollat'] = 'utf8_general_ci';
$db ['speedtest'] ['swap_pre'] = '';
$db ['speedtest'] ['autoinit'] = TRUE;
$db ['speedtest'] ['stricton'] = FALSE;

$db ['et'] ['hostname'] = '61.129.44.41';
$db ['et'] ['username'] = 'etloger';
$db ['et'] ['password'] = 'et@123456';
$db ['et'] ['database'] = 'logdb';
$db ['et'] ['dbdriver'] = 'mysqli';
$db ['et'] ['dbprefix'] = '';
$db ['et'] ['pconnect'] = TRUE;
$db ['et'] ['db_debug'] = TRUE;
$db ['et'] ['cache_on'] = FALSE;
$db ['et'] ['cachedir'] = '';
$db ['et'] ['char_set'] = 'utf8';
$db ['et'] ['dbcollat'] = 'utf8_general_ci';
$db ['et'] ['swap_pre'] = '';
$db ['et'] ['autoinit'] = TRUE;
$db ['et'] ['stricton'] = FALSE;

$db ['etguild'] ['hostname'] = '218.30.78.67';
$db ['etguild'] ['username'] = 'etweb';
$db ['etguild'] ['password'] = 'sql@snda123';
$db ['etguild'] ['database'] = 'etguilddb';
$db ['etguild'] ['dbdriver'] = 'mysqli';
$db ['etguild'] ['dbprefix'] = '';
$db ['etguild'] ['pconnect'] = TRUE;
$db ['etguild'] ['db_debug'] = TRUE;
$db ['etguild'] ['cache_on'] = FALSE;
$db ['etguild'] ['cachedir'] = '';
$db ['etguild'] ['char_set'] = 'utf8';
$db ['etguild'] ['dbcollat'] = 'utf8_general_ci';
$db ['etguild'] ['swap_pre'] = '';
$db ['etguild'] ['autoinit'] = TRUE;
$db ['etguild'] ['stricton'] = FALSE;

$db ['etserver'] ['hostname'] = '61.152.98.211';
$db ['etserver'] ['username'] = 'root';
$db ['etserver'] ['password'] = 'HJdr7%_hCFl{';
$db ['etserver'] ['database'] = 'EtManageDB';
$db ['etserver'] ['dbdriver'] = 'mysqli';
$db ['etserver'] ['dbprefix'] = '';
$db ['etserver'] ['pconnect'] = TRUE;
$db ['etserver'] ['db_debug'] = TRUE;
$db ['etserver'] ['cache_on'] = FALSE;
$db ['etserver'] ['cachedir'] = '';
$db ['etserver'] ['char_set'] = 'utf8';
$db ['etserver'] ['dbcollat'] = 'utf8_general_ci';
$db ['etserver'] ['swap_pre'] = '';
$db ['etserver'] ['autoinit'] = TRUE;
$db ['etserver'] ['stricton'] = FALSE;

$db['ttmobile']['hostname'] = '10.168.10.170';
$db['ttmobile']['username'] = 'mysql';
$db['ttmobile']['password'] = 'mysql';
$db['ttmobile']['database'] = 'test';
$db['ttmobile']['dbdriver'] = 'mysqli';
$db['ttmobile']['dbprefix'] = '';
$db['ttmobile']['pconnect'] = TRUE;
$db['ttmobile']['db_debug'] = TRUE;
$db['ttmobile']['cache_on'] = FALSE;
$db['ttmobile']['cachedir'] = '';
$db['ttmobile']['char_set'] = 'utf8';
$db['ttmobile']['dbcollat'] = 'utf8_general_ci';
$db['ttmobile']['swap_pre'] = '';
$db['ttmobile']['autoinit'] = TRUE;
$db['ttmobile']['stricton'] = FALSE;

$db['ttmobilesysmsg']['hostname'] = '10.168.36.218';
$db['ttmobilesysmsg']['username'] = 'umysql';
$db['ttmobilesysmsg']['password'] = 'pmysql';
$db['ttmobilesysmsg']['database'] = 'test';
$db['ttmobilesysmsg']['dbdriver'] = 'mysqli';
$db['ttmobilesysmsg']['dbprefix'] = '';
$db['ttmobilesysmsg']['pconnect'] = TRUE;
$db['ttmobilesysmsg']['db_debug'] = TRUE;
$db['ttmobilesysmsg']['cache_on'] = FALSE;
$db['ttmobilesysmsg']['cachedir'] = '';
$db['ttmobilesysmsg']['char_set'] = 'utf8';
$db['ttmobilesysmsg']['dbcollat'] = 'utf8_general_ci';
$db['ttmobilesysmsg']['swap_pre'] = '';
$db['ttmobilesysmsg']['autoinit'] = TRUE;
$db['ttmobilesysmsg']['stricton'] = FALSE;


$db['ttmobileback']['hostname'] = '10.168.10.169';
$db['ttmobileback']['username'] = 'mysql';
$db['ttmobileback']['password'] = 'mysql';
$db['ttmobileback']['database'] = 'test';
$db['ttmobileback']['dbdriver'] = 'mysqli';
$db['ttmobileback']['dbprefix'] = '';
$db['ttmobileback']['pconnect'] = TRUE;
$db['ttmobileback']['db_debug'] = TRUE;
$db['ttmobileback']['cache_on'] = FALSE;
$db['ttmobileback']['cachedir'] = '';
$db['ttmobileback']['char_set'] = 'utf8';
$db['ttmobileback']['dbcollat'] = 'utf8_general_ci';
$db['ttmobileback']['swap_pre'] = '';
$db['ttmobileback']['autoinit'] = TRUE;
$db['ttmobileback']['stricton'] = FALSE;

$db['ttmobilelogin']['hostname'] = '10.168.36.219';
$db['ttmobilelogin']['username'] = 'mysql';
$db['ttmobilelogin']['password'] = 'mysql';
$db['ttmobilelogin']['database'] = 'test';
$db['ttmobilelogin']['dbdriver'] = 'mysqli';
$db['ttmobilelogin']['dbprefix'] = '';
$db['ttmobilelogin']['pconnect'] = TRUE;
$db['ttmobilelogin']['db_debug'] = TRUE;
$db['ttmobilelogin']['cache_on'] = FALSE;
$db['ttmobilelogin']['cachedir'] = '';
$db['ttmobilelogin']['char_set'] = 'utf8';
$db['ttmobilelogin']['dbcollat'] = 'utf8_general_ci';
$db['ttmobilelogin']['swap_pre'] = '';
$db['ttmobilelogin']['autoinit'] = TRUE;
$db['ttmobilelogin']['stricton'] = FALSE;

$db['vipkf']['hostname'] = '10.99.82.20';
$db['vipkf']['username'] = 'agentweb';
$db['vipkf']['password'] = 'password';
$db['vipkf']['database'] = 'test';
$db['vipkf']['dbdriver'] = 'mysqli';
$db['vipkf']['dbprefix'] = '';
$db['vipkf']['pconnect'] = TRUE;
$db['vipkf']['db_debug'] = TRUE;
$db['vipkf']['cache_on'] = FALSE;
$db['vipkf']['cachedir'] = '';
$db['vipkf']['char_set'] = 'utf8';
$db['vipkf']['dbcollat'] = 'utf8_general_ci';
$db['vipkf']['swap_pre'] = '';
$db['vipkf']['autoinit'] = TRUE;
$db['vipkf']['stricton'] = FALSE;

$db['monitor']['hostname'] = '10.34.23.38';
$db['monitor']['port'] = 3307;
$db['monitor']['username'] = 'snda';
$db['monitor']['password'] = 'sndaok';
$db['monitor']['database'] = 'db_recorder_monitor';
$db['monitor']['dbdriver'] = 'mysqli';
$db['monitor']['dbprefix'] = '';
$db['monitor']['pconnect'] = TRUE;
$db['monitor']['db_debug'] = TRUE;
$db['monitor']['cache_on'] = FALSE;
$db['monitor']['cachedir'] = '';
$db['monitor']['char_set'] = 'utf8';
$db['monitor']['dbcollat'] = 'utf8_general_ci';
$db['monitor']['swap_pre'] = '';
$db['monitor']['autoinit'] = TRUE;
$db['monitor']['stricton'] = FALSE;




/* End of file database.php */
/* Location: ./application/config/database.php */