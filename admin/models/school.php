<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');
require_once JPATH_COMPONENT.'/helpers/vjeec.php';
 
class VjeecDcmModelSchool extends JModelAdmin
{
	public function __construct($config = array())
	{ 
		parent::__construct($config);

		// Load the Joomla! RAD layer
		if (!defined('FOF_INCLUDED'))
		{
			include_once JPATH_LIBRARIES . '/fof/include.php';
		}
	}
	
	public function getTable($type = 'School', $prefix = 'VjeecTable', $config = array())
	{
		$return = JTable::getInstance($type, $prefix, $config);
		return $return;
	}
  	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_vjeecdcm.school', 'school', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}
	
	/**
         * Method to get the data that should be injected in the form.
         *
         * @return      mixed   The data for the form.
         * @since       2.5
	*/
    protected function loadFormData() { 
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_vjeecdcm.edit.school.data', array());
        if (empty($data)) {
			$data = $this->getItem();
        }
        return $data;
    }
  	
  	public function getItem($pk = null) { 
  		$item = parent::getItem($pk);
  		if ($item) { 
  			$user = JFactory::getUser($item->school_user_id);
  			if ($user) { 
  				$item->school_user_id = $user->get('id');
  				$item->email = $user->get('email');
  				$item->username = $user->get('username');
  			}
  			
  			if (!$item->represent_user_id) { 
  				$item->represent_user_id = 0;
  			}
  		}
  		
  		return $item;
  	}
  	
  	public function getSchoolDetail($schlId) {
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	
	    $query->select(array('*'))
	        ->from('#__vjeecdcm_school')
		  	->where('id = ' . $schlId);
	    try {
	      	$db->setQuery($query);
	      	$result = $db->loadObject();
	      	return $result;
	    } catch (Exception $e) {
	      	return NULL;
	    }
  	}
  
 	public function getAllSchools() {
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	
	    $query->select(array('id', 'name', 'school_user_id'))
	          	->from('#__vjeecdcm_school')
		  		->order('name ASC');
	    try {
	      	$db->setQuery($query);
	      	$result = $db->loadObjectList();
	      	return $result;
	    } catch (Exception $e) {
	      	JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
	      	return NULL;
	    }
  	}
  
  	public function getAllJALSASchools($nsValue='') {
    	$db = JFactory::getDBO();
    	$query = $db->getQuery(true);
    	$whereCond = 'represent_user_id = 604';
    	if (!empty($nsValue)) {
	      	$whereCond = $whereCond . " AND name LIKE '%" . $nsValue . "%'";
	    }
	    
	    $query->select(array('id', 'name', 'school_user_id'))
	          	->from('#__vjeecdcm_school')
		  	  	->where($whereCond)
		  		->order('name ASC');
	    try { 
	      	$db->setQuery($query);
	      	$result = $db->loadObjectList();
		    return $result;
	    } catch (Exception $e) {
	      	return NULL;
	    }
  	}
  
  	public function getAllManagedSchools() {
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
	    try {
	      	$db->setQuery($query);
	      	$result = $db->loadObjectList();
	      	return $result;
	    } catch (Exception $e) {
	      	return NULL;
	    }
  	}
  
  	public function getTargetSchools() {
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	
	    $query->select(array('a.id ', 'a.school_user_id', 'COUNT(*) AS number_request'))
	          	->from('#__vjeecdcm_school AS a')
		  		->join('INNER', '#__vjeecdcm_request_observer AS c ON (a.school_user_id = c.observer_id)')
		  		->group('c.observer_id');
	    try {
	      	$db->setQuery($query);
	      	$result = $db->loadObjectList();
	      	return $result;
	    } catch (Exception $e) {
	      	return NULL;
	    }
  	}
  	
  	public function getGroups() { 
  		$db = JFactory::getDBO();
  		$query = $db->getQuery(true);
		$ret = array();
	    $query->select(array('g.id'))
	          	->from('#__usergroups AS g')
	          	->where('g.title = "Registered" OR g.title = "School" ')
		  		;
  		try {
	      	$db->setQuery($query);
	      	$result = $db->loadObjectList();
	      	foreach ($result as  $item) { 
	      		$ret[] = $item->id;
	      	}
	    } catch (Exception $e) {
	      	return $ret;
	    }
  		return $ret;
  	}
  	
  	public function createUpdateSchoolUserLogin($schoolId, $userId, $usrData)
  	{
  		$db = JFactory::getDBO();
	    $authorize = JFactory::getACL();
	    if ($userId) { 
	    	$user = JFactory::getUser($userId);
	    } else {
	    	$user = clone(JFactory::getUser(0));
	    	$usrData['groups'] = $this->getGroups();
		    $usrData['sendEmail'] = 0;
	    }
	    
	    if (!$user->bind( $usrData)) {
			return false;
	    }
	    
	    if (!$userId) { 
	    	$user->set('registerDate', JFactory::getDate()->toSql());
	    }
	    $user->save();
	    
	    //update school_user_id field of school
	    if (!$userId) { 
		    $school = new stdClass();
			$school->id = $schoolId;
			$school->school_user_id = $user->id;
	        $ret = $db->updateObject('#__vjeecdcm_school', $school, 'id');
	    }
	    
	    /* insert school in list school article
	    $descData = array(
			'catid' => 113,
			'title' => $user->name,
			'fulltext' => $usrData['description'],
			'state' => 1
	    );
	    
	    
	    $table = JTable::getInstance('Content', 'JTable', array());
	    
	    // Bind data
	    if (!$table->bind($descData)) {
	      	$this->setError($table->getError());
	      	return false;
	    }
	    
	    // Check the data.
	    if (!$table->check()) {
	      	$this->setError($table->getError());
	      	return false;
	    }
	    
	    // Store the data.
	    if (!$table->store()) {
	      	$this->setError($table->getError());
	      	return false;
	    }
	    */
  	}
}