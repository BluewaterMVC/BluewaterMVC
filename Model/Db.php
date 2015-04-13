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
abstract class Bluewater_Model_Db
{
// ==========================================================
// Class Constants

   /**
    * This is the terse first line.
    *
    * String value of '1' for use as a TRUE in database fields
    * @name RECORD_TRUE
    */
    const RECORD_TRUE = '1';

   /**
    * String value of '0' for use as a TRUE in database fields
    * @name RECORD_FALSE
    */
    const RECORD_FALSE = '0';

// ==========================================================
// Class Properties

   /**
    * Default DB Access Connection Object
    *
    * @name _objDB
    * @var Object
    * @access protected
    * @static
    *
    * @PHPUnit Not Defined
    *
    */
    protected $_objDB = false;

   /**
    * DB Access Connection Object
    *
    * @name _db
    * @var string
    * @access protected
    *
    * @PHPUnit Not Defined
    *
    */
    protected $_db = false;

   /**
    * Decides if new database should be created upon connection
    *
    * @name createdatabase
    * var string
    * @access protected
    *
    * @since 1.0
    *
    */
    protected $_create_database = false;

   /**
    * db_type
    *
    * DB Type name
    * defaults to 'mysql' since this is the most common used
    *
    * @name db_type
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    */
    protected $db_type = 'mysql';

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
    * @name db_host
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



   /**
    * Field quote character for current database.
    *
    * @name _back_tick
    * @var string
    * @access protected
    *
    * @PHPUnit Not Defined
    *
    */
    protected $_back_tick = null;

   /**
    * DateTime field format for current database.
    *
    * @name _date_time_format
    * @var string
    * @access protected
    *
    * @PHPUnit Not Defined
    *
    */
    protected $_date_time_format = null;

   /**
    * Date field format for current database.
    *
    * @name _date_format
    * @var string
    * @access protected
    *
    * @PHPUnit Not Defined
    *
    */
    protected $_date_format = null;

   /**
    * Time field format for current database.
    *
    * @name _time_format
    * @var string
    * @access protected
    *
    * @PHPUnit Not Defined
    *
    */
    protected $_time_format = null;


   /**
    * DB name this table resides within
    *
    * @name _database
    * @var string $_database DB name this table resides within
    *
    * @access protected
    * @PHPUnit Not Defined|Implement|Completed
    *
    * @since 1.0
    *
    */
    protected $_database = false;

   /**
    * DB Table name to access
    * This is defined in "child" class
    *
    * @name _table
    * @var string
    * @access protected
    *
    * @PHPUnit Not Defined
    *
    */
    protected $_table = null;

   /**
    * DB Table primary key.
    * This is defined in "child" class
    *
    * @name _primary
    * @var string
    * @access protected
    *
    * @PHPUnit Not Defined
    *
    */
    protected $_primary = 'id';

   /**
    * DB Table fields.
    *
    * @name _fields
    * @var array
    * @access protected
    *
    * @PHPUnit Not Defined
    *
    */
    protected $_fields = null;

   /**
    * Schema data for current table.
    *
    * @name _schema
    * @var array
    * @access protected
    *
    * @PHPUnit Not Defined
    *
    */
    protected $_schema = null;

   /**
    * ADOdb Record Set of returned records for SELECT, or FALSE if there
    * was some error. Also to temporarly store name/value pairs for fields
    * and their values, for INSERT and UPDATE
    *
    * @name record_set
    * @var object
    * @access public
    *
    * @PHPUnit Not Defined
    *
    */
    public $record_set = false;

   /**
    * Stores the last SQL statement processed.
    *
    * @name _lastSQL
    * @var string
    * @access protected
    *
    * @PHPUnit Not Defined
    *
    */
    protected $_lastSQL = false;

   /**
    * Logger Object Container
    *
    * @name log
    * @access public
    *
    * @property Logging Class Instance
    */
    public $logger = false;

// ============================================================

   /**
    * Class Constructor
    *
    * @uses Bluewater_Controller_Helper
    * @uses Bluewater_Logger
    *
    * @access public
    *
    * @param mixed $_param INT Record ID to access - optional
    *                      ARRAY data to load record with - optional
    * @return Class Object
    *
    */
    public function __construct( $_param = false )
    {
        // Pull in static DB Object, if we don't already have one
        if (! $this->_db)
            self::getDatabaseAdapter();

        // Load Logger Class
//        $this->log = Bluewater_Logger::getInstance();

        // Load Helper Support Class
//        $this->helper = Bluewater_Helper::getInstance();

        // initialize class properties
        $this->_init_props();

        // If an ID (INT) is passed in, pull that individual record
        if ( is_integer($_param) )
        {
            $this->set_field_value( $this->get_primary_key(), $_param );

            $this->getRecordByID($_param);
        }
        // Maybe we have an array of data
        else if ( is_array($_param) )
        {
            $this->set_data($_param);
        }
        // Otherwise pull POST data, see if there is anything for this class
        else
        {
            $this->set_data($_POST);
        }
    }

   /**
    * Class Destructor
    *
    * @access public
    *
    * @param int $_objDB DB Object to reference
    * @param int $_table_name Table to use
    * @return Class Object
    *
    */
    public function __destruct()
    {
        // This doesn't seem to work!'
        if ($this->record_set)
            $this->record_set->Close();

        // Close record set object, if we have one
        $this->close();
    }

   /**
    * Class debug method. To display properies and last SQL call
    *
    * @access public
    * @final
    *
    * @param int $_objDB DB Object to reference
    * @param int $_table_name Table to use
    * @return Class Object
    *
    */
    public final function debug()
    {
        // Build ancestor tree
        $_class = get_class($this);
        $_classes = array($_class);

        while($_class = get_parent_class($_class))
            $_classes[] = $_class;

        $_struct['Model'] = implode  ( ' -> '  , $_classes );;
        $_struct['Database'] = $this->_database;
        $_struct['Table'] = $this->_table;

        $_struct['fields'] =  $this->getDataArray();

        $_struct['SQL'] = $this->_lastSQL;
        $_struct['ERROR'] = $this->ERROR;

        return $_struct;
    }

