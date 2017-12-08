<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');


class VjeecDcmViewDiplomas extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $canDo;
	
	
	function display($tpl = null) 
  	{ 
  	 	$this->state            = $this->get('State');
     	$this->items            = $this->get('Items');
        $this->pagination       = $this->get('Pagination');
        
  		$attModel = &JModel::getInstance('attachment', 'VjeecDcmModel');
		$reqModel = &JModel::getInstance('requests', 'VjeecDcmModel');
		if (count($this->items)) { 
			foreach ($this->items as $item) { 
				$attach_file = $attModel->getFilePath($item->attachment_id);
				if ($attach_file != null) { 
					$item->attachfile_url = $attach_file;
				}
          		$item->relatedReqs = $reqModel->getDiplomaRelatedRequests($item->diploma_id);
			}
		}
       
        //Check for errors.
        if (count($errors = $this->get('Errors'))) {
           JError::raiseError(500, implode("\n", $errors));
           return false;
        }
        
        //add helpers
        require_once JPATH_COMPONENT.'/helpers/vjeec.php';
        VjeecHelper::addSubmenu('diplomas');
        
    	parent::display($tpl);
	}
}
