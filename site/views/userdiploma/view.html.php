<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
jimport( 'joomla.application.component.model' ); 
/**
 * HTML View class for the UpdHelloWorld Component
 */
class VjeecDcmViewUserDiploma extends JViewLegacy
{
  protected $registeredDiplomas;
 
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
      
      $this->registeredDiplomas = $model->getUserRegisteredDiplomas($user->id);
      //$attModel = $this->getModel('attachment');
      //$reqModel = $this->getModel('request');
      $attModel =& JModelLegacy::getInstance('attachment', 'VjeecDcmModel');
      $reqModel =& JModelLegacy::getInstance('request', 'VjeecDcmModel');
      //dump($this->registeredDiplomas, 'UserDiplomaView, display');
      if ($this->registeredDiplomas != null)
      {
        foreach ($this->registeredDiplomas as $dplm)
        {
          $dplm->elecVersion = $attModel->getFilePath($dplm->attachment_id);
          $dplm->relatedReqs = $reqModel->getDiplomaRelatedRequests($dplm->diploma_id);
        }
      }
    }

    if (count($errors = $this->get('Errors'))) 
    {
      //JError::raiseError(500, implode('<br />', $errors)); 
      //JFactory::getApplication()->enqueueMessage(JText::_('VJEECDCM_USER_DIPLOMA_MSG_GETDIPLOMAS_ERROR'));
      $registeredDiplomas = null;
      //dump($errors, 'Error of getting registered diplomas');
    }
  
    // Display the view   
    parent::display($tpl);
  }
  
}