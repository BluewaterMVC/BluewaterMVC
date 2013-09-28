<?php

/**
 * Controller Command for Bluewater MVC Controllers.
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
 * @subpackage  Front_Controller
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

/**
 * Controller Command for Bluewater MVC Controllers.
 *
 * The Interpreter translates URIs into a command structure which
 * is used by the Dispathcer to determine which Controller::Method
 * is to be called and what parameters are to be sent to that Method.
 *
 * @package     Bluewater_Core
 * @subpackage  Front_Controller
 *
 * @PHPUnit Not Defined
 *
 * @example /examples
 *
 * @author Walter Torres <walter@torres.ws>
 * @version $Revision: 1.0 $
 *
 */
class Bluewater_Controller_Command
{
// ==========================================================
// Class Constants

// ==========================================================
// Class Properties

   /**
    * Singleton instance of this Class Object
    *
    * @var object
    * @access private
    * @static
    *
    * @since 1.0
    */
    private static $_instance = null;

   /**
    * Command Object to handle
    *
    * @var object
    * @access private
    * @static
    *
    * @since 1.0
    */
    private static $_objCommand = null;

// ==========================================================
// Class Methods

   /**
    * Class constructor.
    *
    * This method is 'private' to ensure it is called as a
    * Static Object only.
    *
    * @uses Bluewater_Controller_Command::_url_interpreter()
    *
    * @access private
    * @final This method can not be overloaded
    *
    * @PHPUnit Not Defined
    *
    * @param  void
    * @return void
    */
    private final function __construct()
    {
        // Tear apart the URI
        self::_url_interpreter();
    }

   /**
    * Rips into the HTTP URI value and builds a Command Controller Object
    * containing the Class, its Method to run and any parameters to be
    * sent to that Class Method.
    *
    * GET data is not processed here. GET data can be accessed as usual
    * via the $_GET super global.
    *
    * @uses Bluewater_Controller_Command::_keep_false()
    *
    * @uses Bluewater_Controller_Command::$_objCommand
    *
    * @access private
    * @final This method can not be overloaded
    *
    * @PHPUnit Not Defined
    *
    * @param  void
    * @return void
    */
    private final function _url_interpreter()
    {
        // Standard Object to store Controller, Method and parameters
        self::$_objCommand = new stdClass();

        // First thing, yank any GET data from URI
        // The GET data is dropped. Use $_GET to retrieve this data
        $_requestURI = explode('?', $_SERVER['REQUEST_URI']);

        // pull full URL path given
        $_requestURI = explode('/', $_requestURI[0]);

        // Name of script calling this
        $_scriptName = explode('/',$_SERVER['SCRIPT_NAME']);

        // Join both arrays into a common array
        $_commandArray = array_diff_assoc($_requestURI, $_scriptName);

        // re-index values to ZERO base
        $_commandArray = array_values($_commandArray);

        // remove any empty elements and use callback
        // to keep FALSE and ZERO values
        $_commandArray = array_filter($_commandArray, array($this, '_keep_false'));

        // This is a stanard MVC Controller/View structure
        if ( PATH_BASE === false )
        {
            // URL Structure:
            // 1: controller name
            // 2: action name
            // n: function parameters

            // Define base controller path
            //self::$_objCommand->controllerPath  = APP_CONTROL . '/';

            // First element, if it exists, is the name of the controller to use
            // If it's not defined, set to 'index'
            self::$_objCommand->controllerName = (isset($_commandArray[0]{0})) ? array_shift($_commandArray) : 'index';

            // Next element, if it exists, it the name of the action to take
            // If it's not defined, set to '_default'
            self::$_objCommand->controllerAction = (isset($_commandArray[0]{0})) ? array_shift($_commandArray) : '_default';
        }

        // Otherwise we have a PATH BASED structure
        else
        {
            // URL Structure:
            // 1: section directory name
            // 2: controller directory name
            // 3: method name (option)
            // n: method parameters

            // Define base controller path
            //self::$_objCommand->controllerPath  = MOD_ROOT . '/';

            // First element, if it exists, is the name of the section directory to use
            if (isset($_commandArray[0]{0}))
            {
                // We might need to know this first level later
                $_section_name = $_commandArray[0];

                self::$_objCommand->controllerPath = MOD_ROOT . '/' . array_shift($_commandArray) . '/';
            }
            // If it's not defined
            else
            {
                // We might need to know this first level later
                $_section_name = 'index';

                // set to default index controller
                self::$_objCommand->controllerPath = 'index/';
            }

            //self::$_objCommand->controllerPath .= (isset($_commandArray[0]{0})) ? array_shift($_commandArray) . '/' : 'index/';

            // Next element, if it exists
            if (isset($_commandArray[0]{0}))
            {
                // is the name of the controller directory to use
                self::$_objCommand->controllerPath .=  array_shift($_commandArray) . '/';

                // Next element, if it exists, is the name of the Action to use
                // If it's not defined, set to 'index'
                self::$_objCommand->controllerAction = (isset($_commandArray[0]{0})) ? array_shift($_commandArray) : 'index';
            }
            // If it's not defined
            else
            {
                // set to default index controller for this section
                self::$_objCommand->controllerPath = MOD_ROOT . '/index/';
                self::$_objCommand->controllerAction = 'index';

                // Set the Action to the section name
                self::$_objCommand->controllerAction = $_section_name;

                // Place section name as parameter
                $_commandArray[0] = $_section_name;
            }

            // Default controller to use
            self::$_objCommand->controllerName = 'index';
        }

        // The remaining elements are parameters to the above function,
        // Just pass whatever is left over.
        self::$_objCommand->actionParameters = $_commandArray;

    }

   /**
    * Singleton instance
    *
    * @uses Bluewater_Controller_Command::$_instance
    * @uses Bluewater_Controller_Command::$_objCommand
    *
    * @access public
    * @static
    *
    * @PHPUnit Not Defined
    *
    * @param  boolean $_force      Force a new instance regardless of previous state
    * @return object  $_objCommand Controller Command Object
    */
    public static function getInstance( $_force = false)
    {
        if ( ($_force === true) || (null === self::$_instance) )
        {
            self::$_instance = new self();
        }

        return self::$_objCommand;
    }

   /**
    * Returns a TRUE/FALSE indicating whether given parameter has a value.
    *
    * This is used as a callback for 'array_filter' in order to keep FALSE
    * and ZERO values intack.
    *
    * @access private
    * @final This method can not be overloaded
    *
    * @PHPUnit Not Defined
    *
    * @param  mixed $_value array value to process
    * @return bool whether given parameter has a value
    *
    */
    private final function _keep_false ($_value) {
        return (isset($_value{0}) );
    }

};


// eof
