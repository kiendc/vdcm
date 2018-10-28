<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT.'/helpers/vjeec.php';
class VjeecDcmModelRequests extends JModelList { 
	
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
 
  	public function createPrivateCode($rq) {
    	$date = date_create($rq->created_date);
    	return $rq->request_id . '-' . $rq->degree_code . '-' . $date->format('Ymd') ;
  	}
  
  	public function createRoute($rq) {
    	if ($rq->request_type == 0) { 
    		$jalsaUser = VjeecHelper::getJalsaUserId();
      		return JFactory::getUser($jalsaUser)->get('username') . ' / ' . $rq->target_school_name; // JaLSA
    	} else if ($rq->request_type == 1) {
      		return $rq->target_school_name;
    	}
  	}
  
  	public function createSimpleRoute($rq) {
     	if ($rq->request_type == 0) { 
     		$jalsaUser = VjeecHelper::getJalsaUserId();
        	return  JFactory::getUser($jalsaUser)->get('username'); // JaLSA
      	} else if ($rq->request_type == 1) {
        	return $rq->target_school_name;
      	} 
  	}
  
  	public function addRequest($data) { 
	    $db = JFactory::getDBO();
	    $req = new stdClass();
	    $req->id = NULL;
	    $req->user_id = $data['user_id'];
	    $req->diploma_id = $data['diploma_id'];
	    $req->request_type = $data['request_type'];
	    $req->created_date = JFactory::getDate()->format('Y-m-d');
	    $req->target_school_id = $data['target_school_id'];
	    $req->directory_id = 2; // Chuyen vao thu muc gui den vjeec
	  
	    $result = $db->insertObject('#__vjeecdcm_diploma_request', $req, 'id');
	    if ($result) {
	      // Add observer here
	      $reqId = $db->insertid();
	      $procId = $this->takeActionOnRequest($reqId, 2); // submitted to VJEECC
	      return $reqId;
	    }
	    return -1;
  	}
  
  	public function addRequestFromObj(&$req) {
    	$db = JFactory::getDBO();
	    $req->id = NULL;
	    $req->user_id = JFactory::getUser()->id;
	    $req->created_date = JFactory::getDate()->format('Y-m-d');
	    $req->directory_id = 2; // Chuyen vao thu muc gui den vjeec
	  
	    $result = $db->insertObject('#__vjeecdcm_diploma_request', $req, 'id');
	    if ($result) {
	      // Add observer here
	      $reqId = $db->insertid();
	      $procId = $this->takeActionOnRequest($reqId, 2); // submitted to VJEECC
	      return $reqId;
	      
	    }
	    return -1;
  	}
  
  	public function getCreatedRequests() {
	    $userID = JFactory::getUser()->id;
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    
	    $query->select(array('a.created_date', 
				 'a.current_processing_step',  
				 'a.user_id',
	                         'a.diploma_id',
				 'a.target_school_id',
	                         'a.request_type',
	                         'a.id AS request_id',
	                         'b.holder_name',
	                         'c.name AS degree_name',
	                         'c.degree_code',
				 'd.school_user_id',
	                         'e.begin_date',
	                         'f.name'
	                         ))
	          ->from('#__vjeecdcm_diploma_request AS a')
	          ->join('INNER', '#__vjeecdcm_diploma AS b ON (a.diploma_id = b.id)')
	          ->join('INNER', '#__vjeecdcm_diploma_degree AS c ON (b.degree_id = c.id)')
		  ->join('INNER', '#__vjeecdcm_school AS d ON (a.target_school_id = d.id)')
	          ->join('INNER', '#__vjeecdcm_process AS e ON (a.current_processing_step = e.id)')
	          ->join('INNER', '#__vjeecdcm_processing_step_name AS f ON (e.name_id = f.id)')
	          ->where('a.user_id = '. $userID);
	    try  {
	      	$db->setQuery($query);
	      	$reqs = $db->loadObjectList();
	      	$jalsaUser = VjeecHelper::getJalsaUserId();
	      	foreach ($reqs as $rq) {
	        	$rq->target_school_name =  JFactory::getUser($rq->school_user_id)->get('name');
	        	$rq->code = $this->createCode($rq);
	        
		        if ($rq->request_type == 0) {
		          	$rq->route =  JFactory::getUser($jalsaUser)->get('username') . ' / ' . $rq->target_school_name; // JaLSA
		        } else if ($rq->request_type == 1) {
		          	$rq->route = $rq->target_school_name;
		        }
	      	}
	      	return $reqs;
	    } catch (Exception $e) {
	      	JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
	      	return NULL;
	    }
  	}
  
  	public function getNumberObservingRequests($observerUID) {
	    if ($observerUID == NULL) {
	      	$userID = JFactory::getUser()->id;
	    } else {
	      	$userID = $observerUID;
	    }
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	
	    $query->select('COUNT(request_id)')
	          ->from('#__vjeecdcm_request_observer')
	          ->where('observer_id = ' . $userID);
	    $db->setQuery($query);
	    $count = 0;
	    $count = $db->loadResult();
	    return $count;
  	}
  	
