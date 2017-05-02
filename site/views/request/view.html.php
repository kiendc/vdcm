<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
class VjeecDcmViewRequest extends JViewLegacy
{

  // Overwriting JViewLegacy display method
  protected $dplmId;
  protected $user;
  protected $schools;
  public function display($tpl = null) 
  {
    $layout = JRequest::getVar('layout');
    //dump($layout, 'ViewRequest::display, layout');
    if ($layout == NULL || $layout == 'default')
    {
      parent::display($tpl);
    }
    else if ($layout == 'add')
    {
      $jinput = JFactory::getApplication()->input;
      $this->dplmId = $jinput->get('dplmId', '-1', 'INT');
      $this->displayAddingForm($tpl);
    }
    // Display the view   
   
   
  }
  
  public function displayAddingForm($tpl = null) 
  {
      //dump($this->dplmId, 'Add request diploma id');
      $this->user =  JFactory::getUser();
      $schoolModel =& JModelLegacy::getInstance('school', 'VjeecDcmModel');
      $this->schools = $schoolModel->getAllJALSASchools();
      parent::display($tpl);
  }
  
  public function setDiploma($dplmId)
  {
    $this->dplmId = $dplmId;
  }

}
