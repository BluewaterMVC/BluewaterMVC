<?php

/**
 * Class Abstract to handle Application MVC Models.
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
 * @subpackage  Bluewater_DB
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
 * Bluewater Core Class: Controls and handles Table access
 *
 * @package     Bluewater_Core
 * @subpackage  Bluewater_DB
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
abstract class Bluewater_Model_Abstract
{
// ==========================================================
// Class Properties

   /**
    * Decides if new database should be created upon connection
    *
    * @name _create_database
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    */
    protected $_create_database = false;

   /**
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

   /**
    * DN or IP of DB server
    * defaults to 'localhost' since this is the most common used
    *
    * @name _db_host
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    */
    protected $_db_host = 'localhost';

   /**
    * DB Name to use in this instance
    *
    * @name _db_name
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    */
    protected $_db_name = '';

   /**
    * DB User Name
    *
    * @name _db_user
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    */
    protected $_db_user = '';

   /**
    * DB User Password
    *
    * @name _db_pass
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    */
    protected $_db_pass = '';

   /**
    * ADOdb Debug flag
    *
    * @name debug
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    */
    protected $debug = false;

   /**
    * Any error message from any action
    *
    * @name ERROR
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    */
    protected $ERROR = false;



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
        * - _db_type   -> database types       <- req
        * - _db_user   -> database user name   <- req
        * - _db_pass   -> user password        <- optional
        * - _db_host   -> server host/IP       <- req
        * - _db_port   -> server port number   <- optional
        * - _db_socket -> UNIX Socket          <- optional
        * - _db_name   -> name of database     <- req
        *
        * DB access properties
        * @link http://adodblite.sourceforge.net/howtoinstall.php
        *
        * @access private
        *
        **/

        // Optional properties
        if ( isset($_dsn['_db_type']) )
            $this->set('_db_type', $_dsn['_db_type']);

        if ( isset($_dsn['_db_host']) )
            $this->set('_db_host', $_dsn['_db_host']);

        if ( isset($_dsn['_db_port']) )
            $this->setPort($_dsn['_db_port']);

        if ( isset($_dsn['_db_socket']) )
            $this->setSocket($_dsn['_db_socket']);

        if ( isset($_dsn['_db_pass']) )
            $this->set('_db_pass', $_dsn['_db_pass']);

        // These are required properties
        if ( isset($_dsn['_db_user']) )
            $this->set('_db_user', $_dsn['_db_user']);
        else
            $this->_set_error('"_db_user" is not defined');

        if ( isset($_dsn['_db_name']) )
            $this->set('_db_name', $_dsn['_db_name']);
        else
            $this->_set_error('"_db_name" is not defined');
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
    private final function _dbSetup ( $_type = 'PConnect', $_debug = false )
    {
        // Attempt connection if we have what we need
        if ( ! $this->ERROR )
        {
            // Intantiate DB Connection
            $this->objConn = adonewconnection($this->_db_type);

            // Define network port to access DB Server through
            $this->objConn->port = $this->_db_port;

            // Set DEBUG Level
            $this->objConn->debug = $_debug;

            // Create new DB is asked to
            $this->objConn->_create_database = $this->_create_database;

            // Set connection
            $this->objConn->$_type( $this->_db_host,
                                    $this->_db_user,
                                    $this->_db_pass,
                                    $this->_db_name );

            // Create a data dictionary object, using this connection
            $this->dict = NewDataDictionary($this->objConn);
        }
    }

   /**
    * Central DB Class Query Method. All child Table class queries run through here
    *
    * @category db_access
    *
    * @access public
    * @final
    * @PHPUnit Not Defined|Implement|Completed
    *
    * @param string $_sql SQL string to query DB with
    * @return mixed $_return depends on query
    *
    * @todo add error handling!
    *
    */
    public final function dbQuery ( $_sql = false )
    {
        return $this->objConn->Execute( $_sql );
    }

   /**
    * Central DB Class Error Method.
    *
    * @name _dbQuery
    *
    * @category db_access
    *
    * @access protected
    * @final
    * @PHPUnit Not Defined|Implement|Completed
    *
    * @param string $_sql SQL string to query DB with
    * @return mixed $_return depends on query
    *
    * @todo add error handling!
    *
    */
    public final function _ErrorMsg ( $_sql = false )
    {
        return $this->objConn->ErrorMsg();
    }

