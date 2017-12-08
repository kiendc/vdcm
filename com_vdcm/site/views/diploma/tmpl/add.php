<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JText::script('VJEECDCM_DIPLOMA_ADD_FORM_CREATE_REQUEST_BT_CONFIRM');
JText::script('VJEECDCM_DIPLOMA_ADD_FORM_CREATE_REQUEST_BT_CANCEL');
$doc = JFactory::getDocument(); 
$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css'); 
$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css');

$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-1.9.1.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-validation/js/jquery.validate.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-validation/js/additional-methods.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-validation/localization/messages_vi.js');
$doc->addScript('components/com_vjeecdcm/js/add-diploma.js');
?>
<!--
<div class="vjeecdcm-frame diploma-view-add" style="width: 600px;">
  <div class="vjeecdcm-frame-header">
    <?php
    echo JTEXT::_('VJEECDCM_DIPLOMA_ADD_FRAME_TITLE'); 
    ?>
  </div>
-->
  <div class="vjeecdcm-frame-content" 
       id="add-diploma-dialog" 
       title="<?php  echo JTEXT::_('VJEECDCM_DIPLOMA_ADD_FRAME_TITLE'); ?>">
    <form name="addingDiplomaForm"
	  class="form-validate diploma-register" 
          action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&task=client.addDiploma'); ?>" 
          method="post" enctype="multipart/form-data">
      <fieldset id="diploma-info-fieldset">
        <legend>
          <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_INFORMATION'); ?>
        </legend>
	<dl>
	  <dt>
            <label>
              <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_INFORMATION_NAME'); ?> 
            </label>
	  </dt>
          <dd>
	    <?php
            $dplmDegrees = $this->get('DiplomaDegrees');
            //dump($dplmDegrees, 'diploma view, add layout');
            if ($dplmDegrees == NULL)
            {
              echo '<input type="text" name="dplmName" id="dplmName">';
            }
            else
            {
              echo '<select name="degree_id">';
              foreach ($dplmDegrees as $deg)
              {
		echo '<option value="' . $deg->id .'">' . JText::_($deg->name) . '</option>';
              }
              echo '</select>';
            }
            ?>
	  </dd>
	  <dt>
	    <label>
              <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_INFORMATION_ISSUE_DATE'); ?> 
            </label>
	  </dt>
	  <dd>
     <!-- thêm placeholder -->
            <input type="text" class="date-input" required name="issue_date" id="dplmDate" placeholder="mm/dd/yyyy">
           
      
	  </dd>
	</dl>
	
      </fieldset>
      <fieldset>
        <legend>
          <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER'); ?>
        </legend>
        <label>
        <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_NAME'); ?> 
        </label>
        <input type="text" minlength="2" name="holder_name" id="dplm_holder_name" required/><br>
        <label>
        <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_BIRTHDAY'); ?> 
        </label>
        <!-- thêm placeholder -->
     <input type="text" class="date-input" name="holder_birthday" id="birthday-datepicker" placeholder="mm/dd/yyyy" required/><br>
    

	<label>
    
    
    
        <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_ID_NUMBER'); ?> 
        </label>
        <input type="text" name="holder_id_number" id="dplm_holder_id_number" required/>
      </fieldset>
      <fieldset>
        <legend>
          <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_ATTACHMENTS'); ?> <!-- Thay đổi legend từ "Bản điện tử" thành "Tải hồ sơ", chỉnh sửa ở file ngôn ngữ-->
        </legend>
        <label>
          <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_FILE_NAME'); ?>
        </label>
        <input type="file" name="attachment_file_name" id="file_name_1" required><br><!-- Xóa dấu nháy kép thừa ở đây -->
      </fieldset>
      <br/>
      <!--
      <label>
        <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_ADD_REQUEST'); ?> 
      </label>
      -->
      <input type="checkbox" name="create_request" id="create_request" style="display:none;">
      <br/>
      <button type="reset" class="button" style="float:right;">
        <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_BUTTON_RESET'); ?>
      </button>
      <button type="submit" class="button" name="action" value="confirm" style="float:right;" id="register_button">
        <?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_BUTTON_REGISTER'); ?>
      </button>
      <?php echo JHtml::_('form.token'); ?>
    </form>   
  </div>

<!--
<div id="create-request-cnfrm-dlg" title="<?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_CREATE_REQUEST_DIALOG_TITLE')?>">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_CREATE_REQUEST_MESSAGE')?></p>
</div>
-->
<!--
</div>
-->