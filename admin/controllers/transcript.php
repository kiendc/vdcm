<?php

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class VjeecdcmControllerTranscript extends JControllerForm { 
	
	public function getListSchoolYear() { 
		$diplomaId = JRequest::getVar('diploma_id', 0);
		$model = $this->getModel('transcript');
		$list = $model->getStudyProcess($diplomaId);
		$ret = array();
		if ($list) { 
			foreach ($list as $item) { 
				$ret[] = array('value' => $item->id, 'text' => $item->academic_year);
 			}	
		}
		echo json_encode($ret);
		jexit();
	}
	
	public function initData() { 
		$diplomaId = JRequest::getVar('diploma_id', 0);
		$model = $this->getModel('transcript');
		$transcript = $model->getTranscriptData($diplomaId);
		
		if ($transcript) { 
			$holder_birthday = $transcript->holder_birthday;
			if ($holder_birthday) { 
				$transcript->holder_birthday = date('d/m/Y', strtotime($holder_birthday));
			}
			
			$dated = $transcript->dated;
			if ($dated) { 
				$transcript->dated = $dated != null ? date('d/m/Y', strtotime($dated)) : '';
			}
		}
		
		echo json_encode($transcript);
		jexit();
	}
	
	public function getMarkBySchoolYear() { 
		$schoolYearId = JRequest::getVar('school_year_id', 0);
		$model = $this->getModel('transcript');
	}
	
	public function getStudySumaryBySchoolYear() { 
		$schoolYearId = JRequest::getVar('study_process_id', 0);
		$model = $this->getModel('transcript');
		
		$sumary = $model->getStudySumary($schoolYearId);
		if ($sumary) { 
			$dated = $sumary->dated;
			if ($dated) { 
				$sumary->dated = date('d/m/Y', strtotime($dated));
			}
		}
		
		echo json_encode($sumary);
		jexit();
	}
	
	public function updateStudySumary() { 
		$obj = new stdClass();
		$obj->id = JRequest::getVar('sumary_id', 0);
		$obj->study_process_id = JRequest::getVar('school_year_id', 0);
		$obj->head_teacher = JRequest::getVar('head_teacher', '');
		$obj->school_administrator = JRequest::getVar('school_administrator', '');
		$obj->district = JRequest::getVar('district', '');
		$obj->city = JRequest::getVar('city', '');
		$obj->conduct1 = JRequest::getVar('conduct1', '');
		$obj->conduct2 = JRequest::getVar('conduct2', '');
		$obj->conduct3 = JRequest::getVar('conduct3', '');
		$obj->learning1 = JRequest::getVar('learning1', '');
		$obj->learning2 = JRequest::getVar('learning2', '');
		$obj->learning3 = JRequest::getVar('learning3', '');
		$obj->nb_days_absent = JRequest::getVar('nb_days_absent', null);
		$obj->retaken_conduct = JRequest::getVar('retaken_conduct', '');
		$obj->retaken_learning = JRequest::getVar('retaken_learning', '');
		$obj->straightly_move_up = JRequest::getVar('straightly_move_up', '');
		$obj->qualified_move_up = JRequest::getVar('qualified_move_up', '');
		$obj->unqualified = JRequest::getVar('unqualified', '');
		$obj->holding_certificate = JRequest::getVar('holding_certificate', '');
		$obj->award_contests = JRequest::getVar('award_contests', '');
		$obj->other_special_comment = JRequest::getVar('other_special_comment', '');
		$obj->head_teacher_remark = JRequest::getVar('head_teacher_remark', '');
		$obj->principal_approve = JRequest::getVar('principal_approve', '');
		$dated = JRequest::getVar('sumary_dated', '');
		if ($dated != '') { 
			$obj->dated = date('Y-m-d', strtotime(str_replace('/', '-', $dated)));
		}
		
		$model = $this->getModel('transcript');
		$ret = $model->createUpdateSumary($obj);
		
		echo json_encode($ret);
		jexit();
	}
	
	public function updateTranscriptInfo() { 
		$obj = new stdClass();
		$obj->id = JRequest::getVar('trans_id', 0);
		$obj->diploma_id = JRequest::getVar('diploma_id', 0);
		$obj->place_birth = JRequest::getVar('place_birth', '');
		$obj->ethnic_group = JRequest::getVar('ethnic_group', '');
		$obj->invalid_soldier = JRequest::getVar('invalid_soldier', '');
		$obj->current_residence = JRequest::getVar('current_residence', '');
		$obj->father_name = JRequest::getVar('father_name', '');
		$obj->father_occupation = JRequest::getVar('father_occupation', '');
		$obj->mother_name = JRequest::getVar('mother_name', '');
		$obj->mother_occupation = JRequest::getVar('mother_occupation', '');
		
		$trans_date = JRequest::getVar('trans_date', '');
		if ($trans_date != '') { 
			$obj->dated = date('Y-m-d', strtotime(str_replace('/', '-', $trans_date)));
		}
		
		$diplomaObj =  new stdClass();
		$diplomaObj->id = JRequest::getVar('diploma_id', 0);
		$diplomaObj->holder_name = JRequest::getVar('full_name', '');
		$diplomaObj->gender = JRequest::getVar('gender', '');
		$birthDay = JRequest::getVar('birthday', '');
		if ($birthDay != '') { 
			$diplomaObj->holder_birthday = date('Y-m-d', strtotime(str_replace('/', '-', $birthDay)));
		}
		
		$diplomaModel = $this->getModel('diplomas');
		$resp = $diplomaModel->updateDiplomaInfo($diplomaObj);
		
		$model = $this->getModel('transcript');
		$resp = $model->createUpdateTranscriptInfo($obj);
		
		echo json_encode($resp);
		jexit();
	}
	
	public function addSchoolYear() { 
		$diplomaId = JRequest::getVar('diploma_id', 0);
		$school_year = JRequest::getVar('school_year', '');
		$class = JRequest::getVar('class', '');
		$band = JRequest::getVar('band', '');
		$advanced_course = JRequest::getVar('advanced_course', '');
		$schoolYearId = JRequest::getVar('school_year_id', 0);
		$model = $this->getModel('transcript');
		$ret = $model->createUpdateSchoolYear($diplomaId, $schoolYearId, $school_year, $class, $band, $advanced_course);
		
		echo json_encode($resp);
		jexit();
	}
	
}
?>