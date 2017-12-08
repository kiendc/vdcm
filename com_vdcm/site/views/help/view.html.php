<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
class VjeecDcmViewHelp extends JViewLegacy
{
  // Overwriting JViewLegacy display method
  
  function display($tpl = null) 
  { 
    // Display the viewé
    parent::display($tpl); 
  }
}

