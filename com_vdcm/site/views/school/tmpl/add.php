<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$doc = JFactory::getDocument(); 
$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css'); 
$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css');


$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-1.9.1.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');

?>

<div class="vjeecdcm-frame form-frame" id="add-school-frame">
  <div class="vjeecdcm-frame-header">
    <?php
    echo JTEXT::_('VJEECDCM_SCHOOL_ADD_FRAME_TITLE'); 
    ?>
  </div>

  <div class="vjeecdcm-frame-content"  
       title="<?php  echo JTEXT::_('VJEECDCM_SCHOOL_ADD_FRAME_TITLE'); ?>">
    <form class="form-validate" 
          action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&task=employee.addSchoolFormHandler'); ?>" 
          method="post" enctype="multipart/form-data">
     <fieldset id="diploma-info-fieldset">
        <legend>
          <?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_INFO'); ?>
        </legend>
	<dl>
	  <dt>
            <label>
              <?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_INFORMATION_NAME'); ?> 
            </label>
	  </dt>
          <dd>
	    <input type="text" name="name" id="school_name">
	  </dd>
          
	  <dt>
            <label>
              <?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_DESCRIPTION'); ?> 
            </label>
	  </dt><br/>
	  <dd>
            <textarea name="description" id="schoolDesc"></textarea>
	  </dd>
	</dl>
      </fieldset>
      <fieldset>
        <legend>
          <?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_USER_ACCOUNT'); ?>
        </legend>
	<dt>
        <label>
          <?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_USERNAME'); ?> 
        </label>
	</dt>
	<dd>
          <input type="text" name="username" id="school_username"/><br>
        </dd>
	<dt>
        <label>
          <?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_PASSWORD'); ?> 
        </label>
	</dt>
	<dd>
          <input type="password" name="password" id="school_user_passwd"/><br>
        </dd>
      </fieldset>
      <fieldset>	
	<legend>
          <?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_CONTACT'); ?>
        </legend>
	<dl>
	<dt>
        <label>
          <?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_CONTACT_NAME'); ?> 
        </label>
	</dt>
	<dd>
          <input type="text" name="contact_name" id="school_contact_name"/><br>
        </dd>
	<dt>
        <label>
          <?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_CONTACT_PHONE'); ?> 
        </label>
	</dt>
	<dd>
          <input type="text" name="contact_phone" id="school_contact_phone"/><br>
        </dd>
	<dt>
        <label>
          <?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_CONTACT_EMAIL'); ?> 
        </label>
	</dt>
	<dd>
          <input type="text" name="email" id="school_contact_phone"/><br>
        </dd>
	</dl>
      </fieldset>
  
      <button type="reset" class="button" style="float:right;">
        <?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_BUTTON_RESET'); ?>
      </button>
      <button type="submit" class="button" name="action" value="confirm" style="float:right;">
        <?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_BUTTON_REGISTER'); ?>
      </button>
      <?php echo JHtml::_('form.token'); ?>
    </form>
   
  </div>

</div>
