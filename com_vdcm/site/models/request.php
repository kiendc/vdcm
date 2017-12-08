<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');
 
class VjeecDcmModelRequest extends JModelItem
{
   
  public function createCode($rq)
  {
    $date = date_create($rq->created_date);
    return $rq->degree_code . $date->format('Ymd') . $rq->request_id;
  }
 
  public function createPrivateCode($rq)
  {
    $date = date_create($rq->created_date);
    return $rq->request_id . '-' . $rq->degree_code . '-' . $date->format('Ymd') ;
  }
  
  public function createRoute($rq)
  {
    if ($rq->request_type == 0)
    {
      return JFactory::getUser(604)->get('username') . ' / ' . $rq->target_school_name; // JaLSA
    }
    else if ($rq->request_type == 1)
    {
      return $rq->target_school_name;
    }
  }
  
  public function createSimpleRoute($rq)
  {
     if ($rq->request_type == 0)
      {
        return  JFactory::getUser(604)->get('username'); // JaLSA
      }
      else if ($rq->request_type == 1)
      {
        return $rq->target_school_name;
      } 
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function addRequest($data)
  {
    //dump($data, 'Request Model, add request');
    $db = JFactory::getDBO();
    $req = new stdClass();
    $req->id = NULL;
    $req->user_id = $data['user_id'];
    $req->diploma_id = $data['diploma_id'];
    $req->request_type = $data['request_type'];
    $req->created_date = JFactory::getDate()->toSql();
    $req->target_school_id = $data['target_school_id'];
    $req->directory_id = 2; // Chuyen vao thu muc gui den vjeec
  
    $result = $db->insertObject('#__vjeecdcm_diploma_request', $req, 'id');
    if ($result)
    {
      // Add observer here
      $reqId = $db->insertid();
      $procId = $this->takeActionOnRequest($reqId, 2); // submitted to VJEECC
      return $reqId;
      
    }
    return -1;
  }
  
  public function addRequestFromObj(&$req)
  {
    //dump($data, 'Request Model, add request');
    $db = JFactory::getDBO();
    $req->id = NULL;
    $req->user_id = JFactory::getUser()->id;
    $req->created_date = JFactory::getDate()->toSql();
    $req->directory_id = 2; // Chuyen vao thu muc gui den vjeec
  
    $result = $db->insertObject('#__vjeecdcm_diploma_request', $req, 'id');
    if ($result)
    {
      // Add observer here
      $reqId = $db->insertid();
      $procId = $this->takeActionOnRequest($reqId, 2); // submitted to VJEECC
      return $reqId;
      
    }
    return -1;
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function getCreatedRequests()
  {
   
    $userID = JFactory::getUser()->id;
    //dump($userID, 'ModelUserRequest::getRequests, userID');
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
          ->where('a.user_id = '. $userID)
          ->order('a.id DESC');
    try 
    {
      //dump($query->__toString(), 'ModelUserRequest::getRequests, query');
      $db->setQuery($query);
      $reqs = $db->loadObjectList();
      foreach ($reqs as $rq)
      {
        $rq->target_school_name =  JFactory::getUser($rq->school_user_id)->get('name');
        $rq->code = $this->createCode($rq);
        
        if ($rq->request_type == 0)
        {
          $rq->route =  JFactory::getUser(604)->get('username') . ' / ' . $rq->target_school_name; // JaLSA
        }
        else if ($rq->request_type == 1)
        {
          $rq->route = $rq->target_school_name;
        }
      }
      return $reqs;
    } 
    catch (Exception $e) 
    {
      JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
      return NULL;
    }
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function getNumberObservingRequests($observerUID)
  {
    if ($observerUID == NULL)
      $userID = JFactory::getUser()->id;
    else
      $userID = $observerUID;
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
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function getObservingRequests($observerUID=NULL, $stepId=0, $offset=0, $limit=0, $searchValue='')
  {
   
    if ($observerUID == NULL)
      $userID = JFactory::getUser()->id;
    else
      $userID = $observerUID;  
    $whereCond = 'e.observer_id = '. $userID . ' AND a.directory_id <> -1';
    if ($searchValue != '')
    {
      $whereCond = $whereCond . " AND ( d.name LIKE '%" . $searchValue . "%' " .
                                        "OR b.holder_name LIKE '%" . $searchValue . "%' " .
                                        //"OR CAST(a.id as VARCHAR(10)) LIKE '%" . $searchValue . "%' " .
                                     ")";
    }
    
    if ($stepId != 0)
    {
      $whereCond .= ( ' AND (f.name_id = ' . $stepId . ')' );
    }
    //dump($whereCond, 'Search condition');
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
                         'c.name',
                         'c.degree_code',
			 'd.school_user_id',
                         'f.begin_date',
                         'f.name_id AS proc_step_id',
                         ))
          ->from('#__vjeecdcm_diploma_request AS a')
          ->join('INNER', '#__vjeecdcm_diploma AS b ON (b.id = a.diploma_id)')
          ->join('INNER', '#__vjeecdcm_diploma_degree AS c ON (b.degree_id = c.id)')
	  ->join('INNER', '#__vjeecdcm_school AS d ON (a.target_school_id = d.id)')
          ->join('INNER', '#__vjeecdcm_request_observer AS e ON (a.id = e.request_id)')
          ->join('INNER', '#__vjeecdcm_process AS f ON (a.current_processing_step = f.id)')
          ->where($whereCond)
          ->order('a.id DESC');
    try 
    {
      //dump($query->__toString(), 'ModelCoopRequest::getRequests, query');
      $db->setQuery($query, $offset, $limit);
      $requests = $db->loadObjectList();
      //dump($requests, 'ModelCoopRequest::getRequests, result');
      // Load detail of each request
      return $requests;
    } 
    catch (Exception $e) 
    {
      return NULL;
    }
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function getRequests()
  {
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
			 'd.school_user_id',
                         'e.begin_date',
                         'e.name_id AS proc_step_id',
                         'f.name'
                         ))
          ->from('#__vjeecdcm_diploma_request AS a')
          ->join('INNER', '#__vjeecdcm_diploma AS b ON (a.diploma_id = b.id)')
          ->join('INNER', '#__vjeecdcm_diploma_degree AS c ON (b.degree_id = c.id)')
	  ->join('INNER', '#__vjeecdcm_school AS d ON (a.target_school_id = d.id)')
          ->join('INNER', '#__vjeecdcm_process AS e ON (a.current_processing_step = e.id)')
          ->join('INNER', '#__vjeecdcm_processing_step_name AS f ON (e.name_id = f.id)')
          ->where('directory_id IS NULL OR directory_id >= 0')
          ->order('a.id DESC');
    try 
    {
      //dump($query->__toString(), 'ModelCoopRequest::getRequests, query');
      $db->setQuery($query);
      $requests = $db->loadObjectList();
      //dump($result, 'ModelCoopRequest::getRequests, result');
      // Load detail of each request
      foreach ($requests as $rq)
      {
        $rq->requester_name = JFactory::getUser($rq->user_id)->get('name');
	$rq->target_school_name =  JFactory::getUser($rq->school_user_id)->get('name');
        //$date = date_create($rq->created_date);
        //$rq->code = $rq->degree_code . $date->format('Ymd') . $rq->request_id;
        //$rq->code = $this->createPrivateCode($rq);
        $rq->code = $this->createCode($rq);
        if ($rq->request_type == 0)
        {
          $rq->route =  JFactory::getUser(604)->get('username') . ' / ' . $rq->target_school_name; // JaLSA
        }
        else if ($rq->request_type == 1)
        {
          $rq->route = $rq->target_school_name;
        }
      }
      return $requests;
    } 
    catch (Exception $e) 
    {
      //JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
      return NULL;
    }
          
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function getRequestsInDirectory($dirId, $stepId=0, $offset=0, $limit=0, $searchValue='')
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    //dump($dirId, 'get requests in directory, dir id');
    $whereCond = ' ( a.directory_id';
    if (is_null($dirId) || $dirId == 0)
    {
      $whereCond .= ' IS NULL OR a.directory_id >= 0 ) ';
    }
    else
    {
      $whereCond .= (' = ' . $dirId . ' ) ');
    }
    
    if ($searchValue != '')
    {
      $whereCond .= " AND ( b.holder_name LIKE '%" . $searchValue . "%' " .
                        //"OR c.degree_code LIKE '%" . $searchValue . "%' " .
                        "OR d.name LIKE '%" . $searchValue . "%' " .
                         //"OR CAST(a.id as VARCHAR(10)) LIKE '%" . $searchValue . "%' " .
                         ")";
    }
    
    if ($stepId != 0)
    {
      $whereCond .=  ('AND (e.name_id  = ' . $stepId . ')');
    }
    
    //dump ($whereCond, 'request model get requests');
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
                         'f.name'
                         ))
          ->from('#__vjeecdcm_diploma_request AS a')
          ->join('INNER', '#__vjeecdcm_diploma AS b ON (a.diploma_id = b.id)')
          ->join('INNER', '#__vjeecdcm_diploma_degree AS c ON (b.degree_id = c.id)')
	  ->join('INNER', '#__vjeecdcm_school AS d ON (a.target_school_id = d.id)')
          ->join('INNER', '#__vjeecdcm_process AS e ON (a.current_processing_step = e.id)')
          ->join('INNER', '#__vjeecdcm_processing_step_name AS f ON (e.name_id = f.id)')
          ->where($whereCond)
          ->order('a.id DESC');
    try 
    {
      //dump($query->__toString(), 'ModelCoopRequest::getRequests, query');
      $db->setQuery($query, $offset, $limit);
      $requests = $db->loadObjectList();
      $nbReqs = count($requests);
      //dump($nbReqs, 'Number of found requests');
      //dump($result, 'ModelCoopRequest::getRequests, result');
      // Load detail of each request
      return $requests;
    } 
    catch (Exception $e) 
    {
      //JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
      return NULL;
    }
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  
  public function getNoTargetRequests()
  {
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
    try 
    {
      //dump($query->__toString(), 'ModelCoopRequest::getRequests, query');
      $db->setQuery($query);
      $requests = $db->loadObjectList();
      //dump($result, 'ModelCoopRequest::getRequests, result');
      // Load detail of each request
      foreach ($requests as $rq)
      {
        $rq->requester_name = JFactory::getUser($rq->user_id)->get('name');
        $rq->code = $this->createCode($rq);
      }
      return $requests;
    } 
    catch (Exception $e) 
    {
      //JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
      return NULL;
    }
          
  }
  
  
  
  
  ////////////////////////////////////////////////////////////////////////////
  public function getRequestDetail($reqId)
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    $query->select('*')
          ->from('#__vjeecdcm_diploma_request')
          ->where('id = ' . $reqId);
    try
    {
      $db->setQuery($query);
      $req = $db->loadObject();
      //dump($req, 'Request detail');
      $req->requester_name = JFactory::getUser($req->user_id)->get('name');
      //$req->target_school_name =  JFactory::getUser($req->school_user_id)->get('name');
      
      return $req;
    }
    catch (Exception $e)
    {
      return NULL;
    }
    
  }
  ////////////////////////////////////////////////////////////////////////////
  
  public function getProcessingHistory($reqID)
  {
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
    try
    {
      $db->setQuery($query);
      $result = $db->loadObjectList();
      //dump($result, 'ModelCoopRequest::getRequests, result'
      foreach ($result as $step)
      {
        $step->translated_name = JText::_($step->name);
      }
      return $result;
    }
    catch (Exception $e) 
    {
      JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
      return NULL;
    }
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function getDiplomaRelatedRequests($dplmId)
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    $query->select(array('id', 
			 'created_date',
			 'completed_date'
                         ))
          ->from('#__vjeecdcm_diploma_request')
          ->where('diploma_id = '. $dplmId);
    try 
    {
      //dump($query->__toString(), 'ModelCoopRequest::getRequests, query');
      $db->setQuery($query);
      $requests = $db->loadObjectList();
      //dump($requests, 'ModRequest::updateRequests, related requests '); 
      return $requests;
    } 
    catch (Exception $e) 
    {
      JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
      return NULL;
    }
    
  }

  ////////////////////////////////////////////////////////////////////////////
  
  public function addObserverToRequest($reqId, $userId)
  {
    $db = JFactory::getDBO();
    $reqObs = new stdClass();
    $reqObs->request_id = $reqId;
    $reqObs->observer_id = $userId;
    $result = $db->insertObject('#__vjeecdcm_request_observer', $reqObs);
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function addSchoolAsRequestObserver($reqId, $schoolId)
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    if ($schoolId == null)
    {
      //dump ($reqId, 'acceptSubReq, reqId');
      //dump ($schoolId, 'acceptSubReq, stepId');
      $query->select(array('school_user_id', 'represent_user_id'))
            ->from('#__vjeecdcm_school AS a')
            ->join('INNER', '#__vjeecdcm_diploma_request AS b ON (a.id = b.target_school_id)')
            ->where('b.id = ' . $reqId);
    }
    else
    {
      $query->select(array('school_user_id', 'represent_user_id'))
            ->from('#__vjeecdcm_school')
            ->where('id = ' . $schoolId);      
    }
    try 
      {
        //dump($query->__toString(), 'ModelUserRequest::getRequests, query');
        $db->setQuery($query);
        $result = $db->loadObjectList();
        foreach ($result as $school)
        {
          //dump($result, 'ModelUserRequest::getRequests, result');
          $this->addObserverToRequest($reqId, $school->school_user_id);
          if (!is_null($school->represent_user_id))
          {
            $this->addObserverToRequest($reqId, $school->represent_user_id);
          }
        }
        
      } 
      catch (Exception $e) 
      {
        return NULL;
         
      }
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function canSchoolConfirmRequest($rq, $schoolUID)
  {
      if ($rq->request_type > 1 )
         return false;
                    
      if ($schoolUID == 604)
      {     
          if ($rq->processing_status_id == 1 && $rq->request_type == 0)
                return true;         
          return false;
      }
      if (($rq->processing_status_id == 5 && $rq->request_type == 0) || ($rq->processing_status_id == 1 && $rq->request_type == 1))
          return true;
      return false;
       
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function insertProcessingStep($reqId, $procStepId)
  {
    $db = JFactory::getDBO();
    $actionDate = JFactory::getDate()->toSql();
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
    if ($result)
    {
      return $db->insertid();
    }
    return -1;
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function takeActionOnRequest($reqId, $procStepId)
  {
     
    $procId = $this->insertProcessingStep($reqId, $procStepId);
    if ($procId > -1)
    {
      // Add observer here
      //$procId = $db->insertid();
      $db = JFactory::getDBO();
      $req = new stdClass();
      $req->id = $reqId;
      $req->current_processing_step = $procId;
      $result = $db->updateObject('#__vjeecdcm_diploma_request', $req, 'id');
      return $procId;
    }
    return null;
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function updateAllRequestsAsReceived()
  {
    $userID = JFactory::getUser()->id;
    //dump($userID, 'ModRequest::updateRequests, userID');
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    
    $procCond = '';
    $procNameId = null;
    if ($userID == 604)
    {
      $procCond = 'c.name_id = 1 AND a.request_type = 0' ;
      $procNameId = 5;
    }
    else
    {
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
    try 
    {
      //dump($query->__toString(), 'ModelCoopRequest::getRequests, query');
      $db->setQuery($query);
      $requests = $db->loadObjectList();
      //dump($requests, 'ModRequest::updateRequests, requests to update');
      foreach ($requests as $rq)
      { 
        $this->takeActionOnRequest($rq->request_id, $procNameId);
      }
      return $requests;
    } 
    catch (Exception $e) 
    {
      JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
      return NULL;
    }  
  }
  
  ////////////////////////////////////////////////////////////////////////////  
  
  public function schoolConfirmRequests($reqIds)
  {
    $userId = JFactory::getUser()->id;
    //dump($userId, 'request model, school confirms requests');
    if ($userId == '604')
      $procNameId = 5;
    else
      $procNameId = 6;
    foreach ($reqIds as $rqId)
    {
      $this->takeActionOnRequest($rqId, $procNameId);
    }
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function updateReqProcStep($reqId, $currProcStepId)
  {
    //dump ($reqId, 'updateReqProc, reqId');
    //dump ($currProcStepId, 'updateReqProc, stepId');
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    $query->select(array('next_step_id'
                         ))
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
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function updateReqProcToStep($reqId, $currStep, $desiredStep, $nextStepSequence)
  {
    $desiredStep = $desiredStep + 0; // Force desired step to type int
    
    $db = JFactory::getDBO();
    
    if ($currStep == 2)
    {
      $this->addSchoolAsRequestObserver($reqId, null);   
    }
   
    dump($nextStepSequence, 'Step sequence');
    $nextStep = $nextStepSequence[$currStep - 1] + 0; // Change from string to int
    
    while ($nextStep != $desiredStep && $nextStep != 0)
    {
        dump($nextStep, 'Next step');
        $this->insertProcessingStep($reqId, $nextStep);
        $nextStep = $nextStepSequence[$nextStep - 1] + 0;
    }
   
    if ($nextStep != 0)
    {
      //$this->takeActionOnRequest($reqId, $desiredStep);
      $this->takeActionOnRequest($reqId, $nextStep);
    }
  }
  
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function acceptSubmittedReq($reqId, $stepId)
  {
    // Update current process step of request
    $this->updateReqProcStep($reqId, $stepId);     
    // Add observers
    //$this->addObserverToRequest($reqId, 604); // Add JaLSA as an observer
    // Add target school and it's reprsent as observers
    $this->addSchoolAsRequestObserver($reqId, null); // second argument is set null, target school is added as observer
    return;
  }
  
  ////////////////////////////////////////////////////////////////////////////
 
  public function removeRequest($reqId)
  {
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

  ////////////////////////////////////////////////////////////////////////////
  
  public function removeRequests($reqIds)
  {
      foreach($reqIds as $reqId)
      {
        $this->removeRequest($reqId);
      }
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function moveRequestsToDirectory($reqIds, $dirId)
  {
       $db = JFactory::getDBO();
       $query = $db->getQuery(true);
    
       $query = 'UPDATE imz_vjeecdcm_diploma_request SET directory_id = '. $dirId . ' WHERE id IN (';
       foreach ($reqIds as $reqId)
       {
          $query .= ($reqId . ', ');         
       }
       $query = rtrim($query, ', ');
       $query .= ')';
       //dump($query, 'Request model, move to directory, query');
       $db->setQuery($query);  
       $result = $db->query();
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function modifyTargetSchool($reqId, $schoolId)
  {
    $object = new stdClass();
    $object->id = $reqId;
    $object->target_school_id = $schoolId;
    $db = JFactory::getDbo();
    
    
    try
    {
      if ($db->updateObject('#__vjeecdcm_diploma_request', $object, 'id'))
      {
        $query = $db->getQuery(true);
        $query->select(array('request_type'))
              ->from('#__vjeecdcm_diploma_request')
              ->where('id = ' . $reqId);
        $db->setQuery($query);
        $object->req_type = $db->loadResult();
      return $object;
      }
      return NULL;
    }
    catch (Exception $e) 
    {
      return NULL;
    }
    
  }
  
  public function updateRequestsTargetSchool($reqIds, $schlIds)
  {
    
    foreach($reqIds as $reqId)
    {
      $this->modifyTargetSchool($reqId, $schlIds[$reqId]);
    }
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  
  public function updateRequestsStep($reqIds, $stepIds)
  {
    
    foreach($reqIds as $reqId)
    {
      $stepId = $stepIds[$reqId];
      if ($stepId == 1 || $stepId == 5 || $stepId == 6)
      {
	continue;
      }
      if ($stepId == 2)
      {
          $this->acceptSubmittedReq($reqId, $stepId);    
      }
      else
      {
          $this->updateReqProcStep($reqId, $stepId);     
      }
    }
  }
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function updateRequestsProcToStep($reqIds, $stepIds, $desiredStep)
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    $query->select(array('id, previous_step_id, next_step_id'))
          ->from('#__vjeecdcm_processing_step_name');
    $db->setQuery($query);
    //$nextStepSequence = $db->loadResultArray(2);
    $nextStepSequence = $db->loadColumn(2);
    dump($nextStepSequence, 'update request to step, step sequence');
    
    foreach($reqIds as $reqId)
    {
      $stepId = $stepIds[$reqId];
      $dumpStr = $reqId . ' - ' . $stepId . '-' . $desiredStep;
      //dump($dumpStr , 'upate request process to step');
      if ($stepId == $desiredStep)
      {
        continue;
      }
      
      if ($stepId == 1 || $stepId == 5 || $stepId == 6 )
      {
	continue;
      }
      
      if ($desiredStep == 5 || $desiredStep == 6 || $desiredStep == 2)
      {
	continue;
      }
      
      $this->updateReqProcToStep($reqId, $stepId, $desiredStep, $nextStepSequence);     
      
    }
  }

  public function modifyRequestType($reqId, $reqType)
  {
    $object = new stdClass();
    $object->id = $reqId;
    $object->request_type = $reqType;
    $db = JFactory::getDbo();
    
    try
    {
      $db->updateObject('#__vjeecdcm_diploma_request', $object, 'id');
      return $object;
    }
    catch (Exception $e) 
    {
      return NULL;
    }
  }
}
