<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die('Restricted access');

class VjeecDcmViewTranscript extends JViewLegacy
{	
	protected $transcript;
	protected $process;
	protected $marks;
	
	function display($tpl = null) { 
		$id = JRequest::getVar('cid');
		$model = $this->getModel('transcript');
		$this->transcript = $model->getTranscriptData($id);
		$this->process = $model->getStudyProcess($id);
		$marks = array();
		
		if (count($this->process)) { 
			foreach ($this->process as $item) { 
				$marks[] = $model->getMarksDetail($item->id);
			}
		}
		$this->marks = $marks;
		
        //Check for errors.
        if (count($errors = $this->get('Errors'))) {
           JError::raiseError(500, implode("\n", $errors));
           return false;
        }
        //add helpers
        require_once JPATH_COMPONENT.'/helpers/vjeec.php';
        
        //VjeecHelper::addSubmenu('transcript');
        
    	parent::display($tpl);
	}
	
}