   /**
    * Retrieves default database to use from config file
    *
    * @uses Bluewater_Config::config
    *
    * @access public
    *
    * @param string
    * @return string $_db_name default database from Config
    *
    */
    final public function get_default_db()
    {
        // Pull database to use from config file
        $_default_db = Bluewater_Config::config('db', 'default');
        return Bluewater_Config::config($_default_db, 'db_name');
    }

   /**
    * Attempts to "load" database connection parameters
    *
    * @name _params
    * @access protected
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
    protected final function _params ( $_dsn = false )
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
            $this->set('_db_type', $_dsn['db_type']);

        if ( isset($_dsn['db_host']) )
            $this->set('_db_host', $_dsn['db_host']);

        if ( isset($_dsn['db_port']) )
            $this->setPort($_dsn['db_port']);

        if ( isset($_dsn['db_socket']) )
            $this->setSocket($_dsn['db_socket']);

        if ( isset($_dsn['db_pass']) )
            $this->set('_db_pass', $_dsn['db_pass']);

        // These are required properties
        if ( isset($_dsn['db_user']) )
            $this->_db_user = $_dsn['db_user'];
        else
            $this->_set_error('"db_user" is not defined');

        if ( isset($_dsn['db_name']) )
            $this->_db_name = $_dsn['db_name'];
        else
            $this->_set_error('"db_name" is not defined');
    }

   /**
    * Attempts to "create" a DB conection Object based upon
    * connection passed connection type
    * This method is called by other methods, not directly
    *
    * @name _dbSetup
    * @access protected
    * @final
    *
    * @since 1.0
    *
    * @param string $_type Connection type
    * @return none
    *
    * @link http://adodblite.sourceforge.net/howtoinstall.php
    */
    final protected function _dbSetup ( $_type = 'PConnect', $_debug = false )
    {
        // Attempt connection if we have what we need
        if ( ! $this->ERROR )
        {
            // Intantiate DB Connection
            $this->objADODB = NewADOConnection($this->_db_type);

            // Define network port to access DB Server through
            $this->objADODB->port = $this->_db_port;

            // Set DEBUG Level
            $this->objADODB->debug = $_debug;

            // Create new DB is asked to
            //$this->objConn->createdatabase = $this->createdatabase;

            // Set connection
            $this->connect();

            // Create a data dictionary object, using this connection
            //$this->dict = NewDataDictionary($this->objConn);
        }
    }


// ============================================================

   /**
    * Sets the DB connection for this database.
    * If database name is not set in actual Model Class,
    * this will access the default database as defined in the
    * local.in.php file.
    *
    * @uses Bluewater_DB::connection()
    *
    * @access public
    * @final
    *
    * @param void
    * @return void
    */
    public final function getDatabaseAdapter()
    {

do_print_r($this->model);


        if ( $this->_database === false ) {
            $this->_database = $this->get_default_db();
        }

        $this->_db = Bluewater_DB::connection($this->_database);

do_print_r($this->_db);

do_print_r($this->_database);
exit;

    }

   /**
    * Create the DB connection for this particular Bluewater_Db_Table object.r
    *
    * @uses class|method|global|variable description
    *
    * @access protected
    * @final
    *
    * @param  object $_db DB connection object
    * @return void
    */
    protected final function _setAdapter($_db)
    {
        $this->_db = self::_setupAdapter($_db);
    }

   /**
    * Returns the DB connection for this particular Bluewater_Db_Table object.
    *
    * @uses class|method|global|variable description
    *
    * @access public
    * @final
    *
    * @param void
    * @return DB connection object or null
    */
    public final function getAdapter()
    {
        return $this->_db;
    }

   /**
    * initializes class properties
    *
    * @access private
    * @final
    *
    * @param void
    * @return void
    *
    */
    private final function _init_props()
    {
        $this->_back_tick        = $this->_db->objConn->nameQuote;
        $this->_date_time_format = str_replace('\'', '', $this->_db->objConn->fmtTimeStamp);
        $this->_date_format      = str_replace('\'', '', $this->_db->objConn->fmtDate);
        $this->_time_format      = str_replace('\'', '', $this->_db->objConn->fmtTime);

        // Reteive table data
        $this->_schema = $this->get_table_schema($this->get_table_name());

        // If the table is found...
        if( $this->_schema )
        {
            // Assign field list
            $this->_fields = $this->_schema['field_list'];

            // Cycle through record set and make fields into class properties
            foreach($this->_fields as $_field)
                $this->$_field = ''; //$this->get_field_default_value($_field);
        }
        else
        {
            // @TODO: make this work now that DB has been 'updated'!
//            $this->_db->_set_error('Can not connect to "' . $this->get_table_name() .'"' );;
        }
    }

   /**
    * Pulls data from POST superglobal based upon field names
    * of current table and places values into the corrosponding
    * class properties
    *
    * @access protected
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return void
    *
    */
    protected final function _get_POST_data()
    {
        // Only need to process POST if we have POST to process ;)
        if ( count($_POST) !== 0)
        {
            // Take POST data and hand it off...
            $this->set_data($_POST);
        }
    }

   /**
    * Pulls data from given array based upon field names of
    * current table and places values into the corrosponding
    * class properties
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return void
    *
    */
    public final function set_data( array $_data = array() )
    {
        // Only need to process POST if we have DATA set to process ;)
        if ( count($_data) !== 0)
        {
            // If an 'id' field is defined, pull that record for
            // change comparision
            if ( isset($_data['id']) )
            {
                if ($_data['id'] > 0 )
                    $this->getRecordByID($_data['id']);
                else
                    unset($_data['id']);
            }

            // Cycle through DATA set and place values into
            // class properties. Only replace data that is defined.
            foreach($this->_fields AS $_i => $_prop )
            {
                // See if DATA has any table fields in it for us to use
                if ( isset($_data[$_prop]) )
                {
                    // If DATA is a STRING, it needs to be wrapped
                    if ( gettype($_data[$_prop]) == 'string' )
                    {
                        // Assign new 'cleaned up 'value
                        $_data[$_prop] = mysql_real_escape_string($_data[$_prop]);
                    }

                    // Assign to class property
                    $this->set_field_value($_prop, $_data[$_prop]);
                }
            }
        }
    }

