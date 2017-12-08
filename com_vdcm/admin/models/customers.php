<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class VjeecDcmModelCustomers extends JModelList { 
	
	public function __construct($config = array()) { 
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'client_id', 'a.id',
				'name', 'a.name',
				'username', 'u.username',
				'email', 'u.email',
				'lastvisitDate', 'u.lastvisitDate',
				'nb_requests', 'nb_requests',
				'nb_diplomas', 'nb_diplomas',
				
			);
		}

		parent::__construct($config);
	}
	
	public function getItems() { 
		$items = parent::getItems();
		
		return $items;
	}
	
	protected function populateState($ordering = null, $direction = null) {
		$app = JFactory::getApplication('administrator');

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout', 'default'))
		{
			$this->context .= '.'.$layout;
		}

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		// List state information.
		parent::populateState('a.name', 'desc');
	}
	
	public function getListQuery() { 
		$db = $this->getDbo();
		$query	= $db->getQuery(true);
		
		$query->select($this->getState( 'list.select',
			'a.id AS client_id, a.name, a.client_user_id, nb_requests, nb_diplomas'
		));
		$query->from($db->quoteName('#__vjeecdcm_client'). ' AS a');
		
		//join to users
		$query->select('u.email, u.lastvisitDate');
		$query->join('INNER', $db->quoteName('#__users').' AS u ON a.client_user_id = u.id');
		
		$reqQuery = $db->getQuery(true);
		$query->join('LEFT', '(' . $reqQuery->select('user_id, COUNT(id) AS nb_requests')
							->from($db->quoteName('#__vjeecdcm_diploma_request'))
							->group('user_id') .') AS c ON a.client_user_id = c.user_id');
		$diplomaQuery = $db->getQuery(true);
		$query->join('LEFT', '(' . $diplomaQuery->select('user_id, COUNT(diploma_id) AS nb_diplomas')
							->from($db->quoteName('#__vjeecdcm_user_diploma'))
							->group('user_id') .' ) AS d ON a.client_user_id = d.user_id');
		
		// Filter the items over the search string if set.
		if ($this->getState('filter.search') !== '') { 
			// Escape the search token.
			$token	= $db->Quote('%'.$db->escape($this->getState('filter.search')).'%');
			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'a.id LIKE '.$token;
			$searches[]	= 'a.name LIKE '.$token;
			$searches[]	= 'u.email LIKE '.$token;
			$searches[]	= 'nb_requests LIKE '.$token;
			$searches[]	= 'nb_diplomas LIKE '.$token;
			
			$date = date('Y-m-d', strtotime($db->escape($this->getState('filter.search'))));
			if ($date) { 
				$searches[]	= 'u.lastvisitDate = "'.$date .'"';
			}

			// Add the clauses to the query.
			$query->where('('.implode(' OR ', $searches).')');
		}
		
		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.name')).' '.$db->escape($this->getState('list.direction', 'asc')));
		//echo nl2br(str_replace('#__','vpt_',$query));
		return $query;
	}
	
}