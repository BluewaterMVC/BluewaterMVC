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
 * @category    Bluewater
 * @package     Bluewater_Core
 * @subpackage  Bluewater_DB_Mysql
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
 * @subpackage  Bluewater_DB_Mysql
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
class Bluewater_Model_Db_Mysql_Connection extends Bluewater_Model_Db
{
// ==========================================================
// Class Properties

   /**
    * db_type
    *
    * DB Type name
    * defaults to 'mysql' since this is the most common used
    *
    * @name _db_type
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    */
    protected $_db_type = 'mysql';

   /**
    * db_port
    *
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
    protected $_db_port = '3306';

   /**
    * db_socket
    *
    * Define socket path to use instead of DNS:Port
    *
    * @name db_socket
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
    * Attempts to "load" database connection parameters
    *
    * @name _params
    * @access private
    * @final
    * @uses method _params
    *
    * @since 1.0
    *
    * @param array $_dsn DB access values
    * @return none
    *
    * @link http://adodblite.sourceforge.net/howtoinstall.php
    */
    private final function _params ( $_dsn = false )
    {
       /**
        * @name _dsn
        *
        * This array needs to be in this format
        *
        * Array index elements:
        * - db_type   -> database types       <- req
        * - db_user   -> database user name   <- req
        * - db_pass   -> user password        <- optional
        * - db_host   -> server host/IP       <- req
        * - db_port   -> server port number   <- optional
        * - db_socket -> UNIX Socket          <- optional
        * - db_name   -> name of database     <- req
        *
        * DB access properties
        * @link http://adodblite.sourceforge.net/howtoinstall.php
        *
        * @access private
        *
        **/

        // Optional properties
        if ( isset($_dsn['db_type']) )
            $this->set('db_type', $_dsn['db_type']);

        if ( isset($_dsn['db_host']) )
            $this->set('db_host', $_dsn['db_host']);

        if ( isset($_dsn['db_port']) )
            $this->setPort($_dsn['db_port']);

        if ( isset($_dsn['db_socket']) )
            $this->setSocket($_dsn['db_socket']);

        if ( isset($_dsn['db_pass']) )
            $this->set('db_pass', $_dsn['db_pass']);

        // These are required properties
        if ( isset($_dsn['db_user']) )
            $this->set('db_user', $_dsn['db_user']);
        else
            $this->_set_error('"db_user" is not defined');

        if ( isset($_dsn['db_name']) )
            $this->set('db_name', $_dsn['db_name']);
        else
            $this->_set_error('"db_name" is not defined');
    }

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
        // Call to DB connection method
        // Creating a normal connection
        $this->_dbSetup ( 'PConnect', $_debug );
    }

   /**
    * Attempts to "create" a DB conection Object based upon
    * connection passed connection type
    * This method is called by other methods, not directly
    *
    * @name _dbSetup
    * @access private
    * @final
    *
    * @since 1.0
    *
    * @param string $_type Connection type
    * @return none
    *
    * @link http://adodblite.sourceforge.net/howtoinstall.php
    */
    final private function _dbSetup ( $_type = 'PConnect', $_debug = false )
    {
        // Attempt connection if we have what we need
        if ( ! $this->ERROR )
        {
            // Intantiate DB Connection
            $this->objConn = adonewconnection($this->db_type);

            // Define network port to access DB Server through
            $this->objConn->port = $this->db_port;

            // Set DEBUG Level
            $this->objConn->debug = $_debug;

            // Create new DB is asked to
            $this->objConn->createdatabase = $this->createdatabase;

            // Set connection
            $this->objConn->$_type( $this->db_host,
                                    $this->db_user,
                                    $this->db_pass,
                                    $this->db_name );

            // Create a data dictionary object, using this connection
            $this->dict = NewDataDictionary($this->objConn);
        }
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

