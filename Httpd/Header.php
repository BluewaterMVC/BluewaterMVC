<?php

/**
 * Class to handle Application MVC Views
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
 * @subpackage  Httpd
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
 * Httpd Header generation.
 *
 * @package     Bluewater_Core
 * @subpackage  Httpd
 *
 * @PHPUnit Not Defined
 *
 * @tutorial tutorial.pkg description
 * @example url://path/to/example.php description
 *
 */
class Bluewater_Httpd_Header
{
// ==========================================================
// Class Constants

   /**
    * Hypertext Transfer Protocol Methods
    *
    * @link http://www.faqs.org/rfcs/rfc2616.html#5.1.1
    *
    * @access private|protected|public
    *
    * @constant private string Hypertext Transfer Protocol Methods
    *
    * @private
    * @type string
    */
    const GET    = 'GET';
    const POST   = 'POST';
    const PUT    = 'PUT';
    const DELETE = 'DELETE';
    const HEAD   = 'HEAD';

// ==========================================================
// Class Properties

   /**
    * Singleton Instance Property
    *
    * @name $_instance
    * @access private
    *
    * @property Singleton Instance Property
    */
    private static $_instance = null;

	/**
	* Content Language Header value
	* Defaults to English ['en']
	*
	* @link http://www.faqs.org/rfcs/rfc3282.html
	*
	* @private
	* @type string
	*/
    private $_language = 'en';

    private $_local = null;

    /**
    * Hypertext Transfer Protocol Character Sets
    * Defaults to 'utf-8'
    *
    * @link http://www.faqs.org/rfcs/rfc2616.html#14.2
    *
    * @private
    * @type string
    */
    private $_charset = 'utf-8';

	/**
	* The users language priorities
	* Eg: array('en', 'de', 'fr')
	*
	* @private
	* @type array
	*/
	private $_languagePriority = array();

   /**
    * This the default content type, except for xHTML 1.1, and then
    * only if not in IE.
    *
    * @name _contentType
    * @access private
    *
    * @property public string HTML Page Content Type
    */
    private $_contentType = '';

//<META HTTP-EQUIV="REFRESH" content="60">


// ==========================================================
// Class Methods

   /**
    * Class constructor.
    *
    * @access public
    *
    * @uses Bluewater_Httpd_Header::_set_docType()
    *
    * @PHPUnit Not Defined
    *
    * @param  void
    * @return void
    */
    final public function __construct()
    {
        // Default page content type
        self::set_content_type();

        // Define DOC TYPE properties
        //self::_set_docType();
    }


    public static function getInstance()
    {
        if(self::$_instance === null)
        {
            self::$_instance = new self;
        }

        return self::$_instance;

    }



    public final function send_headers()
    {
       /**
        * This is to make sure that script pages are NOT cached
        * 
        * @link http://www.w3.org/Protocols/rfc2616/rfc2616-sec3.html#3.3.1
        * 
        * Sun, 06 Nov 1994 08:49:37 GMT  ; RFC 822, updated by RFC
        *
        */
        header( 'Expires: ' . gmdate( 'D, d M Y H:i:s \G\M\T', time() ) );
        header( 'Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT' );

       /**
        * This is to fix a bug in IE6 that prevents it from
        * properly storing FORM data for the Back button.
        * It shouldn't make a difference to anything else.
        *
        * @link http://www.phpfreaks.com/tutorials/41/0.php
        */
        header('Cache-Control: private, max-age=0');

        // HTTP/1.1 Directives
    	header('Cache-Control: no-store, no-cache, must-revalidate', false);
    	header('Cache-Control: post-check=0, pre-check=0', false);

        // HTTP/1.0 Directive
        header( 'Pragma: no-cache' );

        // Send Content Type
        header('Content-Type: ' . $this->get_content_type());

        // Content RFC 3282 language value
        header('Content-language: ' . Bluewater_Config::config('locale', 'lang'));
    }

   /**
    * Defines Content Type for HEADER generation on page display.
    *
    * @name set_content_type
    *
    * @public
    *
    * @param void
    * @return void
    */
    public final function set_content_type ( $_content_type = 'html' )
    {
        // Descide which content type to use
        switch  ( $_content_type )
        {
            case 'xml':
            case 'sas':
            case 'rss':
            // Firefix and doesn't like this content type, they force a download.
            // IE 8 works, go figure!
                if ( (isset($_SERVER['HTTP_USER_AGENT']))
                  && (stristr($_SERVER['HTTP_USER_AGENT'], 'Mozilla'))
                   )
                {
                    $this->_contentType = 'text/xml';
                }
                // others work fine
                else
                {
                    $this->_contentType = 'application/' . $_content_type . '+xml';
                }

            break;

            case 'rss':
                $this->_contentType = 'text/html';

                // And to really spice things up a bit, IE does NOT accept xHTML
                // in a true XML format; FF, Opera, etc do. To solve yet another IE
                // weirdness, we need to know what browser we have and sent the proper
                // hearder accordingly.
                // NOTE: To make things even more interesting! Xdebug makes FF throw
                //       an XML error when it displays its usual error messages.
                //       This section needs to be deactivated while in DEBUG mode
                if (isset($_SERVER['HTTP_ACCEPT']) && isset($_SERVER['HTTP_USER_AGENT']))
                {
                    if (stristr($_SERVER['HTTP_ACCEPT'], 'application/xhtml+xml') ||
                        stristr($_SERVER['HTTP_USER_AGENT'], 'W3C_Validator') )
                    {
                        $this->_contentType = 'application/xhtml+xml';
                    }
                }

            break;

            case 'html':
                $this->_contentType = 'text/html';

            break;

            default:
                $this->_contentType = 'text/plain';

            break;
        
        }

        
    
    }

