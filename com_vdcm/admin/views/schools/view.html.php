<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');


class VjeecDcmViewSchools extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	function display($tpl = null) 
  	{ 	
     	$this->items            = $this->get('Items');
        $this->pagination       = $this->get('Pagination');
        $this->state = $this->get('State');
        //Check for errors.
        if (count($errors = $this->get('Errors'))) {
           JError::raiseError(500, implode("\n", $errors));
           return false;
        }
        //add helpers
        require_once JPATH_COMPONENT.'/helpers/vjeec.php';
        VjeecHelper::addSubmenu('schools');
        JToolBarHelper::title(JText::_('Danh sách các trường'), 'school-group');
        JToolbarHelper::editList('school.edit');
        JToolBarHelper::addNew('school.add');
        JToolBarHelper::custom('schools.enable', 'publish', 'VJEEC_REQUEST_STATUS_ACTIVATE', 'VJEEC_REQUEST_STATUS_ACTIVATE');
        JToolBarHelper::custom('schools.disable', 'unpublish', 'VJEEC_REQUEST_STATUS_DEACTIVATE', 'VJEEC_REQUEST_STATUS_DEACTIVATE');
    	parent::display($tpl);
	}
}