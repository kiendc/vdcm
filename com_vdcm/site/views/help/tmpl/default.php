<?php
defined('_JEXEC') or die('Restricted access');
//JHtml::_('behavior.framework');
//jimport( 'joomla.user.helper' );
$doc =  JFactory::getDocument();
$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-1.9.1.js');
#$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/ui-lightness/jquery-ui-1.10.3.custom.css'); 
#$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');
?>

<div class="vjeecdcm-frame">
 <div class="vjeecdcm-frame-header">
 <?php echo JText::_('VJEECDCM_HELP_FRAME_TITLE'); ?>
 </div>
  <div class="vjeecdcm-frame-content"> 
  <?php 	
  //JText::_("VJEECDCM_USER_DASHBOARD_CONTENT");
	$user =JFactory::getUser();
	$id = $user->get('id');
	jimport( 'joomla.access.access' );
	$groups = JUserHelper::getUserGroups($id);
	dump($groups, 'groups of user');
	$article =& JTable::getInstance("content");
	if(strcmp($groups[9],'9')==0)
	{
	  $article->load(94); 
	}
	else
	{
	  $article->load(95);
	}
	echo $article->get('introtext');
  ?>
  </div>
</div>

