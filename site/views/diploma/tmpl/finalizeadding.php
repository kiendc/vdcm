<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JText::script('VJEECDCM_DIPLOMA_FINALIZE_ADDING_FORM_BT_CREATE_REQUEST');
JText::script('VJEECDCM_DIPLOMA_FINALIZE_ADDING_FORM_BT_CREATE_DIPLOMA');
JText::script('VJEECDCM_DIPLOMA_FINALIZE_ADDING_FORM_BT_VIEW_DIPLOMAS');
$doc = JFactory::getDocument(); 
$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css'); 
$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css');

$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-1.9.1.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-validation/js/jquery.validate.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-validation/js/additional-methods.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-validation/localization/messages_vi.js');
$doc->addScript('components/com_vjeecdcm/js/finalizeadding-diploma.js');
?>
<?php if ($this->dplmID > 0) {?>
<div id="create-request-cnfrm-dlg" title="<?php echo JText::_('VJEECDCM_DIPLOMA_ADD_SUCCESS_DLG_TITLE')?>">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
  <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_SUCCESS_MESSAGE')?></p>
  <p><?php echo JText::_('VJEECDCM_DIPLOMA_ADD_SUCCESS_SUGESTION')?></p>
    <form name="finalizeAddingDiplomaForm"
	  class="form-validate diploma-register" 
          action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&task=client.finalizeAddingDiploma       '); ?>" 
          method="post" enctype="multipart/form-data">
      <input type="hidden" name="newDplmId" value="<?php if (isset($this->dplmID)) {echo $this->dplmID; }else {echo -1;}?>">
      <input type="hidden" name="choice" value="create-request">
    </form>
</div>
<?php } else { ?>
<div id="add-diploma-warning-dlg" title="<?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FAILURE_DLG_TITLE')?>">
  <p><?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FAILURE_MESSAGE')?></p></p>
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
    <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FAILURE_SUGESTION')?></p>
    <form name="finalizeAddingDiplomaForm"
	  class="form-validate diploma-register" 
          action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&task=userFinalizeAddingDiplomaFormHandler'); ?>" 
          method="post" enctype="multipart/form-data">
      <input type="hidden" name="choice" value="create-diploma">
    </form>
</div>

<?php } ?>
<!--
</div>
-->