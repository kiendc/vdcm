<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');
 
class VjeecDcmModelSchool extends JModelItem
{
  
  public function getSchoolDetail($schlId)
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);

    $query->select(array('*'))
          ->from('#__vjeecdcm_school')
	  ->where('id = ' . $schlId);
    try 
    {
      $db->setQuery($query);
      $result = $db->loadObject();
      return $result;
    } 
    catch (Exception $e) 
    {
      return NULL;
    }
  }
  
  public function getAllSchools()
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);

    $query->select(array('id', 
			 'name',
			 'school_user_id'
			 ))
          ->from('#__vjeecdcm_school')
	  ->order('name ASC');
    try 
    {
      //dump($query->__toString(), 'ModelUserRequest::getRequests, query');
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
  
  public function getAllJALSASchools($nsValue='')
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    $whereCond = 'represent_user_id = 604';
    if (!empty($nsValue))
    {
      $whereCond = $whereCond . " AND name LIKE '%" . $nsValue . "%'";
    }
    
    $query->select(array('id', 
			 'name',
			 'school_user_id'
			 ))
          ->from('#__vjeecdcm_school')
	  ->where($whereCond)
	  ->order('name ASC');
    try 
    {
      //dump($query->__toString(), 'ModelUserRequest::getRequests, query');
      $db->setQuery($query);
      $result = $db->loadObjectList();
      //dump($result, 'ModelUserRequest::getRequests, result');
      return $result;
    } 
    catch (Exception $e) 
    {
      return NULL;
    }
  }
  
  public function getAllManagedSchools()
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);

    $query->select(array('a.id AS school_id', 
			 'a.name',
			 'a.school_user_id',
			 'b.email',
			 'b.lastvisitDate',
			 'COUNT(c.request_id) AS nb_requests'))
          ->from('#__vjeecdcm_school AS a')
	  ->join('INNER', '#__users AS b ON (a.school_user_id = b.id)')
	  ->join('LEFT', '#__vjeecdcm_request_observer AS c ON (a.school_user_id = c.observer_id)')
	  ->group('a.school_user_id');
    try 
    {
      //dump($query->__toString(), 'ModelUserRequest::getRequests, query');
      $db->setQuery($query);
      $result = $db->loadObjectList();
      //dump($result, 'ModelUserRequest::getRequests, result');
      return $result;
    } 
    catch (Exception $e) 
    {
      return NULL;
    }
  }
  
  public function getTargetSchools()
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);

    $query->select(array('a.id ', 
			 'a.school_user_id',
			 'COUNT(*) AS number_request'))
          ->from('#__vjeecdcm_school AS a')
	  ->join('INNER', '#__vjeecdcm_request_observer AS c ON (a.school_user_id = c.observer_id)')
	  ->group('c.observer_id');
    try 
    {
      //dump($query->__toString(), 'ModelUserRequest::getRequests, query');
      $db->setQuery($query);
      $result = $db->loadObjectList();
      //dump($result, 'ModelUserRequest::getRequests, result');
      return $result;
    } 
    catch (Exception $e) 
    {
      return NULL;
    }
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function addSchool($usrData)
  {
    $authorize	      = & JFactory::getACL();
    $user = clone(JFactory::getUser(0));
    $usrData['groups'] = array('Registered','School');
    $usrData['block'] = 0;
    $usrData['sendEmail'] = 0;
    $usrData['password2'] = $usrData['password'];
    
    if (!$user->bind( $usrData ))
    {
	return false;
    }
    
    $date =& JFactory::getDate();
    $user->set('registerDate', $date->toSql());
    //dump($user, 'School model, user object before saving');
    $user->save();
    //dump($user->id, 'School model, add school, new user id');
    $db = JFactory::getDBO();
    $schl = new stdClass();
    $schl->id = NULL;
    $schl->name = $user->name;
    $schl->school_user_id = $user->id;
    if (isset($usrData['inJALSA']))
    {
      $schl->represent_user_id = 604;
    }
    $result = $db->insertObject('#__vjeecdcm_school', $schl, 'id');
    
    //dump($usrData->description, 'School model, school description');
    $descData = array(
	'catid' => 113,
	'title' => $user->name,
	'fulltext' => $usrData['description'],
	'state' => 1
    );
        
    $table = JTable::getInstance('Content', 'JTable', array());
    
    // Bind data
    if (!$table->bind($descData))
    {
      $this->setError($table->getError());
      return false;
    }

    // Check the data.
    if (!$table->check())
    {
      $this->setError($table->getError());
      return false;
    }

    // Store the data.
    if (!$table->store())
    {
      $this->setError($table->getError());
      return false;
    }

  }
  
}