   /**
    * Returns an array of the current table record
    *
    * @uses method build_db_fields
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return array $_primary current table record
    *
    */
    public final function getDataArray()
    {
        return $this->build_db_fields();
    }

// ==================================================================
// ==================================================================
// DB Table record create/modify/destroy methods


   /**
    * Create the table from an XML table defintion file.
    *
    * Use the "table defintion" file, located in <site>/model/defintions/<table>.xml,
    * to create a new table, first dropping an exisiting table of the same name.
    *
    * @access public
    * @final
    * @PHPUnit Not Defined

    *
    * @param void
    * @return array|bool|int|float|object|string|mixed|void $paramname description
    * @throws void
    *
    */
    protected final function create_table ()
    {
        return false;
    }

   /**
    * Destroy the table.
    *
    * Just drop the table.
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return array|bool|int|float|object|string|mixed|void $paramname description
    * @throws void
    *
    */
    protected final function remove_table()
    {
        return false;
    }

   /**
    * Emptys the contents from the table.
    *
    * Simply guts the table of its contents.
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return mixed $ret_value FALSE if there was an issue, ADORecordSet_empty Object is successful
    * @throws void
    *
    */
    protected final function empty_table()
    {

        // Create SQL
        // TRUNCATE TABLE `<tablename>`
        $_sql = 'TRUNCATE TABLE `' . $this->get_table_name() . '`';

        return $this->dbQuery($_sql);
    }

   /**
    * INFILE LOAD CSV data form data file
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return mixed $ret_value FALSE if there was an issue, ADORecordSet_empty Object is successful
    * @throws void
    *
    */
    protected final function load_table ( $_path = false,
                                          $_feildDelimiter = ',',
                                          $_recordDelimiter = '\r\n' )
    {
        // Default value
        $_ret_value = false;

        // Only query if we have a file to use
        if ( $_path )
        {
            // Create SQL
            $_sql = "
LOAD DATA INFILE '" . $_path . "'
INTO TABLE " . $this->get_table_name() . "
FIELDS TERMINATED BY '" . $_feildDelimiter . "'
OPTIONALLY ENCLOSED BY '\"'
LINES TERMINATED BY '" . $_recordDelimiter . "';";

            $_ret_value = $this->dbQuery($_sql);
        }

        return $_ret_value;
    }


// ==================================================================
// ==================================================================
// DB Table record access methods

   /**
    * Reads the schema using ADOdb fn MetaColumns()
    *
    * @param void
    *
    * @todo Add support for ADOdb fn MetaForeignKeys()
    */
    protected final function get_table_schema()
    {
       /**
        * Method Return value
        * defaults to FALSE
        *
        * @name _return_value
        * @access private
        *
        */
        $_return_value = false;

        // We can only do this if there is a DB connection object
        // for the defined database
        if ($this->_db)
        {
            // Pull schema data on table
            if ( $_columns = $this->_db->dict->MetaColumns($this->get_table_name()) )
            {
                // Make sure fields are found for this table
                if(is_array($_columns))
                {
                    $_struct['tablename'] = $this->get_table_name();
                    $_struct['primarykey'] = false;
                    $_struct['field_list'] = false;
                    $_struct['fields'] = $_columns;

                    // walk down field list and store select pieces of info
                    foreach($_columns as $_column)
                    {
                        // Store list of field names-alone
                        $_struct['field_list'][] = $_column->name;

                        // If primary is found, store it an leave loop
                        if($_column->primary_key)
                        {
                            $_struct['primarykey'] = $_column->name;
                            continue;
                        }
                    }   // foreach($columns as $column)

                    $_return_value = $_struct;

                }   // if(is_array($columns))
                else
                {
                    $this->_set_error('No columns found for table "' . $_strTableName . '"');
                }
            }   // if ( $columns = $this->dict->MetaColumns($_strTableName) )
        }

        return $_return_value;
    }

   /**
    * Retrieves the 'primarykey' of a given table
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param object $con ADOdb Database handle
    * @param string $_strTableName Name of the table to query
    * @return mixed $_key primarykey of table, FALSE upon failure
    */
    protected final function get_table_primarykey($_strTableName = false)
    {
      /**
        * _return_value
        *
        * Method Return value
        * defaults to FALSE
        *
        * @name _return_value
        * @access private
        *
        */
        $_return_value = false;

        // Only access if parameter is given
        if ($_strTableName !== false)
        {
            $_fields = $this->_fields; //$this->get_table_schema($_strTableName);

            $_return_value = $_fields['primarykey'];
        }

        return $_return_value;
    }


   /**
    * Returns the field list for current table
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param string $_field Field name to return type for
    * @return mixed $_field_type DB Field type
    *
    */
    protected final function get_table_field_list ()
    {
        return $this->_fields;
    }

   /**
    * Returns the field type for given field
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param string $_field Field name to return type for
    * @return mixed $_field_type DB Field type
    *
    */
    protected final function get_field_type ( $_field )
    {
        return $this->_schema['fields'][strtoupper($_field)]->type;
    }

   /**
    * Returns the field default value for given field, based on field type
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param string $_field Field name to return type for
    * @return string $_field_type DB Field default value
    *
    */
    protected final function get_field_default_value ( $_field )
    {
        // Default value;
        $_default_value = 'NULL';

        // See if this field has a default value defined
        if ( $this->_schema['fields'][strtoupper($_field)]->has_default )
            $_default_value = $this->_schema['fields'][strtoupper($_field)]->default_value;

        if ( is_null($_default_value) )
            $_default_value = 'NULL';

        // Decide how to handle user data based on field type
        switch ( $this->get_field_type ( $_field ) )
        {
            case 'int';
            case 'int':
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'bigint':
                // if the current field is the primary key,
                // it can NOT be ZERO
                if ( $this->get_primary_key() == $_field )
                    $_value = 'NULL';
                else
                    $_value = ($_default_value) ? $_default_value : 0;
            break;

            case 'float';
            case 'double';
                $_value = ($_default_value) ? $_default_value : 0;
            break;

            case 'varchar';
            case 'var';
            case 'char';
            case 'text':
            case 'longtext':
                $_value = ($_default_value) ? $_default_value : '';
            break;

            case 'datetime';
                $_value = $this->_date_time_format;
            break;

            case 'date';
                $_value = $this->_date_format;
            break;

            case 'time';
                $_value = $this->_time_format;
            break;

            default:
                $_value = ($_default_value) ? $_default_value : '';

        }   // switch ( $_type )

        return $_value;
    }


