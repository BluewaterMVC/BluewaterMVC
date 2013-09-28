<?php



abstract class Bluewater_Module_Abstract extends Bluewater_Controller_Abstract
{

   /**
    * define values for processing
    *
    * @static
    * @final
    * @access public
    *
    * @PHPUnit Not Defined
    *
    * @since 1.0
    *
    * @param  void
    * @return void
    *
    */
    public function __construct(&$objCommand)
    {
        parent::__construct($objCommand);

        // These are modules, not full pages, therefore they
        // will not generate full HTML documents, just enough
        // to display themselves in a parent document.
        $this->view->full_page = false;

        // Pull display type from parameters
        // There will be various display formats, but we will default
        // as defined in the app ini file
        $this->view->view_path = $this->objCommand->controllerPath . 'view';

    }

};

?>