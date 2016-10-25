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

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');
define('BASE_URL', "http://fxbytes.com/bus_booking/dev");

/*-----------------------------------------------------------------------------------
Custom constants 
------------------------------------------------------------------------------------*/
//Assests Path
define('CSS_PATH',BASE_URL."/assets/css/");
define('JS_PATH',BASE_URL."/assets/js/");
define('IMAGE_PATH',BASE_URL."/assets/images/");
//Upload file path constants 
define('STAFF_UPLOADS',"./public/uploads/staff/");

define('BUS_UPLOADS',"./public/uploads/bus/");

define('USER_UPLOADS',"./public/uploads/user/");


//Date constants 
define('CREATED_DATE','Y-m-d h:i:s');

define('LAST_MODIFIED_DATE','Y-m-d h:i:s');

define('DISPLAY_DATE_FULL','d-m-Y h:i:s');

define('DISPLAY_DATE','d-m-Y');

define('CHANGE_INTO_DATE_FORMAT','Y-m-d');

define('DISPLAY_DATE_STRING','dS M Y');


/*Offline sync parameters*/
define('SYNC_PATH',FCPATH.'sync');	
define('SYNC_DOWNLOAD_PATH',SYNC_PATH.'/download');	
define('SYNC_UPLOAD_PATH',SYNC_PATH.'/upload');	
define('RESOURCE_POST_PIC_PATH', '/var/www/fxbytes.com/bus_booking/dev/'); 
define('PASSWORD_PROTECTED_ZIP',false);
define('SQL_NUM_ROW',500);
define('DEAULT_SYNC_FILES','http://fxbytes.com/bus_booking/dev/');