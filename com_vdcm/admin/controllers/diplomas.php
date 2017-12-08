<?php

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.file');

class VjeecdcmControllerDiplomas extends JControllerForm
{ 
	public function __construct($config = array()) { 
		parent::__construct($config);
	}
	
	public function generatePDF() { 
		$certification = new stdClass();
		$certification->type = JRequest::getVar('certificate_type', 1);
		$certifi_date = JRequest::getVar('certificate_date', '');
		if ($certifi_date != '') { 
			$certifi_date = date('F d, Y', strtotime(str_replace('/','-',$certifi_date)));
		}
		$certification->date = $certifi_date;
		
		$certification->reference_no = JRequest::getVar('certificate_reference', '');
		require_once JPATH_COMPONENT.'/helpers/vjeec.php';
		$student_name = JRequest::getVar('student_name', '');
		$student_name = VjeecHelper::remove_vietnamese_accents($student_name);
		$certification->name = strtoupper($student_name);
		$certification->gender = JRequest::getVar('student_gender', '');
		$birthDay = JRequest::getVar('student_birthday', '');
		if ($birthDay != '') { 
			$birthDay = date('F d, Y', strtotime(str_replace('/','-',$birthDay)));
		}
		$certification->birthday = $birthDay;
		$certification->id_no = JRequest::getVar('student_idno', '');
		$certification->level_study = ucfirst(strtolower(JRequest::getVar('level_study', '')));
		$certification->study_mode = JRequest::getVar('study_mode', '');
		$certification->major = JRequest::getVar('student_major', '');
		$certification->ranking = JRequest::getVar('student_ranking', '');
		$certification->reg_no = JRequest::getVar('reg_no', '');
		$certification->issued_by = JRequest::getVar('issued_by', '');
		$issueDate = JRequest::getVar('issued_date', '');
		if ($issueDate != '') { 
			$issueDate = date('F d, Y', strtotime(str_replace('/','-',$issueDate)));
		}
		$certification->dated = $issueDate;
		
		$model = $this->getModel('diplomas');
		$template = $model->generate2Pdf($certification);
		
		$view = $this->getView("certification", "html");
		$school_name = JRequest::getVar('school_name', '');
		$sender_name = JRequest::getVar('sender_name', '');
		$orinal_name = JRequest::getVar('orinal_student_name', '');
		//concat School_name, $sender_name, student_name, Certificate.pdf
		$fileName = $school_name. ', '. $sender_name .', '. $orinal_name. ', Certificate.pdf';
		$view->setLayout("default");
		$view->assignRef('html2pdf', $template);
		$view->assignRef('fileName', $fileName);
		
		$view->display();
		$app = JFactory::getApplication();
		$app->close();
	}
	
	public function download() { 
		$requestId = JRequest::getVar('request_id', 0);
		$model = $this->getModel('diplomas');
		$ret = $model->getDiplomaInfo($requestId);
		$app = JFactory::getApplication();
		if (!$ret) { 
			$app->close();
		}
		 
		$file = JPATH_BASE.'/../'. $ret->path;
		if (!file_exists($file)) { 
			$app->close();
		}
		
		$fileName = $ret->school_name .', '. $ret->sender_name  .', '. $ret->holder_name. '.pdf';
		header('Content-Description: File Transfer');
   		header('Content-Type: application/octet-stream');
		header('Content-Type: application/pdf');
	    header('Content-Disposition: attachment; filename="' . $fileName . '"');
	    header('Content-Transfer-Encoding: binary');
	    header('Cache-Control: must-revalidate');
	    header('Content-Length: ' . filesize($file));
	    readfile($file);
		$app->close();
	}
	
	public function generateTranscipt() { 
		$model = $this->getModel('diplomas');
		$template = $model->getTranscipts();
		$view = $this->getView("certification", "html");
		$view->setLayout("transcipts");
		$view->assignRef('html2pdf', $template);
		//$view->assignRef('fileName', 'Transcript.pdf');
		
		$view->display();
		$app = JFactory::getApplication();
		$app->close();
	}
	
	public function getCertificateInfo() { 
		$diplomaId = JRequest::getVar('diploma_id', 0);
		if (!$diplomaId) { 
			echo json_encode(null);
			jexit();
		}
		
		$model =  $this->getModel('diplomas');
		$diploma = $model->getCertificateInfo($diplomaId);
		
		$diploma->birthday = $diploma->birthday != null ? date('d/m/Y', strtotime($diploma->birthday)) : '';
		$diploma->issue_date = $diploma->issue_date != null ? date('d/m/Y', strtotime($diploma->issue_date)) : '';
		$diploma->expected_send_date = $diploma->expected_send_date != null ? date('d/m/Y', strtotime($diploma->expected_send_date)) : '';
		
		echo json_encode($diploma);
		jexit();
	}
	
	public function getSelectMajors() { 
		$model = $this->getModel('diplomas');
		$list = $model->getMajorList();
		
		$ret = array();
		if (count($list)) {
			foreach ($list as $item) { 
				$ret[] = $item->major;
			}
		}
		echo json_encode($ret);
		jexit();
	}
	
	public function updateDiploma() { 
		$diploma = new stdClass();
		$diploma->id = JRequest::getVar('diploma_id', 0);
		$certificate_date = JRequest::getVar('certificate_date', '');
		$expectedSendDate = null;
		if ($certificate_date != '') { 
			$diploma->certifi_date = date('Y-m-d', strtotime(str_replace('/', '-', $certificate_date)));
			$expectedSendDate = date('Y-m-d', strtotime(str_replace('/', '-', $certificate_date)));
		}
		$diploma->reference = JRequest::getVar('certificate_reference', '');
		$diploma->holder_name = JRequest::getVar('student_name', '');
		$diploma->gender = JRequest::getVar('student_gender', '');
		
		$birthDay = JRequest::getVar('student_birthday', '');
		if ($birthDay != '') { 
			$diploma->holder_birthday = date('Y-m-d', strtotime(str_replace('/', '-', $birthDay)));
		}
		$diploma->holder_identity_value = JRequest::getVar('student_idno', '');
		$diploma->study_mode = JRequest::getVar('study_mode', '');
		$diploma->ranking = JRequest::getVar('student_ranking', '');
		$diploma->reg_no = JRequest::getVar('reg_no', '');
		$diploma->issuer = JRequest::getVar('issued_by', '');
		$issued_date = JRequest::getVar('issued_date', '');
		$diploma->certificate_finished = JRequest::getVar('certifi_finished', 1);
		
		if ($issued_date != '') { 
			$diploma->issue_date = date('Y-m-d', strtotime(str_replace('/', '-', $issued_date)));
		}
		
		$model = $this->getModel('diplomas');
		$major = JRequest::getVar('student_major', '');
		
		if ($major != '') { 
			$major_id = $model->findMajorByName($major);
			
			if ($major_id) { 
				$diploma->major_id = $major_id;
			}
		}
		
		// Khi có cập nhật bằng
		// Tự động cập nhật trạng thái từ Chờ xử lý -> Đang xử lý
		$reqModel = $this->getModel('requests');
		//$reqModel->updateRequestProcessStepByDiploma($diploma->id, 3); remove in this verison
		
		
		//update expected send date
		$reqId = $model->getRequestId($diploma->id);
		$reqModel->updateExpectedSendDate2($reqId, $expectedSendDate);
		
		//update diploma infomation
		$ret = $model->updateDiplomaInfo($diploma);
		echo json_encode($ret);
		jexit();
	}
}
?>
