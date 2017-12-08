<?php
defined('_JEXEC') or die;

// Execute the task.
$controller	= JControllerLegacy::getInstance('vjeecdcm');
$controller->execute(JRequest::getVar('task'));
$controller->redirect();
?>
