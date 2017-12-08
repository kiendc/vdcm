<?php
defined('_JEXEC') or die('Restricted access');

class VjeecDcmViewSchool extends JViewLegacy
{
	protected $item;
	
	function display($tpl = null) 
  	{ 	
  		$this->form = $this->get('Form');
		$this->item      = $this->get('Item');
		
  		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
    	parent::display($tpl);
	}
	
	
	protected function addToolbar() {
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user      = JFactory::getUser();
		$canDo     = JHelperContent::getActions('com_vjeecdcm');
		$isNew = $this->item->id ? false : true;
		JToolbarHelper::title(JText::_($isNew ? 'Tạo mới trường' : 'Cập nhật trường'), 'school ' . 'school-group');
		JToolBarHelper::apply('school.apply');
		JToolBarHelper::save('school.save');
		JToolbarHelper::cancel('school.cancel', 'JTOOLBAR_CLOSE');
	}
}
?>