<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');
 
class VjeecDcmModelTranscript extends JModelItem { 
	 
	public function getSubjectList() { 
		$db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select(array('s.id', 's.name'))
          ->from('#__vjeecdcm_subject AS s')
          ->order('s.index ASC');
        try {
            $db->setQuery($query);
            $list = $db->loadObjectList();
            return $list;
        } catch (Exception $e) {
            return NULL;
        }
	}
	
	public function getTranscriptData($diplomaId) { 
		$db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select(array('a.holder_name, a.holder_birthday, a.gender, b.*'))
          ->from('#__vjeecdcm_diploma as a ')
          ->join('LEFT', '#__vjeecdcm_transcripts_info AS b ON a.id = b.diploma_id')
          ->where('a.id = '. $diplomaId);
          //echo str_replace('#__', 'vpt_', $query);die();
		try {
            $db->setQuery($query);
            $list = $db->loadObject();
            return $list;
        } catch (Exception $e) {
            return NULL;
        }
	}
	
	public function getStudySumary($studyProcessId) { 
		$db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select(array('s.*'))
          ->from('#__vjeecdcm_study_sumary AS s')
          ->where('s.study_process_id = '. $studyProcessId);
		try {
            $db->setQuery($query);
            $list = $db->loadObject();
            return $list;
        } catch (Exception $e) {
            return NULL;
        }
	}
	
	public function createUpdateTranscriptInfo($obj) {  
		$db = JFactory::getDBO();
  		$ret = array('status' => 'success', 'msg' => '');
  		try {
  			if ($obj->id) { 
  				$result = $db->updateObject('#__vjeecdcm_transcripts_info', $obj, 'id');	
  			} else { 
  				$result = $db->insertObject('#__vjeecdcm_transcripts_info', $obj, 'id');
  			}
  			
  			if ($result) { 
  				$ret['msg'] = 'Cập nhật thành công.';
  			} else { 
  				$ret['status'] = 'error';
  				$ret['msg'] = 'Đã có lỗi xảy ra.';
  			}
  		} catch (Exception $e) { 
  			$ret['status'] = 'error';
  			$ret['msg'] = $e->getMessage();
  		}
  		return $ret;
	}
	
	public function createUpdateSumary($sumaryObj) { 
		$db = JFactory::getDBO();
		$ret = array('status' => 'success', 'msg' => '');
		try {
  			if ($sumaryObj->id) { 
  				$result = $db->updateObject('#__vjeecdcm_study_sumary', $obj, 'id');
  			} else {
  				$result = $db->insertObject('#__vjeecdcm_study_sumary', $obj, 'id');
  			}
  			if ($result) { 
  				$ret['msg'] = 'Cập nhật thành công.';
  			} else { 
  				$ret['status'] = 'error';
  				$ret['msg'] = 'Đã có lỗi xảy ra.';
  			}
  		} catch (Exception $e) { 
  			$ret['status'] = 'error';
  			$ret['msg'] = $e->getMessage();
  		}
  		
  		return $ret;
	}
	
	public function getStudyProcess($diplomaId) { 
		$db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select(array('s.*'))
          ->from('#__vjeecdcm_study_process AS s')
          ->join('INNER', '#__vjeecdcm_diploma as d ON s.diploma_id = d.id')
          ->where('s.diploma_id = ' . $diplomaId)
          ->order('s.id ASC');
          
        try {
            $db->setQuery($query);
            $list = $db->loadObjectList();
            
            foreach ($list as $item) { 
            }
            
            return $list;
        } catch (Exception $e) {
            return NULL;
        }
	}
	
	public function createUpdateSchoolYear($diplomaId, $schoolYearId, $school_year, $class, $band, $advanced_course) {
		$db = JFactory::getDBO();
		$obj = new stdClass();
		$obj->diploma_id = $diplomaId;
		$obj->academic_year = $school_year;
		$obj->class = $class;
		$obj->band = $band;
		$obj->advanced_course = $advanced_course;
		
		if ($schoolYearId) { 
			$obj->id = $schoolYearId;
			$ret = $db->updateObject('#__vjeecdcm_study_process', $obj, 'id');
		} else { 
			$ret = $db->insertObject('#__vjeecdcm_study_process', $obj, 'id');
		}
		return $ret;
	}
	
	
	public function insertUpdateMark($obj) { 
		$db = JFactory::getDBO();
      	$result = $db->updateObject('#__vjeecdcm_mark', $obj, 'id');
	}
	
	public function getMarksDetail($studyProcessId) { 
		$db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select(array('s.id as subject_id, s.name, m.mark1, m.mark2, whole_year, retake_mark'))
        	->from('#__vjeecdcm_subject as s')
        	->join('INNER', '#__vjeecdcm_mark as m ON s.id = m.subject_id')
          	->join('INNER', '#__vjeecdcm_study_process AS p ON m.study_process_id = p.id')
          	->where('m.study_process_id = '. $studyProcessId)
          	->order('s.index ASC');
		try {
            $db->setQuery($query);
            $list = $db->loadObjectList();
            return $list;
        } catch (Exception $e) {
            return NULL;
        }
	}
}
?>