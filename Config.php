<?php

/**
 * Configuration Class to process Bluewater MVC Core config file
 * and an application level config file.
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
 * @subpackage  Support
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
  * Configuration Class to process Bluewater MVC Core config file
  * and an application level config file.
  *
  * Configuration files are standard INI format file.
  *
  * @package     Bluewater_Core
  * @subpackage  Support
  * @link        http://web.bluewatermvc.org
  *
  * @PHPUnit Not Defined
  *
  * @howto {@link http://guides.bluewatermvc.org/doku.php/dev/classes/general/config#configuration_class}
  * @example url://path/to/example.php description
  *
  * @todo Create PHPUnit test, tutorials and example files for this class
  * @todo look into converting this into a DBA access type class via SPL
  *
  */
class Bluewater_Config
{
// ==========================================================
// Class Constants

// ==========================================================
// Class Properties

   /**
    * Class instance
    *
    * @var object
    * @access private
    * @static
    *
    * @since 1.0
    */
    private static $_instance = false;

   /**
    * Config data in named array
    *
    * @var array
    * @access private
    * @static
    *
    * @since 1.0
    */
    private static $_conf = null;

   /**
    * Flag to determine whether to process and 'label' sections
    * within CONFIG file
    *
    * @var bool
    * @access private
    * @static
    *
    * @since 1.0
    */
    private static $_process_sections = true;

   /**
    * Helper Object Container
    *
    * @var object
    * @access private
    * @static
    *
    * @since 1.0
    */
    private static $helper = null;

// ==========================================================
// Class Methods

   /**
    * Load actual config files for processing.
    *
    * If config files are not defined, no error is thrown.
    *
    * @uses Bluewater_Helper
    * @uses Helper_Array_Insert
    *
    * @uses Bluewater_Config::_load()
    * @uses Bluewater_Config::_parse_constants()
    * @uses Bluewater_Config::_parse_fields()
    * @uses Bluewater_Config::local()
    *
    * @uses Bluewater_Config::$_process_sections
    * @uses Bluewater_Config::$helper
    * @uses Bluewater_Config::$_conf
    *
    * @uses BLUEWATER
    * @uses APP_ROOT
    *
    * @final
    * @access private
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  bool $process_sections Whether to seperate Sections into pieces
    * @return void
    *
    */
    final private function __construct($process_sections = true)
    {
        // Keep property for other methods
        self::$_process_sections = $process_sections;

        // Load Helper Support Class
        self::$helper = new Bluewater_Helper;

        // Path to main Config file to load
        $_main_config = BLUEWATER . '/Bluewater.ini.php';

        // Try to load main file config file into class instance
        if(file_exists($_main_config))
        {
            self::$_conf = self::_load($_main_config);

            // Application config file to load
            $_app_config = APP_ROOT . '/Config/app.ini.php';

            // See if we have a Application config to handle
            if(file_exists($_app_config))
            {
                // Load the Application config
                $_app_config = self::_load($_app_config);

                // Update config array with these new items
                self::$_conf = self::$helper->array_insert(self::$_conf, $_app_config);
            }

            // Build CONSTANTS from both INI files
            self::_parse_constants();

            // Build CONFIG name/value pairs
            self::_parse_fields();

            // Process i18n settings
            self::locale();

            // Set default TZ
            date_default_timezone_set(Bluewater_Config::config('general', 'tz'));
        }
    }

   /**
    * A private 'clone' method it ensure that this singleton class
    * can not be duplicated.
    *
    * @final
    * @access private
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  void
    * @return void
    *
    */
    final private function __clone() {}

   /**
    * Makes sure that this call is a single instance and returns that instance
    *
    * @uses Bluewater_Config
    *
    * @uses Bluewater_Config::$_instance
    *
    * @static
    * @final
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  bool   $_process_sections  Whether to seperate section into seperate pieces
    * @return object $_instance          Class instance
    *
    */
    static final public function init($_process_sections = true)
    {
        // If we don't have an instance, make one
        if ( isset(self::$_instance) )
            self::$_instance = new Bluewater_Config($_process_sections);

        return self::$_instance;
    }

   /**
    * Retrieve section of config data or specfic config element
    *
    * @uses Bluewater_Config::$_conf
    *
    * @static
    * @final
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param string $_section Which section to pull
    * @param string $_field   specfic config element to pull info
    * @return array $_conf    Config info array
    *
    */
    static public final function config($_section = false, $_field = false)
    {
        // assume we fail
        // Give back a NULL, as FALSE could be an expected value
        // But then so can NULL :/
        $_conf = null;

        // If a SECTION is requested, see if we have it
        if ( $_section )
        {
            // If SECTION exists, pull it
            if ( array_key_exists($_section, self::$_conf))
            {
                // If a specific FIELD is requested
                if ( $_field )
                {
                    // See if that FIELD exists
                    if ( array_key_exists($_field, self::$_conf[$_section]))
                    {
                        $_conf = self::$_conf[$_section][$_field];
                    }
                }

                // Otherwise retun the entire SECTION
                else
                {
                    $_conf = self::$_conf[$_section];
                }
            }
        }

        // Otherwise just give back the entire config array
        else
            $_conf = self::$_conf;

        return $_conf;
    }


