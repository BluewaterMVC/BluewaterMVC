<?php

/**
 * Bluewater MVC Core Bootstrap file.

 * This is where all the pathing and relative information
 * is defined and referenced from.
 *
 * Your application index.php must INCLUDE this file first off to
 * establish relative pathing properly
 *
 * This file should live outside of web accessible directories
 *
 * This file is part of Bluewater MVC.<br />
 * <i>Copyright (c) 2006 - 2010 Walter Torres <walter@torres.ws></i>
 *
 * <b>NOTICE OF LICENSE</b><br />
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.
 * It is also available through the world-wide-web at:
 * {@link http://opensource.org/licenses/osl-3.0.php}.<br />
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@bluewatermvc.org so one can sent to you immediately.
 *
 * <b>DISCLAIMER</b><br />
 * Do not modify to this file if you wish to upgrade Bluewater MVC
 * in the future. If you wish to customize Bluewater MVC for your needs
 * please refer to {@link http://web.bluewatermvc.org} for more information.
 *
 * PHP version 5
 *
 * @category    Bluewater
 * @package     Bluewater_Core
 * @subpackage  Support
 * @link        http://web.bluewatermvc.org
 *
 * @copyright   Copyright (c) 2006 - 2010 Walter Torres <walter@torres.ws>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @filename    $RCSfile: $
 * @version     $Revision: $
 * @modifiedby  $LastChangedBy: $
 * @date        $Date: $
 *
 */


// ******************************************************************
/**
 * Set error reporting level: This sets all error types
 *
 * @link http://us3.php.net/error_reporting
 *
 * E_ALL: All errors and warnings
 *
 * E_STRICT : Run-time notices. Enable to have PHP suggest changes to your
 * code which will ensure the best interoperability and forward compatibility
 * of your code.
 *
 * This should be redefined in production as '0'
 */
error_reporting(E_ALL|E_STRICT);


// ******************************************************************
// ******************************************************************

// No need to change anything below this point.
// Just make sure you know what this is doing if you decide to
// change anything!

// ******************************************************************
// ******************************************************************

// NOTE: The CONSTANTS "SITE_ROOT", "APP_ROOT", "CACHE_ROOT" and "LIBRARY"
//       must be defined in the "index.php" located in your
//       application web root.


/**
 * BLUEWATER defines where all the Bluewater MVC Core files reside.
 *
 * This is a child directory of LIBRARY
 * Do not place a slash at the end of this path.
 *
 * @name     BLUEWATER
 * @constant string
 *
 * @since    1.0
 *
 */
define('BLUEWATER', LIBRARY . '/Bluewater' );

/**
 * MODEL defines where all the Application specific Database support
 * files reside.
 *
 * This is a child directory of APP_ROOT
 * Do not place a slash at the end of this path.
 *
 * @name     MODEL
 * @constant string
 *
 * @since    1.0
 *
 */
define('APP_MODEL', APP_ROOT . '/Model' );

/**
 * CONTROL defines where all the Application specific Controllers
 * and business logic files reside.
 *
 * This is a child directory of APP_ROOT
 * Do not place a slash at the end of this path.
 *
 * @name     CONTROL
 * @constant string
 *
 * @since    1.0
 *
 */
define('APP_CONTROL', APP_ROOT . '/Controller' );

/**
 * VIEW defines where all the Application specific GUI pages reside.
 *
 * This is a child directory of APP_ROOT
 * Do not place a slash at the end of this path.
 *
 * @name     VIEW
 * @constant string
 *
 * @since    1.0
 *
 */
define('APP_VIEW', APP_ROOT . '/View' );

/**
 * TEMPLATE defines where all the Application specific GUI templates reside.
 *
 * This is a child directory of VIEW
 * Do not place a slash at the end of this path.
 *
 * @name     TEMPLATE
 * @constant string
 *
 * @since    1.0
 *
 */
define('APP_TEMPLATE', APP_VIEW . '/Templates' );

/**
 * TEMPLATE defines where all the Application specific GUI templates reside.
 *
 * This is a child directory of VIEW
 * Do not place a slash at the end of this path.
 *
 * @name     TEMPLATE
 * @constant string
 *
 * @since    1.0
 *
 */
define('MOD_ROOT', APP_ROOT . '/Modules' );


