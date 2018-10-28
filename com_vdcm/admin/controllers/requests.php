<?php

// No direct access.
defined('_JEXEC') or die;

class VjeecdcmControllerRequests extends JControllerAdmin
{ 
	public function __construct($config = array()) { 
		parent::__construct($config);
	}
	
	public function payment() { 
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$model =  $this->getModel('requests');
		$cid = JRequest::getVar('cid', array(), '', 'array');
		if (!is_array($cid) || count($cid) < 1) {
			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
		} else { 
			$fail = array();
			$success =  array();
			foreach ($cid as $id) {
				$currentSteps = $model->getCurrentStepsRequest($id);
				if ($currentSteps->id == 2) {
					$ret = $model->updateProcessStepRequest($id, 7);
					if ($ret) {
						$success[] = $id;	
					} else { 
						$fail[] = $id;
					}
				}
			}
		}
		if (count($fail)) { 
			JFactory::getApplication()->enqueueMessage('Đã có lỗi xảy ra với yêu cầu: '. implode(',', $fail), 'error');
		}
			
		if (count($success)) { 
			JFactory::getApplication()->enqueueMessage('Cập nhật thành công với yêu cầu: '. implode(',', $success), 'message');
		}
		$this->setRedirect('index.php?option=com_vjeecdcm&view=requests');
	}
	
	public function nextStep() { 
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$model =  $this->getModel('requests');
		// Get items to remove from the request.
		$cid = JRequest::getVar('cid', array(), '', 'array');

		if (!is_array($cid) || count($cid) < 1) {
			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
		} else { 
			$fail = array();
			$success =  array();
			foreach ($cid as $id) { 
				// includes current, previous and next step
				$currentSteps = $model->getCurrentStepsRequest($id);
				if ($currentSteps != null) { 
					//update current
					$stepId = $currentSteps->id;
					$nextStepId = $currentSteps->next_step_id;
					
					if ($stepId == 1 || $stepId == 5 || $stepId == 6) {
						continue;
	      			}
	      			
					if ($stepId == 2) { 
	          			$model->addSchoolAsRequestObserver($id, null);
		      		}
		      		
					if ($nextStepId != 0) { 
						$ret = $model->updateProcessStepRequest($id, $nextStepId);
						if ($ret) {
							$success[] = $id;	
						} else { 
							$fail[] = $id;
						}
					}
				}
			}
			if (count($fail)) { 
				JFactory::getApplication()->enqueueMessage('Đã có lỗi xảy ra với yêu cầu: '. implode(',', $fail), 'error');
			}
			
			if (count($success)) { 
				JFactory::getApplication()->enqueueMessage('Cập nhật thành công với yêu cầu: '. implode(',', $success), 'message');
			}
			
			$this->setRedirect('index.php?option=com_vjeecdcm&view=requests');
		}
	}
	
	private function updateRequestsStep($stepId) { 
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$model =  $this->getModel('requests');
		// Get items to remove from the request.
		$cid = JRequest::getVar('cid', array(), '', 'array');

		if (!is_array($cid) || count($cid) < 1) {
			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
		} else { 
			$fail = array();
			$success =  array();
			foreach ($cid as $id) { 
				// includes current, previous and next step
				$currentSteps = $model->getCurrentStepsRequest($id);
				if ($currentSteps != null) { 
					if ($currentSteps->id != $stepId) { 
						// add school
						if ($currentSteps->id == 2) { 
			          		$model->addSchoolAsRequestObserver($id, null);    
				      	}
			      		
						$ret = $model->updateProcessStepRequest($id, $stepId);
						if ($ret) {
							$success[] = $id;
						} else { 
							$fail[] = $id;
						}
					}
				}
				$model->updateSchoolRequestIfMissing($id); //update request not showing in JaLSA view
			}
			if (count($fail)) { 
				JFactory::getApplication()->enqueueMessage('Đã có lỗi xảy ra với yêu cầu: '. implode(',', $fail), 'error');
			}
			
			if (count($success)) { 
				JFactory::getApplication()->enqueueMessage('Cập nhật thành công với yêu cầu: '. implode(',', $success), 'message');
			}
			
			$this->setRedirect('index.php?option=com_vjeecdcm&view=requests');
		}
	}
	
	public function process() { 
		$this->updateRequestsStep(3);
	}
	
	public function sent() { 
		$this->updateRequestsStep(1);
	}
	
	public function archiveRequests() { 
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel('requests');
		// Get items to remove from the request.
		$cid = JRequest::getVar('cid', array(), '', 'array');

		if (!is_array($cid) || count($cid) < 1) {
			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
		} else {
			$ret = $model->moveRequestsToDirectory($cid, 1);
			if ($ret) { 
				JFactory::getApplication()->enqueueMessage('Lưu trữ thành công yêu cầu : ' . implode(',', $cid), 'message');
			} else { 
				JFactory::getApplication()->enqueueMessage('Đã có lỗi xảy ra', 'error');
			}
			$this->setRedirect('index.php?option=com_vjeecdcm&view=requests');
		}
	}
	
