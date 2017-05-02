<?php

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class VjeecdcmControllerCommon extends JControllerForm
{ 
	public function __construct($config = array()) { 
		parent::__construct($config);
	}
	
	public function getHolderInfo() { 
		$diplomaId = JRequest::getVar('diploma_id', 0);
		$holderModel = $this->getModel('holder');
		$holder_info = array();
		if ($diplomaId) {
			$infos = $holderModel->getInfo($diplomaId);
			if (count($infos)) { 
				foreach ($infos as $item) {
					$holder_info[] = array(JText::_($item->name), $item->value);
				}
			}
		}
		echo json_encode($holder_info);
		jexit();
	}
	
	public function getBirthDay() {
		$diplomaId = JRequest::getVar('diploma_id', 0);
		$holderModel = $this->getModel('holder');
		$birthDay = null;
		if ($diplomaId) { 
			$infos = $holderModel->getInfo($diplomaId);
			if (count($infos) == 4 && isset($infos[3]->value)) {  
				$date_str = $infos[3]->value;
				$birthDay  = date('F d, Y', strtotime(str_replace('/', '-', $date_str)));
			}
		}
		
		echo json_encode($birthDay);
		jexit();
	}
	
	
}
?>