   /**
    * Add or update specific config element
    *
    * @uses Bluewater_Config::$_process_sections
    * @uses Bluewater_Config::$_conf
    *
    * @static
    * @final
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  string $_section Which section to place config element into
    * @param  string $_field   Specfic config element to place value into
    * @param  string $_value   Value to place into config element
    * @return void
    *
    */
    static public final function setConfig($_section = false, $_field = false, $_value = false)
    {

        if ( self::$_process_sections )
        {
            if ( ! isset(self::$_conf[$_section]) )
                self::$_conf[$_section] = array();

            self::$_conf[$_section][$_field] = $_value;
        }

        else
            self::$_conf[$_field] = $_value;
    }

   /**
    * Load config data into Class propery array
    * This method is based upon the example given at
    * {@link http://us.php.net/manual/en/function.parse-ini-file.php#82900}
    * by asohn@aircanopy.net
    *
    * @uses Bluewater_Config::$_process_sections
    *
    * @static
    * @final
    * @access private
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  string $file    INI file to load
    * @return array  $_result Results of INI file parsing
    *
    */
    static private final function _load($file = false)
    {
        // create empty array, just incase
        $_result = array();

        // Create storage array
        $_ini_data = array();

        // What section are we working on
        $section = '';

        // Determine if there is a file to open
        if(is_file($file)) {

            // Load the INI file, raw
            $ini = file($file);

            // If the file is empty, return empty array
            if (count($ini) == 0)
                return array();

            // Define some internal variables
            $sections = array();
            $values = array();
            $i = 0;

            // walk down the file and parse
            foreach ($ini as $line) {
                $line = trim($line);
                $line = str_replace("\t", ' ', $line);

                // Comments
                if (!preg_match('/^[a-zA-Z0-9[]/', $line))
                    continue;

                // Sections
                if ($line{0} == '[')
                {
                    // remove square brackets
                    $section = str_replace(array('{', '[', ']', '}'),'',$line);
                    $sections[] = $section;
                    continue;
                }


                // In order to prevent this from blowing up if an '='
                // is not given, check for it first...
                // And add it if we don't have it
                if ( ! stristr($line, '=') )
                    $line .= ' = ';

                // Key-value pair
                list($key, $value) = explode('=', $line, 2);

                // Trim things up
                $key = trim($key);
                $value = trim($value);

                // Drop comments
                $tmp = explode(';', $value);

                if (count($tmp) == 2) {
                    if ((($value{0} != '"') && ($value{0} != "'")) ||
                         preg_match('/^".*"\s*;/', $value) ||
                         preg_match('/^".*;[^"]*$/', $value) ||
                         preg_match("/^'.*'\s*;/", $value) ||
                         preg_match("/^'.*;[^']*$/", $value) ) {
                        $value = $tmp[0];
                    }
                }

                // Store name / value pairs
                if (self::$_process_sections === true)
                {
                    // See if this is a double level
                    $_section = explode (':', $section, 2);

                    // Store at double level
                    if ( count($_section) == 2)
                        $_result[$_section[0]][$_section[1]][$key] = $value;

                    // Single level
                    else
                        $_result[$_section[0]][$key] = $value;
                }
                else
                    $_result[$key] = $value;
            }
        }

        return $_result;

    }

   /**
    * Converts items defined as constants into PHP constants and then
    * removes the contant list from the class.
    *
    * @uses Bluewater_Config::_parse_value()
    *
    * @uses Bluewater_Config::$_conf
    *
    * @static
    * @final
    * @access private
    *
    * @PHPUnit Not Defined
    *
    * @staticvar array $_conf['constants'] array of defined constants
    *
    * @param  void
    * @return void
    */
    static private final function _parse_constants()
    {
        // Pull CONSTANTS from self::$_conf
        foreach ( self::$_conf['constants'] AS $_constant => $_value )
        {
            if (! defined(strtoupper($_constant)))
                define($_constant, self::_parse_value($_value, 'constants'));
        }

        // Dump 'constants' array
        unset (self::$_conf['constants']);
    }