   /**
    * Pulls Content Type for HEADER generation on page display.
    *
    * @name get_content_type
    * @author Walter Torres <walter@torres.ws>
    *
    * @public
    *
    * @param void
    * @return void
    */
    public final function get_content_type ()
    {
        return $this->_contentType;
    }

	/**
	* Add a Header to the set of Headers associated with this Request.
	*
	* @param string	The new header name
	* @param string	The new header value. The values are ArrayLists of the
	*  corresponding header values.
	* @public
	* @returns void
	*/
	function addHeader($name, $value) {

		$name = strtolower($name);
		$values = $this->headers[$name];	// ArrayList

		if($values === null) {
			// No existing header values found for this "name" key
			// so create a new header "values" array
			$values = array();				// ArrayList
		}

		// Add the new header "value" to
		$values[] = $value;	// values.add(value)

		// Add the "values" to the header for this "name" key
		// (overwrite old "values" array in headers[], if any)
		$headers[$name] = $values;

	}


   /**
    * Define DOC Type use for HEADER generation on page display.
    *
    * @name get_docType
    *
    * @uses constant DOC_TYPE detemines Content Type to use for page generation
    *
    * @final
    * @public
    *
    * @param void
    * @return void
    */
    public final function get_docType ($_docType = false)
    {
        // Verify DOC_TYPE has been defined
        if ( $_docType == false )
            $_docType = DOC_TYPE;


        $_docType = ($_docType == 'html') ? 'HTML5' : $_docType;

        // Descide which content type to use
        switch  ( $_docType )
        {
            case 'sas':
            case 'rss':
            case 'xml':
                $_docType = '<?xml version="1.0" encoding="' . $this->_charset . '"?>' . "\n\n";

            break;


            case 'x1':
                $_docType = '<?xml version="1.0" encoding="' . $this->_charset . '"?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml"
      xml:lang="' . Bluewater_Config::config('locale', 'lang') . '"
      lang="'     . Bluewater_Config::config('locale', 'lang') . '">

';
            break;

            // This section should ONLY be used if you are going to server and define
            // your pages as xHTML 1.1 strict
            // @link http://xhtml.com/en/xhtml/reference/doctype/
            case 'x1.1':
                $_docType = '<?xml version="1.0" encoding="' . $this->_charset . '"?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
         "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"
         [ <!ATTLIST html id ID #IMPLIED > ] >

<html xmlns="http://www.w3.org/1999/xhtml"
      xml:lang="en"
	  id="lead">

';

            break;

            case 'HTML5':
                $_docType = '<!DOCTYPE html>';
            break;

            // Default setting to make things eaiser!
            // Regular HTML 4.01
            case 'HTML4':
                $_docType = '<!DOCTYPE HTML
          PUBLIC "-//W3C//DTD HTML 4.01//EN"
          "http://www.w3.org/TR/html4/strict.dtd">

';

            default:
                $_docType = '';

            break;

        }

        return $_docType;

    }

   /**
    * Pulls DOC Type for HEADER generation on page display.
    *
    * @name send_docType
    * @author Walter Torres <walter@torres.ws>
    *
    * @uses constant DOC_TYPE detemines Content Type to use for page generation
    *
    * @public
    *
    * @param void
    * @return void
    */
    public final function send_docType ($_docType = false)
    {
        echo $this->get_docType($_docType);
    }


   /**
    * Pulls DOC Type for HEADER generation on page display.
    *
    * @name send_docType
    * @author Walter Torres <walter@torres.ws>
    *
    * @uses constant DOC_TYPE detemines Content Type to use for page generation
    *
    * @public
    *
    * @param void
    * @return void
    *
    * @todo FIX the hard link to styles, it needs to be define SOMEWHERE!
    */
    public final function send_HTML_head ($_title = '')
    {
        $_HTML_head = '
<head>
    <title>' . $_title . '</title>

    <meta http-equiv="content-type"       content="' . $this->get_content_type() . 'charset=utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />

    <link rel="shortcut icon"         href="' . WEB_ROOT . 'favicon.ico" />
    <link rel="icon" type="image/ico" href="' . WEB_ROOT . 'favicon.ico" />

    <link rel="stylesheet" type="text/css" href="' . CSS_PATH . '/clear.css" />
    <link rel="stylesheet" type="text/css" href="' . CSS_PATH . '/file_icons.css" />

';

/*
    // Build list of CSS files to load
    foreach ( Yacs_loader::$_css_files AS $_i => $_css_file )
    {
        $_HTML_head = '    <link href="' . CSS_PATH . '/' . $_css_file . '" rel="stylesheet" type="text/css" />' . "\n";
    }

    $_HTML_head .= "\n";

    // Build list of Javascript files to load
    foreach ( Yacs_loader::$_js_scripts AS $_i => $_js_file )
    {
        $_HTML_head .= '    <script src="' . JS_PATH . '/' . $_js_file . '" type="text/javascript"></script>' . "\n";
    }
*/

		$_HTML_head .= '</head>';

		echo $_HTML_head;

    }

};

// eof