// ==========================================================
// Miscellaneous support Methods

   /**
    * Converts SQL Date[Time] format used within current adpater into UNIX Timestamp
    *
    * @name sqlDate_to_unixtime
    *
    * @category db_support
    *
    * @access public
    * @final
    * @PHPUnit Not Defined|Implement|Completed
    *
    * @param date $_php_date PHP Date Type variable
    * @return string $_unixtime SQL Date[Time] format used within current adpater into UNIX Timestamp
    *
    */
    public final function sqlDate_to_unixtime ( $_sql_date = false )
	{
		if ( ! empty($_sql_date) )
		{
			$_unixtime = strtotime($_sql_date);
		}
		else
		  $_unixtime = 0;

		return $_unixtime;
	}


   /**
    * Converts PHP Date into Date format used within current adpater
    *
    * @name phpDate_to_sqlDate
    *
    * @category db_support
    *
    * @access public
    * @final
    * @PHPUnit Not Defined|Implement|Completed
    *
    * @param date $_php_date PHP Date Type variable
    * @return string $_sql_date PHP Date converted to SQL DATE used within current adpater
    *
    */
    public final function phpDate_to_sqlDate ( $_php_date = false )
	{
		if ( ! empty($_php_date) )
		{
			$_date = date($this->get_sqlDate_format(), $_php_date);
		}
		else
            $_date = $this->objConn->fmtDefaultDate;

		return $_date;
	}

   /**
    * Returns date format used within current adpater
    *
    * @name get_sqlDate_format
    *
    * @category db_support
    *
    * @access public
    * @final
    * @PHPUnit Not Defined|Implement|Completed
    *
    * @param none
    * @return string $_date_format date format used within current adpater
    *
    */
    public final function get_sqlDate_format ()
    {
        // Remove the single quotes from the string format
        return $this->objConn->fmtDate;
    }


   /**
    * Converts PHP Date into Date Time format used within current adpater
    *
    * @name phpDate_to_sqlDatetime
    *
    * @category db_support
    *
    * @access public
    * @final
    * @PHPUnit Not Defined|Implement|Completed
    *
    * @param date $_php_date PHP Date Type variable
    * @return string $_sql_date PHP Date converted to SQL DATE used within current adpater
    *
    */
    public final function phpDate_to_sqlDatetime ( $_php_date = false )
	{
		if ( ! empty($_php_date) )
		{
			$_date = date($this->get_sqlDatetime_format(), $_php_date);
		}
		else
            $_date = $this->objConn->fmtDefaultTimeStamp;

		return $_date;
	}

   /**
    * Returns date-time format used within current adpater
    *
    * @name get_datetime_format
    *
    * @category db_support
    *
    * @access public
    * @final
    * @PHPUnit Not Defined|Implement|Completed
    *
    * @param none
    * @return string $_date_format date-time format used within current adpater
    *
    */
    public final function get_sqlDatetime_format ()
    {
        // Remove the single quotes from the string format
        return $this->objConn->fmtTimeStamp;
    }


   /**
    * Converts PHP Date into Time format used within current adpater
    *
    * @name phpDate_to_sqlDatetime
    *
    * @category db_support
    *
    * @access public
    * @final
    * @PHPUnit Not Defined|Implement|Completed
    *
    * @param date $_php_date PHP Date Type variable
    * @return string $_sql_date PHP Date converted to SQL TIME used within current adpater
    *
    */
    public final function phpDate_to_sqlTime ( $_php_date = false )
	{
		if ( ! empty($_php_date) )
		{
			$_date = date($this->get_sqlTime_format(), $_php_date);
		}
		else
            $_date = $this->objConn->fmtDefaultTime;

		return $_date;
	}

   /**
    * Returns Time format used within current adpater
    *
    * @name get_sqlTime_format
    *
    * @category db_support
    *
    * @access public
    * @final
    * @PHPUnit Not Defined|Implement|Completed
    *
    * @param none
    * @return string $_time_format Time format used within current adpater
    *
    */
    public final function get_sqlTime_format ()
    {
        // Remove the single quotes from the string format
        return $this->objConn->fmtTime;
    }

