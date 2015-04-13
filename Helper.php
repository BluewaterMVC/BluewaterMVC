<?php

/**
 * Controller Helper Class
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
 * @subpackage  Helper
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
 * Controller Helper Class
 *
 * Multiline description can go here and
 * it will be picked up as written.
 * DON'T!! but a blank line betwen the desc text and the CVS/SVN
 * info below, unless you don't this info in your phpDocs
 *
 * @package     Bluewater_Core
 * @subpackage  Helper
 *
 * @PHPUnit Not Defined
 *
 * @example /examples
 *
 * @author Walter Torres <walter@torres.ws>
 * @version $Revision: 1.0 $
 *
 */
class Bluewater_Helper extends Bluewater_Singleton_Abstract
{
// ==========================================================
// Class Constants

// ==========================================================
// Class Properties

   /**
    * Helper Class name
    *
    * @var string
    * @access private
    * @static
    *
    * @since 1.0
    */
    private $_class = '';


// ==========================================================
// Class Methods

   /**
    * Class Constructor is empty on purpose
    *
    * @access public
    * @final
    *
    * @param void
    * @return void
    *
    */
    public final function __construct()
    {
        // Empty on purpose
    }

   /**
    * Magical PHP Method to call unknown methods within
    * an unknown class.
    *
    * @author Walter Torres <walter@torres.ws>
    *
    * @uses Bluewater_Helper::_loadHelper()
    * @uses Bluewater_Helper::$_class
    *
    * @final
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  string $_method Method to call
    * @param  array  $_args   An array of arguements to send to method
    * @return mixed  $helper  Whatever operation Helper Class performs
    *
    */
    public final function __call($_method, $_args)
    {
        // Attempt to load Controller
        $this->_loadHelper($_method);

        // Attempt to instantiate new class object
        if(class_exists($this->_class, false))
        {
            $_class = new $this->_class;

            // Call Method with parameters
            return $_class->helper($_args);
        }
        else
        {
            // Now we THROW an exception to handle this failure on our
            // own terms.
            throw new Exception('Helper: ' . $_method . ' method could be located.');
        }
    }

   /**
    * Load HELP Class files
    *
    * Each file corresponds to an individual Helper Method.
    * The Class name of these "helper" files should be defined as:
    *    class Helper_{Helper_Name}
    * Each Helper class must have a "helper" method defined. This
    * method does the setup or actual work.
    *
    * @uses Bluewater_Helper::_upper_first()
    * @uses Bluewater_Helper::$_class
    * @uses APP_ROOT
    * @uses BLUEWATER
    *
    * @access private
    * @final
    *
    * @param string $_helperName Name of Helper Class to utilize
    * @return void
    */
    private final function _loadHelper($_helperName)
    {
        // Tear apart helper name, capitalize first letter, and put back together
        $_filename = implode ('_', array_map(array($this,'_upper_first'), explode( '_', $_helperName)) );

        // Create Class name
        $this->_class = 'Helper_' . $_filename;

        $_appHelperPath  = APP_ROOT  . '/Helper/' . $_filename . '.php';
        $_blueHelperPath = BLUEWATER . '/Helper/' . $_filename . '.php';

        // Try to load Application level Helper first, as this will supersede
        // Bluewater MVC Library Helpers
        if(file_exists($_appHelperPath))
        {
            require_once($_appHelperPath);
        }
        // Otherwise try to load Bluewater Library Helper
        else if(file_exists($_blueHelperPath))
        {
            require_once($_blueHelperPath);
        }

        // As an aside, if the new class has a 'destruct' method defined,
        // it will be added to the shutdown functions list.
        // This is not to be confused with the magic '__destruct()'
        // method within PHP 5
        if(method_exists($_helperName, 'destruct'))
            register_shutdown_function(array($_helperName, 'destruct'));
    }

   /**
    * Helper class to capitalize first letter of sentence/word.
    *
    * Simple quick function to make a sentence/word into a Sentence Case,
    * first letter is capitalize all others are lower case. Yes, PHP
    * has "ucfirst()", but it has a flaw; it does not effect any other
    * letters of a given string. If any of them are uppercase, they stay
    * uppercase. This method forces all letters to lower and then caps
    * the first.
    *
    * Yes, this should, and is, a HELPER, but this class doesn't have
    * access to the HELPERs.
    *
    * @access private
    * @final
    *
    * @param  string $_str String to change word case on
    * @return string $_str String that was changed
    */
    private final function _upper_first($_str)
    {
        return ucfirst(strtolower($_str));
    }

};

// eof