   /**
    * Runs any SQL query passed in. Simply passes SQL to Bluewater_DB_Connection::dbQuery().
    * Sets returned results into Class $record_set and also returns the results.
    *
    * @uses class Bluewater_Db
    * @uses method Bluewater_Db::db_access
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param $_sql SQL query to run
    * @return mixed new Record ID upon INSERT, FALSE on error
    *
    */
    public function dbQuery( $_sql = false )
    {
        // Keep this SQL call
        $this->_lastSQL = $_sql;

        $this->record_set = $this->_db->dbQuery($_sql);

        // Log all queries, but don't log the log or the Auth calls
        if ( ( $this->_table != 'event_log' ) &&
             ( $this->_table != 'user' )      )
        {
            $_msg = 'SQL' . "\n" . $_sql;
            $this->log->write($_msg, 2);
        }

        if ( $this->record_set === false )
        {
            // Pull back_trace
            $_trace = debug_backtrace();

            $this->ERROR = $this->_db->_ErrorMsg();


// @TODO: Make this a better solution!
echo '<h1>SQL ERROR</h1>';

do_print_r($this->debug(), false, true);
exit;

        }

        return $this->record_set;
    }


   /**
    * Adds a new record into the current table
    * This is just a wrapper to the DB Class 'dbInsert'
    *
    * @uses class Bluewater_Db
    * @uses method Bluewater_Db::db_access
    *
    * @access public
    * @final
    * @PHPUnit Not Defined|Implement|Completed
    *
    * @param void
    * @return mixed new Record ID upon INSERT, FALSE on error
    *
    */
    public function dbInsert()
    {
        // Loop through field list
        foreach ( $this->get_table_field_list() AS $_field )
        {
            // Determine if this field is in table schema. If it's not, skip it
            if ( isset($this->_schema['fields'][strtoupper($_field)] ) )
            {
                // Pull current value and convert to a STRING
                $_value = (string)$this->get_field_value($_field);

                // If field value is not set, use table defined default field value
                if ( ! isset($_value{0}) )
                    $_value = $this->get_field_default_value($_field );

                if ( $_value != 'NULL' )
                {
                    switch ( $this->get_field_type ( $_field ) )
                    {
                        // Need to make sure any text is SQL 'safe'
                        case 'varchar':
                        case 'var':
                        case 'char':
                        case 'text':
                        case 'longtext':
                        case 'datetime':
                        case 'date':
                        case 'time':
                        case 'timestamp':
                        case 'enum':
                            $_value = $this->_db->objConn->qstr($_value);
                        break;

                        // Try to convert value into an integer
                        case 'int':
                        case 'tinyint':
                        case 'smallint':
                        case 'mediumint':
                        case 'bigint':
                            // Returns an INT value, no ZEROS, no commas
                            // And if value is not a number, it is set to ZERO
                            $_value = number_format($_value, 0, '', '' );
                        break;

                        // Try to convert value into an decimal number
                        case 'float':
                        case 'decimal':
                            // Returns an FLOAT value, padded to the 8th
                            // dedcimal place, no commas
                            // And if value is not a number, it is set to ZERO
                            // @TODO: instead of hard coding the 8, determine the
                            //        current field and use it's decimal place value
                            $_value = number_format($_value, 8, '.', '' );
                        break;

                        default:
                            if ( ! isset($_value) )
                                $_value = 'NULL';
                    }
                }

                // Add current value into array
                $_values[] = $_value;

                // Add current field to field list
                $_fields[] = $this->_quoteField ( $_field );

            }   // ( isset($this->_schema['fields'][strtoupper($_field)] ) )

        }   // foreach ( $_data AS $_field => $_value )

        // Build INSERT SQL
        $_insertSQL = 'INSERT ' . $this->_quoteField ($this->get_table_name())
                    . ' (' . implode ( ', ', $_fields ) . ') ' . "\n"
                    . ' VALUES '
                    . ' (' . implode ( ', ', $_values ) . '); ';

        // Run the query
        $_result = $this->dbQuery($_insertSQL);

        // Default a return value
        $_new_id = false;

        // If the query ran OK, set the new record ID
        if ( $_result )
        {
            $_new_id = $this->_db->objConn->Insert_ID();
            $this->set_field_value('id', $_new_id );
        }

        // Send back the results of our INSERT attempt
        return $_new_id;
    }

   /**
    * UPDATE a record from the current DB Table.
    * This overrides parent class 'dbUpdate' method
    *
    * @uses class Bluewater_Db
    * @uses method Bluewater_Db::db_access
    *
    * @access public
    * @final
    * @PHPUnit Not Defined|Implement|Completed
    *
    * @param void
    * @return mixed new Record ID upon UPDATE, FALSE on error
    *
    */
    public function dbUpdate()
    {
        // Loop through field list
        foreach ( $this->get_table_field_list() AS $_field )
        {
            // Skip primary key
            if ( $_field == $this->get_primary_key() )
                continue;

            if ( $_field == 'created_on' )
                continue;

            if ( $_field == 'created_by' )
                continue;

            // Determine if this field is in table schema. If it's not, skip it
            if ( isset($this->_schema['fields'][strtoupper($_field)] ) )
            {
                // Pull current value and convert to a STRING
                $_value = (string)$this->get_field_value($_field);

                // If field value is not set, use table defined default field value
                if ( ! isset($_value{0}) )
                    $_value = $this->get_field_default_value($_field );

                if( $_value != 'NULL')
                {
                    switch ( $this->get_field_type ( $_field ) )
                    {
                        // Need to make sure any text is SQL 'safe'
                        case 'varchar':
                        case 'var':
                        case 'char':
                        case 'text':
                        case 'longtext':
                        case 'enum':
                        case 'datetime':
                            $_value = $this->_db->objConn->qstr($_value);
                        break;

                        case 'date':
                        case 'time':
                        case 'timestamp':
                            $_value = $this->_db->objConn->qstr($_value, true);
                        break;

                        // Try to convert value into an integer
                        case 'int':
                        case 'tinyint':
                        case 'smallint':
                        case 'mediumint':
                        case 'bigint':
                            // Returns an INT value, no ZEROS, no commas
                            // And if value is not a number, it is set to ZERO
                            $_value = number_format($_value, 0, '', '' );
                        break;

                        // Try to convert value into an decimal number
                        case 'float':
                        case 'decimal':
                            // Returns an FLOAT value, padded tothe 8th
                            // dedcimal place, no commas
                            // And if value is not a number, it is set to ZERO
                            // @TODO: instead of hard coding the 8, determine the
                            //        current field and use it's decimal place value
                            $_value = number_format($_value, 8, '.', '' );
                        break;

                        default:
                            if ( ! isset($_value) )
                                $_value = '';
                    }
                }

                // Add current value into array
                $_data[] = $this->_quoteField ( $_field ) . ' = ' . $_value;

            }   // ( isset($this->_schema['fields'][strtoupper($_field)] ) )

        }   // foreach ( $_data AS $_field => $_value )

        // Build UPDATE SQL
        $_sql = 'UPDATE ' . $this->_quoteField ($this->get_table_name()) . "\n"
              . ' SET ' . implode ( ', ', $_data ) . "\n"
              . ' WHERE ' . $this->_quoteField ($this->get_primary_key()) . ' = '
              . $this->get_field_value($this->get_primary_key());

        return $this->dbQuery($_sql);
    }



