<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument(); 

$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css'); 
$doc->addStyleSheet("//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css");

$doc->addScript("//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js");
$doc->addScript("//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js");
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-combobox.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-validation/js/jquery.validate.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-validation/js/additional-methods.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-validation/localization/messages_vi.js');
$doc->addScript('components/com_vjeecdcm/js/add-request.js');

?>
<!--
<div class="vjeecdcm-frame diploma-view-add" style="width: 600px;">
  <div class="vjeecdcm-frame-header">
    <?php
    echo JTEXT::_('VJEECDCM_REQUEST_ADD_FRAME_TITLE'); 
    ?>
  </div>
-->
  <div class="vjeecdcm-frame-content"
       id="add-request-dialog"
       title="<?php  echo JTEXT::_('VJEECDCM_REQUEST_ADD_FRAME_TITLE'); ?>">
    <form name="addingRequestForm" 
	  class="form-validate"
	  id="add-request-form" 
	  action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&task=client.addRequest'); ?>" method="post">
      <fieldset>
        <legend>
          <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION'); ?>
        </legend>
	<input type="hidden" name="diploma_id" value="<?php echo $this->dplmId; ?>">
	<input type="hidden" name="user_id" value="<?php echo $this->user->id; ?>">
	<dl>
	  <dt>
        <label>
          <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_TYPE'); ?> 
        </label>
	</dt>
	  <dt>
        <select name="request_type">
          <option value="0"><?php echo JText::_('VJEECDCM_DIPLOMA_REQUEST_TBL_REQUEST_TYPE_0'); ?>  </option>
          <option value="1"><?php echo JText::_('VJEECDCM_DIPLOMA_REQUEST_TBL_REQUEST_TYPE_1'); ?>  </option>
        </select>
	</dt>
	  <dd>
	<label>
          <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_SCHOOL'); ?> 
        </label>
	</dd>
	  <dt>
        <select name="target_school_id" id="trgSchool">
	  <?php
	    foreach ($this->schools as $school)
              {
		echo '<option value="' . $school->id .'">' . JText::_($school->name) . '</option>';
              }
	     
	  ?>
	</select>
	</dt>
	</dl>
      </fieldset>
      <fieldset>
	<legend>
          <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT'); ?>
        </legend>
	<label>
	  <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT_ADDRESS'); ?> 
	</label>
	<input type="text" name="contact_address" id="contact_address" required>	  
	<label>
	  <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT_TELEPHONE'); ?> 
	</label>
	<input type="text" name="contact_telephone" id="contact_telephone" required>
      </fieldset>
      <!--
      <fieldset>
        <legend>
          <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_FS_ATTACHMENTS'); ?>
        </legend>
        <label>
          <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_FS_FILE_NAME'); ?>
        </label>
        <input type="file" name="attachment_file_name" id="file_name_1">"
      </fieldset>
      -->
      <!--
      <button type="submit" class="button cancel" name="action" value="cancel" style="float:right;">
        <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_BUTTON_CANCEL'); ?>
      </button>
      -->
      <button type="reset" class="button" style="float:right;">
        <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_BUTTON_RESET'); ?>
      </button>
      <button type="submit" class="button" name="action" value="confirm" style="float:right;">
        <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_BUTTON_CREATE'); ?>
      </button>
      <?php echo JHtml::_('form.token'); ?>
    </form>
  </div>
<!--
</div>
-->