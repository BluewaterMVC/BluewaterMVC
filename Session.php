<?php

/**
 * PHP Session Control Class.
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
 * @subpackage  Security
 * @link        http://web.bluewatermvc.org
 *
 * @copyright   Copyright (c) 2006 - 2010 Walter Torres <walter@torres.ws>
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @author      Walter Torres <walter@torres.ws>
 * @filename    $RCSfile: $
 * @version     $Revision: $
 * @modifiedby  $LastChangedBy: $
 * @date        $Date: $
 *
 */

/**
 * PHP Session Control Class.
 *
 * Very basic at this point.
 *
 * @package     Bluewater_Core
 * @subpackage  Security
 *
 * @PHPUnit Not Defined
 *
 * @tutorial tutorial.pkg description
 * @example url://path/to/example.php description
 *
 * @todo Built out security measures
 *
 */
class Bluewater_Session
{
// ==========================================================
// Class Constants

// ==========================================================
// Class Properties

   /**
    * Class instance
    *
    * @var object
    * @access private
    * @static
    *
    * @since 1.0
    */
    private static $_instance = false;


// ==========================================================
// Class Methods

   /**
    * Activate Sessions for this script run and built "fingerprint"
    * ID for some level of session security.
    *
    * @uses SALT
    *
    * @access private
    *
    * @PHPUnit Not Defined
    *
    * @param  void
    * @return void
    */
    private function __construct()
    {
       /**
        * SESSIONS *must* be activiated *before* any other HTTP header
        * information is sent to the browser.
        *
        * @link http://us3.php.net/manual/en/function.session-start.php
        */
        session_start();

        // Just to make sure
        $_SERVER['HTTP_USER_AGENT'] = (Isset($_SERVER['HTTP_USER_AGENT']{0})) ? $_SERVER['HTTP_USER_AGENT'] : 'command line';

        // @TODO: Enhance this part to add a bit more security with sessions
        //        and try to limit possible session hijacking
        $_SESSION['fingerprint'] = md5(SALT . $_SERVER['HTTP_USER_AGENT'] . session_id());
    }

   /**
    * Destroys the SESSION just before script shutsdown.
    *
    * @static
    * @final
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  void
    * @return void
    *
    */
    static final public function destruct()
    {
        // Wipe the SESSION super global
        $_SESSION = array();

        // Destroy the session
        session_destroy();
    }

   /**
    * Makes sure that this call is a single instance.
    *
    * @uses Bluewater_Session
    * @uses Bluewater_Session::$_instance
    *
    * @static
    * @final
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  void
    * @return void
    *
    */
    static final public function init()
    {
        // If we don't have an instance, make one
        if ( isset(self::$_instance) )
            self::$_instance = new Bluewater_Session;
    }


   /**
    * Determine if class property has been defined
    *
    * @static
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  string $_property Name of property to access
    * @return boolean $_value   Indicating if property has been defined
    *
    */
    static public function is_set($_property)
    {
        return isset($_SESSION[$_property]);
    }


   /**
    * Removes class property
    *
    * @static
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  string $_property Name of property to remove
    * @return void
    *
    */
    static public function un_set($_property)
    {
        unset($_SESSION[$_property]);
    }


   /**
    * Create or update class property
    *
    * @static
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  string $_property Name of property to create and assign
    * @param  mixed  $_value    Value to assign to new property
    * @return void
    *
    */
    static public function set($_property, $_value)
    {
        $_SESSION[$_property] = $_value;
    }


   /**
    * Retrieve class property
    *
    * @static
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  string $_property Name of property to access
    * @return mixed  $_value    Value of requested property
    *
    */
    static public function get($_property)
    {
        return (isset($_SESSION[$_property])) ? $_SESSION[$_property] : null;
    }


   /**
    * A private 'clone' method it ensure that this singleton class
    * can not be duplicated.
    *
    * @final
    * @access private
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  void
    * @return void
    *
    */
    final private function __clone() {}

};


// eof
