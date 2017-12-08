<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');
 
class VjeecDcmModelDiploma extends JModelItem
{
  /**
   * @var object item
   */
  protected $diplomaID;
 
  public function setID($dplmID)
  {
    //dump($dplmID, 'Diploma ID passed to model');
    $this->diplomaID = $dplmID;
    //dump($this->diplomaID, 'Diploma ID is stored inside object diploma');
  }
  
  /////////////////////////////////////////////////////////////////////////////
  
  public function getID()
  {
    //dump($this->diplomaID, 'Diploma id before returning in get');
    return $this->diplomaID;
  }
  
  /////////////////////////////////////////////////////////////////////////////

  public function getDiplomaDegrees()
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    $query->select(array('name', 'id', 'degree'))
          ->from('#__vjeecdcm_diploma_degree')
	  ->order('degree ASC');
    //dump($query->__toString(), 'Diploma model, query to get degrees');
    try
    {
      $db->setQuery($query);
      $result = $db->loadObjectList();
      return $result;
    }
    catch (Exception $e) 
    {
      JFactory::getApplication()->enqueueMessage('We could not retrieve diploma degrees', 'error');
      return NULL;
    }
  }
  
  /////////////////////////////////////////////////////////////////////////////
  
  public function getDiploma($dplmID)
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    
    $query->select(array('*'))
	  ->from('#__vjeecdcm_diploma')
	  ->where('id = '. $dplmID);
    try 
    {
     
      $db->setQuery($query);
      $result = $db->loadObjectList();
      return $result[0];
    } 
    catch (Exception $e) 
    {
      return NULL;
    }
  }
 
  /////////////////////////////////////////////////////////////////////////////
  
  public function getDiplomaDetail($dplmID)
  {
    //dump($dplmID, 'ModelDiploma::getDiplomaDetail, dplmID');
    if ($dplmID == NULL)
    {
      JFactory::getApplication()->enqueueMessage('You should set a value for diploma ID', 'error');
      return NULL;
    }

    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    
    $query->select(array('a.id',
			 'b.name',
			 'a.holder_name', 
			 'a.holder_birthday',
			 'a.issue_date', 
			 'a.serial',
			 'a.reference',
			 'a.attachment_id',
			 'a.degree_id'))
      ->from('#__vjeecdcm_diploma AS a')
      ->join('INNER', '#__vjeecdcm_diploma_degree AS b ON (a.degree_id = b.id)')
      ->where('a.id = '. $dplmID);
    try 
    {
     
      $db->setQuery($query);
      $result = $db->loadObject();
      $result->name = JText::_($result->name);
      //dump($result, 'ModelDiploma::getDiplomaDetail, result');
      return $result;
    } 
    catch (Exception $e) 
    {
      JFactory::getApplication()->enqueueMessage('We could not retrieve information of the diploma with id={$this->diplomaID}', 'error');
      return NULL;
    }
  }

  ////////////////////////////////////////////////////////////////////////////
  
  public function getUserRegisteredDiplomas($userId)
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    
    $query->select(array('*'))
      ->from('#__vjeecdcm_user_diploma AS a')
      ->join('INNER', '#__vjeecdcm_diploma AS b ON (a.diploma_id = b.id)')
      ->join('INNER', '#__vjeecdcm_diploma_degree AS c ON (b.degree_id = c.id)')
      ->where('a.user_id = '. $userId);
    try {
      $db->setQuery($query);
      $result = $db->loadObjectList();
      return $result;
    } catch (Exception $e) {
      JFactory::getApplication()->enqueueMessage('There is something wrong in getting diplomas', 'error');
      return NULL;
    }
  }
  
  
  ////////////////////////////////////////////////////////////////////////////
  
  public function getAllDiplomas()
  {
    $db = JFactory::getDBO();
    $query = $db->getQuery(true);
    
    $query->select(array('a.id',
                         'a.diploma_id', 
			 'a.registration_date', 
			 'a.user_id',
			 'b.attachment_id',
			 'b.holder_name',
			 'c.name'))
      ->from('#__vjeecdcm_user_diploma AS a')
      ->join('INNER', '#__vjeecdcm_diploma AS b ON (a.diploma_id = b.id)')
      ->join('INNER', '#__vjeecdcm_diploma_degree AS c ON (b.degree_id = c.id)')
      ->order('a.diploma_id DESC');
      
    try {
      $db->setQuery($query);
      $result = $db->loadObjectList();
      return $result;
    } catch (Exception $e) {
      JFactory::getApplication()->enqueueMessage('There is something wrong in getting diplomas', 'error');
      return NULL;
    }
  }
  
  /////////////////////////////////////////////////////////////////////////////

  public function addDiploma($data)
  {
    $db = JFactory::getDBO();
    $dplm = new stdClass();
    $dplm->id = NULL;
    $dplm->degree_id = $data['degree_id'];
    $dplm->issue_date = $data['issue_date'];
    $dplm->holder_name = $data['holder_name'];
    $dplm->holder_birthday = $data['holder_birthday'];
    $dplm->holder_identity_type = 1; // So CMTND
    $dplm->holder_identity_value = $data['holder_id_number'];
    $dplm->attachment_id = $data['attachment_id'];
    
    //dump($dplm, 'diploma model, addDiploma, dplm');
    
    $result = $db->insertObject('#__vjeecdcm_diploma', $dplm, 'id');
    if ($result)
    {
      return $db->insertid();
    }
    return -1;
  }

  public function addDiplomaFromObj(&$dplm)
  {
    $db = JFactory::getDBO();
    $dplm->id = NULL;
    $dplm->holder_identity_type = 1; // So CMTND
    
    //dump($dplm, 'diploma model, addDiploma, dplm');
    
    $result = $db->insertObject('#__vjeecdcm_diploma', $dplm, 'id');
    if ($result)
    {
      return $db->insertid();
    }
    return -1;
  }
  /////////////////////////////////////////////////////////////////////////////
  
  public function registerDiplomaToUser($dplmID)
  {
    // set the variables from the passed data
    $db = JFactory::getDBO();
    $date =& JFactory::getDate();
    $usrDplm = new stdClass();
    $usrDplm->id = NULL;
    $usrDplm->diploma_id = $dplmID;
    $usrDplm->user_id = JFactory::getUser()->id;
    $usrDplm->registration_date = $date->toSql();
    $usrDplm->certified_by_vjeec = 'VJEECDCM_USER_DIPLOMA_TBL_CERTIFIED_NO';
    //dump($usrDplm, 'user diploma model, registerNewDiploma, usrDplm');
    $result = $db->insertObject('#__vjeecdcm_user_diploma', $usrDplm, 'id');
    //dump($result, 'user diploma model, registerNewDiploma, result');
    return $result;
    
  }
  
  /////////////////////////////////////////////////////////////////////////////
  
  public function removeDiploma($usrDplmId)
  {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select(array('diploma_id'))
          ->from('#__vjeecdcm_user_diploma')
          ->where('id = ' . $usrDplmId);
    $db->setQuery($query);
    $result = $db->loadObjectList();
    if ($result != NULL)
    {
      $dplmId = $result[0]->diploma_id;
      //dump($dplmId, 'crudController, removeDiploma, dplmId');
    }
    $query->clear();
    $query->delete('#__vjeecdcm_diploma')
          ->where(array('id = ' . $dplmId));
    $db->setQuery($query);
    $result = $db->query();
    //dump($result, 'crudController, removeDiploma, result of deleting diploma');
    $query->clear();
    $query->delete('#__vjeecdcm_user_diploma')
          ->where(array('id = ' . $usrDplmId));
    $db->setQuery($query);
    $result = $db->query();
    //dump($result, 'crudController, removeDiploma, result of deleting user-diploma');
  }
  
  /////////////////////////////////////////////////////////////////////////////
  
  public function getRequestHistory($dplmId)
  {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select(array('a.diploma_id',
			 'a.request_id',))
          ->from('#__vjeecdcm_user_diploma')
          ->where('id = ' . $usrDplmId);
    $db->setQuery($query);
    $result = $db->loadObjectList();
    $table = JTable::getInstance('Content', 'JTable', array()); 
  }
  
  
  /////////////////////////////////////////////////////////////////////////////
  public function modifyDegree($dplmId, $degreeId)
  {
    $object = new stdClass();
    $object->id = $dplmId;
    $object->degree_id = $degreeId;
    $db = JFactory::getDbo();
    $dumpStr = $dplmId . '-' . $degreeId;
    //dump($dumpStr, "modify degree, dplmId");
    try
    {
      $db->updateObject('#__vjeecdcm_diploma', $object, 'id');
      return $object;
    }
    catch (Exception $e) 
    {
      return NULL;
    }
  }
  
  
  /////////////////////////////////////////////////////////////////////////////
  
  public function modifyHolderName($dplmId, $holderName)
  {
    $object = new stdClass();
    $object->id = $dplmId;
    $object->holder_name = $holderName;
    $db = JFactory::getDbo();

    $dumpStr = $dplmId . '-' . $holderName;
    //dump($dplmId , "modify holder name, dumpStr");
    try
    {
      $db->updateObject('#__vjeecdcm_diploma', $object, 'id');
      return $object;
    }
    catch (Exception $e) 
    {
      return NULL;
    }
  }
  
  public function modifyHolderInfo($dplmId, $infoId, $infoVal)
  {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    //dump($dplmId, "modify degree, dplmId");
    $query->update('#__vjeecdcm_holder_info')
          ->set(array('value = ' . $db->quote($infoVal)))
	  ->where(array('diploma_id = ' . $dplmId,
			'info_id = ' . $infoId));
    try
    {
      $db->setQuery($query);
      $result = $db->query();
      $dumpStr = $dplmId . '-' . $infoId . '-' . $infoVal . '-' . $result ; 
      //dump($dumpStr, 'modify holder info, dumpStr');
      return $result;
    }
    catch (Exception $e) 
    {
      return NULL;
    }
  }
}
