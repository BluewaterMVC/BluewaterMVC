<?php

/**
 * Logs text to a file.
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
 * @subpackage  Bluewater_Logger
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
 */


/**
 * Logs text to a file.
 * Loosely based upon Kevin Waterson code from PHPRO.ORG.
 *
 * Walter 'updated' it to work a bit smoother and use a DB instead of a file
 *
 * @package     Bluewater_Core
 * @subpackage  Bluewater_Logger
 *
 * @PHPUnit Not Defined
 *
 * @example usage
 * $log = logger::getInstance();
 * $log->write('An error has occured', $err_level, $_echo);
 *
 * $Id: $
 * @filename  $RCSfile: $
 * @version   $Revision: $
 * @date      $Date: $
 * @copyright (c) PHPRO.ORG (2009)
 * @license   Licensed under the GNU GPL. For full terms see the file COPYING.
 *            OSI Certified Open Source Software
 *
 * @filesource
 *
 */
class Bluewater_Logger
{
   /**
    * Class instance
    *
    * @var object
    * @access private
    * @static
    *
    * @since 1.0
    */
    private static $_instance = NULL;

   /**
    * Continer to hold DB Table Object
    *
    * @var object
    * @access private
    * @static
    *
    * @since 1.0
    */
    private static $_objTable = null;

    /**
     *
     * @Constructor is set to private to stop instantion
     *
     */
    private function __construct()
    {
        // Leave empty on purpose
    }

    /**
     *
     * Magic Property Setter
     *
     * @access public
     *
     * @param string $name
     * @param mixed $value
     *
     */
    public function __set($name, $value)
    {
        switch($name)
        {
            case 'logfile':
                if(!file_exists($value) || !is_writeable($value))
                    throw new Exception("$value is not a valid file path");

                $this->logfile = $value;
            break;

            default:
               throw new Exception("$name cannot be set");
        }
    }

    /**
     *
     * Write to the logfile
     *
     * @access public
     * @uses self::backtrace()
     *
     * @param string $message
     * @return number of bytes written, false other wise
     *
     */
    public function write($_message = '', $_event_type = 1, $_echo = false)
    {
        // See if this should be skipped
        if ( ($_event_type == 2) && (LOG_SQL === false) )
            return;

        if ( ($_event_type == 4) && (LOG_ERR === false) )
            return;

        if ( ($_event_type == 1) && (LOG_TRACE === false) )
            return;

        // Discover who called this and from where
        $back = backtrace();

        self::$_objTable->id            = null;
        self::$_objTable->event         = $_message;
        self::$_objTable->event_type_id = $_event_type;
        self::$_objTable->file          = $back[0]['file'];
        self::$_objTable->line          = $back[0]['line'];
        self::$_objTable->timestamp     = date('Y-m-d H:i:s');

        self::$_objTable->dbInsert();

        // Reflect it back out, if asked to
        if ( $_echo )
        {
            echo $_message . "<br />\r\n";
            flush();
        }

    }

    /**
    *
    * Return logger instance or create new instance
    *
    * @access public
    *
    * @return object (PDO)
    *
    */
    public static function getInstance()
    {
        if (!self::$_instance)
        {
            self::$_instance = new Bluewater_Logger;

            // instantiate a DB Table Object, but only if we are to log to database
            if ( LOGGER_SQL )
                self::$_objTable = new Bluewater_DB_Table_Generic('event_log');
        }

        return self::$_instance;
    }


    /**
     * Clone is set to private to stop cloning
     *
     */
    private function __clone()
    {
    }

   /**
    * a "pretty-print" debug_backtrace, pulled from php.net
    *    /www.php.net/manual/en/function.debug-backtrace.php
    *
    * This simple traces the code path to the place this method is called,
    * since the script began. Nothing automatic. Just add "echo backtrace()"
    * anywhere you need to see how you got to particular point
    *
    * @author diz at ysagoon dot com
    * @date 23-Nov-2004 03:40
    *
    * @name backtrace()
    * @access public
    * @category debugging
    *
    * @uses debug_backtrace() built-in PHP method
    * @static
    * @final
    *
    * @param void
    * @return string backtrace result text, formatted
    *
    */
    function backtrace()
    {
        $backtrace = debug_backtrace();

        $_stack = array();

        // remove call to this method
        unset($backtrace[0]);

        foreach ($backtrace as $_i => $bt) {
            $args = '';

            foreach ($bt['args'] as $a)
            {
                if (!empty($args))
                {
                    $args .= ', ';
                }

                switch (gettype($a))
                {
                    case 'integer':
                    case 'double':
                        $args .= $a;
                        break;
                    case 'string':
                        $a = htmlspecialchars(substr($a, 0, 32)).((strlen($a) > 32) ? '...' : '');
                        $args .= "\"$a\"";
                        break;
                    case 'array':
                        $args .= 'Array(' . count($a) . ')';
                        break;
                    case 'object':
                        $args .= 'Object(' . get_class($a) . ')';
                        break;
                    case 'resource':
                        $args .= 'Resource(' . strstr($a, '#') . ')';
                        break;
                    case 'boolean':
                        $args .= $a ? 'True' : 'False';
                        break;
                    case 'NULL':
                        $args .= 'Null';
                        break;
                    default:
                        $args .= 'Unknown';
                }
            }

            // @NOTE some 'magic' PHP functions do not set some of these items
            if ($bt['line'] > 0)
                $_stack[$_i]['line'] = $bt['line'];
            else
                $_stack[$_i]['line'] = 'not defined';

            if (isset($bt['file']{0}))
            {
                // Just want the last directory name of the full file path
                $_dir = explode(DIRECTORY_SEPARATOR, dirname ( $bt['file']));
                $_dir = $_dir[count($_dir)-1];

                $_stack[$_i]['file'] = $_dir . DIRECTORY_SEPARATOR . basename($bt['file']);
            }
            else
                $_stack[$_i]['file'] = 'not defined';

            $_stack[$_i]['call'] = '';

            if (isset($bt['class']{0}))
                $_stack[$_i]['call'] .= $bt['class'] . $bt['type'];

            $_stack[$_i]['call'] .= $bt['function'] . '( ' . $args . ')';

        }

        // (sort of) quick way to renumber array)
        $_stack = array_values ( $_stack );

        return $_stack;
    }

};

// eof
