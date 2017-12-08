<?php
// No direct access.
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');
JHtml::_('behavior.formvalidation');

$doc =  JFactory::getDocument();
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jquery.js', 'text/javascript');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jquery-ui.js', 'text/javascript');
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/custom.css');
?>

<form name="adminForm" id="adminForm" class="form-validate" method="post" enctype="multipart/form-data">
	<fieldset id="diploma-info-fieldset">
    	<legend><?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_INFO'); ?></legend>
        <div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('name'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('name'); ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('description'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('description'); ?>
			</div>
		</div>
	</fieldset>
      
    <fieldset>
        <legend><?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_USER_ACCOUNT'); ?></legend>
        <div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('username'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('username'); ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('password'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('password'); ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('password2'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('password2'); ?>
			</div>
		</div>
    </fieldset>
    
    <fieldset>
		<legend><?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_CONTACT'); ?></legend>
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('contact_name'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('contact_name'); ?>
			</div>
		</div>	
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('email'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('email'); ?>
			</div>
		</div>	
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('phone'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('phone'); ?>
			</div>
		</div>	
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('fax'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('fax'); ?>
			</div>
		</div>	
   	</fieldset>
	<input type="hidden" name="task" value="school.save" />
    <?php echo JHtml::_('form.token'); ?>
</form>