<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JText::script('VJEECDCM_SV_BT_ADD_SCHOOL');

$doc =  JFactory::getDocument();
$doc->addScriptDeclaration('
     var JURI_BASE = "<?php echo JURI::base(); ?>";
');
$doc->addScript('components/com_vjeecdcm/ext/data-tables/media/js/jquery.js');
$doc->addScript('components/com_vjeecdcm/ext/data-tables/media/js/jquery.dataTables.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');
$doc->addScript('components/com_vjeecdcm/ext/data-tables/extras/TableTools/media/js/TableTools.js');
$doc->addScript('components/com_vjeecdcm/js/view-emplschool.js');


$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/demo_page.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/jquery.dataTables.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css');
?>




<div id="req-view-toolbar">
  <button id='schl-view-create-btn'><?php echo JText::_('VJEECDCM_SV_BT_ADD_SCHOOL');?></button>
</div>

<!--
<form name="schoolTableForm"
      action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&task=employee.schoolViewFormHandler'); ?>"
      method="post">
  <input type="hidden" name="buttonClicked" id="buttonClicked" value="">
-->  
  <table id="school-table">
    <?php
    $user = JFactory::getUser();
    echo '<thead>';
    echo '<tr>';
    echo '<th></th><th></th>';
    echo '<th class="center"> <input type="checkbox" name="checkAll' . '" value="0"> </th>';
    echo '<th>' . JText::_( 'VJEECDCM_SV_CT_NAME' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_SV_CT_EMAIL' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_SV_CT_LAST_VISIT_DATE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_SV_CT_NB_REQUESTS' ) . '</th>';
    echo '</tr>';
    echo '</thead>'; 
    echo '<tbody>';
    if ($this->schools)
    {
      foreach ($this->schools as $schl)
      {
	echo '<tr>';
	echo '<td>' . $schl->school_id . '</td>';
	echo '<td>' . $schl->school_user_id . '</td>';
	echo '<td class="center"> <input type="checkbox" name="check[]' . '" value="' . $schl->school_id . '"> </td>';
	echo '<td>' . $schl->name . '</td>';
	echo '<td>' . $schl->email . '</td>';
	echo '<td>' . $schl->lastvisitDate . '</td>';
	echo '<td>' . $schl->nb_requests . '</td>';
	echo '</tr>';
      } // Enf of foreach
      
    }
   
    echo '</tbody>'; 
    ?>
  </table>
<!--
</form>
-->
<div id="school-adding-dlg" >
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
	  </dt>
	  <dd>
            <textarea name="description" id="schoolDesc"></textarea>
	  </dd>
	  <label>
              <?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_FS_IN_JALSA'); ?> 
            </label>
	  </dt>
	  <dd>
            <input type="checkbox" name="inJALSA" id="inJALSA"></textarea>
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
  
      <input type="reset" id="reset-adding-form" value="<?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_BUTTON_RESET'); ?>" style="float:right;">
      <input type="submit" id="submit-adding-school" value="<?php echo JText::_('VJEECDCM_SCHOOL_ADD_FORM_BUTTON_REGISTER'); ?>" style="float:right;">

      <?php echo JHtml::_('form.token'); ?>
    </form>
   
</div>

