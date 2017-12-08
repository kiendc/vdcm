<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
class VjeecDcmViewDiploma extends JView
{
  // Overwriting JView display method
  public function display($tpl = null) 
  {
    // Display the view   
    parent::display($tpl);
  }
  
  function displayDiplomaDetail($dplmID)
  {
    dump($dplmID, 'ViewDiploma::displayDiplomDetail, dplmID');
    $model = $this->getModel();
    dump($model, 'ViewDisplay::diplayDiplomaDetail, model');
    $dplmDetail = $model->getDiplomaDetail($dplmID);
    dump($dplmDetail, 'ViewDiploma::displayDiplomaDetail, dplmDetail');
    if ($dplmDetail != NULL)
    {    
     $this->assignRef('dplmDetail', $dplmDetail);
     }
    parent::display();
  }
}