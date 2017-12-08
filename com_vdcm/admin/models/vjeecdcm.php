<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelitem library
jimport('joomla.application.component.modelitem');
 
/**
 * VjeecDcm Model for access information relating activities of
 * the component.
 */
class VjeecDcmModelVjeecDcm extends JModelItem
{
  protected $msg;
  protected $userID;
  
  
  public function getSystemNotices()
  {
    $user =JFactory::getUser();
    $id = $user->get('id');
    $lang = $user->getParam('language');
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    if ($lang == 'ja-JP')
    {
      $query->select(array('introtext'
                         ))
          ->from('#__content')
          ->where('catid = 111');
    }
    else
    {
      $query->select(array('introtext'
                         ))
          ->from('#__content')
          ->where('catid = 112');
    }
    try 
    {
      $db->setQuery($query);
      $articles = $db->loadObjectList();
      return $articles;
    }
    catch (Exception $e) 
    {
      JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
      return NULL;
    }
  }
  
}