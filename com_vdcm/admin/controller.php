<?php

// No direct access to this file
defined('_JEXEC') or die;

class VjeecDcmController extends JControllerLegacy
{
    protected $default_view = 'requests';
    
   	public function display($cachable = false, $urlparams = Array())
    { 
    	$view	= JRequest::getCmd('view', 'requests');
		$layout = JRequest::getCmd('layout', 'default');
		$id		= JRequest::getInt('id');
		
        parent::display();
        return $this;
    }
}

?>