	public function deleteRequests() { 
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$model = $this->getModel('requests');
		// Get items to remove from the request.
		$cid = JRequest::getVar('cid', array(), '', 'array');

		if (!is_array($cid) || count($cid) < 1) {
			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
		} else { 
			$ret = $model->moveRequestsToDirectory($cid, -1);
			if ($ret) { 
				JFactory::getApplication()->enqueueMessage('Xóa tạm thời thành công yêu cầu : ' . implode(',', $cid), 'message');
			} else { 
				JFactory::getApplication()->enqueueMessage('Đã có lỗi xảy ra', 'error');
			}
			$this->setRedirect('index.php?option=com_vjeecdcm&view=requests');
		}
	}
	
	private function buildCoverPage($type) { 
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$cid = JRequest::getVar('cid', array(), '', 'array');
		if (!is_array($cid) || count($cid) < 1) {
			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
		}
		
		$model = $this->getModel('requests');
		$list = $model->getRequestsInfo($cid);
		
		if (count($list)) { 
			$ret = $this->validateRequests($list, $type);
			
			if ($ret['status'] != 'success') { 
				JFactory::getApplication()->enqueueMessage($ret['msg'], 'error');
				return $this->setRedirect('index.php?option=com_vjeecdcm&view=requests');
			}
			
			$shipmentDate = null;
			if ($ret['date'] != '') { 
				$shipmentDate = $ret['date'];
			}
			
			//generate cover page
			$template = $model->generateCoverPage($list, $shipmentDate, $type);
			$view = $this->getView("coverpage", "html");
			
			//concat z, cover page, school_name.pdf
			if ($type == 'school') {  
				$fileName = 'z, cover page, '. $list[0]->school_name. ' ' .date('d.m', strtotime($shipmentDate)).'.pdf';
			} else { 
				$fileName = 'Cover page JaLSA '. date('d.m', strtotime($shipmentDate)).'.pdf';	
			}
			
			$view->setLayout("default");
			$view->assignRef('html2pdf', $template);
			$view->assignRef('fileName', $fileName);
			
			$view->display();
			$app = JFactory::getApplication();
			$app->close();		
		} else { 
			JFactory::getApplication()->enqueueMessage('Không tìm thấy yêu cầu.', 'error');
			$this->setRedirect('index.php?option=com_vjeecdcm&view=requests');
		}
		
	}
	
	public function schoolCoverPage() { 
		$this->buildCoverPage('school');
	}
	
	public function jalsaCoverPage() { 
		$this->buildCoverPage('jalsa');
	}
	
	public function newCoverPage() { 
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$cid = JRequest::getVar('cid', array(), '', 'array');
		if (!is_array($cid) || count($cid) < 1) {
			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
		}
		
		$model = $this->getModel('requests');
		$list = $model->getRequestsInfo($cid);
		
		if (count($list)) { 
			$ret = $this->validateRequests($list, 'jalsa');
			
			if ($ret['status'] != 'success') { 
				JFactory::getApplication()->enqueueMessage($ret['msg'], 'error');
				return $this->setRedirect('index.php?option=com_vjeecdcm&view=requests');
			}
			
			$shipmentDate = null;
			if ($ret['date'] != '') { 
				$shipmentDate = $ret['date'];
			}
			
			
			$view = $this->getView("coverpage", "html");
			
			$fileName = 'Cover page JaLSA '. date('d.m', strtotime($shipmentDate)).'.pdf';	
			
			$view->assignRef('fileName', $fileName);
			$view->assignRef('list',$list);
			$view->date = $shipmentDate;
			
			//$this->setRedirect('index.php?option=com_vjeecdcm&view=coverpage&layout=jalsa');
			$view->setLayout("jalsa");
			$view->display();
			//$app = JFactory::getApplication();
			//$app->close();	
				
		} else { 
			JFactory::getApplication()->enqueueMessage('Không tìm thấy yêu cầu.', 'error');
			$this->setRedirect('index.php?option=com_vjeecdcm&view=requests');
		}
		
	}
	