  	public function getRequestsInfo($cid) { 
  		 
  		$db = $this->getDbo();
		$query	= $db->getQuery(true);
		
		$query->select($this->getState( 'list.select',
			'a.id as request_id, a.created_date, a.request_type, a.expected_send_date, a.request_type, a.target_school_id'
		));
		$query->from($db->quoteName('#__vjeecdcm_diploma_request'). ' AS a');
		
		//join to diploma
		$query->select('b.holder_name, b.certificate_type');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_diploma').' AS b ON a.diploma_id = b.id');
		
		//join to diploma_degree
		$query->select('c.name_en AS degree_name, c.degree_code, c.cover_name');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_diploma_degree').' AS c ON b.degree_id = c.id');
		
		//join to school
		$query->select('d.name as school_name');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_school').' AS d ON a.target_school_id = d.id');
		
		$query->where('a.id IN ('.implode(',', $cid) .')');
		$query->order('d.name ASC, b.holder_name ASC');
		
		try { 
			$db->setQuery($query);
			$ret = $db->loadObjectList();
			if (count($ret)) { 
				foreach ($ret as $item) { 
					$item->code = $this->createCode($item);
					$item->degree_name = ucfirst(strtolower($item->degree_name));
				}
			}
		} catch (Exception $e) { 
			$ret = null;
		}
		return $ret;
  	}
  	
  	public function processQualification($degree_name, $degreeCode) {
  		$vjeec_certificate = ''; 
  		if ($degreeCode[0] == 'C' || $degreeCode[1] == 'C') { 
  			$vjeec_certificate = '(temporary VJEEC certificate)';
  		} else { 
  			$vjeec_certificate = '(VJEEC certificate)';
  		}
  		
  		$transcripText = '';
  		if ($degreeCode[0] == 'T') { 
  			if (strpos($degreeCode, 'H')) { 
  				$transcripText = 'School record';
  			} else { 
  				$transcripText = 'Academic transcripts';
  			}
  		}
  		
  		$ret = '';
  		if (strpos($degree_name, 'and')) { 
  			$ret = substr($degree_name, 0, strpos($degree_name, 'and'));
  		} else { 
  			$ret = $degree_name;
  		}
  		//replace (temporary) text by space 
  		$ret = str_replace('(temporary)', '', $ret);
  		
  		if ($vjeec_certificate != '') { 
  			$ret .=  ' '. $vjeec_certificate;
  		}
  		
  		if ($transcripText != '') { 
  			$ret .=  ' & '. $transcripText;
  		}
  		
  		if ($degreeCode == 'TH') { 
  			$ret = $transcripText;
  		}
  		
  		return $ret;
  	}
  	public function formatCoverPage(&$list)
  	{
  		foreach ($list as $item) {
  			$item->holder_name = strtoupper(VjeecHelper::remove_vietnamese_accents($item->holder_name));
  		}
  	}
  	public function generateCoverPage($list, $date, $type='school') { 
  		require_once JPATH_COMPONENT.'/helpers/vjeec.php';
  		
  		if ($type == 'school') { 
  			$schoolId = $list[0]->target_school_id;
			$schoolObj = $this->getSchoolDetail($schoolId);
  		}
  		
  		if ($date != null) { 
  			$date = date('F d, Y', strtotime($date));
  		}
		
		$tbody = '';
		$i = 1;
		foreach ($list as &$item) { 
			if ($type == 'school') { 
				$tbody .= '<tr class="content">
							<td valign="top" style="width:35pt;">'. $i .'</td>
							<td valign="top" align="left" style="width:125pt;padding-left:5pt;">'. strtoupper(VjeecHelper::remove_vietnamese_accents($item->holder_name)) .'</td>
							<td valign="top" style="width:100pt;">'. $item->code .'</td>
							<td valign="top" style="width:170pt;">'.$item->cover_name .'</td>
						</tr>';
			} else { 
				$tbody .= '<tr class="content2">
							<td align="center" style="width:35pt;">'. $i .'</td>
							<td style="width:100pt;">'. $item->code .'</td>
							<td style="width:130pt;">'. strtoupper(VjeecHelper::remove_vietnamese_accents($item->holder_name)) .'</td>
							<td style="width:250pt;">'.$item->school_name .'</td>
						  </tr>';
			}
			$i++;
		}
		
		if ($type == 'school') {
  			$template = '<style>
  							#tbl_school_info { padding:0;margin:60pt 0 0 60pt;border:1px solid #000;font-size:10pt;font-family:Arial;font-weight:bold;}
  							#tbl_school_info td {width:430pt;padding:3pt 0 3pt 5pt;}
  							#shipment {padding:0;margin:40pt 0 10pt 65pt;font-size:10pt;}
  							#tbl_list_student { border-collapse: collapse;padding:0;margin:0 0 0 60pt;font-size:10pt;font-family:Arial;}
  							#tbl_list_student tr td { border: 1px solid #000;}
  							#tbl_list_student tr#tr_head td {text-align:center;font-weight:bold;padding:3pt 0 6pt 0;color:#002060;}
  							#tbl_list_student tr.content td {padding:3pt 0 10pt 0;word-wrap: break-word;text-align:center;}
  						</style>
  						<table id="tbl_school_info">
  						<tr>
  							<td> SCHOOL NAME: '.  $schoolObj->name .'</td>
  						</tr>
  						<tr>	
  							<td> ADDRESS: '.  $schoolObj->address .'</td>
						</tr>	  							
						<tr>	  							
  							<td> ZIP CODE: '.  $schoolObj->zipcode .'</td>
  						</tr>
  						<tr>	
  							<td> TEL: '.  $schoolObj->phone .'</td>
  						</tr>
  						<tr>	
  							<td> ATTN: '.  $schoolObj->contact_name .'</td>
  						</tr>
  					</table>
  					<p id="shipment"> Shipment on <span style="color:red;">'.$date.'</span></p>
  					<table id="tbl_list_student" cellspacing="0">
  						<tr>
  							<td colspan="4" style="border:1px solid #000;width:435pt;vertical-align:top;padding:6pt 0 15pt 0;text-align:center;font-weight:bold;color:#002060; background:#dbe5f1;font-size:10pt;font-family:Arial;">
  								VERIFICATION OF QUALIFICATIONS
  							</td>
  						</tr>
  						<tr id="tr_head">
  							<td style="width:35pt;">No.</td>
  							<td style="width:125pt;text-align:center;font-weight:bold;padding:3pt 0 6pt 0;color:#002060;">Student Name</td>
  							<td style="width:100pt;text-align:center;font-weight:bold;padding:3pt 0 6pt 0;color:#002060;">Reference No.</td>
  							<td style="width:170pt;text-align:center;font-weight:bold;padding:3pt 0 6pt 0;color:#002060;">Qualifications</td>
  						</tr>'. $tbody .'
  					</table>
  					';
		} else { 
			$template = '<style>
							#tbl_jalsa { border-collapse: collapse;padding:0;font-size:10pt;font-family:Arial;}
							#tbl_jalsa tr td { border:1px solid #000;text-align:left;word-wrap: break-word;}
							#tbl_jalsa tr#tr_head2 {background: #92d050;font-weight:bold;font-size:11pt;color:#002060;}
							#tbl_jalsa tr#tr_head2 td{ padding: 3pt 0 6pt 0;text-align:center;}
							#tbl_jalsa tr.content2 td { padding:0 0 0 5pt;}
							#shipment2 { font-weight:bold;padding:0;margin:40pt 0 10pt 65pt;font-size:12pt;}
						</style>	
						<p id="shipment2">VJEEC to JaLSA: Shipment on '.$date .'. Total requests: '. count($list) .'</p>	
						<table id="tbl_jalsa" cellspacing="0">
  						<tr id="tr_head2">
  							<td style="width:35pt;">Order</td>
  							<td style="width:100pt;">Code</td>
  							<td style="width:130pt;">Student</td>
  							<td style="width:250pt;">School</td>
  						</tr>'. $tbody .'
  					</table>';
		}
  		require_once(JPATH_COMPONENT .'/pdf/html2pdf.class.php');
		
		define('FONT_EMBEDDING_MODE', 'all');
		try { 
			$html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8');
			$html2pdf->pdf->SetDisplayMode('real');
			//add Arial Narrow font
			$html2pdf->writeHTML($template, false);
			
		} catch(HTML2PDF_exception $e) { 
			echo $e;
		}
		
		return $html2pdf;
  	}
  	
