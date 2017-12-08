<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view'); 

class VjeecDcmViewEmplClient extends JViewLegacy
{
  // Overwriting JViewLegacy display method
  protected $clients;
  
  function display($tpl = null) 
  {
    // Assign data to the view
    $model = JModelLegacy::getInstance('Client', 'VjeecDcmModel');
    
    $this->clients = $model->getAllClients();
    //dump($this->clients, "viewEmplClient, clients");
    // Display the view
    parent::display($tpl);
    
  }
}

