<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
jimport( 'joomla.application.component.model' ); 
/**
 * HTML View class for the UpdHelloWorld Component
 */
class VjeecDcmViewEmplDiploma extends JViewLegacy
{
  protected $diplomas;
 
  //protected $dplAddForm;
  // Overwriting JViewLegacy display method
  public function display($tpl = null) 
  {
    $user = JFactory::getUser();
    //$model = $this->getModel('diploma');
    $model =& JModelLegacy::getInstance('diploma', 'VjeecDcmModel');
    //dump($model, 'UserDiplomaView display entry'); 
    if($model != null)
    {
      
      $this->diplomas = $model->getAllDiplomas();
      $attModel =& JModelLegacy::getInstance('attachment', 'VjeecDcmModel');
      $reqModel =& JModelLegacy::getInstance('request', 'VjeecDcmModel');
      if ($this->diplomas != null)
      {
        foreach ($this->diplomas as $dplm)
        {
          $dplm->elecVersion = $attModel->getFilePath($dplm->attachment_id);
          $dplm->relatedReqs = $reqModel->getDiplomaRelatedRequests($dplm->diploma_id);
        }
      }
    }

    if (count($errors = $this->get('Errors'))) 
    {
      $registeredDiplomas = null;
    }
  
    // Display the view   
    parent::display($tpl);
  }
  
}