// ******************************************************************
// Define default path values in case this is not run via Web Server
$_web_root = '/';
$_login_redirect = false;
$_iis = false;


// This section is only run if the script is called from a webserver
if ( isset($_SERVER['HTTP_HOST']{0}) )
{
   /**
    * Defines relative pathing of the web server
    *
    * Similar to SITE_ROOT, except this defines relative pathing from
    * the web server POV. This allows files to be moved, or the entire
    * site to be relocated and all pathing will be uneffected.
    *
    * @author Walter Torres <walter@torres.ws>
    *
    * @access public
    *
    * @param  string $_web_root default value of web root
    * @return void
    *
    * @since 1.0
    */
    function get_web_root(&$_web_root)
    {
        // just the subfolder part
        $_web_root = dirname($_SERVER['SCRIPT_NAME']);

        // Make sure we don't only have a backslash
        if ( $_web_root == '\\' )
        {
            $_web_root = '';
        }
        else
        {
            // fixes URL problems with some web servers (for some reason, dirname()
            // isn't always consistent with the slash at the end of the DIR name,
            // causing images to become dead)
            if ($_web_root{strlen($_web_root)-1} != '/')
                $_web_root .= '/';
        }

        // Determine which protocol was used
        $_protocol = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https' : 'http';

        // We might not need this
        $port = '';

        // See if the $_SERVER['SERVER_PORT'] was defined
        if ( isset( $_SERVER['SERVER_PORT']{0} ) ) {
            // We don't need PORT if it is using standard port numbers
            if ( (($_SERVER['SERVER_PORT'] != '80' ) && ($_protocol == 'http' ))
            ||  (($_SERVER['SERVER_PORT'] != '443') && ($_protocol == 'https')) ) {
                $port = ':' . $_SERVER['SERVER_PORT'];
            }
        }

        // Define Server name/address
        if ( isset( $_SERVER['HTTP_HOST']{0} ) ) {
            $host = $_SERVER['HTTP_HOST'];
        } else if ( isset( $_SERVER['SERVER_NAME']{0} ) ) {
                $host = $_SERVER['SERVER_NAME'];
            } else if ( isset( $_SERVER['SERVER_ADDR']{0} ) ) {
                    $host = $_SERVER['SERVER_ADDR'];
                } else {
                    $host = 'www.example.com';
        }

        // Build the URL sting
        $_web_root = $_protocol . '://' . $host . $port . $_web_root;

        // Correct DIR BOUNDRIES
        $_web_root = str_ireplace('\\', '/', $_web_root). '/';
    };

    // Where are we?
    get_web_root($_web_root);


   /**
    * Boolean indicating whether this server is IIS or not
    *
    * @name WIN_IIS
    * @var  boolean
    *
    */
    $_iis = ( strstr( $_SERVER['SERVER_SOFTWARE'], 'IIS' ) !== false ) ? true : false;

   /**
    * Web based path to login screen
    *
    * @name LOGIN_REDIRECT
    * @var  string
    *
    */
    $_login_redirect = $_web_root . '/login';

};

/**
 * WIN_IIS indicates if current server is Windows IIS
 *
 * @name     WIN_IIS
 * @constant boolean
 *
 * @since    1.0
 *
 */
define ('WIN_IIS', $_iis );

/**
 * WEB_ROOT defines the actual URL used by the current application.
 *
 * Use this constant to ensure proper pathing on all application links.
 *
 * @name     WEB_ROOT
 * @constant string
 *
 * @since    1.0
 *
 */
define ('WEB_ROOT', $_web_root );

/**
 * JS_PATH defines where Javascript files are to be served from.
 *
 * Use this constant to ensure quick access to the script files.
 *
 * @name     JS_PATH
 * @constant string
 *
 * @since    1.0
 *
 */
define ('JS_PATH', WEB_ROOT . 'js' );

/**
 * CSS_PATH defines where CSS files are to be served from.
 *
 * Use this constant to ensure quick access to the CSS files.
 *
 * @name     CSS_PATH
 * @constant string
 *
 * @since    1.0
 *
 */
define ('CSS_PATH', WEB_ROOT . 'css' );

