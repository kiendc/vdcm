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
  
  public function getRequests($expectedDate)
  {
      $db = JFactory::getDBO();
      $query = $db->getQuery(true);
      $query->select(array('a.created_date',
                           'a.id AS request_id',
                           'b.holder_name',
                           'c.degree_code',
                           'c.degree_name',
                           'd.name AS school_name',
                           'd.id AS school_id'));
      
      $query->from('#__vjeecdcm_diploma_request AS a');
      $query->join('INNER', '#__vjeecdcm_diploma AS b ON (a.diploma_id = b.id)');
      $query->join('INNER', '#__vjeecdcm_diploma_degree AS c ON (b.degree_id = c.id)');
      $query->join('INNER', '#__vjeecdcm_school AS d ON (a.target_school_id = d.id)');
      
      $query->where('a.expected_send_date IS NOT NULL', 'AND');
      $query->where('a.expected_send_date = "'. $expectedDate .'"', 'AND');
      $query->where('b.forgery == 0');
      
      $query->order('school_name');
      try
      {
          JLog::add('ModelCoverpage::getRequest, query: ' . $query->__toString(), JLog::ERROR);
          $db->setQuery($query);
          $queryRes = $db->loadObjectList();
          return $queryRes;
      }
      catch (Exception $e)
      {
          JLog::add('Error in get requests for cover page', JLog::ERROR);
          return NULL;
      }
          
  }
  
}
  
 
