<?php

/**
 * Helper class to capitalize first letter of sentence/word.
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
 * please refer to {@link http://web.bluewatermvc.org} for more information. *
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
 * @filesource
 *
 */


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
 * @package     Bluewater_Core
 * @subpackage  Helper
 *
 * @example /examples
 *
 * @author Walter Torres <walter@torres.ws>
 * @version $Revision: 1.0 $
 *
 */
class Helper_Upper_First
{
// ==========================================================
// Class Constants

// ==========================================================
// Class Properties

// ==========================================================
// Class Methods

   /**
    * Each Helper class has to have an empty __construct method
    *
    * @access public
    * @final
    *
    * @param void
    * @return void
    *
    */
    final final public function __construct()
    {
        // Empty on purpose.
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
    * Method arguments are actually sent within an indexed array.
    *
    * @access public
    *
    * @param array $_args Method arguments are sent within an indexed array
    * @return string $_str String that was changed
    */
    public final function helper(array $_args = array())
    {
        // Make sure we have something
        return (isset($_args[0])) ? ucfirst(strtolower($_args[0])) : '';

    }

};


// eof
