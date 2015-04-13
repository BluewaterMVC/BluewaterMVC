<?php

/**
 * Front Controller Dispatch for Bluewater MVC Controllers.
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
 * Front Controller Dispatch for Bluewater MVC Controllers.
 *
 * Dispatcher translates URIs to determine which Controller::Method
 * is to be used and what parameters are to be sent to that Method.
 *
 * Based upon some ideas of Doug Hill
 * http://www.phpaddiction.com/tags/axial/url-routing-with-php-part-one/
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
class Bluewater_Dispatcher
{
// ==========================================================
// Class Constants

// ==========================================================
// Class Properties

   /**
    * Controller Command Object
    *
    * @var object
    * @access private
    * @static
    *
    * @since 1.0
    */
    private static $_objCommand;


// ==========================================================
// Class Methods

   /**
    * Class constructor.
    *
    * This method is 'private' to ensure it is called as a
    * Static Object only.
    *
    * @uses Bluewater_Controller_Command::getInstance()
    *
    * @access private
    * @final This method can not be overloaded
    *
    * @uses Bluewater_Controller_Command::getInstance() Used to parse URI for Command Control
    *
    * @PHPUnit Not Defined
    *
    * @param void
    * @return void
    */
    private final function __construct()
    {
        // Define Class Command Object
        self::$_objCommand = Bluewater_Controller_Command::getInstance();

    }

   /**
    * Dispatch Controller Class
    *
    * @uses Bluewater_Dispatcher::$_objCommand
    * @uses Bluewater_Dispatcher::_load_controller()
    *
    * @static
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @param void
    * @return void
    */
    static public function Dispatch()
    {
        // See if this object has been instantiated already
        if (self::$_objCommand === null)
            // If not, instantiate here
            new self();

        // Attempt to load Controller
        self::_load_controller();

        // Instantiate New Controller
        $_controllerClass = ucfirst(strtolower(self::$_objCommand->controllerName)) . '_Controller';
        $_controller = new $_controllerClass(self::$_objCommand);

        // If 'action' (method) does not exist,
        // or we can't even use it...
        if( (self::$_objCommand->controllerAction === false)
         || (! is_callable(array($_controller, self::$_objCommand->controllerAction))) )
        {
            // Place 'action' back on parameter stack since it is not a class method,
            // the default method might need it
            if ( self::$_objCommand->controllerAction{0} )
                array_unshift(self::$_objCommand->actionParameters, self::$_objCommand->controllerAction);

            //  set it to '_default'
            self::$_objCommand->controllerAction = '_default';
        }

        // What action is called
        $_actionName = self::$_objCommand->controllerAction;

        // Run the 'action' (method) and pass any parameters we might have
        $_controller->$_actionName(self::$_objCommand->actionParameters);

        // Display the view
        $_controller->view->render($_controller);
    }


   /**
    * Attempts to auto-load defined Controller Class file
    *
    * @uses Bluewater_Dispatcher::_load_controller()
    * @uses Bluewater_Dispatcher::$_objCommand
    * @uses CONTROL
    *
    * @static
    * @final This method can not be overloaded
    * @access private
    *
    * @PHPUnit Not Defined
    *
    * @param void
    * @return void
    */
    static final private function _load_controller()
    {
        // Build Controller Path
        if ( isset(self::$_objCommand->controllerPath{0}) )
            $_controller_path = self::$_objCommand->controllerPath . self::$_objCommand->controllerName . '.php';
        else
            $_controller_path = APP_CONTROL . '/' . self::$_objCommand->controllerName . '.php';

        // Load that controller, if we can
        if (file_exists($_controller_path))
        {
            require_once($_controller_path);
        }
        // Otherwise THROW an error
        else
        {
            // Now we THROW an exception to handle this failure on our own terms.
            //throw new Exception('"' . self::$_objCommand->controllerName . '" Controller not found');

            // If not, set an error
            self::$_objCommand->controllerPath = '';
            self::$_objCommand->controllerName  = 'index';

            // Load Error controller
            self::_load_controller();
        }
    }   // private function load_controller()


   /**
    * Allows control to pass from one controller to another
    * without having to perform a direct URL request
    *
    * @uses Bluewater_Controller_Command::getInstance()
    * @uses Bluewater_Dispatcher::_load_controller()
    * @uses Bluewater_Dispatcher::$_objCommand
    *
    * @access public
    * @final This method can not be overloaded
    *
    * @PHPUnit Not Defined
    *
    * @param string $_fakeURL fake URL for Dispatch to process
    * @return void
    */
    final public function Internal( $_fakeURL = false)
    {
        // Rebuild new URI
        $_SERVER['REQUEST_URI'] = '/' . $_fakeURL;

        // Call the URI Interpreter by hand to force Controller access
       self::$_objCommand = Bluewater_Controller_Command::getInstance();

        // Attempt to load Controller
        self::_load_controller();

        // Instantiate New Controller
        $_controllerClass = ucfirst(strtolower(self::$_objCommand->controllerName)) . '_Controller';
        $_controller = new $_controllerClass(self::$_objCommand);

        // What action is called
        $_actionName = self::$_objCommand->actionName;

        // Run the action and pass any parameters we might have
        $_controller->$_actionName(self::$_objCommand->actionParameters);

        // This is a kludge to access a second Controller from a first.
        // We will assume that the VIEW for this controler method is not
        // used, just its business logic.
        // NO VIEW CALL

        return;
    }

};

// eof
