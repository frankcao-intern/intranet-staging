<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/**
 * Site Constants --------------------------------------------------------
 */

/*
 * Permission constants
 */
define("PERM_READ",		            1);//read a page
define("PERM_WRITE",	            2);//edit a page
define("PERM_DELETE",	            4);//delete a page - not applicable to sections
define("PERM_PERM",		            8);//set permissions on a page
define("PERM_PROPERTIES",	        16);//modify the page properties
define("PERM_PAGE_ALL",             31); //sum of all permissions without publish

define("PERM_PUBLISH",	            32);//for sections, means group can publish to this section
define("PERM_ALL",                  63); //sum of all permissions

/**
 * EXCEPTIONS
 */
define('E_AD_EMPTY', 51);
define('E_AD_PWD_EXPIRED', 52);
define('E_AD_LOGIN_FAILED', 53);
define('E_AD_PWD_NEVER_SET', 54);
define('E_AD_RECORD_ACCESS', 55);
define('E_AD_PWD_FORCE_CHANGE', 56);
define('E_AD_PWD_WARN', 57);
define('E_AD_ACCT_LOCKED', 58);

define('E_CAL_PUBLISH', 301);
define('E_CAL_NO_SECTIONS', 302);
define('E_CAL_INVALID_EVENT_ID', 303);

/**
 * Section IDs
 */
define('SID_CALENDAR', 3);
define('SID_WHOS_WHO', 4);
define('SID_PRIVATE', 5);
define('SID_PRIORITIES', 9);
define('SID_CRUD', 11);
define('SID_ADS_PLACEMENTS', 14);
define('SID_PHOTOS', 15);
define('SID_RETAIL', 16);
define('SID_RETAIL_CALENDAR', 17);
define('SID_PRIORITIES_FAQ', 18);
define('SID_ADS', 19);
define('SID_PLACEMENTS', 20);

/*
 * VERSION
 */
define('FISHNET_VERSION', '12.10.1'); //new versioning is yy.m.hotfix (e.g. 12.8.0 = August 2012)

/* End of file constants.php */
/* Location: ./application/config/constants.php */
