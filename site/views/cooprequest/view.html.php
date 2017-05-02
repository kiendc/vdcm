<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view'); 

class VjeecDcmViewCoopRequest extends JViewLegacy
{
  // Overwriting JViewLegacy display method
  protected $requests;
  
  function display($tpl = null) 
  {
    // Display the view
    parent::display($tpl);
    
  }
}