  	public function getSchoolDetail($schoolId) { 
  		$db = $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select($this->getState( 'list.select',
			'a.name, a.address, a.zipcode, a.phone, a.contact_name'
		));
		$query->from($db->quoteName('#__vjeecdcm_school'). ' AS a');
		$query->where('a.id = '.$schoolId);
		
		try { 
			$db->setQuery($query);
			$school = $db->loadObject();
		} catch (Exception $e) { 
			$school = null;
		}
		return $school;
  	}
  	
  	public function getDiplomaByRequest($reqId) {
  		$db = $this->getDbo();
  		$query	= $db->getQuery(true);
  		$query->select($this->getState( 'list.select',
  				'a.diploma_id'
  		));
  		$query->from($db->quoteName('#__vjeecdcm_diploma_request'). ' AS a');
  		$query->where('a.id = '.$reqId);
  	
  		try {
  			$db->setQuery($query);
  			$ret = $db->loadObject();
  			
  			$diploma = new stdClass();
  			$diploma->id = $ret->diploma_id;
  			$diploma->certificate_finished = 2; //trang thai hoan thanh
  			
  			return $diploma;
  		} catch (Exception $e) {
  			return null;
  		}
  	}
  	
  	public function getDiplomaObjByRequest($reqId, $value) {
  		$db = $this->getDbo();
  		$query	= $db->getQuery(true);
  		$query->select($this->getState( 'list.select',
  				'a.diploma_id'
  		));
  		$query->from($db->quoteName('#__vjeecdcm_diploma_request'). ' AS a');
  		$query->where('a.id = '.$reqId);
  		 
  		try {
  			$db->setQuery($query);
  			$ret = $db->loadObject();
  				
  			$diploma = new stdClass();
  			$diploma->id = $ret->diploma_id;
  			$diploma->forgery = $value;
  			return $diploma;
  		} catch (Exception $e) {
  			return null;
  		}
  	}
  	 	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
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
		
		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', null, 'int');
		$this->setState('filter.state', $state);
		
