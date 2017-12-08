<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');
 
class VjeecDcmModelClient extends JModelItem
{
  public function getAllClients()
  {
    $db = JFactory::getDBO();
    /*
    $query = $db->getQuery(true);    
    $query->select(array('a.id AS client_id', 
			 'a.name',
			 'a.client_user_id',
			 'b.email',
			 'b.lastvisitDate',
			 'COUNT(c.id) AS nb_requests',
			 'COUNT(d.diploma_id) AS nb_diplomas'))
          ->from('#__vjeecdcm_client AS a')
	  ->join('INNER', '#__users AS b ON (a.client_user_id = b.id)')
	  ->join('LEFT', '#__vjeecdcm_diploma_request AS c ON (a.client_user_id = c.user_id)')
	  ->join('LEFT', '#__vjeecdcm_user_diploma AS d ON (a.client_user_id = d.user_id)')
	  ->group('a.client_user_id');
    */
    $query = 'SELECT a.id AS client_id, a.name, a.client_user_id, b.email, b.lastvisitDate, nb_requests, nb_diplomas ' .
             'FROM #__vjeecdcm_client AS a ' .
	     'INNER JOIN #__users AS b ON (a.client_user_id = b.id) ' .
	     'LEFT JOIN ' . '( SELECT user_id, COUNT(id) AS nb_requests '.
			      'FROM #__vjeecdcm_diploma_request ' .
			      'GROUP BY user_id ) AS c ' . 'ON a.client_user_id = c.user_id ' .
	     'LEFT JOIN ' . '( SELECT user_id, COUNT(diploma_id) AS nb_diplomas ' .
			       'FROM #__vjeecdcm_user_diploma ' .
			       'GROUP BY user_id ) AS d ' . 'ON a.client_user_id = d.user_id' ;/*. 
	     'LEFT JOIN ' . '( SELECT user_id, profile_value AS phone_number ' .
	                       'FROM #__user_profiles ' .
			       'WHERE profile_key = "profile.phone" ) AS e ON a.client_user_id = e.user_id';*/
    try 
    {
      //dump($query->__toString(), 'ModelClient, query');
      $db->setQuery($query);
      $result = $db->loadObjectList();
      //dump($result, 'ModelUserRequest::getRequests, result');
      return $result;
    } 
    catch (Exception $e) 
    {
      JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
      return NULL;
    }
  }
  
}
