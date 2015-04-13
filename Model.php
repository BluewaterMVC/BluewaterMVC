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
 * @subpackage  Model
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
 * Series of DB access methods for better access to the ADOdb Library
 *
 * This class is based off of ADOdb: Supports Frontbase, MaxDB, MiniSql, MSSQL,
 * MySql, Postgres, SqLite and Sybase.
 *
 * @link http://adodb.sourceforge.net/
 * @link http://sourceforge.net/projects/adodb/
 *
 * @category    Bluewater
 * @package     Bluewater_Core
 * @subpackage  Model
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
// ==========================================================
   /**
    * INCLUDE ADOdb access library
    *
    * This class is based upon ADOdb. It won't work without it.
    * @link http://phplens.com/lens/adodb/docs-adodb.htm
    *
    */
    require_once LIBRARY . '/adodb5/adodb.inc.php';

    // Force Indexing By Name
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

    // Force Lower-case Keys
    define('ADODB_ASSOC_CASE', 0);

// ==========================================================


class Bluewater_Model extends Bluewater_Singleton_Abstract
{
// ==========================================================
// Class Properties

   /**
    * Data source type. ie: db, ftp, etc
    *
    * @name _type
    *
    * @access private
    * @final
    *
    * @since 1.0
    *
    */
    protected $_type = false;

   /**
    * name of Data source
    *
    * @name _data_name
    *
    * @access private
    * @final
    *
    * @since 1.0
    *
    */
    protected $_data_name = false;

   /**
    * Database Connection Object
    *
    * @name objDB
    *
    * @access public
    * @final
    *
    * @since 1.0
    *
    */
    public $objDB = false;

   /**
    * Decides if new database should be created upon connection
    *
    * @name _sources
    *
    * @access private
    * @final
    *
    * @since 1.0
    *
    */
    protected $_sources = false;

   /**
    * Helper Object Container
    *
    * @var object $helper Helper Object Container
    * @access public
    *
    * @since 1.0
    *
    */
    protected $helper = false;

   /**
    * Central Logging Class
    *
    * @var string $logger Central Logging Class
    * @access public
    *
    * @since 1.0
    *
    */
    protected $logger = false;

// ==========================================================
// Class Methods

   /**
    *  Prevent singletons from being instantiated other than via getInstance();
    *
    * @access protected
    * @final
    *
    * @since 1.0
    *
    * @param  none
    * @return void
    *
    */
    final protected function __construct()
    {
        // Load Logger Class, but only if logging is turned on
        if ( LOGGER_ERR || LOGGER_TRACE || LOGGER_SQL )
            $this->logger = Bluewater_Logger::getInstance();

        // Load Helper Support Class
        $this->helper = Bluewater_Helper::getInstance();

        // Pull list of data sources (databases, FTP, rss, etc) that
        // this app can use.
        $_source_list = Bluewater_Config::config('data_sources', 'sources');

        if ( $_source_list !== null )
        {
            foreach ( $_source_list as $_source_name )
            {
                $_name = strtolower($_source_name);

                // Pull source credentials from Bluewater_Config
                $_data = Bluewater_Config::config($_source_name);
                $_source = $_data['db_source'];
                unset($_data['db_source']);

                // Place into array by data source type
                $this->_sources[$_source][$_name] = $_data;
            }
        }
    }

   /**
    * Attempts to "destroy" a DB conection Object
    *
    * @access public
    * @final
    *
    * @since 1.0
    *
    * @param none
    * @return void
    *
    */
    final public function __destruct()
    {
/*
        if (isset($this->objConn) && is_object($this->objConn))
        {
            $this->objConn->close();
            unset($this->objConn);
        }
*/
    }

   /**
    * Magical PHP Method to call unknown methods.
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
    * @param  string $_model_type Type of Data Model to access
    * @param  array  $_args       An array of arguements to send to method
    * @return mixed  $objDB       Whatever operation Model Class performs
    *
    */
    public final function __call($_model_type, array $_args = array())
    {
        // Make sure the data type asked for has been defined in the app.ini file

        // Yes, array_keys_exist() is usually what you might want here,
        // but in this case we are interested in whether the connection
        // name has some value. Besides, isset() is much faster!
        if ( isset($this->_sources[$_model_type]) )
        {
            if ( isset($_args[0]) )
            {
                // The $_args coming in should be a single name; the name of
                // the data model to use as defined in the app.config.ini file
                $_data_name = $_args[0];

                // Make sure we have what is asked for
                if ( isset($this->_sources[$_model_type][$_data_name]) )
                {
                    // Retrieve element type; no reason to ask this several times
                    $_type = gettype($this->_sources[$_model_type][$_data_name]);

                    // If this is an array, then a connection object has not been
                    // created yet, it needs to be created.
                    if ($_type == 'array')
                    {
                        // Create new DB connection Object
                        $objDB = $this->_factory($_model_type, $_data_name);

                        // Reassigning DB connection Object to simplified format
                        $this->_sources[$_model_type][$_data_name] = $objDB->objADODB;
                    }
                    // If this is an object, then use is
                    else if ($_type != 'object')
                    {
                        // Now we THROW an exception to handle this failure on our own terms.
                        throw new Exception('Data Definition for "' . $_args[0] . '" was defined improperly.');
                    }

                    // Return Object for Method Chaining
                    return $this->_sources[$_model_type][$_data_name];

                }
            }
            else
            {
                // Now we THROW an exception to handle this failure on our own terms.
                throw new Exception('Data Definition for "' . $_args[0] . '" was not defined in Application INI file.');
            }
        }
        else
        {
            // Now we THROW an exception to handle this failure on our own terms.
            throw new Exception('Model Definition for "' . $_model_type . '" was not defined in Application INI file.');
        }

    }

   /**
    * Factory method to create a seperate connection object for each possible data source
    *
    * @access private
    * @final
    *
    * @since 1.0
    *
    * @param $_model_type string Type of Database or other data access method
    * @return void
    *
    */
    final private function _factory($_model_type = false, $_data_name = false)
    {
        // Only works if we know what data model to use
        if ( ( $_model_type !== false )
          && ( $_data_name  !== false ) )
        {
            // Create Class name
            $_class = 'Bluewater_Model_'
                    .  $this->helper->Upper_First($_model_type)
                    .  '_'
                    .  $this->helper->Upper_First($this->_get_source_type($_model_type, $_data_name))
                    .  '_Connection';

            return new $_class($this->_get_dsn($_model_type, $_data_name));
        }
    }

   /**
    * Retrieve DSN property values from Config data
    *
    * @access private
    * @final
    *
    * @since 1.0
    *
    * @param $_model_type string Type of Database or other data access method
    * @return $_dsn array Data source access credentials
    *
    */
    final private function _get_dsn($_model_type = false, $_data_name = false)
    {
        // Default return value
        $_dsn = false;

        // Only works if we know what data model to use
        if ( ( $_model_type !== false )
          && ( $_data_name  !== false ) )
        {
            $_dsn = $this->_sources[$_model_type][$_data_name];
        }

        return $_dsn;
    }

   /**
    * Retrieve Data source Type from Config data
    *
    * @access private
    * @final
    *
    * @since 1.0
    *
    * @param $_model_type string Type of Database or other data access method
    * @return $_type string Type of data access model
    *
    */
    final private function _get_source_type($_model_type = false, $_data_name = false)
    {
        // Default return value
        $_type = false;

        // Only works if we know what data model to use
        if ( ( $_model_type !== false )
          && ( $_data_name  !== false ) )
        {
            $_type = $this->_sources[$_model_type][$_data_name]['db_type'];
        }

        return $_type;
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