   /**
    * If Record ID is defined within the Class, than that Record is Updated,
    * othterwise a new Record is created. Either way the record ID is returned
    * and Class properties are updated
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return int $id Current Model Record ID
    *
    * @uses self::$id Record ID of UPDATEd or INSERTed record
    *
    */
    final public function dbReplace()
    {

        // If we have an ID, then we just need to UPDATE this record
        if ( $this->id > 0 )
            $this->dbUpdate();

        // Otherwise Create a new record
        else
            $this->dbInsert();

        return $this->id;
    }


   /**
    * Search the current table.
    *
    * Any found records are placed in the $record_set property
    * for easy access via the various "Get*" methods.
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param array $_data name/value pair array of data to SEARCH table for
    * @param string $_op How to search table AND|OR: Default 'AND'
    * @param string $_fields comma delimited list of fields to retrieve: Default '*'
    *
    * @return boolean $_search_results Array of found record[s], FALSE, if not
    *
    */
    public function dbSearch( $_data = false, $_op = 'AND', $_fields = '*', $_type = 'array' )
    {
        // Assume we fail
        $_search_results = false;

        // If $_data is set, search current table
        if ( is_array($_data ))
        {
            // Loop through table field list, pull out only fields
            // used within the current table
            foreach ( $_data AS $_field => $_value )
            {
                // Determine if this field is in table schema. If it's not, skip it
                if ( isset($this->_schema['fields'][strtoupper($_field)] ) )
                {
                    // If field value is not set, use table defined field default value
                    if (is_null($_value))
                        $_value = (string)$this->get_field_default_value($_field );

                    switch ( $this->get_field_type ( $_field ) )
                    {
                        // Need to make sure any text is SQL 'safe'
                        case 'varchar':
                        case 'var':
                        case 'char':
                        case 'text':
                        case 'longtext':
                        case 'datetime':
                        case 'date':
                        case 'time':
                        case 'timestamp':
                        case 'enum':
                            $_value = $this->_db->objConn->qstr($_value);
                        break;

                        // Try to convert value into an integer
                        case 'int':
                        case 'tinyint':
                        case 'smallint':
                        case 'mediumint':
                        case 'bigint':
                            // Returns an INT value, no ZEROS, no commas
                            // And if value is not a number, it is set to ZERO
                            $_value = number_format($_value, 0, '.', '' );
                        break;

                        // Try to convert value into an decimal number
                        case 'float':
                        case 'decimal':
                            // Returns an FLOAT value, padded tothe 8th
                            // dedcimal place, no commas
                            // And if value is not a number, it is set to ZERO
                            // @TODO: instead of hard coding the 8, determine the
                            //        current field and use it's decimal place value
                            $_value = number_format($_value, 8, '.', '' );
                        break;

                        default:
                            if ( ! isset($_value) )
                                $_value = '';
                    }

                    // Add current value into array
                    $_values[] = $this->_quoteField ( $_field ) . ' = ' . $_value;

                }   // ( isset($this->_schema['fields'][strtoupper($_field)] ) )
            }   // foreach ( $this->get_table_field_list() AS $_field )

            // Build SQL
            $_sql = 'SELECT ' . $_fields . "\n"
            .  ' FROM ' . $this->_quoteField ($this->get_table_name()) . "\n"
            .  ' WHERE ' . implode ( ' ' . $_op . ' ', $_values );

            $this->dbQuery($_sql);

            if ( $_type == strtolower('assoc'))
                $_search_results = $this->GetAssoc();

            else
                $_search_results = $this->GetArray();

            // Move DB Pointer to top of the record set, tis is so the record set
            // can be accessed independantly-- for whatever reason.
            $this->MoveFirst();

        }   // if ( $_data )

        return $_search_results;
    }

   /**
    * Actually remove a record from the Table
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param int $_record_id Comma seperated list of Record IDs to record
    * @return void
    *
    */
    final public function dbKill($_record_ids = false)
    {
            // Build SQL
            $_sql = 'DELETE FROM ' . $this->_quoteField ($this->get_table_name()) . "\n"
                  . ' WHERE `id` IN (' . $_record_ids . ')';

            $this->dbQuery($_sql);
    }


