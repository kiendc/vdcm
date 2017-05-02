<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view'); 

class VjeecDcmViewUserRequest extends JViewLegacy
{
  // Overwriting JViewLegacy display method
  protected $requests;
  
  function display($tpl = null) 
  {
    // Assign data to the view
    //$model = $this->getModel('request');
    $model =& JModelLegacy::getInstance('request', 'VjeecDcmModel');
    $this->requests = $model->getCreatedRequests();
    // Check for errors.
    if (count($errors = $this->get('Errors'))) 
    {
      JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
      return false;
    }
    // Display the view
    parent::display($tpl);
    
  }
}

