<?php
defined('_JEXEC') or die('Restricted access');

class VjeecDcmViewCustomer extends JViewLegacy
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
		$isNew = $this->item->id ? false : true;
		JToolbarHelper::title(JText::_($isNew ? 'Tạo mới khách hàng' : 'Cập nhật khách hàng'), 'school ' . 'school-group');
		JToolBarHelper::apply('customer.apply');
		JToolBarHelper::save('customer.save');
		JToolBarHelper::cancel('customer.cancel');
	}
}
?>