   /**
    * selects the record data and fills up the class
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param int $_id record id to retrieve
    * @param int $_active_only indicates whether to retrieve 'active' records or not
    * @return void
    *
    */
    public function getRecordByID( $_record_id = false, $_active_only = true )
    {
       /*
        * Default return value for method
        * Assume we failed
        *
        * @var private mixed array of order details, or FALSE if not found
        * @name $_found
        * @access private
        *
        * @PHPUnit Not Defined|Implement|Completed
        */
        $_found = false;

        // Short cut to make sure we have what we need. String wrapped INT
        // will be converted its proper INT value, anything else will be set
        // to ZERO. Everything this is except TRUE, we have to force that one.
        $_record_id = ($_record_id === true) ? 0 : (int)$_record_id;

        // Only update property if we have a postive integer
        if ( ( is_integer($_record_id) ) && ( $_record_id > 0 ) )
            $this->set_primary_key_value($_record_id);

        if ( $this->get_primary_key_value() > 0 )
        {
            // Pull record from DB
            $_sql = 'SELECT * FROM ' . $this->_quoteField($this->get_table_name())  . ' ' .
                     'WHERE '        . $this->_quoteField($this->get_primary_key()) . ' = '
                  . $this->get_primary_key_value();

            $this->dbQuery($_sql);

            // Stick Record Set in Class property
            if ( $this->record_set->rowCount() > 0 )
            {
                // store record off in class properties
                $this->rs_to_properties();
                $_found = true;
            }
            // Or create an error
            else
            {
                $this->ERROR = 'Record [' . $this->get_primary_key_value() . '] was not found.';
            }
        }

        return $_found;
    }


// ==================================================================
// ==================================================================

    /**
     * ?????
     *
     * @access public
     * @final
     * @PHPUnit Not Defined
     *
     * @param string Field names to pull form table
     * @param string Which Field to have results sorted by
     * @param boolean Whether to pull inactive records as well
     * @return void
     */
    public function dbAll ( $_fields = '*', $_sort = false, $_activeOnly = true )
    {
        // Pull record from DB

        $_sql = 'SELECT ' . $_fields . "\n"
              . ' FROM ' . $this->get_table_name() . ' ' . "\n";

        if ( $_activeOnly )
            $_sql .= ' WHERE active=\'' . self::RECORD_TRUE . '\'';

        if ( $_sort )
            $_sql .= ' ORDER BY ' . $this->_quoteField ( $_sort );

        // Stick Record Set in Class property
        if ( $_rs = $this->_db->objConn->Execute($_sql) )
        {
            $this->record_set = $_rs;
        }
        // Or create an error
        else
        {
            $this->ERROR = 'Record [' . $this->get_primary_key_value() . '] was not found.';
            $this->record_set = false;
        }
    }

   /**
    * Returns an array of all active records in this table
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return mixed $_customers array of active customers
    *
    */
    public function getActive()
    {
        $this->dbAll ( '*', true );

        return $this->record_set->GetAssoc();
    }


// ==================================================================
// ==================================================================

   /**
    * Returns the given table field name (not validated as this is an internal call)
    * with the current DB specific field quotes
    *
    * @access protected
    * @final
    * @PHPUnit Not Defined
    *
    * @param str $_field_name Current Table Field name to quote
    * @return str $_quoted_field empty, if nothing is passed, DB specific quoted field name
    *
    */
	protected final function _quoteField ($_field_name = false)
	{
		// Assume we don''t have anything to quote
		$_quoted_field = '';

		if ( $_field_name )
		{
			$_quoted_field = $this->_back_tick . $_field_name . $this->_back_tick;
		}

		return $_quoted_field;
	}
// ==================================================================
// ==================================================================
// DB Table access methods

   /**
    * Sets the name of current table
    *
    * @access final
    * @final
    * @PHPUnit Not Defined
    *
    * @param
    * @return
    *
    */
    final public function set_table_name( $_table = false )
    {
        if ( isset($_table{0}) )
        $this->_table = $_table;
    }

   /**
    * Return the name of current table
    *
    * @access final
    * @final
    * @PHPUnit Not Defined
    *
    * @param
    * @return
    *
    */
    public function get_table_name()
    {
       return $this->_table;
    }

   /**
    * Sets the name of current tables Primary Key
    *
    * @access protected
    * @final
    * @PHPUnit Not Defined
    *
    * @param
    * @return
    *
    */
    protected function set_primary_key($_key_name = false)
    {
        if ( isset($_key_name{0}) )
            $this->_primary = $_key_name;
        else
            $this->_primary = $this->get_table_primarykey($this->get_table_name());
    }

   /**
    * Return the name of current tables Primary Key
    *
    * @access final
    * @final
    * @PHPUnit Not Defined
    *
    * @param
    * @return
    *
    */
    public function get_primary_key()
    {
        return $this->_primary;
    }

   /**
    * Sets the value of current tables Primary Key
    *
    * @access protected
    * @final
    * @PHPUnit Not Defined
    *
    * @param
    * @return
    *
    */
    protected function set_primary_key_value($_key_value = false)
    {
        // Convert to string
        $_key_value = (string)$_key_value;

        if ( isset($_key_value{0}) )
        {
            $_key = $this->get_primary_key();
            $this->$_key = $_key_value;
        }
    }

   /**
    * Return the value of current tables Primary Key
    *
    * @access final
    * @final
    * @PHPUnit Not Defined
    *
    * @param
    * @return
    *
    */
    public final function get_primary_key_value()
    {
        $_key = $this->get_primary_key();
        return $this->$_key;
    }

   /**
    * Cycle through class property values and place in named array
    *
    * @access final
    * @final
    * @PHPUnit Not Defined
    *
    * @param
    * @return
    *
    */
    public final function build_db_fields()
    {
        // Cycle through class property values and place in named array
        foreach($this->_fields as $_i => $_prop)
            // Pull data from class properties and run Magic Quotes
            // to make them SQL "safe"
            //			$fields[$_prop] = $this->_db->objConn->qstr($this->$_prop);
            $_fields[$_prop] = $this->$_prop;

        return $_fields;
    }

   /**
    * If anything was returned, store it off in class properties
    *
    * @access protected
    * @final
    * @PHPUnit Not Defined
    *
    * @param
    * @return
    *
    */
    protected final function rs_to_properties()
    {
        // If anything was returned, store it off in class properties
        if ( $this->RecordCount() > 0 )
        {
            // And since this is a single record, by definition
            // drop the field values into class properties
            foreach ( $this->Fields() AS $_field => $_value )
                $this->set_field_value ($_field, $_value );
        }
        // No records where returned
        else
        {
            $this->ERROR = 'Record [' . $this->get_primary_key_value() . '] was not found.';
        }

    }



// ==================================================================
// ==================================================================
// DB Table Record access methods

// These methods are simple Object verifiation and wrappers to the
// ADORecordSet methods. They simply make sure we have an ADO Record Set
// before caling the ADORecordSet Method



