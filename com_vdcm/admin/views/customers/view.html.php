<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');


class VjeecDcmViewCustomers extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	function display($tpl = null) 
  	{ 	
     	$this->items            = $this->get('Items');
        $this->pagination       = $this->get('Pagination');
        $this->state       		= $this->get('State');
        
        //Check for errors.
        if (count($errors = $this->get('Errors'))) {
           JError::raiseError(500, implode("\n", $errors));
           return false;
        }
        
        require_once JPATH_COMPONENT.'/helpers/vjeec.php';
        VjeecHelper::addSubmenu('customers');
        $this->addToolBar();
    	parent::display($tpl);
	}
	
	private function addToolBar() { 
		$user	= JFactory::getUser();
		JToolBarHelper::title(JText::_('Danh sách khách hàng'), 'customer-view');
		JToolbarHelper::editList('customer.edit');
		JToolBarHelper::addNew('customer.add');
		//JToolbarHelper::deleteList('', 'customer.delete');
	}
}