	public function JSONCoverPage() 
	{ 
		$cid = JRequest::getVar('cid', array(), '', 'array');
		/*
		$cid_chunks = array_chunk($cid, 500);
		$model = $this->getModel('requests');
		$list = array();
		for ($i = 0; $i < count($cid_chunks); $i++)
		{
			$list[] = $model->getRequestsInfo($cid_chunks[$i]);
		} 
		echo json_encode($list);
		*/
		echo json_encode($cid);
		jexit();
	}
	/**
  	 * 
  	 * Validate requests with same expected_send_date or target school conditions
  	 * @param array $requests
  	 * @param string $type is school or jalsa
  	 */
  	public function validateRequests(&$requests, $type = 'school') { 
  		$schools = array();
  		$date = array();
  		$ret = array('status' => 'success', 'msg' => '');
  		foreach ($requests as $item) { 
  			//echo $item->expected_send_date, '<br/>';
  			if (!in_array($item->school_name, $schools)) { 
  				$schools[] = $item->school_name;
  			}
  			
  			if (!in_array($item->expected_send_date, $date)) { 
  				$date[] = $item->expected_send_date;
  			}
  			$item->holder_name = strtoupper(VjeecHelper::remove_vietnamese_accents($item->holder_name));
  		}
  		
  		if (count($date) > 1) {
  			$ret['status'] = 'error';
  			$ret['msg'] = 'Danh sách yêu cầu không cùng ngày dự kiến gửi. Hãy lọc yêu cầu theo "Ngày dự kiến gửi".';
  			return $ret;
  		}
  		
  		if ($type == 'school') { 
	  		if (count($schools) > 1) { 
	  			$ret['status'] = 'error';
	  			$ret['msg'] = 'Danh sách yêu cầu không cùng trường gửi đến. Hãy lọc các yêu cầu theo Trường.';
	  			return $ret;
	  		}
  		}
  		
  		$ret['date'] = $date[0];
  		return $ret;
  	}
  	
  	/**
  	 * Update diploma has been finished certificate
  	 */
  	public function finished() { 
  		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
  		$model =  $this->getModel('requests');
  		// Get items to remove from the request.
  		$cid = JRequest::getVar('cid', array(), '', 'array');
  		
  		if (!is_array($cid) || count($cid) < 1) {
  			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
  		} else {
  			$fail = array();
  			$success =  array();
  			foreach ($cid as $id) {
  				// includes current, previous and next step
  				$diploma = $model->getDiplomaByRequest($id);
  				if ($diploma != null) { 
  					$dipModel = $this->getModel('diplomas');
  					$ret = $dipModel->updateDiplomaInfo($diploma);
  					if ($ret['status'] == 'success') {
  						$success[] = $id;
  					} else {
  						$fail[] = $id;
  					}
  				}
  			}
  			if (count($fail)) {
  				JFactory::getApplication()->enqueueMessage('Đã có lỗi xảy ra với yêu cầu: '. implode(',', $fail), 'error');
  			}
  				
  			if (count($success)) {
  				JFactory::getApplication()->enqueueMessage('Cập nhật thành công với yêu cầu: '. implode(',', $success), 'message');
  			}
  		}
  		$this->setRedirect('index.php?option=com_vjeecdcm&view=requests');
  	}
  	
  	/**
  	 * Update diploma has been finished certificate
  	 */
  	public function forgery() { 
  		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
  		$model =  $this->getModel('requests');
  		// Get items to remove from the request.
  		$cid = JRequest::getVar('cid', array(), '', 'array');
  	
  		if (!is_array($cid) || count($cid) < 1) {
  			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
  		} else {
  			$fail = array();
  			$success =  array();
  			foreach ($cid as $id) {
  				// includes current, previous and next step
  				$diploma = $model->getDiplomaObjByRequest($id, 1);
  				if ($diploma != null) { 
  					$dipModel = $this->getModel('diplomas');
  					$ret = $dipModel->updateDiplomaInfo($diploma);
  					if ($ret['status'] == 'success') {
  						$success[] = $id;
  					} else {
  						$fail[] = $id;
  					}
  				}
  			}
  			if (count($fail)) {
  				JFactory::getApplication()->enqueueMessage('Đã có lỗi xảy ra với yêu cầu: '. implode(',', $fail), 'error');
  			}
  	
  			if (count($success)) {
  				JFactory::getApplication()->enqueueMessage('Cập nhật thành công với yêu cầu: '. implode(',', $success), 'message');
  			}
  		}
  		$this->setRedirect('index.php?option=com_vjeecdcm&view=requests');
  	}
  	
  	public function removeForgery() {
  		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
  		$model =  $this->getModel('requests');
  		// Get items to remove from the request.
  		$cid = JRequest::getVar('cid', array(), '', 'array');
  		 
  		if (!is_array($cid) || count($cid) < 1) {
  			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
  		} else {
  			$fail = array();
  			$success =  array();
  			foreach ($cid as $id) {
  				// includes current, previous and next step
  				$diploma = $model->getDiplomaObjByRequest($id, 0);
  				if ($diploma != null) {
  					$dipModel = $this->getModel('diplomas');
  					$ret = $dipModel->updateDiplomaInfo($diploma);
  					if ($ret['status'] == 'success') {
  						$success[] = $id;
  					} else {
  						$fail[] = $id;
  					}
  				}
  			}
  			if (count($fail)) {
  				JFactory::getApplication()->enqueueMessage('Đã có lỗi xảy ra với yêu cầu: '. implode(',', $fail), 'error');
  			}
  			 
  			if (count($success)) {
  				JFactory::getApplication()->enqueueMessage('Cập nhật thành công với yêu cầu: '. implode(',', $success), 'message');
  			}
  		}
  		$this->setRedirect('index.php?option=com_vjeecdcm&view=requests');
  	}
}

?>