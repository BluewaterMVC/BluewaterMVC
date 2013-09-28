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
 * @subpackage  View
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
 * Class to handle Application MVC Views.
 *
 * @package     Bluewater_Core
 * @subpackage  View
 *
 * @PHPUnit Not Defined
 *
 * @tutorial tutorial.pkg description
 * @example url://path/to/example.php description
 *
 */
class Bluewater_View
{
// ==========================================================
// Class Constants

// ==========================================================
// Class Properties

   /**
    * HTTPD Header Class Instance
    *
    * @var Object $_http HTTPD Header Class Instance
    * @access protected
    *
    * @since 1.0
    */
    protected $_http = false;

   /**
    * Command Class Instance
    *
    * @var Object $helper Command Class Instance
    * @access private
    *
    * @since 1.0
    */
    private $_objCommand = false;

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

   /**
    * Base path to VIEW files
    *
    * @var boolean $view_path Base path to VIEW files
    * @access public
    *
    * @since 1.0
    */
    public $view_path = false;

   /**
    * Whether to generate full HTML page
    *
    * @var boolean $full_page Whether to generate full HTML page
    * @access public
    *
    * @since 1.0
    */
    public $full_page = true;

   /**
    * Name of HTML page to display
    *
    * @var string $gui_name Name of HTML page to display
    * @access public
    *
    * @since 1.0
    */
    public $gui_name = '';

   /**
    * Type of Page to generate
    * Defaults to 'HTML'
    *
    * @var string $page_type Page Type
    * @access public
    *
    * @since 1.0
    */
    public $page_type = 'html';

   /**
    * Name to display in TITLE in HEAD block
    *
    * @var string $page_title Page HTML Title
    * @access public
    *
    * @since 1.0
    */
    public $page_title = '';

   /**
    * Name to display as page Title
    * Not to be confused with TITLE in HEAD block
    *
    * @var string $page_name Name to display as page Title
    * @access public
    *
    * @since 1.0
    */
    public $page_name = '';

   /**
    * URL to 'return' to
    *
    * @var array $back_link URL back Links
    * @access public
    *
    * @since 1.0
    */
    public $back_link = '';

   /**
    * Error Message for page views
    *
    * @var string $err_msg Error Messages for page views
    * @access public
    *
    * @since 1.0
    */
    public $err_msg = false;

   /**
    * Error Message for page views
    *
    * @var array $_errors Error Messages for page views
    * @access private
    *
    * @since 1.0
    */
    private $_errors = array();

// ==========================================================
// Class Methods

   /**
    * Class constructor.
    *
    * @access public
    *
    * @uses Bluewater_Httpd_Header::getInstance()
    * @uses Bluewater_Config::helper()
    * @uses Bluewater_View::$helper
    *
    * @PHPUnit Not Defined
    *
    * @param  void
    * @return void
    */
    final public function __construct()
    {
        // Load Logger Class, but only if logging is turned on
        if ( LOGGER_ERR || LOGGER_TRACE || LOGGER_SQL )
            $this->logger = Bluewater_Logger::getInstance();

        // Load Helper Support Class
        $this->helper = Bluewater_Helper::getInstance();

        // Load HTTPD Header Class
        $this->_http = Bluewater_Httpd_Header::getInstance();

        // Path to VIEW display
        // Using standard MVC structure
        $this->view_path = APP_VIEW;
    }

   /**
    * Processes a view script.
    *
    * @uses Bluewater_Httpd_Header::send_headers()
    * @uses Bluewater_Httpd_Header::send_docType()
    * @uses Bluewater_Httpd_Header::send_HTML_head()

    * @uses Bluewater_View::$_objCommand
    * @uses Bluewater_View::$gui_name
    * @uses Bluewater_View::$_http
    *
    * @uses TEMPLATE
    *
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  object $controller Command Controller Object
    * @return void
    *
    */
    public function render(&$_controller)
    {
        $this->_objCommand = $_controller->objCommand;

        // Run display, but only if we have a display to run
        if ( $_controller->view )
        {
            // Begin caching HTML output
            ob_start();

            // Page to display
            // Using standard MVC structure
            $this->gui_name = $this->view_path . '/' . $this->gui_name;

            // Make sure we send the right kind of content type to browser
            $this->_http->set_content_type ( $this->page_type );

            // Determine if requested page actually exists
            if ( file_exists($this->gui_name) )
            {
                if ( ($this->page_type == 'html')
                  && ($this->full_page  === true)
                   )
                {
                    // Send Correct HTTP Headers
                    $this->_http->send_headers();

                    // Send current HTTP DOC Type
                    $this->_http->send_docType($this->page_type);

                    // Include page header info
                    $this->_http->send_HTML_head($this->page_title);

                    // Load base template for display
                    require_once APP_TEMPLATE . '/structure.php';
                }
                // load other file types
                else
                {
                    require_once $this->gui_name;
                }
            }
            else
            {
                echo $this->err_msg;
            }

            // Dump cached HTML output
            echo ob_get_clean();
        }
    }

   /**
    * Creates an error message element in the error array for display
    * on the final page.
    *
    * @uses Bluewater_View::$_errors
    *
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  string $_label   Error label
    * @param  string $_message Error message
    * @return void
    *
    */
	final public function set_error( $_label = false, $_message = false )
	{
		if ( $_label && $_message )
			$this->_errors[$_label] = $_message;
	}

   /**
    * Retrieves an error message via 'label' element for display
    * on the final page.
    *
    * @uses Bluewater_View::$_errors
    *
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  string $_label   Error label
    * @return string|null $_message Error message, or NULL if not defined
    *
    */
	final public function get_error( $_label = false )
	{
		$_message = null;

		if ( $_label !== false )
		{
			if ( isset($this->_errors[$_label]))
				$_message = $this->_errors[$_label];
		}

		return $_message;
	}



};

// eof
