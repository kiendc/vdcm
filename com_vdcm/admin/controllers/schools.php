<?php

// No direct access.
defined('_JEXEC') or die;

class VjeecdcmControllerSchools extends JControllerAdmin
{ 
	public function __construct($config = array()) { 
		parent::__construct($config);
	}
	
	public function disable() { 
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		$cid = JRequest::getVar('cid', array(), '', 'array');
		if (!is_array($cid) || count($cid) < 1) { 
			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
		}
		$model = $this->getModel('schools');
		foreach ($cid as $id) { 
			$ret = $model->enableDisableSchool($id, 1);
			if ($ret) {
				$success[] = $id;	
			} else { 
				$fail[] = $id;
			}
		}
		
		if (count($fail)) { 
			JFactory::getApplication()->enqueueMessage('Đã có lỗi xảy ra trường với id: '. implode(',', $fail), 'error');
		}
			
		if (count($success)) { 
			JFactory::getApplication()->enqueueMessage('Hủy hoạt thành công trường với id: '. implode(',', $success), 'message');
		}
		$this->setRedirect('index.php?option=com_vjeecdcm&view=schools');
	}
	
	public function enable() { 
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$cid = JRequest::getVar('cid', array(), '', 'array');
		if (!is_array($cid) || count($cid) < 1) { 
			JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
		}
		
		$model = $this->getModel('schools');
		foreach ($cid as $id) { 
			$ret = $model->enableDisableSchool($id, 0);
			if ($ret) {
				$success[] = $id;	
			} else { 
				$fail[] = $id;
			}
		}
		
		if (count($fail)) { 
			JFactory::getApplication()->enqueueMessage('Đã có lỗi xảy ra trường với id: '. implode(',', $fail), 'error');
		}
			
		if (count($success)) { 
			JFactory::getApplication()->enqueueMessage('Kích hoạt thành công trường với id: '. implode(',', $success), 'message');
		}
		$this->setRedirect('index.php?option=com_vjeecdcm&view=schools');
	}

	//public function add() 
	//{
	//
	//	JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	//	JFactory::getApplication()->enqueueMessage('Them mot truong thành công trường với id: '. implode(',', $success), 'message');
	//	$this->setRedirect('index.php?option=com_vjeecdcm&view=school');
	//}
}

?>
