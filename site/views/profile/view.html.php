<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
class VjeecDcmViewProfile extends JViewLegacy
{

  // Overwriting JViewLegacy display method
  protected $data;
  protected $user;
  public function display($tpl = null) 
  {
    JModelLegacy::addIncludePath (JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_users' . DIRECTORY_SEPARATOR . 'models');
    $profileModel =& JModelLegacy::getInstance('profile', 'UsersModel');
    parent::display($tpl);
  }
  ////////////////////////
  public function getData()
  {
    $this->user = JFactory::getUser();
  }

}
