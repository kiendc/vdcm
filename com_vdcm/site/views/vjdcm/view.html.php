<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
class VjeecDcmViewVjDcm extends JViewLegacy
{
  // Overwriting JViewLegacy display method
  protected $welcomeMsg;
  protected $sysNotices;
  protected $privMsgs;
  protected $user;
  function display($tpl = null) 
  { 
    // Display the viewé
    $user =JFactory::getUser();
    $id = $user->get('id');
    $lang = $user->getParam('language');
    $groups= $user->get('groups'); 
    if (in_array(9, $groups))
    {
	$this->setLayout('client');
    }
    $article =& JTable::getInstance("content");
    
    if ($lang == 'ja-JP')
    {
        $article->load(96); 
    }
    else
    {
	$article->load(97);
    }
    $this->welcomeMsg = $article->get('introtext');
    $this->sysNotices = $this->get('SystemNotices');
    parent::display($tpl); 
  }
}

