<?php

/**
 * Class Abstract to handle Application MVC Controllers.
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
 * @subpackage  Controller
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
 * Class Abstract to handle Application MVC Controllers
 *
 * @package     Bluewater_Core
 * @subpackage  Controller
 *
 * @PHPUnit Not Defined
 *
 * @abstract
 *
 * @example /examples
 *
 * @author Walter Torres <walter@torres.ws>
 * @version $Revision: 1.0 $
 *
 */
abstract class Bluewater_Controller_Abstract
{
// ==========================================================
// Class Properties

   /**
    * Command Object
    *
    * @var Object $objCommand Command Object
    * @access public
    *
    * @since 1.0
    *
    */
    public $objCommand = false;

   /**
    * Controller Name
    *
    * @var string $controllerName Controller Name
    * @access public
    *
    * @since 1.0
    *
    */
    public $controllerName = false;

   /**
    * Action [Method] Name
    *
    * @var string $controllerAction Action [Method] Name
    * @access public
    *
    * @since 1.0
    *
    */
	public $controllerAction = false;

   /**
    * Path to Controller Class File
    *
    * @var string $controllerPath Path to Controller Class File
    * @access public
    *
    * @since 1.0
    *
    */
	public $controllerPath = false;

   /**
    * Controller View, used for PATH BASED MVC
    *
    * @var string controllerView Controller Pash Name
    * @access public
    *
    * @since 1.0
    *
    */
    public $controllerView = false;

   /**
    * Default view to run
    *
    * @var string $view Default view to run
    * @access public
    *
    * @since 1.0
    *
    */
    public $view = false;

   /**
    * Central Model Class
    *
    * @var string $model Central Model Class
    * @access protected
    *
    * @since 1.0
    *
    */
    protected $model = false;

   /**
    * Helper Object Container
    *
    * @var object $helper Helper Object Container
    * @access protected
    *
    * @since 1.0
    *
    */
    protected $helper = false;

   /**
    * Central Logging Class
    *
    * @var string $logger Central Logging Class
    * @access protected
    *
    * @since 1.0
    *
    */
    protected $logger = false;

   /**
    * Is this 'section' secure, requiring a user/admin login to access
    * Set this in each controller as needed
    *
    * @var boolean $_secure Is this 'section' secure
    * @access protected
    *
    * @since 1.0
    *
    */
	protected $_secure = false;

   /**
    * Is this 'section' secure, requiring aa admin login to access
    * Set this in each controller as needed
    *
    * @var boolean $_admin Is this 'section' for Admin use only
    * @access protected
    *
    * @since 1.0
    *
    */
    protected $_admin = false;

   /**
    * Authentication Object
    *
    * @var Object $objAuth Authentication Object
    * @access public
    *
    * @since 1.0
    *
    */
    public $objAuth = false;


// ==========================================================
// Class Methods

   /**
    * Class constructor.
    *
    * @access public
    *
    * @uses Bluewater_Controller_Abstract::_is_secure()
    * @uses Bluewater_Controller_Abstract::$objCommand
    * @uses Bluewater_Controller_Abstract::$controllerName
    * @uses Bluewater_Controller_Abstract::$controllerAction
    * @uses Bluewater_Controller_Abstract::$logger
    * @uses Bluewater_Controller_Abstract::$helper
    * @uses Bluewater_Controller_Abstract::$model
    * @uses Bluewater_Controller_Abstract::$view
    * @uses Bluewater_Controller_Abstract::$objAuth
    * @uses Bluewater_Config::config()
    * @uses Bluewater_Auth
    * @uses Bluewater_Auth::$logged_in
    * @uses Bluewater_Logger::getInstance()
    * @uses Bluewater_Helper::getInstance()
    * @uses Bluewater_Model::getInstance()
    * @uses Bluewater_View
    * @uses Bluewater_View::$gui_name
    *
    * @uses LOGGER_SQL
    * @uses LOGGER_ERR
    * @uses LOGGER_TRACE
    * @uses WEB_ROOT
    *
    * @PHPUnit Not Defined
    *
    * @param  object $objCommand Command Controller Object
    * @return void
    */
    function __construct(&$objCommand)
    {
        $this->objCommand = $objCommand;

        // Retrieve name of controller
        $this->controllerName = $this->objCommand->controllerName;

        // Retrieve name of action [method]
        $this->controllerAction = $this->objCommand->controllerAction;

        // Load Logger Class, but only if logging is turned on
        if ( LOGGER_ERR || LOGGER_TRACE || LOGGER_SQL )
            $this->logger = Bluewater_Logger::getInstance();

        // Load Helper Support Class
        $this->helper = Bluewater_Helper::getInstance();

        // Load MVC Model Class, but only if a database or 2 are defined
        // in the App config file
        if ( Bluewater_Config::config('data_sources') )
            $this->model = Bluewater_Model::getInstance();

        // Load MVC View Class
        $this->view = new Bluewater_View;

        // By default, the VIEW name will match the Action name.
        // NOTE: This can be overwritten in the Action/method itself, if need be.
        $this->view->gui_name = ( $this->controllerAction == '_default' ) ? 'index.phtml' : $this->controllerAction. '.phtml';

		// Is the calling Controller to be secured?
		if ( $this->_is_secure() )
		{
    		// Initialize Authentication object
    		$this->objAuth = new Bluewater_Auth;

			if ( ($this->objAuth->logged_in === false) && ( ! isset($_POST['sublogin']) ) )
				header('Location: ' . WEB_ROOT . 'auth');
		}
    }

   /**
    * Default action for this controller. This needs to be defined
    * in the final class file.
    *
    * @abstract
    * @access protected
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  array $_parameters method parameters
    * @return void
    *
    */
    abstract protected function _default(array $_parameters = array());

   /**
    * Returns a boolean indicating that this "section" needs have
    * s USER logged in before it can be accessed.
    *
    * This method can not be redefined in the local class.
    *
    * @uses Bluewater_Controller_Abstract::$_secure
    *
    * @abstract
    * @access protected
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  void
    * @return void
    *
    */
	protected final function _is_secure()
	{
		return $this->_secure;
	}

   /**
    * Returns a boolean indicating that this "section" needs have
    * s ADMIN logged in before it can be accessed.
    *
    * This method can not be redefined in the local class.
    *
    * @uses Bluewater_Controller_Abstract::$_secure
    *
    * @abstract
    * @access protected
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  void
    * @return void
    *
    */
	protected final function _is_admin()
	{
		return $this->_admin;
	}

};

// eof
