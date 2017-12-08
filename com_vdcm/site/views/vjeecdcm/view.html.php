<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
class VjeecDcmViewVjeecDcm extends JViewLegacy
{
  // Overwriting JViewLegacy display method
  protected $welcomeMsg;
  protected $sysNotices;
  protected $privMsgs;
  function display($tpl = null) 
  { 
    // Display the viewé
    $user =JFactory::getUser();
    $id = $user->get('id');
    $lang = $user->getParam('language');
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

