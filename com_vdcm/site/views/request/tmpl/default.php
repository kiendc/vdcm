<?php
defined('_JEXEC') or die('Restricted access');
//JHtml::_('behavior.modal');
$doc =  JFactory::getDocument();
$doc->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery.jeditable.mini.js');
$doc->addScript('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js');
$doc->addScript('components/com_vjeecdcm/js/emplrequest-edit.js');

$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css');
?>

<div class="vjeecdcm-frame"> 
  <div class="vjeecdcm-frame-header">
  <?php echo JText::_('VJEECDCM_REQUEST_DETAIL_FRAME_TITLE'); ?>
  </div>
  <div class="vjeecdcm-frame-toolbar">
    
  </div>
  <div class="vjeecdcm-frame-content">
    <form name="certGenForm" id="cert-gen-form" 
      action="index.php?option=com_vjeecdcm&task=employee.editRequest">
    <div id="request-content-editable">
         
    <span id="certRef" class="editable"><input name="certRef" value=""></span>
    </div>   
    <div id="exhibit-view">
      <iframe id='exhibit-pdf' style="width:100%;height:500px;" src="media/media/certificates/mau-2.pdf"></iframe>>  
    </div>
 
  
  </form>
  </div>
</div>