// ==========================================================
// DN Connection support Methods

   /**
    * specifies the socket or named pipe that should be used
    *
    * @name setSocket
    * @access public
    * @final
    *
    * @since 1.0
    *
    * @param string $_socket socket or named pipe that should be used
    * @return none
    *
    */
    public final function setSocket ( $_socket = null )
    {
        $this->_db_socket = $_socket;
    }

   /**
    * Port number to connect through.
    *
    * @name setPort
    * @access public
    * @final
    *
    * @since 1.0
    *
    * @param int $_port Port number to connect through.
    * @return none
    *
    */
    public final function setPort ( $_port = false )
    {
        // If not defined, pull default value
        if ( $_port === false )
            $_port = $this->_db_port;

        $this->_db_port = $_port;
    }

   /**
    * Echos every query along with the error result
    *
    * @name setDebug
    * @access public
    * @final
    *
    * @since 1.0
    *
    * @param boolean $_debug DEBUG state
    * @return none
    *
    */
    public final function setDebug ( $_debug = true )
    {
        // Set ADObd DEBUG Flag
        $this->debug = $_debug;

        // Set PHP mySQL trace mode
        ini_set  ( 'mysql.trace_mode', $_debug ? '1' : '0' );
    }

   /**
    * If the database does not exist and you would like ADOdb Lite
    * to automatically create the database then set the _create_database
    * switch to true.
    *
    * @name setCreateDB
    * @access public
    * @final
    *
    * @since 1.0
    *
    * @param boolean $_create Create DB state
    * @return none
    *
    */
    public final function setCreateDB  ( $_create = true )
    {
        // Set ADObd DEBUG Flag
        $this->_create_database  = $_create;
    }


// ==========================================================
// Class Getter and Setter Methods

   /**
    * get
    *
    * Returns current value of given proprty
    *
    * @name get
    *
    * @category GETTER
    *
    * @access public
    * @final
    *
    * @since 1.0
    *
    * @param string $_prop_name name of property to access
    * @return mixed current value of property
    *
    */
    public final function get( $_prop_name )
    {
        return $this->$_prop_name;
    }

   /**
    * Set
    *
    * Defines value of given proprty
    *
    * @name set
    *
    * @category SETTER
    *
    * @access public
    * @final
    *
    * @since 1.0
    *
    * @param string $_prop_name  name of property to define
    * @param string $_prop_value value to define property with
    * @return none
    *
    */
    public final function set( $_prop_name = false, $_prop_value = '' )
    {
        if ( $_prop_name )
            $this->$_prop_name = $_prop_value;
    }

   /**
    * _set_error
    *
    * Adds given message to ERROR array
    *
    * @name _set_error
    *
    * @category ERROR
    *
    * @access public
    * @final
    *
    * @since 1.0
    *
    * @param string $_msg  Error Message to generate
    * @return none
    *
    */
    public final function _set_error ($_msg = false)
    {
        if ($_msg === false)
            $_msg = 'Undefined Error.';

        $this->ERROR[] = $_msg;
    }

   /**
    * get_errors
    *
    * Retrun error message, if any.
    * FALSE If no errors where generated
    *
    * @name get_errors
    * @author Walter Torres <walter@torres.ws>
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param none
    * @return none
    *
    */
    public final function get_errors ()
    {
        return $this->ERROR;
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

