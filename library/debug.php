<?php


  /**
   * debug.php : simple variable display methods
   *
   * $Id: debug.php,v 1.9 2005/04/04 17:56:39 walter Exp $
   */

// ****************************************************************************
// ****************************************************************************

//. Place this block of code at the top of your config file *BEFORE* you include this file.

/**
  * debug level settings
  * 0 - Debugging off.
  * 1 - Some debugging.  SQL log, do_print_r(), etc.
  * 2 - More debugging.  Some more output
  * 3 - lots of debugging.  Lots more debugging output
  *
  */
// define('DEBUG_LEVEL', 0);

// ****************************************************************************
// ****************************************************************************




   /**
    * Method public mixed do_print_r( mixed [,boolean] [,string] )
    *
    * This mimics 'print_r' but gives a better looking display in HTML.
    *
    * This method will accept any valid PHP variable type and either
    * display the data in a structued format for HTML, or, optionaly
    * return the display information as a sting.
    *
    * This method has the same parameter list as 'print_r', plus a label
    *
    * NOTE: Added a conditional to check PHP version. Seems this was
    *       dieing in older PHPs, since their 'print_r' did not have a
    *       second parameter
    *
    * @author Walter Torres <walter@torres.ws>
    *
    * @name do_print_r
    * @access public
    * @category debugging
    *
    * @uses DEBUG          constant to determine if in DEBUG mode
    * @uses print_r()      built-in PHP method
    * @uses old_print_r()  workaround to handle older PHP parsers
    * @static
    * @final
    *
    * @param mixed   $_what        any valid PHP variable type
    * @param string  $_label      Add label to display
    * @param bool    $_backtrace  Display code trace
    * @param bool    $_output     Either display data or return it as a string
    * @return string $_retData    Data as HTML string
    * @return void
    */
    function do_print_r ( $_what = 'null|someting', $_label = null, $_backtrace = false, $_output = false )
    {
        Bugger::dump( $_what, $_label, $_backtrace, $_output);
    }

class Bugger
{
    static private $_trace = null;

    /**
     * Dump information about a variable
     *
     * @param mixed $variable Variable to dump
     * @param string $caption Caption of the dump
     * @return void
     */
    public static function dump($variable = 'null|someting', $caption = null)
    {
        // prepare the output string
        $html = '';
        $output = '';

        // Only display something if we have something.
        // This makes the display a 'trace marker' if nothing is passed
        if ( $variable !== 'null|someting' )
        {
            // start the output buffering
            ob_start();

            // generate the output
            var_dump($variable);

            // get the output
            $output = ob_get_clean();

            $maps = array(
                'string'    => '/(string\((?P<length>\d+)\)) (?P<value>\"(?<!\\\).*\")/i',
                'array'        => '/\[\"(?P<key>.+)\"(?:\:\"(?P<class>[a-z0-9_\\\]+)\")?(?:\:(?P<scope>public|protected|private))?\]=>/Ui',
                'countable'    => '/(?P<type>array|int|string)\((?P<count>\d+)\)/',
                'resource'    => '/resource\((?P<count>\d+)\) of type \((?P<class>[a-z0-9_\\\]+)\)/',
                'bool'        => '/bool\((?P<value>true|false)\)/',
                'float'        => '/float\((?P<value>[0-9\.]+)\)/',
                'object'    => '/object\((?P<class>[a-z_\\\]+)\)\#(?P<id>\d+) \((?P<count>\d+)\)/i',
            );

            foreach ($maps as $function => $pattern) {
                $output = preg_replace_callback($pattern, array('self', '_process' . ucfirst($function)), $output);
            }
        }

        print '<pre style="' . self::_getContainerCss() . '">'
             . self::_buildHeader($variable, $caption)
             . $output
             . '</pre>';
    }


    /**
     * Build Header
     *
     * @param string  $variable value to display
     * @param string  $caption  caption to display
     * @return string
     */
    private static function _buildHeader($variable, $caption)
    {
        $_varName = self::_get_var_name();
        $_trimVar = trim ( $_varName, '\'"' );

        // Discover who called this and where
        $_header  = '  file: ' . self::$_trace['file'] . ' <br />';
        $_header .= '  line: ' . self::$_trace['line'] . ' <br />';

        if ( $_varName )
        {
            $_constants = get_defined_constants();
            if(isset($_constants[$_varName]))
            {
                $_varType = 'constant';
                $variable = $_constants[$_trimVar];
            }
            else if($_varName{0} == '$')
            {
                $_varType = 'scalar';
            }
            else
            {
                $_varType = 'literal';
            }

            $_varType = '&nbsp;&nbsp;[' . $_varType . '::' . strtolower(gettype($variable)) . ']';

            $_h2 = '<h2 style="' . self::_getHeaderCss() . '">' . $_varName;
            $_h2 .= '<span style="' . self::_getTypeCss() . '">' . $_varType . '</span>';

            if (!empty($caption))
                $_h2 .= ' - ' . $_h2;

            $_header = $_h2 . '</h2>' . $_header . '<hr />';
        }

        // Rebuild output
        return $_header;
    }

