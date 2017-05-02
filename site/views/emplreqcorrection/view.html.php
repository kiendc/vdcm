<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view'); 
jimport( 'joomla.application.component.model' );
class VjeecDcmViewEmplReqCorrection extends JViewLegacy
{
  // Overwriting JViewLegacy display method
  protected $requests;
  protected $schools;
  function display($tpl = null) 
  {
    // Assign data to the view
    $model =& JModelLegacy::getInstance('request', 'VjeecDcmModel');
    $attModel =& JModelLegacy::getInstance('attachment', 'VjeecDcmModel');
    $schlModel =& JModelLegacy::getInstance('school', 'VjeecDcmModel');
    $this->requests = $model->getNoTargetRequests();
    
    $this->schools = $schlModel->getAllSchools();
    $userID = JFactory::getUser()->id;
    // Check for errors.
    foreach ($this->requests as $rq)
    {
      //$rq->elecDplm = $attModel->getFilePath($rq->attachment_id);
      $rq->elecDocInfo = $attModel->getFileInfo($rq->attachment_id);
    }
    if (count($errors = $this->get('Errors'))) 
    {
      JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');
      return false;
    }

    // Display the view
    parent::display($tpl);
    
  }
}

