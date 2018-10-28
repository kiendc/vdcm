<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');
require_once JPATH_COMPONENT.'/helpers/vjeec.php';
class VjeecDcmViewRequests extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	function display($tpl = null) { 	
  		$this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        
        require_once JPATH_COMPONENT.'/models/attachment.php';
        
        $attModel = new VjeecDcmModelAttachment();
        
        if (count($this->items)) { 
        	foreach ($this->items as $item) {
        		$attach_file = $attModel->getFilePath($item->attachment_id);
        		$item->attachfile_url = null;
        		$item->download_link = null;
				if ($attach_file != null) { 
					$file = JPATH_BASE.'/../'. $attach_file;
					if (file_exists($file)) { 
						$item->attachfile_url = $attach_file;
						$item->download_link = JRoute::_('index.php?option=com_vjeecdcm&task=diplomas.download&request_id='.$item->request_id);
					}
				}
        	}
        }
        //Check for errors.
        if (count($errors = $this->get('Errors'))) {
           JError::raiseError(500, implode("\n", $errors));
           return false;
        }
        //add helpers
        require_once JPATH_COMPONENT.'/helpers/vjeec.php';
        
        VjeecHelper::addSubmenu('requests');
        $this->addToolBar();
        
    	parent::display($tpl);
	}
	
	private function addToolBar() { 
		$user	= JFactory::getUser();
		JToolBarHelper::title(JText::_('VJEECDCM_USER_REQUEST_FRAME_TITLE'), 'requests-view');
		
		JToolBarHelper::custom('requests.payment', 'payment', 'payment', 'VJEECDCM_REQUEST_PROCESS_WAITING', true);
		JToolBarHelper::divider();
		JToolBarHelper::custom('requests.nextStep','nextstep','nextstep', 'VJEECDCM_RV_SPSMN_AUTO', true);
		JToolBarHelper::divider();
		JToolBarHelper::custom('requests.process','inprocess','inprocess', 'VJEECDCM_RV_SDMN_PROCESSING', true);
		JToolBarHelper::divider();
		JToolBarHelper::custom('requests.sent','sent', 'sent', 'VJEECDCM_REQUEST_PROCESS_SENT', true);
		JToolBarHelper::divider();
		JToolBarHelper::archiveList('requests.archiveRequests');
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('','requests.deleteRequests');
		JToolBarHelper::custom('requests.schoolCoverPage', 'coverpage', 'coverpage', 'School cover page', true);
		JToolBarHelper::custom('requests.jalsaCoverPage', 'jalsacover', 'jalsacover', 'JaLSA cover page', true);
		JToolBarHelper::custom('requests.finished', 'apply', 'apply', 'Đã hoàn thành', true);
		
		// Chi co supper admin cap nhat dc ho so gia mao
		if (VjeecHelper::isSuperAdmin()) { 
			JToolBarHelper::divider();
			JToolBarHelper::divider();
			JToolBarHelper::divider();
			JToolBarHelper::divider();
			JToolBarHelper::divider();
			JToolBarHelper::divider();
			JToolBarHelper::divider();
			JToolBarHelper::custom('requests.forgery', 'unpublish', 'unpublish', 'Forgery', true);
			JToolBarHelper::custom('requests.removeForgery', 'remove', 'remove', 'Not forgery', true);
		}
	}
}