    /**
     * Process strings
     *
     * @param array $matches Matches from preg_*
     * @return string
     */
    private static function _processString(array $matches)
    {
        $matches['value'] = htmlspecialchars($matches['value']);
        return '<span style="color: #0000FF;">string</span>(<span style="color: #1287DB;">' . $matches['length'] . ')</span> <span style="color: #6B6E6E;">' . $matches['value'] . '</span>';
    }


    /**
     * Process arrays
     *
     * @param array $matches Matches from preg_*
     * @return string
     */
    private static function _processArray(array $matches)
    {
        // prepare the key name
        $key = '<span style="color: #008000;">"' . $matches['key'] . '"</span>';
        $class = '';
        $scope = '';

        // prepare the parent class name
        if (isset($matches['class']) && !empty($matches['class'])) {
            $class = ':<span style="color: #4D5D94;">"' . $matches['class'] . '"</span>';
        }

        // prepare the scope indicator
        if (isset($matches['scope']) && !empty($matches['scope'])) {
            $scope = ':<span style="color: #666666;">' . $matches['scope'] . '</span>';
        }

        // return the final string
        return '[' . $key . $class . $scope . ']=>';
    }


    /**
     * Process countables
     *
     * @param array $matches Matches from preg_*
     * @return string
     */
    private static function _processCountable(array $matches)
    {
        $type = '<span style="color: #0000FF;">' . $matches['type'] . '</span>';
        $count = '(<span style="color: #1287DB;">' . $matches['count'] . '</span>)';

        return $type . $count;
    }


    /**
     * Process boolean values
     *
     * @param array $matches Matches from preg_*
     * @return string
     */
    private static function _processBool(array $matches)
    {
        return '<span style="color: #0000FF;">bool</span>(<span style="color: #0000FF;">' . $matches['value'] . '</span>)';
    }


    /**
     * Process floats
     *
     * @param array $matches Matches from preg_*
     * @return string
     */
    private static function _processFloat(array $matches)
    {
        return '<span style="color: #0000FF;">float</span>(<span style="color: #1287DB;">' . $matches['value'] . '</span>)';
    }


    /**
     * Process resources
     *
     * @param array $matches Matches from preg_*
     * @return string
     */
    private static function _processResource(array $matches)
    {
        return '<span style="color: #0000FF;">resource</span>(<span style="color: #1287DB;">' . $matches['count'] . '</span>) of type (<span style="color: #4D5D94;">' . $matches['class'] . '</span>)';
    }


    /**
     * Process objects
     *
     * @param array $matches Matches from preg_*
     * @return string
     */
    private static function _processObject(array $matches)
    {
        return '<span style="color: #0000FF;">object</span>(<span style="color: #4D5D94;">' . $matches['class'] . '</span>)#' . $matches['id'] . ' (<span style="color: #1287DB;">' . $matches['count'] . '</span>)';
    }


    /**
     * Get the CSS string for the output container
     *
     * @return string
     */
    private static function _getContainerCss()
    {
        return self::_arrayToCss(array(
            'background-color'      => '#d6ffef',
            'border'                => '1px solid #bbb',
            'border-radius'         => '4px',
            '-moz-border-radius'    => '4px',
            '-webkit-border-radius' => '4px',
            'font-size'             => '12px',
            'line-height'           => '1.4em',
            'margin'                => '30px',
            'padding'               => '7px',
        ));
    }

    /**
     * Get the CSS string for the output header
     *
     * @return string
     */
    private static function _getHeaderCss()
    {

        return self::_arrayToCss(array(
            'font-size'     => '18px',
            'font-weight'   => 'bold',
            'margin'        => '0 0 5px 0',
            'padding'       => '3px',
        ));
    }

   /**
    * Get the CSS string for the output header
    *
    * @return string
    */
    private static function _getTypeCss()
    {

        return self::_arrayToCss(array(
            'font-size'     => '14px',
            'font-style'    => 'italic',
        ));
    }

    /**
     * Convert a key/value pair array into a CSS string
     *
     * @param array $rules List of rules to process
     * @return string
     */
    private static function _arrayToCss(array $rules)
    {
        $strings = array();

        foreach ($rules as $key => $value) {
            $strings[] = $key . ': ' . $value;
        }

        return join('; ', $strings);
    }


   /**
    * Retrives the name of the variable which this Class
    * is processing.
    *
    * @name _get_var_name
    * @access private
    * @final
    *
    * @since 1.0
    *
    * @param none
    * @return mixed $_var string Name of the Variable
    *
    */
    private static function _get_var_name ( )
    {
        $returnValue = false;

        self::$_trace = debug_backtrace();

        // Pull off Debug call
        foreach ( self::$_trace as $_i => $_trace )
        {
            if ( basename(self::$_trace[0]['file']) == 'debug.php' )
            {
                array_shift(self::$_trace);
                continue;
            }

            break;
        }

        // Pull calling file
        self::$_trace = array_shift(self::$_trace);

        if(isset(self::$_trace)) {
            $arrLines = file(self::$_trace['file']);
            $code = $arrLines[(self::$_trace['line']-1)];

            //find call to Debug class
            if(preg_match('/\bdo_print_r\s*\(\s*(.+)\s*\);/i', $code, $arrMatches)){
                $results = explode(',',$arrMatches[1]);

             $returnValue = $results[0];
             }
        }

        return $returnValue;
    }

};

# eof
