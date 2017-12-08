<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Include dependancy of the main model form
jimport('joomla.application.component.modellist');
 
class VjeecDcmModelClientRequest extends JModelList { 
	
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'request_code', 'request_code',
				'created_date', 'a.created_date',
				'current_processing_step', 'a.current_processing_step',
				'request_type', 'a.request_type',
				'holder_name', 'b.holder_name',
				'degree_name', 'c.name',
				'degree_code', 'c.degree_code',
				'school_name', 'd.name',
				'school_user_id', 'd.school_user_id',
				'begin_date', 'e.begin_date',
				'proc_step_id', 'e.name_id',
				'current_status', 'f.name'
			);
		}

		parent::__construct($config);

		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null) {
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
		
		$statusId = $this->getUserStateFromRequest($this->context.'.filter.status', 'filter_status', null, 'int');
		$this->setState('filter.status', $statusId);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_vjeecdcm');
		$this->setState('params', $params);
		
		// List state information.
		parent::populateState('b.holder_name', 'desc');
	}
	
  	public function getItems() { 
  		$items	= parent::getItems();
  		return $items;
  	}
  	
	protected function getListQuery() { 
		// Create a new query object.
		$db = $this->getDbo();
		$query	= $db->getQuery(true);
		
		$userID = JFactory::getUser()->id;
		
		$query->select($this->getState( 'list.select',
			"a.id, a.created_date, a.current_processing_step, a.request_type,
			 CONCAT(c.degree_code, CONCAT(DATE_FORMAT(a.created_date, '%Y%m%d'), a.id)) AS request_code"
		));
		$query->from($db->quoteName('#__vjeecdcm_diploma_request'). ' AS a');
		
		//join to users
		$query->join('INNER', $db->quoteName('#__users').' AS u ON a.user_id = u.id');
		
		//join to diploma
		$query->select('b.holder_name');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_diploma').' AS b ON a.diploma_id = b.id');
		
		//join to diploma_degree
		$query->select('c.name AS degree_name, c.degree_code');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_diploma_degree').' AS c ON b.degree_id = c.id');
		
		//join to school
		$query->select('d.name as school_name, d.school_user_id');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_school').' AS d ON a.target_school_id = d.id');
		
		//join to process
		$query->select('e.begin_date');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_process').' AS e ON a.current_processing_step = e.id');
		
		//join to processing_step_name
		$query->select('f.name as current_status');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_processing_step_name').' AS f ON e.name_id = f.id');
		
		$query->where('a.user_id = '.$userID);
		
		// Filter the items over the search string if set.
		if ($this->getState('filter.search') !== '') { 
			// Escape the search token.
			$token	= $db->Quote('%'.$db->escape($this->getState('filter.search')).'%');
			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= "CONCAT(c.degree_code, CONCAT(DATE_FORMAT(a.created_date, '%Y%m%d'), a.id)) LIKE $token";
			$searches[]	= 'b.holder_name LIKE '.$token;
			$searches[]	= 'd.name LIKE '.$token;
			$searches[]	= 'f.name LIKE '.$token;
			
			$date = date('Y-m-d', strtotime($db->escape($this->getState('filter.search'))));
			if ($date) { 
				$searches[]	= 'e.begin_date = "'.$date .'"';
				$searches[]	= 'a.created_date = "'.$date . '"';
			}

			// Add the clauses to the query.
			$query->where('('.implode(' OR ', $searches).')');
		}
		
		$schoolId = (int)$this->getState('filter.school');
		if ($schoolId) { 
			$query->where('d.id = ' . $schoolId);
		}
		
		$status = (int)$this->getState('filter.status');
		if ($status) { 
			$query->where('f.id = ' . $status);
		}
		
		$field = $this->getState('list.ordering');
		if ($field == 'request_code' || $field == '') { 
			$field = "CONCAT(c.degree_code, CONCAT(DATE_FORMAT(a.created_date, '%Y%m%d'), a.id))";
		}
		
		// Add the list ordering clause.
		$query->order($field.' '.$db->escape($this->getState('list.direction', 'desc')));
		//echo nl2br(str_replace('#__','vj_',$query));
		return $query;
	}
}
