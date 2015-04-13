<?php

/**
 * Helper class to recursively merges arrays
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
 * Helper class to recursively merges arrays.
 *
 * If one of the Arguments isn't an Array, first Argument is returned.
 * If an Element is an Array in both Arrays, Arrays are merged recursively,
 * otherwise the element in $ins will overwrite the element in $arr
 * (regardless if key is numeric or not). This also applies to Arrays in
 * $arr, if the Element is scalar in $ins (in difference to the previous
 * approach).
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
class Helper_Array_Insert
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
    * Recursively merges arrays
    *
    * Setup inbound parameters for actual method call.
    *
    * Method arguments are actually sent within an indexed array.
    *
    * @uses Helper_Array_Insert::_array_insert()
    *
    * @access public
    *
    * @param array $_args Method arguments are sent within an indexed array
    * @return array $arr Merges arrays
    */
    public final function helper(array $_args = array())
    {
        // Method parameters come bundled in $_args array

        // Array to merge into
        $arr = ( isset($_args[0])) ? $_args[0] : array();

        // Array to merge from
        $ins = ( isset($_args[1])) ? $_args[1] : array();

        // Send off to recursive method
        return $this->_array_insert($arr,$ins);
    }

   /**
    * Recursively merges arrays
    *
    * This method does the actual work.
    *
    * @author thomas@thoftware.de
    * @link http://us2.php.net/manual/en/function.array-merge-recursive.php#82976
    *
    * @uses Helper_Array_Insert::_array_insert()
    *
    * @access private
    *
    * @param array $arr Array to merge into
    * @param array $ins Array to merge from
    *
    * @return array $arr Merges arrays
    */
    private final function _array_insert(array $arr = array(), array $ins = array())
    {
        # Loop through all Elements in $ins:
        if (is_array($arr) && is_array($ins))
        {
            foreach ($ins as $k => $v)
            {
                # Key exists in $arr and both Elements are Arrays: Merge recursively.
                if (isset($arr[$k]) && is_array($v) && is_array($arr[$k]))
                    $arr[$k] = $this->_array_insert($arr[$k],$v);
                # Place more Conditions here (see below)
                elseif (is_int($k))
                    $arr[] = $v;
                # Otherwise replace Element in $arr with Element in $ins:
                else
                    $arr[$k] = $v;
            }
        }

        # Return merged Arrays:
        return($arr);
    }

};


// eof
