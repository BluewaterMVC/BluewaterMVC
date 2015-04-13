<?php

/**
 * Generic DB Table Access Class File
 *
 * Class library to process and display Generic Tables
 *
 * @category    Bluewater
 * @package     Bluewater_Core
 * @subpackage  Bluewater_DB
 *
 * $Id: $
 * @filename  $RCSfile: $
 * @version   $Revision: $
 * @date      $Date: $
 * @copyright (c) 2008 Walter Torres
 * @license   Licensed under the GNU GPL. For full terms see the file COPYING.
 *            OSI Certified Open Source Software
 *
 * @filesource
 *
 */


/**
 * Generic DB Table Access Class File
 *
 * Class library to process and display Generic Tables. This works a bit different
 * than defined table class fines. The class instantiation call assumes that any
 * inbound string is the name of the table to access, and not an record ID or an
 * array of data to populate the class with. These need to be call explicately.
 *
 * @extends Bluewater_Model_Abstract
 *
 * @package     Bluewater_Core
 * @subpackage  Bluewater_DB
 *
 * @PHPUnit Not Defined
 *
 * @tutorial /path/to/tutorial.php Complete Class tutorial
 * @example url://path/to/example.php description
 */

class Bluewater_DB_Table_Generic extends Bluewater_Model_Abstract
{

// ==========================================================
// Class Properties

   /**
    * DB Table name to access
    *
    * @name _table
    * @var string $_name DB Table name to access
    *
    * @access protected
    * @PHPUnit Not Defined|Implement|Completed
    *
    */
    protected $_table = '';


// ==========================================================
// Class Methods

   /**
    * Class constructor
    *
    * @access public
    * @PHPUnit Not Defined|Implement|Completed
    *
    * @param string $_table
    * @return mixed Table Object or FALSE if could not be created
    *
    */
    final public function __construct($_table = false)
    {
        // Only process if we have a table access
        if ( $_table !== false )
        {
            $this->_table = $_table;
            parent::__construct();
        }

        // We don''t have anything
        else
            return false;

    }
};

  /* =====================================================================

   Version Control Info
   $RCSfile: $
   $Revision: $
   $Date: $
   $Author: $

   $Log: index.php,v $

*/

// eof
