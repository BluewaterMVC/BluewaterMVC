<?php

/**
 * PHP Singleton Control Class
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
 * @subpackage  Singleton
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
 * PHP Singleton Control Class
 * 
 * danbettles at yahoo dot co dot uk
 * http://www.php.net/manual/en/function.get-class.php#86230
 * 08-Oct-2008 06:56
 * http://danbettles.blogspot.com/2008/10/implementing-singleton-base-class-in.html
 *
 * @package     Bluewater_Core
 * @subpackage  Singleton 
 *
 * @PHPUnit Not Defined
 *
 * @tutorial tutorial.pkg description
 * @example url://path/to/example.php description
 *
 */
abstract class Bluewater_Singleton_Abstract
{
    protected function __construct() {}


   /**
    * Singleton instance
    *
    * @param boolean $_force Force a new instance regardless of previous state
    * @return object Object
    */
    final public static function getInstance($_force = false)
    {
        static $_aoInstance = array();

       /*
        * get_called_class() 5.3 standard function
        * Gets the name of the class the static method is called in.
        * 'get_called_class' is defined at the bottom of this file if
        * this class is used in PHP less than 5.3.0
        */
        $_calledClassName = get_called_class();

        if ( ($_force === true) || (isset ($_aoInstance[$_calledClassName]) === false) )
        {
            $_aoInstance[$_calledClassName] = new $_calledClassName();
        }

        return $_aoInstance[$_calledClassName];
    }

   /**
    * Prevent singletons from being cloned
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    * @param  none
    * @return none
    *
    */
    final private function __clone() {}

};



/********************************
 * Retro-support of get_called_class()
 * Tested and works in PHP 5.2.4
 * laurence+nospam at sol1 dot com dot au
 * 30-Sep-2009 07:32
 * http://www.php.net/manual/en/function.get-called-class.php#93799
 ********************************/
if(function_exists('get_called_class') === false)
{
    function get_called_class($_bt = false, $_level = 1)
    {
        if ($_bt === false)
            $_bt = debug_backtrace();

        if (isset($_bt[$_level]) === false)
        {
            throw new Exception('Cannot find called class -> stack level too deep.');
        }

        else if (isset($_bt[$_level]['type']) === false)
        {
            throw new Exception ('type not set');
        }

        else
        {
            switch ($_bt[$_level]['type'])
            {
                case '::':
                    $_levelines = file($_bt[$_level]['file']);
                    $_i = 0;
                    $_callerLine = '';

                    do {
                        $_i++;
                        $_callerLine = $_levelines[$_bt[$_level]['line'] - $_i] . $_callerLine;
                    } while (stripos($_callerLine,$_bt[$_level]['function']) === false);

                    preg_match('/([a-zA-Z0-9\_]+)::' . $_bt[$_level]['function'] . '/', $_callerLine, $_matches);

                    if ( isset($_matches[1]) === false)
                    {
                        // must be an edge case.
                        throw new Exception ('Could not find caller class: originating method call is obscured.');
                    }

                    else if ( ($_matches[1] === 'self') || ($_matches[1] === 'parent') )
                    {
                        return get_called_class($_bt, $_level++);
                    }

                    else
                    {
                        return $_matches[1];
                    }

                    break;

                case '->':
                    if ( $_bt[$_level]['function'] === '__get' )
                    {
                        // edge case -> get class of calling object
                        if ( is_object($_bt[$_level]['object']) === false )
                        {
                            throw new Exception ('Edge case fail. __get called on non object.');
                        }
                        else
                        {
                            return get_class($_bt[$_level]['object']);
                        }

                        return $_bt[$_level]['class'];
                    }

                default: throw new Exception ('Unknown backtrace method type');
            }
        }
    }
};

// eof