   /**
    * Returns array containing current row, or false if EOF.
    * FetchRow() internally moves to the next record after returning the current row.
    *
    * @link http://phplens.com/adodb/reference.functions.fetchrow.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return mixed $_return_value array containing current row, or false if EOF or not a record set
    *
    */
    public final function FetchRow()
    {
       /**
        * _return_value
        *
        * Method Return value
        * defaults to FALSE
        *
        * @name _return_value
        * @access private
        *
        */
        $_return_value = false;

        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            $_return_value = $this->record_set->FetchRow();

        return $_return_value;
    }

   /**
    * Sets $array to the current row, or false if EOF.
    * FetchInto() internally moves to the next record after returning the current row.
    * Depreciated in favor of FetchRow.
    *
    * @link http://phplens.com/adodb/reference.functions.fetchinto.html
    * *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param array $_array Array to drop current record data into
    * @return void
    *
    */
    public final function FetchInto( &$_array )
    {
        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            $_array = $this->record_set->FetchRow();
    }

   /**
    * Returns the current row as an object.
    * Depreciated in favor of FetchNextObject for of accessing rows as objects
    *
    * @link http://phplens.com/adodb/reference.functions.fetchobject.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param boolean $toupper object fields are set to upper-case
    * @return mixed $_return_value Object containing current row, or false if EOF or not a record set
    *
    */
    public final function FetchObject( $_toupper = true )
    {
       /**
        * _return_value
        *
        * Method Return value
        * defaults to FALSE
        *
        * @name _return_value
        * @access private
        *
        */
        $_return_value = false;

        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            $_return_value = $this->record_set->FetchObject($_toupper);

        return $_return_value;
    }

   /**
    * Gets the current row as an object and moves to the next row automatically.
    * Returns false if at end-of-file.
    *
    * @link http://phplens.com/adodb/reference.functions.fetchnextobjects.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param boolean $toupper object fields are set to upper-case
    * @return mixed $_return_value Object containing current row, or false if EOF or not a record set
    *
    */
    public final function FetchNextObject( $_toupper = true )
    {
       /**
        * _return_value
        *
        * Method Return value
        * defaults to FALSE
        *
        * @name _return_value
        * @access private
        *
        */
        $_return_value = false;

        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            $_return_value =  $this->record_set->FetchNextObject($_toupper);

        return $_return_value;
    }

   /**
    * Returns the current record as an object. Fields are not upper-cased, unlike FetchObject.
    *
    * @link http://phplens.com/adodb/reference.functions.fetchobj.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return mixed $_return_value Object containing current row, or false if EOF or not a record set
    *
    */
    public final function FetchObj()
    {
       /**
        * _return_value
        *
        * Method Return value
        * defaults to FALSE
        *
        * @name _return_value
        * @access private
        *
        */
        $_return_value = false;

        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            $_return_value = $this->record_set->FetchObj();

        return $_return_value;
    }

   /**
    * Returns the current record as an object. Fields are not upper-cased, unlike FetchObject.
    *
    * @link http://phplens.com/adodb/reference.functions.fetchnextobj.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return mixed $_return_value Object containing current row, or false if EOF or not a record set
    *
    */
    public final function FetchNextObj()
    {
       /**
        * _return_value
        *
        * Method Return value
        * defaults to FALSE
        *
        * @name _return_value
        * @access private
        *
        */
        $_return_value = false;

        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            $_return_value = $this->record_set->FetchNextObj();

        return $_return_value;
    }

   /**
    * Generate a 2-dimensional array of records from the current cursor
    * position, indexed from 0 to $number_of_rows - 1
    *
    * @link http://phplens.com/adodb/reference.functions.getrows.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param int $_row_count how many rows, from current cursor point to return
    * @return mixed $_return_value array containing current row, or false if EOF or not a record set
    *
    */
    public final function GetRows($_row_count = -1)
    {
       /**
        * _return_value
        *
        * Method Return value
        * defaults to FALSE
        *
        * @name _return_value
        * @access private
        *
        */
        $_return_value = false;

        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            $_return_value =  $this->record_set->GetArray($_row_count);

        return $_return_value;
    }

   /**
    * Generate a 2-dimensional array of records from the current cursor
    * position, indexed from 0 to $number_of_rows - 1
    *
    * @link http://phplens.com/adodb/reference.functions.getarray.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param int $_row_count how many rows, from current cursor point to return
    * @return mixed $_return_value array containing current row, or an empty if EOF or not a record set
    *
    */
    public final function GetArray($_row_count = -1)
    {
       /**
        * Method Return value
        * defaults to an empty array
        *
        * @name $_data_array
        * @access private
        *
        */
        $_data_array = array();

        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            $_data_array =  $this->record_set->GetArray($_row_count);

        return $_data_array;
    }

   /**
    * Returns an associative array for the recordset. If the number of columns
    * returned is greater to two, a 2-dimensional array is returned, with the
    * first column of the recordset becomes the keys to the rest of the rows.
    *
    * @link http://phplens.com/adodb/reference.functions.getassoc.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param boolean $force_array array is created for each value
    * @return mixed $_return_value associative array containing current row, or empty array if EOF or not a record set
    *
    */
    public final function GetAssoc($_force_array = false)
    {
       /**
        * Method Return value
        * defaults to an empty array
        *
        * @name $_data_array
        * @access private
        */
        $_data_array = array();

        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            $_data_array =  $this->record_set->GetAssoc($_force_array);

        return $_data_array;
    }

   /**
    * Returns an associative array for the recordset. If the number of columns
    * returned is greater to two, a 2-dimensional array is returned, with the
    * first column of the recordset becomes the keys to the rest of the rows.
    *
    * @link http://phplens.com/adodb/reference.functions.move.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param int $to record to move cursor to, defaults to first record
    * @return boolean $_moved if TRUE, cursor was moved, if FALSE it could not move
    *
    */
    public final function Move($_to = 0)
    {
        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            return $this->record_set->Move($_to);
    }

