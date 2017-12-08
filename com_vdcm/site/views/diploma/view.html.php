<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
class VjeecDcmViewDiploma extends JViewLegacy
{

  // Overwriting JViewLegacy display method
  public $dplmID;
  
  public function display($tpl = null) 
  {
    $jinput = JFactory::getApplication()->input;
    $this->dplmID = $jinput->get('dplmId', 0, 'INT'); 
    parent::display($tpl);
  }
  
  ////////////////////////////////////////////////////////////////////////////
 
  public function diplayDiploma($dplmId)
  {
    $this->dplmID = $dplmId;
    parent::display();
  }

}