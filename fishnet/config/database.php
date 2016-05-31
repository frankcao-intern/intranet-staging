<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/
switch(ENVIRONMENT){
	case 'development': {
		$active_group = 'dev';
		break;
	}
	case 'testing': {
		$active_group = 'testing';
		break;
	}
	case 'production': {
		$active_group = 'production';
		break;
	}
}

$active_record = TRUE;

$db['dev']['hostname'] = "localhost";
$db['dev']['username'] = "intranet";
$db['dev']['password'] = "JApvxThGrzRBsVp9";
$db['dev']['database'] = "intranet-dev";
$db['dev']['dbdriver'] = "mysql";
$db['dev']['dbprefix'] = "fn_";
$db['dev']['pconnect'] = TRUE;
$db['dev']['db_debug'] = TRUE;
$db['dev']['cache_on'] = FALSE;
$db['dev']['cachedir'] = '';
$db['dev']['char_set'] = 'utf-8';
$db['dev']['dbcollat'] = 'utf8_general_ci';
$db['dev']['swap_pre'] = '';
$db['dev']['autoinit'] = TRUE;
$db['dev']['stricton'] = FALSE;

$db['import']['hostname'] = "localhost";
$db['import']['username'] = "intranet";
$db['import']['password'] = "JApvxThGrzRBsVp9";
$db['import']['database'] = "import";
$db['import']['dbdriver'] = "mysql";
$db['import']['dbprefix'] = "";
$db['import']['pconnect'] = FALSE;
$db['import']['db_debug'] = TRUE;
$db['import']['cache_on'] = FALSE;
$db['import']['cachedir'] = '';
$db['import']['char_set'] = 'utf-8';
$db['import']['dbcollat'] = 'utf8_general_ci';
$db['import']['swap_pre'] = '';
$db['import']['autoinit'] = TRUE;
$db['import']['stricton'] = FALSE;

$db['testing']['hostname'] = "localhost";
$db['testing']['username'] = "intranet";
$db['testing']['password'] = "JApvxThGrzRBsVp9";
$db['testing']['database'] = "intrane_staging";//
$db['testing']['database'] = "intranet";
$db['testing']['dbdriver'] = "mysql";
$db['testing']['dbprefix'] = "fn_";
$db['testing']['pconnect'] = TRUE;
$db['testing']['db_debug'] = TRUE;
$db['testing']['cache_on'] = FALSE;
$db['testing']['cachedir'] = '';
$db['testing']['char_set'] = 'utf-8';
$db['testing']['dbcollat'] = 'utf8_general_ci';
$db['testing']['swap_pre'] = '';
$db['testing']['autoinit'] = TRUE;
$db['testing']['stricton'] = FALSE;

$db['production']['hostname'] = "localhost";
$db['production']['username'] = "intranet";
$db['production']['password'] = "JApvxThGrzRBsVp9";
$db['production']['database'] = "intranet";
$db['production']['dbdriver'] = "mysql";
$db['production']['dbprefix'] = "fn_";
$db['production']['pconnect'] = TRUE;
$db['production']['db_debug'] = FALSE;
$db['production']['cache_on'] = FALSE;
$db['production']['cachedir'] = '';
$db['production']['char_set'] = 'utf-8';
$db['production']['dbcollat'] = 'utf8_general_ci';
$db['production']['swap_pre'] = '';
$db['production']['autoinit'] = TRUE;
$db['production']['stricton'] = FALSE;

$db['import']['hostname'] = "localhost";
$db['import']['username'] = "intranet";
$db['import']['password'] = "JApvxThGrzRBsVp9";
$db['import']['database'] = "import";
$db['import']['dbdriver'] = "mysql";
$db['import']['dbprefix'] = "";
$db['import']['pconnect'] = FALSE;
$db['import']['db_debug'] = TRUE;
$db['import']['cache_on'] = FALSE;
$db['import']['cachedir'] = '';
$db['import']['char_set'] = 'utf-8';
$db['import']['dbcollat'] = 'utf8_general_ci';
$db['import']['swap_pre'] = '';
$db['import']['autoinit'] = TRUE;
$db['import']['stricton'] = FALSE;

/* End of file database.php */
/* Location: ./application/config/database.php */
