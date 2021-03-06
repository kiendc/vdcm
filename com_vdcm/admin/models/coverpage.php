<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT.'/helpers/vjeec.php';

class VjeecDcmModelCoverpage extends JModelList 
{
   
  public function createCode($rq)
  {
    $date = date_create($rq->created_date);
    return $rq->degree_code . $date->format('Ymd') . $rq->request_id;
  }
 
  public function getExpectedDates($date)
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    $query->select(array('DISTINCT(expected_send_date)'))
          ->from('#__vjeecdcm_diploma_request')
          ->where('expected_send_date IS NOT NULL AND expected_send_date >= "' .$date. '"')
          ->order('expected_send_date');
    try 
    {
      //dump($query->__toString(), 'ModelCoopRequest::getRequests, query');
      JLog::add('ModelCoverpage::getExpectedDates, query: ' . $query->__toString(), JLog::ERROR);
      $db->setQuery($query);
      $dates = $db->loadObjectList();
      //dump($result, 'ModelCoopRequest::getRequests, result');
      // Load detail of each request
      return $dates;
    } 
    catch (Exception $e) 
    {
      //JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
      JLog::add('Error in get requests for cover page', JLog::ERROR);
      return NULL;
    }
  }
  
  public function getRequests($expectedDate, $requestType)
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    $query->select(array('a.created_date',
                         'a.id AS request_id',
			 'a.request_type',
                         'b.holder_name',
                         'c.degree_code',
			 'c.cover_name AS degree_name',
			 'd.id AS school_id',
			 'd.name AS school_name',
			 'd.address AS school_address',
			 'd.zipcode AS school_zipcode',
			 'd.phone AS school_phone',
			 'd.contact_name AS school_contact'
                         ))
          ->from('#__vjeecdcm_diploma_request AS a')
          ->join('INNER', '#__vjeecdcm_diploma AS b ON (a.diploma_id = b.id)')
          ->join('INNER', '#__vjeecdcm_diploma_degree AS c ON (b.degree_id = c.id)')
	  ->join('INNER', '#__vjeecdcm_school AS d ON (a.target_school_id = d.id)')
          ->where('a.expected_send_date IS NOT NULL AND '. 
		  'expected_send_date = "'. $expectedDate .'" AND '. 
		  'b.forgery = 0 AND '. 
		  'a.request_type = '. $requestType)
          ->order('school_name');
    try 
    {
      //dump($query->__toString(), 'ModelCoopRequest::getRequests, query');
      JLog::add('ModelCoverpage::getRequest, query: ' . $query->__toString(), JLog::ERROR);
      $db->setQuery($query);
      $queryRes = $db->loadObjectList();
      //dump($result, 'ModelCoopRequest::getRequests, result');
      // Load detail of each request
      foreach ($queryRes as $rq)
      {
        $rq->code = $this->createCode($rq);
      }
      return $queryRes;
    } 
    catch (Exception $e) 
    {
      //JFactory::getApplication()->enqueueMessage('There is something wrong in collecting the requests of this user' , 'error');
      JLog::add('Error in get requests for cover page', JLog::ERROR);
      return NULL;
    }
          
  }
  
}
  
 