/**
 * IMAGE_PATH defines where image files are to be served from.
 *
 * Use this constant to ensure quick access to the image files.
 *
 * @name     IMAGE_PATH
 * @constant string
 *
 * @since    1.0
 *
 */
define ('IMAGE_PATH', WEB_ROOT . 'images' );

// Release vars
unset( $_web_root );
unset( $_iis );
unset( $_login_redirect );


// ******************************************************************

// Remove any existing autoloads
spl_autoload_register(null, false);

// specify extensions that may be loaded
spl_autoload_extensions('.php');

/**
 * Class library autoloader using a pseudo namespace format.
 *
 * If a Class is not already loaded within PHP, this magic
 * function will attempt to load the class from within the Library
 * directory.
 *
 * Class names are in a pseudo namespace format: 'First_Second_Third',
 * where each part of the class name is used to build a pathname
 * for the class. The example here would be parsed into:
 *    /library/First/Second/Third.php
 * The last word after the last underscore is assumed to be the
 * actual file name of the class
 *
 * If the class file can't be located, than a dummy class is created
 * and an Exception is thrown.
 *
 * @author Walter Torres <walter@torres.ws>
 *
 * @access   public
 * @uses     Exception
 *
 * @uses LIBRARY path to Library Class files
 *
 * @param  string $_className Class to load
 * @return void
 *
 */
function library_loader ($_className)
{
    $_classPath = LIBRARY . '/'
    . str_replace('_', '/', $_className)
    . '.php';

    // If the file is where we think it should be, load it
    _loader($_classPath, $_className);

};


/**
 * Application Model Class autoloader using a pseudo namespace format.
 *
 * Model Files are located in the model directory under the name of the
 * Database they represent. Each file corresponds to an individual table.
 * The Class name of these "model" files should be defined as:
 *    class {dbName}_{tableName}
 * Using this method, multiple databases can be accessed within a single
 * application. We are only concerned about the first part of the name,
 * before the first underscore '_', all other underscores are ignored.
 *
 * If the class file can't be located, than a dummy class is created
 * and an Exception is thrown.
 *
 * @author Walter Torres <walter@torres.ws>
 *
 * @access   public
 * @uses     Exception
 *
 * @uses MODEL path to Application Model Class files
 *
 * @param  string $_className Class to load
 * @return void
 */
function module_loader ($_className)
{
    // Tear apart class name
    $_parts = explode ( '_', $_className);

    // Put the Class/Table name backtogether
    $_className = implode ('_', $_parts);

    $_classPath = MOD_ROOT    . '/'
    . str_replace('_', '/', $_className)
    . '.php';

    // If the file is where we think it should be, load it
    _loader($_classPath, $_className);

};

/**
 * Application Model Class autoloader using a pseudo namespace format.
 *
 * Model Files are located in the model directory under the name of the
 * Database they represent. Each file corresponds to an individual table.
 * The Class name of these "model" files should be defined as:
 *    class {dbName}_{tableName}
 * Using this method, multiple databases can be accessed within a single
 * application. We are only concerned about the first part of the name,
 * before the first underscore '_', all other underscores are ignored.
 *
 * If the class file can't be located, than a dummy class is created
 * and an Exception is thrown.
 *
 * @author Walter Torres <walter@torres.ws>
 *
 * @access   public
 * @uses     Exception
 *
 * @uses MODEL path to Application Model Class files
 *
 * @param  string $_className Class to load
 * @return void
 */
function model_loader ($_className)
{
    $_classPath = APP_MODEL . '/'
    . str_replace('_', '/', $_className)
    . '.php';

    // If the file is where we think it should be, load it
    _loader($_classPath, $_className);

};


function _loader($_classPath = false, $_className)
{
    if( ($_classPath) && (file_exists($_classPath)) )
    {
        require_once($_classPath);

        // As an aside, if the new class has a 'destruct' method defined,
        // it will be added to the shutdown functions list.
        // This is not to be confused with the magic '__destruct()'
        // method within PHP 5
        if(method_exists($_className,'destruct'))
            register_shutdown_function(array($_className,'destruct'));

    }
};

    // Register the loader functions, but don't throw an exeption on failure
    spl_autoload_register('library_loader', false);
    spl_autoload_register('module_loader', false);
    spl_autoload_register('model_loader', false);


// eof