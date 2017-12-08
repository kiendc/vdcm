<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modelform');
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');

class VjeecDcmModelCustomer extends JModelAdmin { 
	
	public function __construct($config = array())
	{ 
		parent::__construct($config);

		// Load the Joomla! RAD layer
		if (!defined('FOF_INCLUDED'))
		{
			include_once JPATH_LIBRARIES . '/fof/include.php';
		}
	}
	
	public function getTable($type = 'Customer', $prefix = 'VjeecTable', $config = array())
	{
		$return = JTable::getInstance($type, $prefix, $config);
		return $return;
	}
  	
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_vjeecdcm.customer', 'customer', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_vjeecdcm.edit.customer.data', array());
        if (empty($data)) {
			$data = $this->getItem();
        }
        return $data;
    }
  	
  	public function getItem($pk = null) { 
  		$item = parent::getItem($pk);
  		if ($item) { 
  			$user = JFactory::getUser($item->client_user_id);
  			if ($user) { 
  				$item->email = $user->get('email');
  				$item->username = $user->get('username');
  			}
  		}
  		return $item;
  	}
  	
  	public function getGroups() { 
  		$db = JFactory::getDBO();
  		$query = $db->getQuery(true);
		$ret = array();
	    $query->select(array('g.id'))
	          	->from('#__usergroups AS g')
	          	->where('g.title = "Registered" OR g.title = "Client"');
  		try {
	      	$db->setQuery($query);
	      	$results = $db->loadObjectList();
	      	foreach ($results as $item) { 
	      		$ret[] = $item->id;
	      	}
	    } catch (Exception $e) {
	      	return $ret;
	    }
  		return $ret;
  	}
  	
  	public function createUpdateLoginedUser($clientId, $userId, $userData) { 
  		
  		$db = JFactory::getDBO();
	    $authorize = JFactory::getACL();
	    if ($userId) { 
	    	$user = JFactory::getUser($userId);
	    	if (!$user->get('groups')) { 
	    		$user->set('groups', $this->getGroups());
	    	}
	    } else {
	    	$user = clone(JFactory::getUser(0));
	    	$userData['groups'] = $this->getGroups();
		    $userData['block'] = 0;
		    $userData['sendEmail'] = 0;
	    }
	    
	    if (!$user->bind($userData)) { 
			return false;
	    }
	    
	    if (!$userId) {
	    	$user->set('registerDate', JFactory::getDate()->toSql());
	    }
	    $user->save();
	    
	    //update client_user_id field of client
	    if (!$userId) { 
		    $client = new stdClass();
			$client->id = $clientId;
			$client->client_user_id = $user->id;
	        $ret = $db->updateObject('#__vjeecdcm_client', $client, 'id');
	    }
  	}
}
?>