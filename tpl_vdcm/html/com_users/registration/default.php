<?php defined('_JEXEC') or die;
/**
 * @version        $Id: default.php 21543 2011-06-15 22:48:00Z chdemko $
 * @package        Joomla.Site
 * @subpackage    com_users
 * @copyright    Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license        GNU General Public License version 2 or later; see LICENSE.txt
 * @since        1.6
 */
$doc =  JFactory::getDocument();
$doc->addScript('components/com_vjeecdcm/ext/data-tables/media/js/jquery.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');
$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css');

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');


?>
<div class="vjeecdcm-frame" id="registration-frame">
<div class="vjeecdcm-frame-header">
<?php echo JText::_('VJEECDCM_LOGIN_FRAME_TITLE'); ?>
</div>
<div class="vjeecdcm-frame-content">
<section class="registration<?php echo $this->pageclass_sfx?>">
<?php if ($this->params->get('show_page_heading')) : ?>
<h1>
    <?php echo htmlspecialchars($this->params->get('page_heading')); ?>
</h1>
    <?php endif; ?>

<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=registration.register'); ?>" method="post" class="form-validate">
    <?php foreach ($this->form->getFieldsets() as $fieldset): // Iterate through the form fieldsets and display each one.?>
    <?php $fields = $this->form->getFieldset($fieldset->name); ?>
    <?php if (count($fields)): ?>
        <fieldset>
            <?php if (isset($fieldset->label)): // If the fieldset has a label set, display it as the legend.?>
            <legend><?php echo JText::_($fieldset->label);?></legend>
            <?php endif;?>
            <dl>
                <?php foreach ($fields as $field): // Iterate through the fields in the set and display them.?>
                <?php if ($field->hidden): // If the field is hidden, just display the input.?>
                    <?php echo $field->input; ?>
                    <?php else: ?>
                    <dt>
                        <?php echo $field->label; ?>
                        <?php if (!$field->required && $field->type != 'Spacer'): ?>
                        <span class="optional"><?php echo JText::_('COM_USERS_OPTIONAL');?></span>
                        <?php endif; ?>
                    </dt>
                    <dd>
                        <?php
                            
                            //dump($field->input, "Registration dt input");
                            if ($field->input != ' ')
                            {
                                echo $field->input;
                            }
                            else
                            {
                                //echo '<div style="width: 50px; height:14px; display: block;"> </div>';
                                echo '&nbsp;&nbsp;&nbsp;';
                            }
                        ?>
                    </dd>
                    <?php endif; ?>
                <?php endforeach;?>
            </dl>
        </fieldset>
        <?php endif; ?>
    <?php endforeach;?>

    <button type="submit" class="validate"><?php echo JText::_('JREGISTER');?></button>
    <?php echo JText::_('COM_USERS_OR');?>
    <a href="<?php echo JRoute::_('');?>" title="<?php echo JText::_('JCANCEL');?>"><?php echo JText::_('JCANCEL');?></a>
    <input type="hidden" name="option" value="com_users" />
    <input type="hidden" name="task" value="registration.register" />
    <?php echo JHtml::_('form.token');?>

</form>
</section>
</div>
</div>