   /**
    * Move the internal cursor to the next row.
    *
    * @link http://phplens.com/adodb/reference.functions.movenext.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return boolean $_moved if TRUE, cursor was moved, if FALSE it could not move
    *
    */
    public final function MoveNext()
    {
        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            return $this->record_set->MoveNext();
    }

   /**
    * Move the internal cursor to the first row row.
    *
    * @link http://phplens.com/adodb/reference.functions.movefirst.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return boolean $_moved if TRUE, cursor was moved, if FALSE it could not move
    *
    */
    public final function MoveFirst()
    {
        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            return $this->record_set->MoveFirst();
    }

   /**
    * Move the internal cursor to the last row.
    *
    * @link http://phplens.com/adodb/reference.functions.movelast.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return boolean $_moved if TRUE, cursor was moved, if FALSE it could not move
    *
    */
    public final function MoveLast()
    {
        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            return $this->record_set->MoveFirst();
    }

   /**
    * Returns the current row of the record set.
    *
    * @link http://phplens.com/adodb/reference.functions.absoluteposition.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return boolean $_moved if TRUE, cursor was moved, if FALSE it could not move
    *
    */
    public final function AbsolutePosition()
    {
        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            return $this->record_set->AbsolutePosition();
    }

   /**
    * Returns the current row of the record set.
    *
    * @link http://phplens.com/adodb/reference.functions.currentrow.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return boolean $_moved if TRUE, cursor was moved, if FALSE it could not move
    *
    */
    public final function CurrentRow()
    {
        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            return $this->record_set->CurrentRow();
    }

   /**
    * Returns the number of rows in the record set.
    *
    * @link http://phplens.com/adodb/reference.functions.recordcount.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return int $_count number of records in record set
    *
    */
    public final function RecordCount()
    {
        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            return $this->record_set->RecordCount();
    }

   /**
    * Returns the number of fields in the record set.
    *
    * @link http://phplens.com/adodb/reference.functions.fieldcount.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return int $_count number of fields in record set
    *
    */
    public final function FieldCount()
    {
        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            return $this->record_set->FieldCount();
    }

   /**
    * Returns the value of the associated column for the current row
    *
    * @link http://phplens.com/adodb/reference.functions.fields.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return array array of record field names
    *
    */
    public final function Fields()
    {
        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            $_fields = $this->record_set->fields;
        else
            $_fields = $this->_display_fields();

        return $_fields;
    }

   /**
    * Returns the value of the associated column for the current row
    *
    * @link http://phplens.com/adodb/reference.functions.fields.html
    *
    * @name Field
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param string $_colname name of field to return value
    * @return int $_count number of records in record set
    *
    */
    public final function Field($_colname)
    {
        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            return $this->record_set->Fields($_colname);
    }

   /**
    * Closes the recordset, cleaning all memory and resources associated with the recordset.
    *
    * @link http://phplens.com/adodb/reference.functions.close.html
    *
    * @uses class ADOdb Record Sets
    * @uses ADOdb
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return void
    *
    */
    public final function Close()
    {
        // pass call only if we have a record set object
        if ( is_object ( $this->record_set ) )
            return $this->record_set->Close();
    }


// ==================================================================
// ==================================================================

   /**
    * Set bypass flag on form validation
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    */
    public final function set_form_validation ( $_flag = true )
    {
        $this->validate = $_flag;
    }

// =====================================================================
// Psuedo-Abstract Class methods

   /**
    * Any data validation, before INSERT or UPDATE, is performed here.
    * This method should be defined in the child class.
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    *
    * @param void
    * @return boolean $_results Boolean indicating if data was validated or not
    *
    */
    protected function validate_data() {}


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


// =====================================================================
// Field getters and setters

   /**
    * defines a class property
    *
    * @uses $_fields Class property of table fields
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param int $_field table field to define
    * @param int $_value value to set defined table field to
    * @return void
    *
    */
    public final function set_field_value( $_field = false, $_value = null )
    {
        // if a field name was given, set it, regardless of what was sent
        if ( $_field )
            $this->$_field = $_value;
    }

   /**
    * retrieves a class property
    *
    * @uses $_fields Class property of table fields
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param int $_field table field to pull
    * @return void
    *
    */
    public final function get_field_value( $_field = false )
    {
       /**
        * Default return value.
        *
        * @var mixed $_retVal returned value
        * @access private
        * @static
        */
        $_retVal = false;

        // if a field name was given, retrieve its value, if there is one
        if ( $_field )
            $_retVal =  (isset($this->$_field)) ? $this->$_field : null;

        return $_retVal;
    }

   /**
    * Create an array of the tables fields and their current value
    * for debugging purposes.
    *
    * @access protected
    * @final
    * @PHPUnit Not Defined
    *
    * @param void
    * @return void
    *
    */
    protected final function _display_fields ()
    {
        foreach ( $this->_fields AS $_field )
            $_fields[$_field] = $this->$_field;

        return $_fields;
    }

   /**
    * If the database does not exist and you would like ADOdb Lite
    * to automatically create the database then set the createdatabase
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

   /**
    * Wraps each value of an element in a single quote and then
    * JOINs the values in a COMMA seperated list
    *
    * @access protected
    * @final
    * @PHPUnit Not Defined
    *
    * @param array $_array Simple array to join together
    * @return void
    *
    */
    protected final function _quote_and_join ( array $_array = array())
    {
        // Private function for this method only
	    function quote_it($_value)  { return '\'' . $_value . '\''; };

        $_array = join(',', array_map('quote_it', $_array) );

        return $_array;
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


// =====================================================================
// Error Handling

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

   /**
    * ADODB_Pager wrapper
    *
    * @uses ADODB_Pager
    *
    * @access public
    * @final
    * @PHPUnit Not Defined
    *
    * @param $sql
    * @return $id
    *
    */
    public final function pager ($sql, $id)
    {
    	include_once(LIBRARY. '/adodb5/adodb-pager.inc.php');
		$pager=new ADODB_Pager($this->_db->objConn,$sql,$id,false);
        return $pager;
    }

};

// =====================================================================
// =====================================================================

/**
 * $Header: $
 *
 * $Log: $
 *
 */

// eof
