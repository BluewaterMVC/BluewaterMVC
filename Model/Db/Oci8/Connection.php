<?php

/**
 * Series of DB access methods for better access to ADOdb
 *
 * This class is based off of ADOdb:
 * Supports Frontbase, MaxDB, MiniSql, MSSQL, MySql, Postgres, SqLite and Sybase.
 *
 * @link http://adodb.sourceforge.net/
 * @link http://sourceforge.net/projects/adodb/
 *
 * @category    DB
 * @package     Bluewater_Core
 * @subpackage  Bluewater_DB_Oracle
 *
 * @extends ADOdb
 * @uses class ADOdb
 * @requires ADOdb
 *
 * @tutorial /path/to/tutorial.php Complete Class tutorial
 * @example url://path/to/example.php description
 *
 * @author $Author: $
 * @version $Revision: 1.0 $
 * @copyright copyright information
 * @license URL name of license
 *
 */

 /**
 * Bluewater Core Class: Controls and handles Database access
 *
 * @package     Bluewater_Core
 * @subpackage  Bluewater_DB_Oracle
 *
 * @PHPUnit Not Defined
 *
 * @abstract
 *
 * @example /examples
 * @tutorial /path/to/tutorial.php Complete Class tutorial
 *
 * @author Walter Torres <walter@torres.ws>
 * @version $Revision: 1.0 $
 *
 */
class Bluewater_Model_Db_Oci8_Connection extends Bluewater_Model_Db
{
// ==========================================================
// Class Properties

   /**
    * DB Type name
    * defaults to 'oci8' since this is the most common used
    *
    * @name _db_type
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    */
    protected $_db_type = 'oci8';

   /**
    * SQL Server port to use for this connection
    * defaults to '3367' since this is the most common used
    *
    * @name _db_port
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    */
    protected $_db_port = '1521';

   /**
    * Define socket path to use instead of DNS:Port
    *
    * @name _db_socket
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    */
    protected $_db_socket = null;

// ==========================================================
// Class Methods

   /**
    * Class constructor
    *
    * Determines if the right values are passed in before
    * attempting to create a new DB connection
    *
    * @access public
    *
    * @since 1.0
    *
    * @param array $_dsn DB access values
    * @param bool  $_debug flags use of DEBUG displays
    * @return none
    *
    */
    public function __construct( $_dsn = false, $_debug = false, $_connection_type = 'pconnect' )
    {
        // Set ADObd DEBUG Flag
        $this->setDebug ( $_debug );

        $_connection_type = 'db_' . $_connection_type;

        // Load DB access parameters, if we were given any
        if ( $_dsn )
        {
            $this->_params ( $_dsn );
            $this->$_connection_type( $_debug );
        }

    }

   /**
    * Class destructor
    *
    * Attempts to "destroy" a DB conection Object
    *
    * @access public
    * @final
    *
    * @since 1.0
    *
    * @param none
    * @return none
    *
    */
    public final function __destruct()
    {
        if (isset($this->objConn) && is_object($this->objConn))
        {
            $this->objConn->Close();
            unset($this->objConn);
        }
    }


// ==========================================================
// DB Connection Creation Methods

   /**
    * Attempts to "create/force" a DB conection Object
    *
    * @name db_new
    * @access public
    * @final
    * @uses method _dbSetup
    *
    * @since 1.0
    *
    * @param bool  $_debug flags use of DEBUG displays
    * @return none
    *
    * @link http://adodblite.sourceforge.net/howtoinstall.php
    */
    public final function db_new ($_debug = false)
    {
        // Call to DB connection method
        // Forcing a new connection
        $this->_dbSetup ( 'NConnect', $_debug );
    }

   /**
    * Attempts to "create" a 'normal' DB conection Object
    *
    * @name db_connect
    * @access public
    * @final
    * @uses method _dbSetup
    *
    * @since 1.0
    *
    * @param none
    * @return none
    *
    * @link http://adodblite.sourceforge.net/howtoinstall.php
    */
    public final function db_connect ($_debug = false)
    {
        // Call to DB connection method
        // Creating a normal connection
        $this->_dbSetup ( 'Connect', $_debug );
    }

   /**
    * Attempts to "create" a 'persistant' DB conection Object
    *
    * @name db_pconnect
    * @access public
    * @final
    * @uses method _dbSetup
    *
    * @since 1.0
    *
    * @param none
    * @return none
    *
    * @link http://adodblite.sourceforge.net/howtoinstall.php
    */
    public final function db_pconnect ($_debug = false)
    {
        $this->connection_string = '(DESCRIPTION = (ADDRESS_LIST = (ADDRESS=(PROTOCOL=TCP)(HOST=' . $this->_db_name . ')(PORT=' . $this->_db_port . ')))(CONNECT_DATA=(SERVICE_NAME=' . $this->_db_host . ')))';

        // Call to DB connection method
        // Creating a normal connection
        $this->_dbSetup ( 'PConnect', $_debug );

    }


    public final function connect()
    {
        $this->objADODB->Connect($this->connection_string, 'DATA_HOLDER', 'ZACKS');
    }


// ==================================================================
// ==================================================================


   /**
    * A wrapper to the mySQL FOUND_ROWS function
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    *
    * @param void
    * @return void
    */
    final public function FOUND_ROWS()
    {
        $this->dbQuery('SELECT "found", FOUND_ROWS();');
        $_tmp = $this->GetAssoc();
        return $_tmp['found'];
    }

};


// ==========================================================
// ==========================================================

/**
  * $Header: $
  *
  * $Log: $
  *
  **/

// eof