		$schoolId = $this->getUserStateFromRequest($this->context.'.filter.school', 'filter_school', null, 'int');
		$this->setState('filter.school', $schoolId);
		
		$userID = $this->getUserStateFromRequest($this->context.'.filter.requester', 'filter_requester', null, 'int');
		$this->setState('filter.requester', $userID);
		
		$statusId = $this->getUserStateFromRequest($this->context. '.filter.status', 'filter_status', null, 'int');
		$this->setState('filter.status', $statusId);
		
		$expectedSendDate = $this->getUserStateFromRequest($this->context.'.filter.expectedSendDate', 'filter_expected_send_date', null);
		if ($expectedSendDate != '') { 
			$expectedSendDate = date('Y-m-d', strtotime(str_replace('/', '-', $expectedSendDate)));
		}
		
		$this->setState('filter.expectedSendDate', $expectedSendDate);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_vjeecdcm');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'desc');
	}
	
  	public function getItems() {   	  
    	$items	= parent::getItems();
    	$jalsaUser = VjeecHelper::getJalsaUserId();
  		foreach ($items as $rq) { 
	        	$rq->code = $this->createCode($rq);
	        	
	        	if ($rq->request_type == 0) {
	          		$rq->route =  JFactory::getUser($jalsaUser)->get('username') . ' / ' . $rq->school_name; // JaLSA
	        	} else if ($rq->request_type == 1) {
	          		$rq->route = $rq->school_name;
	        	}
      	}
      	
		return $items;
  	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.6
	 */
	protected function getListQuery() { 
		// Create a new query object.
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
		$query->select('b.holder_name, b.attachment_id, b.certificater, b.transcripter, b.certificate_finished, forgery');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_diploma').' AS b ON a.diploma_id = b.id');
		
		//join to diploma_degree
		$query->select('c.name AS degree_name, c.degree_code');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_diploma_degree').' AS c ON b.degree_id = c.id');
		
		//join to school
		$query->select('d.name as school_name, d.school_user_id');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_school').' AS d ON a.target_school_id = d.id');
		
		//join to process
		$query->select('e.begin_date, e.name_id AS proc_step_id');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_process').' AS e ON a.current_processing_step = e.id');
		
		//join to processing_step_name
		$query->select('f.name as current_status');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_processing_step_name').' AS f ON e.name_id = f.id');
		
		$loggedUser = JFactory::getUser();
		// filter requests which be assigned to employee
		if (!VjeecHelper::isAdministrator()) { 
			$query->where('(b.certificater = '. $loggedUser->get('id') .' OR b.transcripter = '. $loggedUser->get('id') .' ) ');
		}
		
		$filterIsSet = false;
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
			//$filterIsSet = true;
		}
		
		$state = (int)$this->getState('filter.state');
		if ($state == 0) { 
			$query->where('(directory_id IS NULL OR directory_id >= 0)');
		} else { 
			$query->where('directory_id = ' . $state);
			//$filterIsSet = true;
		}
		
		$schoolId = (int)$this->getState('filter.school');
		if ($schoolId) { 
			$query->where('d.id = ' . $schoolId);
			$filterIsSet = true;
		}
		
		$requesterId = (int)$this->getState('filter.requester');
		if ($requesterId) { 
			$query->where('u.id = ' . $requesterId);
			$filterIsSet = true;
		}
		
		$status = (int)$this->getState('filter.status');
		if ($status) { 
			$query->where('f.id = ' . $status);
			$filterIsSet = true;
		}
		
		$expectedSendDate = $this->getState('filter.expectedSendDate');
		if ($expectedSendDate) { 
			$query->where('a.expected_send_date = "' . $expectedSendDate . '"');
			$filterIsSet = true;
		}
		
		if (!$filterIsSet){
			$query->where('a.created_date  BETWEEN "2018-08-19" AND "2019-03-31"', 'AND');
			$query->where('f.id != 5', 'AND');
			$query->where('f.id != 6');
		}	
		// Add the list ordering clause.
		$orderBy = $db->escape($this->getState('list.ordering', 'a.id'));
		if ($orderBy == 'a.expected_send_date') { 
			$orderBy = ' ISNULL(a.expected_send_date), a.expected_send_date';
		}
		
		$query->order($orderBy.' '.$db->escape($this->getState('list.direction', 'desc')));
		//echo nl2br(str_replace('#__','vpt_',$query));
		$query->setLimit(10);
		return $query;
	}
	
	public function getCurrentStepsRequest($requestId) { 
		$db = $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select('a.current_processing_step as current_process_id, f.id, f.next_step_id, f.previous_step_id, f.name');
		$query->from($db->quoteName('#__vjeecdcm_diploma_request'). ' AS a');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_process').' AS e ON a.current_processing_step = e.id');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_processing_step_name').' AS f ON e.name_id = f.id');
		$query->where('a.id = ' . $requestId);
		try {
			$db->setQuery($query);
			$result = $db->loadObject();
		} catch (Exception $e) { 
			echo $e->getMessage();
			$result = null;
		}
		return $result;
	}
	
	public function updateProcessStepRequest($requestId, $stepId) { 
		
		return $this->takeActionOnRequest($requestId, $stepId);
	}
	
	/**
	 * 
	 * Move requests to directory
	 * @param array $cid
	 * @param integer $dirID
	 */
	public function moveRequestsToDirectory($cid, $dirID) { 
		$db = $this->getDbo();
		$query	= $db->getQuery(true);
		
		$fields = array( $db->quoteName('directory_id') . ' = ' . $dirID );
	 
		// Conditions for which records should be updated.
		$conditions = array(
	    	$db->quoteName('id') . ' in ('. implode(',', $cid) .')'
		);
		
		$query->update($db->quoteName('#__vjeecdcm_diploma_request'))->set($fields)->where($conditions);
	 	
		try {
			$db->setQuery($query);
			$result = $db->query();
			return $result;
		} catch (Exception $e) { 
			echo $e->getMessage();
	      	return false;
	    }
	}
	
  
  	public function getRequestsInDirectory($dirId, $stepId=0, $offset=0, $limit=0, $searchValue='') {
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    $whereCond = ' ( a.directory_id';
	    if (is_null($dirId) || $dirId == 0) {
	      	$whereCond .= ' IS NULL OR a.directory_id >= 0 ) ';
	    } else {
	      	$whereCond .= (' = ' . $dirId . ' ) ');
	    }
	    
	    if ($searchValue != '') {
	      	$whereCond .= " AND ( b.holder_name LIKE '%" . $searchValue . "%' " .
	                        "OR d.name LIKE '%" . $searchValue . "%' " . ")";
	    }
	    
	    if ($stepId != 0) {
	      	$whereCond .=  ('AND (e.name_id  = ' . $stepId . ')');
	    }
	    
	    $query->select(array('a.created_date', 
					'a.current_processing_step', 
					'a.user_id',
					'a.diploma_id',
					'a.target_school_id',
	                'a.request_type',
					'a.id AS request_id',
					'a.directory_id',
					'b.holder_name',
					'b.holder_identity_type',
					'b.holder_identity_value',
					'b.attachment_id',
					'c.name AS degree_name',
					'c.degree_code',
					'd.school_user_id',
					'e.begin_date',
					'e.name_id AS proc_step_id',
					'f.name')
	    		)
	          	->from('#__vjeecdcm_diploma_request AS a')
	          	->join('INNER', '#__vjeecdcm_diploma AS b ON (a.diploma_id = b.id)')
	          	->join('INNER', '#__vjeecdcm_diploma_degree AS c ON (b.degree_id = c.id)')
		  		->join('INNER', '#__vjeecdcm_school AS d ON (a.target_school_id = d.id)')
	          	->join('INNER', '#__vjeecdcm_process AS e ON (a.current_processing_step = e.id)')
	          	->join('INNER', '#__vjeecdcm_processing_step_name AS f ON (e.name_id = f.id)')
	          	->where($whereCond)
	          	->order('a.id DESC');
	    try {
	      	$db->setQuery($query, $offset, $limit);
	      	$requests = $db->loadObjectList();
	      	$nbReqs = count($requests);
	      	// Load detail of each request
	      	return $requests;
	    } catch (Exception $e) { 
	      	return NULL;
	    }
  	}
  
  	public function getNoTargetRequests() {
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    $query->select(array('a.created_date', 
				 	'a.current_processing_step', 
				 	'a.user_id',
				 	'a.diploma_id',
				 	'a.target_school_id',
                    'a.request_type',
                    'a.id AS request_id',
                    'b.holder_name',
                    'b.attachment_id',
                    'c.name AS degree_name',
                    'c.degree_code',
                    'e.begin_date',
                    'e.name_id AS proc_step_id',
                    'f.name'
	          ))
	          ->from('#__vjeecdcm_diploma_request AS a')
	          ->join('INNER', '#__vjeecdcm_diploma AS b ON (a.diploma_id = b.id)')
	          ->join('INNER', '#__vjeecdcm_diploma_degree AS c ON (b.degree_id = c.id)')
	          ->join('INNER', '#__vjeecdcm_process AS e ON (a.current_processing_step = e.id)')
	          ->join('INNER', '#__vjeecdcm_processing_step_name AS f ON (e.name_id = f.id)')
	          ->where('a.target_school_id = 0');
	    try {
	      	$db->setQuery($query);
	      	$requests = $db->loadObjectList();
	      	// Load detail of each request
	      	foreach ($requests as $rq) {
	        	$rq->requester_name = JFactory::getUser($rq->user_id)->get('name');
	        	$rq->code = $this->createCode($rq);
	     	}
	      	return $requests;
	    } catch (Exception $e) {
	      	//JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
	      	return NULL;
	    }     
  	}
  	
  	public function getRequestDetail($reqId) {
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    $query->select('a.*, c.name as degree_name')
	          ->from('#__vjeecdcm_diploma_request AS a')
	          ->join('INNER', '#__vjeecdcm_diploma AS b ON (a.diploma_id = b.id)')
	          ->join('INNER', '#__vjeecdcm_diploma_degree AS c ON (b.degree_id = c.id)')
	          ->where('a.id = ' . $reqId);
	    try {
	      	$db->setQuery($query);
	      	$req = $db->loadObject();
	      	$req->requester_name = JFactory::getUser($req->user_id)->get('name');
	      	return $req;
	    } catch (Exception $e) { 
	      	return NULL;
	    }
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
  
  	public function getDiplomaRelatedRequests($dplmId) {
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    $query->select(array('id', 
				 'created_date',
				 'completed_date'
	                         ))
	          ->from('#__vjeecdcm_diploma_request')
	          ->where('diploma_id = '. $dplmId);
	    try {
	      	$db->setQuery($query);
	      	$requests = $db->loadObjectList();
	      	return $requests;
	    } catch (Exception $e) {
	      	JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
	      	return NULL;
	    }
  	}
  
  	public function addObserverToRequest($reqId, $userId) {
	    $db = JFactory::getDBO();
	    $reqObs = new stdClass();
	    $reqObs->request_id = $reqId;
	    $reqObs->observer_id = $userId;
	    $result = $db->insertObject('#__vjeecdcm_request_observer', $reqObs);
  	}
  	
  	public function addSchoolAsRequestObserver($reqId, $schoolId = null) { 
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    if ($schoolId == null) {
	      	$query->select(array('school_user_id', 'represent_user_id'))
            	->from('#__vjeecdcm_school AS a')
	            ->join('INNER', '#__vjeecdcm_diploma_request AS b ON (a.id = b.target_school_id)')
	            ->where('b.id = ' . $reqId);
	    } else {
	      	$query->select(array('school_user_id', 'represent_user_id'))
	            ->from('#__vjeecdcm_school')
	            ->where('id = ' . $schoolId);      
	    }
	    
	    try {
	        $db->setQuery($query);
	        $result = $db->loadObjectList();
	        foreach ($result as $school) { 
	          	$this->addObserverToRequest($reqId, $school->school_user_id);
	          	if (!is_null($school->represent_user_id)) {
	            	$this->addObserverToRequest($reqId, $school->represent_user_id);
	          	}
	        }
	        
      	} catch (Exception $e) {
        	return NULL;
      	}
  	}
  	
  	public function updateSchoolRequestIfMissing($reqId) { 
  		$db = JFactory::getDBO();
  		$query = $db->getQuery(true);
  		$query->select(array('request_id', 'observer_id'))
  			->from('#__vjeecdcm_request_observer')
  			->where('request_id = ' . $reqId);
  		try {
  			$db->setQuery($query);
  			$result = $db->loadObjectList();
  			if (!count($result)) { 
  				$this->addSchoolAsRequestObserver($reqId);
  			}
  		} catch (Exception $e) {
  			return NULL;
  		}
  	}
  
  	public function canSchoolConfirmRequest($rq, $schoolUID) {
      	if ($rq->request_type > 1 )
         	return false;
		$jalsaUser = VjeecHelper::getJalsaUserId();
      	if ($schoolUID == $jalsaUser) {     
          	if ($rq->processing_status_id == 1 && $rq->request_type == 0)
                return true;         
          	return false;
      	}
      	
      	if (($rq->processing_status_id == 5 && $rq->request_type == 0) || ($rq->processing_status_id == 1 && $rq->request_type == 1))
          	return true;
      	return false;
  	}
  	
  	public function updateRequestProcessStepByDiploma($diplomaId, $procStepId) { 
  		$db = JFactory::getDBO();
  		$query = $db->getQuery(true);
  		$query->select(array('a.id'))
            	->from('#__vjeecdcm_diploma_request AS a')
            	->join('INNER', $db->quoteName('#__vjeecdcm_diploma').' AS b ON a.diploma_id = b.id')
	            ->where('a.diploma_id = ' . $diplomaId);
        $db->setQuery($query);
        $obj = $db->loadObject();
        if (!$obj) {  
        	return null;
        }
        
        $reqId = $obj->id;
        $currentStep = $this->getCurrentStepsRequest($reqId);
        if (!$currentStep) { 
        	return null;
        }
        
        if ($currentStep->id != $procStepId && $currentStep->name == 'VJEECDCM_REQUEST_PROCESS_WAITING') {
        	$this->takeActionOnRequest($reqId, $procStepId);
        }
  	}
  
  	public function insertProcessingStep($reqId, $procStepId) {
	    $db = JFactory::getDBO();
	    $actionDate = JFactory::getDate()->format('Y-m-d');
	    $proc = new stdClass();
	    $proc->request_id = $reqId;
	    $proc->begin_date = $actionDate;
	    if ($procStepId !== 3) {
	      	$proc->end_date = $actionDate; 
	    } else {
	      	$proc->end_date = null;
	    }
	    $proc->name_id = $procStepId;
	    $result = $db->insertObject('#__vjeecdcm_process', $proc, 'id');
	    if ($result) {
	      	return $db->insertid();
	    }
	    return -1;
  	}
  
  	public function takeActionOnRequest($reqId, $procStepId) { 
    	$procId = $this->insertProcessingStep($reqId, $procStepId);
    	if ($procStepId == 7) {  // Cập nhật ngày dự kiến gửi ở bước Chờ xử lý $procStepId == 7
    		$this->updateExpectedSendDate($reqId);
    	}
    	
    	if ($procId > -1) {
	      	//Add observer here
	      	$db = JFactory::getDBO();
		    $req = new stdClass();
		    $req->id = $reqId;
		    $req->current_processing_step = $procId;
	      	$result = $db->updateObject('#__vjeecdcm_diploma_request', $req, 'id');
	      	return $procId;
    	}
    	return null;  
  	}
  	
  	public function updateExpectedSendDate($reqId) { 
  		$db = JFactory::getDBO();
  		$query = $db->getQuery(true);
  		$query->select(array('request_type, expected_send_date'))
            	->from('#__vjeecdcm_diploma_request AS a')
	            ->where('a.id = ' . $reqId);
        $db->setQuery($query);
        $ret = $db->loadObject();
        $request_type = (int) $ret->request_type;
        $expected_date = $ret->expected_send_date;
        $currentDate = date('Y-m-d');
        if ($request_type) { 
        	$expected_send_date = date('Y-m-d', strtotime($currentDate. ' + 15 day'));
        } else { 
        	$day = (int) date('d');
        	if ($day <= 15) { 
        		$expected_send_date = date("Y-m-d", strtotime( date('Y-m-01') ." +1 month" ));
        	} else { 
        		$expected_send_date = date("Y-m-d", strtotime( date('Y-m-16') ." +1 month" ));
        	}
        }
        
        //just update when this field is empty
        if (!$expected_date) {
        	$req = new stdClass();
			$req->id = $reqId;
			$req->expected_send_date = $expected_send_date;
        	$result = $db->updateObject('#__vjeecdcm_diploma_request', $req, 'id');
        	return $result;
        }
  	}
  	
  	public function updateExpectedSendDate2($reqId, $expectedSendDate) { 
  		$db = JFactory::getDBO();
  		$query = $db->getQuery(true);
  		$req = new stdClass();
  		$req->id = $reqId;
  		$req->expected_send_date = $expectedSendDate;
  		$ret = null;
  		if ($reqId != null) {
  			$result = $db->updateObject('#__vjeecdcm_diploma_request', $req, 'id');
  		}
  		return $result;
  	}
  	
  	public function updateAllRequestsAsReceived() {
	    $userID = JFactory::getUser()->id;
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    
	    $procCond = '';
	    $procNameId = null;
	    $jalsaUser = VjeecHelper::getJalsaUserId();
	    if ($userID == $jalsaUser) {
	      	$procCond = 'c.name_id = 1 AND a.request_type = 0' ;
	      	$procNameId = 5;
	    } else {
	      	$procCond = '(c.name_id = 1 OR c.name_id = 5)';
	      	$procNameId = 6;
	    }
	    $query->select(array('a.current_processing_step',
	                         'a.request_type',
				 'a.user_id',
				 'b.request_id',
	                         'c.name_id'
	                         ))
	          ->from('#__vjeecdcm_diploma_request AS a')
	          ->join('INNER', '#__vjeecdcm_request_observer AS b ON (a.id = b.request_id)')
	          ->join('INNER', '#__vjeecdcm_process AS c ON (c.id = a.current_processing_step)')
	          ->where('b.observer_id = '. $userID . ' AND ' . $procCond) ;
	    try {
	      	$db->setQuery($query);
	      	$requests = $db->loadObjectList();
	      	foreach ($requests as $rq) { 
	        	$this->takeActionOnRequest($rq->request_id, $procNameId);
	      	}
	      	return $requests;
	    } catch (Exception $e) {
	      	JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
	      	return NULL;
	    }
  	}
  
  	public function schoolConfirmRequests($reqIds) {
	    $userId = JFactory::getUser()->id;
	    $jalsaUser = VjeecHelper::getJalsaUserId();
	    if ($userId == $jalsaUser) {
	      	$procNameId = 5;
	    } else {
	      	$procNameId = 6;
	    }
	    foreach ($reqIds as $rqId) {
	      	$this->takeActionOnRequest($rqId, $procNameId);
	    }
  	}
  
  	public function updateReqProcStep($reqId, $currProcStepId) { 
    	$db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    $query->select(array('next_step_id'))
	          ->from('#__vjeecdcm_processing_step_name')
	          ->where('id = '. $currProcStepId);
	    try 
	    {
	      $db->setQuery($query);
	      $steps = $db->loadObjectList();
	      foreach($steps as $stp)
	      {
	        $this->takeActionOnRequest($reqId, $stp->next_step_id);
	      }
	    }
	    catch (Exception $e) 
	    {
	      return NULL;
	    }
  	}
  
  	public function updateReqProcToStep($reqId, $currStep, $desiredStep, $nextStepSequence) {
    	$desiredStep = $desiredStep + 0; // Force desired step to type int
	    $db = JFactory::getDBO();
	    if ($currStep == 2) {
	      	$this->addSchoolAsRequestObserver($reqId, null);   
	    }
	    $nextStep = $nextStepSequence[$currStep - 1] + 0; // Change from string to int
	    while ($nextStep != $desiredStep && $nextStep != 0) { 
	        $this->insertProcessingStep($reqId, $nextStep);
	        $nextStep = $nextStepSequence[$nextStep - 1] + 0;
	    }
	    
	    if ($nextStep != 0) { 
	      	$this->takeActionOnRequest($reqId, $nextStep);
	    }
  	}
  
  	public function acceptSubmittedReq($reqId, $stepId) {
	    //Update current process step of request
	    $this->updateReqProcStep($reqId, $stepId);     
	    // Add observers
	    // Add JaLSA as an observer
	    // Add target school and it's reprsent as observers
	    $this->addSchoolAsRequestObserver($reqId, null); // second argument is set null, target school is added as observer
	    return;
  	}
 
  	public function removeRequest($reqId) {
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    
	    $query->delete('#__vjeecdcm_process');
	    $query->where('request_id = '. $reqId);   
	    $db->setQuery($query);  
	    $result = $db->query();
	    
	    $query->delete('#__vjeecdcm_request_observer');
	    $query->where('request_id = '. $reqId);
	    $db->setQuery($query);  
	    $result = $db->query();
	    
	    $query->delete('#__vjeecdcm_diploma_request');
	    $query->where('id = '. $reqId);
	    $db->setQuery($query);  
	    $result = $db->query();
  	}
  
  	public function removeRequests($reqIds) {
      	foreach($reqIds as $reqId) {
      		$this->removeRequest($reqId);
      	}
  	}
  	
  	public function getListEmployee() { 
  		$db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    $query->select(array('u.id, u.name'))
	          ->from('#__users AS u')
	          ->join('INNER', '#__user_usergroup_map AS m ON (u.id = m.user_id)')
	          ->join('INNER', '#__usergroups AS g ON (m.group_id = g.id)')
	          ->where('g.title = "Employee" AND u.block <> 1');
	    try { 
	    	$db->setQuery($query);
	    	$employees = $db->loadObjectList();
	    	return $employees;
	    }catch (Exception $e) { 
	    	return null;
	    }
  	}
  	
	public function updateAssignee($reqId, $type, $user_id) {
    	$object = new stdClass();
    	$object->id = $reqId;
    	if ($type == "certificate") { 
    		$object->certificater = $user_id;
    	} else { 
    		$object->transcripter = $user_id;
    	}
    	$db = JFactory::getDbo();
    	try {
      		$ret = $db->updateObject('#__vjeecdcm_diploma', $object, 'id');
      		return $ret;
    	} catch (Exception $e) {
      		return NULL;
    	}
  	}
  
  	public function modifyTargetSchool($reqId, $schoolId) {
    	$object = new stdClass();
    	$object->id = $reqId;
    	$object->target_school_id = $schoolId;
    	$db = JFactory::getDbo();
    	
    	try {
      		if ($db->updateObject('#__vjeecdcm_diploma_request', $object, 'id')) {
        		$query = $db->getQuery(true);
        		$query->select(array('request_type'))
              		->from('#__vjeecdcm_diploma_request')
              		->where('id = ' . $reqId);
        		$db->setQuery($query);
        		$object->req_type = $db->loadResult();
      			return $object;
      		}
      		return NULL;
    	} catch (Exception $e) {
      		return NULL;
    	}
  	}
  
  	public function updateRequestsTargetSchool($reqIds, $schlIds) {
    
	    foreach($reqIds as $reqId) {
	      	$this->modifyTargetSchool($reqId, $schlIds[$reqId]);
	    }
  	}
  	
  	public function updateRequestsStep($reqIds, $stepIds) { 
    	foreach($reqIds as $reqId) {
      		$stepId = $stepIds[$reqId];
      		if ($stepId == 1 || $stepId == 5 || $stepId == 6) {
				continue;
      		}
      		if ($stepId == 2) {
          		$this->acceptSubmittedReq($reqId, $stepId);    
      		} else {
          		$this->updateReqProcStep($reqId, $stepId);     
      		}
    	}
  	}
  
  	public function updateRequestsProcToStep($reqIds, $stepIds, $desiredStep) {
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    $query->select(array('id, previous_step_id, next_step_id'))
	          ->from('#__vjeecdcm_processing_step_name');
	    $db->setQuery($query);
	    $nextStepSequence = $db->loadResultArray(2);
	    foreach($reqIds as $reqId) {
	      	$stepId = $stepIds[$reqId];
	      	if ($stepId == $desiredStep) {
	        	continue;
	      	}
	      	
	      	if ($stepId == 1 || $stepId == 5 || $stepId == 6 ) {
				continue;
	      	}
	      	
	      	if ($desiredStep == 5 || $desiredStep == 6 || $desiredStep == 2) {
				continue;
	      	}
	      
	      	$this->updateReqProcToStep($reqId, $stepId, $desiredStep, $nextStepSequence);
	    }
  	}

  	public function modifyRequestType($reqId, $reqType) {
	    $object = new stdClass();
	    $object->id = $reqId;
	    $object->request_type = $reqType;
	    $db = JFactory::getDbo();
	    
	    try {
	      	$db->updateObject('#__vjeecdcm_diploma_request', $object, 'id');
	      	return $object;
	    } catch (Exception $e) {
	      	return NULL;
	    }
  	}
}
