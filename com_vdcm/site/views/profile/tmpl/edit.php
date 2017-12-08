<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument(); 
$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css'); 
$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/ui-lightness/jquery-ui-1.10.3.custom.css'); 
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-1.9.1.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');
//$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/dev/ui/jquery.ui.core.js');
//$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/dev/ui/jquery.ui.widget.js');
//$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/dev/ui/jquery.ui.datepicker.js');
$doc->addScriptDeclaration("
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        //$('.vjeecdcm-frame-content').dialog();
        $('.date-input').datepicker();
        $('button').button();
        $('button.cancel').click(function(event){history.back();});           
    });
");

//$doc->addScript("includes/js/joomla.javascript.js");  
  
//JHTML::_('behavior.calendar');  
?>

<div class="vjeecdcm-frame diploma-view-add" style="width: 600px;">
  <div class="vjeecdcm-frame-header">
    <?php

    //if ($this->dplAddForm == NULL)
    //{
    //  echo 'Form could not be loaded';
    //} else {
    echo JTEXT::_('VJEECDCM_DIPLOMA_ADD_FRAME_TITLE'); 
    ?>
  </div>

  <div class="vjeecdcm-frame-content" id="add-diploma-dialog" title="<?php  echo JTEXT::_('VJEECDCM_REQUEST_ADD_FRAME_TITLE'); ?>">
    <form class="form-validate diploma-register" action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&task=crud.addDiploma'); ?>" method="post">
      <fieldset>
        <legend>
          <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION'); ?>
        </legend>
        <label>
          <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_NAME'); ?> 
        </label>
        <select name="reqType">
          <option value="0"><?php echo JText::_('VJEECDCM_DIPLOMA_REQUEST_TBL_REQUEST_TYPE_0'); ?>  </option>
          <option value="1"><?php echo JText::_('VJEECDCM_DIPLOMA_REQUEST_TBL_REQUEST_TYPE_1'); ?>  </option>
          <option value="2"><?php echo JText::_('VJEECDCM_DIPLOMA_REQUEST_TBL_REQUEST_TYPE_2'); ?>  </option>
        </select>
        <br>
        <label>
        <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_SERIES'); ?> 
        </label>
        <input type="text" name="serial" id="dplmSeries"><br>
        <label>
        <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_INFORMATION_REFERENCE'); ?> 
        </label>
        <input type="text" name="reference" id="dplmReference"><br>
        <label>
        <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_INFORMATION_ISSUE_DATE'); ?> 
        </label>
        <input type="text" class="date-input" name="issue_date" id="dplmDate"><br>
      </fieldset>
      <fieldset>
        <legend>
          <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER'); ?>
        </legend>
        <label>
        <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_NAME'); ?> 
        </label>
        <input type="text" name="holder_name" id="dplm_holder_name"><br>
        <label>
        <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_BIRTHDAY'); ?> 
        </label>
        <input type="text" class="date-input" name="holder_birthday" id="birthday-datepicker"><br>
      </fieldset>
      <br/>
      <button type="submit" class="button cancel" name="action" value="cancel" style="float:right;">
        <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_BUTTON_CANCEL'); ?>
      </button>
      <button type="reset" class="button" style="float:right;">
        <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_BUTTON_RESET'); ?>
      </button>
      <button type="submit" class="button" name="action" value="confirm" style="float:right;">
        <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_BUTTON_REGISTER'); ?>
      </button>
      <?php echo JHtml::_('form.token'); ?>
    </form>
  </div>

</div>
