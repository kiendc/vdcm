<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT.'/helpers/vjeec.php';

class VjeecDcmModelSchools extends JModelList { 
	
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'school_name', 'a.name',
				'phone', 'a.phone',
				'username', 'u.username',
				'email', 'u.email',
				'lastvisitDate', 'u.lastvisitDate',
				'nb_requests', 'c.nb_requests',
			);
		}

		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout', 'default'))
		{
			$this->context .= '.'.$layout;
		}

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		$schoolId = $this->getUserStateFromRequest($this->context.'.filter.school', 'filter_school', null, 'int');
		$this->setState('filter.school', $schoolId);
		
		$status = $this->getUserStateFromRequest($this->context.'.filter.status', 'filter_status', null, 'int');
		$this->setState('filter.status', $status);

		// List state information.
		parent::populateState('a.name', 'asc');
	}
	
	public function enableDisableSchool($schoolId, $block) { 
		$db = $this->getDbo();
		$schoolObj = new stdClass();
		$schoolObj->id = $schoolId;
		$jalsaUser = VjeecHelper::getJalsaUserId();
		$schoolObj->represent_user_id = $block ? 0 : $jalsaUser;
		
		//get school user logined
		$userId = $this->getUserLoginedBySchool($schoolId);
		if ($userId) { 
			$usrObj = new stdClass();
			$usrObj->id = $userId;
			$usrObj->block = $block;
		}
		
		try {
			//update school table
    		$ret = $db->updateObject('#__vjeecdcm_school', $schoolObj, 'id');
    		//update school user 
    		if (isset($usrObj)) {
    			$ret = $db->updateObject('#__users', $usrObj, 'id');
    		}
    		
		} catch (Exception $e) { 
			echo $e->getMessage(); die('fuck');
			$ret = false;
		}
		return $ret;
	}
	
	public function getUserLoginedBySchool($schoolId) { 
		$db = $this->getDbo();
		$query	= $db->getQuery(true);
		
		$query->select(array('a.id, a.school_user_id, a.represent_user_id'))
			  ->from($db->quoteName('#__vjeecdcm_school') .'  AS a')
			  ->join('INNER', '#__users AS u ON (a.school_user_id = u.id) ')
			  ->where('a.id = '.$schoolId);
		try { 
			$db->setQuery($query); 
	    	$ret = $db->loadObject();
	    	$schoolUserId = $ret->school_user_id;
		} catch (Exception $e) { 
			$schoolUserId = NULL;
		}
		
		return $schoolUserId;
	}
	
	public function getListQuery() { 
		$db = $this->getDbo();
		$query	= $db->getQuery(true);
		$subQuery = $db->getQuery(true);
		
		$subQuery->select('COUNT(id) as nb_requests, target_school_id')
			 ->from($db->quoteName('#__vjeecdcm_diploma_request'). ' AS b' )
			 ->group('target_school_id');

		$query->select($this->getState( 'list.select',
						'a.id AS school_id, 
						 a.name, 
						 a.represent_user_id, 
						 a.phone, 
						 a.fax, u.username, 
						 u.email, u.lastvisitDate, 
						 a.school_user_id, 
						 c.nb_requests'
		));
		$query->from($db->quoteName('#__vjeecdcm_school'). ' AS a');
		
		//$query->select('u.email, u.lastvisitDate');
		$query->join('INNER', $db->quoteName('#__users').' AS u ON a.school_user_id = u.id');
		
		//$query->select('COUNT(c.request_id) AS nb_requests');
		$query->join('LEFT', '(' . $subQuery . ') AS c ON a.id = c.target_school_id');
		
		if ($this->getState('filter.search') !== '') { 
			// Escape the search token.
			$token	= $db->Quote('%'.$db->escape($this->getState('filter.search')).'%');
			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'a.id LIKE '.$token;
			$searches[]	= 'a.name LIKE '.$token;
			$searches[]	= 'a.phone LIKE '.$token;
			$searches[]	= 'a.fax LIKE '.$token;
			$searches[]	= 'u.username LIKE '.$token;
			$searches[]	= 'u.email LIKE '.$token;
			//$searches[]	= 'COUNT(c.request_id) LIKE '.$token;
			
			$date = date('Y-m-d', strtotime($db->escape($this->getState('filter.search'))));
			if ($date) { 
				$searches[]	= 'u.lastvisitDate = "'.$date .'"';
			}
			
			// Add the clauses to the query.
			$query->where('('.implode(' OR ', $searches).')');
		}
		
		$schoolId = (int)$this->getState('filter.school');
		if ($schoolId) { 
			$query->where('a.id = ' . $schoolId);
		}
		
		$status = (int)$this->getState('filter.status', -1);
		if ($status == 0) { 
			$query->where('a.represent_user_id IS NULL OR  a.represent_user_id = ' . $status);
		} 
		
		if ($status > 0) { 
			$query->where('a.represent_user_id = ' . VjeecHelper::getJalsaUserId());
		}
		
		//$query->group('a.id, a.name, a.phone, a.fax, u.email, u.lastvisitDate, a.school_user_id');
		$query->order($db->escape($this->getState('list.ordering', 'a.name')).' '.$db->escape($this->getState('list.direction', 'asc')));
		//echo nl2br(str_replace('#__','imz_',$query));
		return $query;
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
	
	public function getItems() { 
		$items = parent::getItems();
		return $items;
	}

}

?>
