<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
$doc =  JFactory::getDocument();
//$doc->addStyleSheet('components/com_vjeecdcm/css/datagrid.css');
$doc->addScript('//code.jquery.com/jquery-1.10.2.min.js');
$doc->addScript('//cdn.datatables.net/1.10.0/js/jquery.dataTables.js');
#$doc->addScript('components/com_vjeecdcm/ext/data-tables/extras/TableTools/media/js/TableTools.js');
$doc->addScript('//cdnjs.cloudflare.com/ajax/libs/datatables-tabletools/2.1.5/js/TableTools.js');
//$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');
$doc->addScript('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js');
$doc->addScript('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.js');
$doc->addScript('//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jqueryui-editable/js/jqueryui-editable.min.js');
$doc->addScript('components/com_vjeecdcm/js/view-userrequests.js');

$doc->addStyleSheet('components/com_vjeecdcm/ext/select2-3.5.0/select2.css');
$doc->addStyleSheet("//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jqueryui-editable/css/jqueryui-editable.css");
$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/jquery.dataTables.css');
//$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css'); 
$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
?>

<script type="text/javascript">
 	// set site_url variable which to be used in create_request.js file 
	var site_url = "<?php echo JURI::root(); ?>";
</script>

<div id="req-view-toolbar">
  <button id='req-view-create-btn'><?php echo JText::_('VJEECDCM_RV_BT_CREATE_REQUESTS');?></button>
</div>


<table id="request-table">
<?php
 echo '<thead>';
    echo '<tr>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_CODE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_CREATED_DATE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_SCHOOL' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_PROCESS_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_LAST_UPDATE_DATE' ) . '</th>';
    echo '<th>' . JText::_( 'Ngày dự kiến gửi' ) . '</th>';
   
    echo '</tr>';
    echo '</thead>'; 
echo '<tbody>';
if ($this->requests)
{
  foreach ($this->requests as $rq)
  {
    echo '<tr>';
    echo '<td>' . $rq->code . '</td>';
    echo '<td>' . $rq->created_date . '</td>';
    echo '<td>' . $rq->holder_name . '</td>';
    echo '<td>' . JText::_($rq->degree_name) . '</td>';
    echo '<td>' . $rq->route . '</td>';
    echo '<td>' . JText::_($rq->name) . '</td>';
    echo '<td>' .  $rq->begin_date . '</td>';
    echo '<td>' .  $rq->expected_send_date . '</td>';
    echo '</tr>';
  }
} 
echo '</tbody>';
 
?>
</table>



<div id="req-adding-dlg" class="detail-dlg">
  
  <div id="req-content" class="info-input">
    <form name="req-adding-form" id="req-adding-form"
          method="post" >
    <div class="detail-sec" id="req-detail-sec-1">
    <h3>Thong tin </h3>
      <div>
	<p>
          <label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_SCHOOL' ); ?></label>
          <span class='req-editable' id='req-detail-target-school'></span></p>
	<p><label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_TYPE' ); ?></label>
          <span class="req-editable" id="req-detail-type"></span>
        </p>
      </div>
    </div>
    
    <div class="detail-sec" id="req-detail-sec-2">
    <h3>Ho so </h3>
      <div>
        <input type="hidden" id="req-detail-dplm-id" value="0"/>
	<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ); ?></label>
          <span class='dplm-editable' id='req-detail-diploma'></span></p>
	<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_TITLE' ); ?></label>
          <span class="dplm-editable editable-txt" id='req-detail-holder'></span></p>
	<p>
          <label><?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_ID_NUMBER' ); ?></label>
          <span class="dplm-editable editable-txt" id="req-detail-holder-info-1"></span>
	  <label><?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_BIRTHDAY' ); ?></label>
          <span class='dplm-editable' id="req-detail-holder-info-4"></span>
        </p>
	<p><label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT_TELEPHONE' ); ?></label>
          <span class='dplm-editable editable-txt' id="req-detail-holder-info-2"></span>
        </p>
  	<p>
          <label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT_ADDRESS' ); ?></label>
          <span class='dplm-editable' id="req-detail-holder-info-3"></span>
        </p>	
        </div>
      </div>
      <input type="hidden" id="upload-path" value=""/>
      <div>
        <button type="reset" class="button" style="float:right;">
          <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_BUTTON_RESET'); ?>
        </button>
        <button type="submit" class="button" name="action" id='create-req-btn' value="confirm" style="float:right;">
          <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_BUTTON_CREATE'); ?>
        </button>  
      </div>
      <?php echo JHtml::_('form.token'); ?>
    </form>
  </div>
  
  <div id="req-elec-doc" class="preview">
    <form id="upload-form" 
          method="post"
          enctype="multipart/form-data" target="upload_target" >
    File: <input name="upload-file" type="file" />
          <input type="submit" name="submitBtn" value="<?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_ATTACHMENTS');?>" />
    </form>
 
    <iframe id="upload_target" name="upload_target"
          src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
    <iframe id='elec-doc' style="width: 98%;height:640px;" src="javascript:false;"></iframe>>  
  </div>
  
</div>

<div id="progress-dlg" title="File upload">
  <div class="progress-label">Starting upload...</div>
  <div id="progressbar"></div>
</div>

