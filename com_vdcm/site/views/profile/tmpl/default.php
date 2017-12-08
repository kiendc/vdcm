<?php
defined('_JEXEC') or die('Restricted access');
//JHtml::_('behavior.modal');
$doc =  JFactory::getDocument();
$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/ui-lightness/jquery-ui-1.10.3.custom.css'); 
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-1.9.1.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');
?>

<div class="vjeecdcm-frame">
  <div class="vjeecdcm-frame-header">
    <?php
    echo JText::_(' VJEECDCM_USER_PROFILE_FRAME_TITLE'); 
    ?> 
  </div> 
  <?php
  //dump($this->dplmDetail, 'default layout of Diploma view (html format), dplmDetail');
  $this->getData();
  if ($this->user != NULL)
  {  
    echo  $this->user->name;
  }
  else
  {
    echo 'Error';
  }
  ?> 
</div>