   /**
    * Parses name/value pairs into variable names and their values.
    *
    * @uses Bluewater_Config::_parse_value()
    *
    * @uses Bluewater_Config::$_conf
    *
    * @static
    * @final
    * @access private
    *
    * @PHPUnit Not Defined
    *
    * @staticvar array $_conf['constants'] array of defined constants
    *
    * @param  void
    * @return void
    */
    static private final function _parse_fields()
    {
        // Pull sections from self::$_conf
        foreach ( self::$_conf AS $_section => $_set )
        {
            // Loop through each section and process the name/value pairs
            foreach ($_set AS $_name => $_value)
                self::$_conf[$_section][$_name] = self::_parse_value($_value, $_section);
        }
    }

   /**
    * Recursive method to parse individual value of a variable
    * and convert it into its PHP datatype equivalent
    *
    * @uses Bluewater_Config::_save_boolean()
    *
    * @static
    * @final
    * @access private
    *
    * @PHPUnit Not Defined
    *
    * @param  string         $value   Value of name/value pair to convert
    * @param  boolean|string $section FALSE or 'constants', used to parse value as a constant or not
    * @return string         $value   Converted value
    */
    static private final function _parse_value($value, $section = false)
    {
        // This only has to process if $value has anything
        if (isset($value{0}) )
        {
            // Pull existing CONSTANTS
            $_php_constants = get_defined_constants();

            // Remove leading and trailing white space
            $value = trim($value);

            // See if a defined CONSTANT has been called for
            // Anything inside curly braces [{}] is understood
            // to be a constant

            // Replace curlys with a pair of tidles
            $value = str_replace(array('{', '}'), '~~', $value);

            // split 'value' into pieces based upon '~~'
            // this gives us multiple CONSTANTS, if used
            $value = explode ( '~~', $value );

            // Walk down this list of values
            foreach ( $value AS $_value )
            {
                // Reassign to CONSTANT value, if it exists
                if ( isset($_php_constants[$_value]))
                    $_value = $_php_constants[$_value];

                // Place into array
                $_constants[] = $_value;
            }

            // Build new value
            $value = join ( '', $_constants );

            // If the value is QUOTED, single or double, the QOUTES need
            // to be stripped and the value left as it is
            $reg_ex = '/^([\'"])?(.+?)([\'"])?$/';
            preg_match($reg_ex, $value, $_match);

            // If $_match has a length of 4, then the value is quoted
            if ( count($_match) == 4)
            {
                $value = $_match[2];
            }

            // otherwise value can be parsed to se if it should be made
            // into an array
            else
            {
                // If $value contains a COMMA, than $value is really an array
                // Except a CONSTANT cannot be an array
                if ( ( $section != 'constants' ) && (strstr($value, ',') ) )
                {
                    // Split and trim at the same time!
                    $value = preg_split ('/\s*[,]\s*/', $value);

                    foreach ($value as $_i => $_value)
                        // convert booleans and nulls
                        $value[$_i] = self::_save_boolean($value[$_i]);
                }

                // A Single value than
                else
                {
        			// convert booleans and nulls
                    $value = self::_save_boolean($value);
                }
           }

        }

        return $value;
    }

   /**
    * Converts individual value of a variable into a BOOLEAN or a PHP NULL
    *
    * @static
    * @final
    * @access private
    *
    * @PHPUnit Not Defined
    *
    * @param  string         $_value   Value of name/value pair to convert
    * @return booledan|null  $_value   Converted value
    */
    static final private function _save_boolean($_value)
    {
		// convert booleans and nulls
		switch (strtolower($_value)) {
		case 'true':
			$_value = true;
			break;
		case 'false':
			$_value = false;
			break;
		case 'null':
			$_value = null;
			break;
		}

		return $_value;
    }


   /**
    * Loads the LOCALE config setting, defines 'locale' and activates
    * get_text and its settings
    *
    * @uses get_text
    * @uses APP_ROOT
    * @uses Bluewater_Config::config()
    * @uses Bluewater_Config::setConfig()
    *
    * @final
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @param  string $_domain Which 'domain' of messages to use for translation
    * @return void
    *
    */
    public final function locale ($_domain = 'core')
    {
        // This is not defined on WINDOWS
        if (!defined('LC_MESSAGES'))
            define('LC_MESSAGES', 6);

        $locale = Bluewater_Config::config('locale');

        $locale = $locale['lang'] . '_' . $locale['country'];
        self::setConfig('locale', 'i18n', $locale);

        putenv('LANGUAGE=' . $locale);  // for some Linux systems
        putenv('LC_ALL=' . $locale);    // for everyone else

        // setlocale is case-sensitive
        //setlocale(LC_ALL, $locale);
        $locale_set = setlocale(LC_ALL, $locale . ".utf8",
                                        $locale . ".UTF8",
                                        $locale . ".utf-8",
                                        $locale . ".UTF-8",
                                        $locale);

        bindtextdomain($_domain, APP_ROOT . '/locale');
        bind_textdomain_codeset($_domain, 'UTF-8');
        textdomain($_domain);
    }

};


// eof
