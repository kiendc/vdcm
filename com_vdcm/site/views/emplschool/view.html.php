<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view'); 
jimport( 'joomla.application.component.model');


class VjeecDcmViewEmplSchool extends JViewLegacy
{
  // Overwriting JViewLegacy display method
  protected $schools;
  
  function display($tpl = null) 
  {
    // Assign data to the view
    $model =& JModelLegacy::getInstance('school', 'VjeecDcmModel');
    //dump($model, "viewEmplSchool, model");
    $this->schools = $model->getAllManagedSchools();
    //dump($this->schools, "viewEmplSchool, model");
    parent::display($tpl);
    
  }
}

