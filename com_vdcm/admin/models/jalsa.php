<?php 

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class VjeecDcmModelJalsa extends JModelList { 
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'created_date', 'a.created_date',
				'expected_send_date' => 'a.expected_send_date',
				'current_processing_step', 'a.current_processing_step',
				'diploma_id', 'a.diploma_id',
				'request_type', 'a.request_type',
				'requester_name', 'u.name',
				'holder_name', 'b.holder_name',
				'attachment_id', 'b.attachment_id',
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
	}
	
	public function createCode($rq) {   
    	$date = date_create($rq->created_date);
    	return $rq->degree_code . $date->format('Ymd') . $rq->request_id;
  	}
	
	public function getItems() { 
		$items = parent::getItems();
		foreach ($items as $rq) { 
        	$rq->code = $this->createCode($rq);
        	$processing_history = $this->getProcessingHistory($rq->id);
        	$rq->last_update = null;
        	$rq->processing_status_id = null;
			foreach ($processing_history as $step) {
                if ($step->id != $rq->current_processing_step)
                    continue;
                $rq->processing_status = JText::_($step->name);
                $rq->last_update = $step->begin_date;
                $rq->processing_status_id = $step->name_id;
                break;
            }
		}
		return $items;
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
		
		$statusId = $this->getUserStateFromRequest($this->context.'.filter.status', 'filter_status', null, 'int');
		$this->setState('filter.status', $statusId);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_vjeecdcm');
		$this->setState('params', $params);
		
		// List state information.
		parent::populateState('a.id', 'desc');
	}
	
	public function getProcessingHistory($reqID) {
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    $query->select(array('a.id', 
				 'a.begin_date', 
				 'a.end_date',
				 'a.result',
	             'a.name_id',
	             'b.name'
	             ))
	          ->from('#__vjeecdcm_process AS a')
	          ->join('INNER', '#__vjeecdcm_processing_step_name AS b ON (a.name_id = b.id)')
	          ->where('a.request_id = '. $reqID);
	    try {
	      	$db->setQuery($query);
	      	$result = $db->loadObjectList();
	      	foreach ($result as $step) {
	        	$step->translated_name = JText::_($step->name);
	      	}
	      	return $result;
	    } catch (Exception $e) {
	      	JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
	      	return NULL;
	    }
  	}
	
	
	public function getListQuery() { 
		
		$db = $this->getDbo();
		$query	= $db->getQuery(true);
		
		$query->select($this->getState( 'list.select',
			'a.id, a.created_date, a.current_processing_step, a.diploma_id, a.request_type, a.id AS request_id, a.expected_send_date'
		));
		$query->from($db->quoteName('#__vjeecdcm_diploma_request'). ' AS a');
		
		//join to users
		$query->select('u.name AS requester_name, u.username as sender_name');
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
		
		//join to observer
		//$query->select('d.name as school_name, d.school_user_id');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_request_observer').' AS o ON a.id = o.request_id');
		
		//join to process
		$query->select('e.begin_date, e.name_id AS proc_step_id');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_process').' AS e ON a.current_processing_step = e.id');
		
		//join to processing_step_name
		$query->select('f.name as current_status');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_processing_step_name').' AS f ON e.name_id = f.id');
		
		$query->where('o.observer_id = 604');
		
        // Filter the items over the search string if set.
		if ($this->getState('filter.search') !== '') { 
			// Escape the search token.
			$token	= $db->Quote('%'.$db->escape($this->getState('filter.search')).'%');
			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'a.id LIKE '.$token;
			$searches[]	= 'u.name LIKE '.$token;
			$searches[]	= 'd.name LIKE '.$token;
			$searches[]	= 'b.holder_name LIKE '.$token;
			$searches[]	= 'f.name LIKE '.$token;
			
			$date = date('Y-m-d', strtotime($db->escape($this->getState('filter.search'))));
			if ($date) { 
				$searches[]	= 'e.begin_date = "'.$date .'"';
				$searches[]	= 'a.created_date = "'.$date . '"';
				$searches[]	= 'a.expected_send_date = "'.$date . '"';
			}

			// Add the clauses to the query.
			$query->where('('.implode(' OR ', $searches).')');
		}
		
		$state = (int)$this->getState('filter.state');
		if ($state == 0) { 
			$query->where('(directory_id IS NULL OR directory_id >= 0)');
		} else { 
			$query->where('directory_id = ' . $state);
		}
		
		$schoolId = (int)$this->getState('filter.school');
		if ($schoolId) { 
			$query->where('d.id = ' . $schoolId);
		}
		
		$requesterId = (int)$this->getState('filter.requester');
		if ($requesterId) { 
			$query->where('u.id = ' . $requesterId);
		}
		
		$status = (int)$this->getState('filter.status');
		if ($status) { 
			$query->where('f.id = ' . $status);
		}
		
		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.id')).' '.$db->escape($this->getState('list.direction', 'desc')));
		//echo nl2br(str_replace('#__','vpt_',$query));
		return $query;
	}

}
	
?>