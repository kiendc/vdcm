<?php 
// No direct access.
defined('_JEXEC') or die;

$loggeduser = JFactory::getUser();
$canDo = VjeecHelper::getActions();
$isAdmin = VjeecHelper::isAdministrator();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$doc =  JFactory::getDocument();


$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jquery.js', 'text/javascript');
//select2
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/select2.min.js', 'text/javascript');
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/select2.css', 'text/css');
// jqueryui
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jquery-ui.js', 'text/javascript');
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/jquery-ui.css', 'text/css');
//x-editable
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/jqueryui-editable.css', 'text/css');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jqueryui-editable.js', 'text/javascript'); 

//$doc->addScript($this->baseurl.'/../components/com_vjeecdcm/ext/jquery.form.js');
	
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/requests.js', 'text/javascript');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/create_request.js', 'text/javascript');

$doc->addStyleSheet($this->baseurl.'/../components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/custom.css');



$component_path = $this->baseurl. '/components/com_vjeecdcm';
?>

<script type="text/javascript">
 	// set site_url variable which to be used in create_request.js file 
	var site_url = "<?php echo JURI::root(); ?>";
</script>

<form action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&view=requests');?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('VJEEC_REQUEST_FIND_DIPLOMA'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('VJEEC_REQUEST_FIND_DIPLOMA'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('VJEEC_REQUEST_SEARCH'); ?>" />
			<input type="submit" value="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>" />
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_RESET'); ?></button>
		</div>
		<div class="filter-select">
			<span class="faux-label"><?php echo JText::_('VJEEC_REQUEST_FILTER_DIPLOMA_BY'); ?></span>
			<select name="filter_state" class="inputbox" onchange="this.form.submit()" style="width:140px;">
				<?php echo JHtml::_('select.options', VjeecHelper::getStateOptions(), 'value', 'text', $this->state->get('filter.state'));?>
			</select>
			
			<select name="filter_requester" class="inputbox" onchange="this.form.submit()" style="width:250px;">
				<option value="">-<?php echo JText::_('VJEECDCM_RV_CT_REQUEST_REQUESTER');?>-</option>
				<?php echo JHtml::_('select.options', VjeecHelper::getRequesterOptions(), 'value', 'text', $this->state->get('filter.requester'));?>
			</select>

			<select name="filter_school" class="inputbox" onchange="this.form.submit()" style="width:250px;">
				<option value="">-<?php echo JText::_('VJEEC_REQUEST_SCHOOL');?>-</option>
				<?php echo JHtml::_('select.options', VjeecHelper::getSchoolOptions(), 'value', 'text', $this->state->get('filter.school'));?>
			</select>

			<select name="filter_status" class="inputbox" onchange="this.form.submit()">
				<option value="">-<?php echo JText::_('VJEEC_REQUEST_STATUS');?>-</option>
				<?php echo JHtml::_('select.options', VjeecHelper::getStatusOptions(), 'value', 'text', $this->state->get('filter.status'));?>
			</select>
			
			<select name="filter_expected_send_date" class="inputbox" onchange="this.form.submit()">
				<option value="">-<?php echo JText::_('VJEEC_REQUEST_EXPECTED_SEND_DATE');?>-</option>
				<?php echo JHtml::_('select.options', VjeecHelper::getExpectedDateOptions(), 'value', 'text', $this->state->get('filter.expectedSendDate'));?>
			</select>
		</div>
	</fieldset>
	<div class="clr"></div>
	
	<button id="req-view-create-btn" style="margin:10px;height:30px;color:#054993;font-weight:bold;"><?php echo JText::_('VJEECDCM_RV_BT_CREATE_REQUESTS');?></button> <br/>
	
	<table class="adminlist">
		<thead>
			<tr>
				<th width="2%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="nowrap" width="3%">
					<?php echo JHtml::_('grid.sort', 'ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="5%">
					<?php echo JHtml::_('grid.sort', 'VJEECDCM_RV_CT_REQUEST_CODE', 'request_id', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'VJEECDCM_RV_CT_REQUEST_REQUESTER', 'requester_name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_SHORT_TITLE', 'b.holder_name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="3%">
					<?php echo JText::_('VJEEC_REQUEST_CERTIFICATION'); ?>
				</th>
				<?php if ($isAdmin) { ?>
					<th class="nowrap" width="7%">
						<?php echo JText::_('Certificater'); ?>
					</th>
					<th class="nowrap" width="8%">
						<?php echo JText::_('Transcripter'); ?>
					</th>
				<?php } ?>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'VJEEC_REQUEST_DIPLOMA', 'a.diploma_id', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="15%">
					<?php echo JHtml::_('grid.sort', 'VJEECDCM_RV_CT_REQUEST_SCHOOL', 'd.name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="5%">
					<?php echo JHtml::_('grid.sort', 'VJEECDCM_RV_CT_REQUEST_PROCESS_TITLE', 'current_status', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="6%">
					<?php echo JHtml::_('grid.sort', 'VJEECDCM_RV_CT_REQUEST_CREATED_DATE', 'a.created_date', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="6%">
					<?php echo JHtml::_('grid.sort', 'VJEECDCM_RV_CT_REQUEST_LAST_UPDATE_DATE', 'e.begin_date', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="7%">
					<?php echo JHtml::_('grid.sort', 'VJEEC_REQUEST_EXPECTED_SEND_DATE', 'a.expected_send_date', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="<?php echo $isAdmin ? 14: 13; ?>">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : 
			$cls = "row".$i % 2;
			if ((int) $item->certificate_finished == 2) { 
				$cls = 'row_success';
			}
			if ((int) $item->forgery == 1) { 
				$cls = 'row_danger';
			}
			
		?>
			<tr class="<?php echo $cls; ?>" >
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td class="center">
					<?php echo $item->id; ?>
				</td>
				<td class="center">
					<span title="<?php echo JText::_('VJEEC_REQUEST_VIEW_DIPLOMA_DETAIL');?>" class="request_detail editable editable-click" request_id="<?php echo $item->request_id;?>" download_link="<?php echo $item->download_link;?>"><?php echo $item->code; ?></span>
				</td>
				<td class="center">
					<?php echo $item->requester_name; ?>
				</td>
				<td class="center">
					<span title="<?php echo JText::_('VJEEC_REQUEST_CLICK_TO_VIEW');?>" class="holder_info editable editable-click"  diploma_id="<?php echo $item->diploma_id;?>">
						<?php echo $item->holder_name; ?>
					</span>
				</td>
				<td class="center">
					<?php if ($isAdmin || $item->certificater == $loggeduser->get('id')) { ?>
						<a href="#" title="<?php echo JText::_('VJEEC_REQUEST_CREATE_CERTIFICATE');?>" class="get_certificate_info" reference="<?php echo $item->code;?>" sender_name="<?php echo $item->sender_name; ?>" school_name="<?php echo $item->school_name; ?>" full_name="<?php echo $item->holder_name;?>" diploma_id="<?php echo $item->diploma_id;?>">
							<img src="<?php echo $component_path .'/helpers/css/images/icon-32-certification.png'; ?>" />
						</a>
					<?php } ?>
				</td>
				<?php if ($isAdmin) { ?>
					<td>
						<a class="assign_work editable editable-click" title="<?php echo JText::_('VJEEC_REQUEST_CHOICE_EMPLOYEE');?>" type="certificate" data-pk="<?php echo $item->diploma_id ?>">
							<?php echo $item->certificater ? JFactory::getUser($item->certificater)->get('name') : ''; ?>
						</a>
					</td>
					<td>
						<a class="assign_work editable editable-click" title="<?php echo JText::_('VJEEC_REQUEST_CHOICE_EMPLOYEE');?>" type="transcript" data-pk="<?php echo $item->diploma_id ?>">
							<?php echo $item->transcripter ? JFactory::getUser($item->transcripter)->get('name') : ''; ?>
						</a>
					</td>
				<?php } ?>
				<td class="center">
					<span class="diploma_info editable editable-click" title="<?php echo JText::_('VJEEC_REQUEST_VIEW_DIPLOMA');?>" download_link="<?php echo $item->download_link;?>"  path="<?php echo $item->attachfile_url != null ? JURI::root(). $item->attachfile_url : ''; ?>">
						<?php echo JText::_($item->degree_name); ?>
					</span> &nbsp;&nbsp;
					<?php if ($item->download_link != null) { ?>
						<a class="download_diploma" href="<?php echo $item->download_link; ?>" title="<?php echo JText::_('VJEEC_REQUEST_DOWNLOAD_DIPLOMA');?>"><img src="<?php echo $component_path .'/helpers/css/images/icon-16-download.png'; ?>" /></a>
					<?php } ?>
				</td>
				<td class="center">
					<a class="editable-route editable editable-click" data-type="select2" data-pk="<?php echo $item->request_id;?>">
						<?php echo $item->route; ?>
					</a>
				</td>
				<td class="center" style="font-weight:bold;font-size:11px;">
					<span class="status_<?php echo $item->proc_step_id;?>"><?php echo JText::_($item->current_status); ?></span>
				</td>
				<td class="center">
					<?php echo date('d-m-Y', strtotime($item->created_date)); ?>
				</td>
				<td class="center">
					<?php echo date('d-m-Y', strtotime($item->begin_date)); ?>
				</td>
				<td class="center">
					<span class="red"><?php echo $item->expected_send_date != '' ? date('d-m-Y', strtotime($item->expected_send_date)) : ''; ?></span>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="1" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</div>
</form>

<!-- Edit certificate dialog  -->

<div id="image-viewer-dialog" style="display: none;">
    <img id="elec-doc-image" src="" />
</div>
<div id="pdf-viewer-dialog" style="display: none;">
	<a id="down_diploma1" class="download_diploma" title="<?php echo JText::_('VJEEC_REQUEST_DOWNLOAD_DIPLOMA');?>"><img src="<?php echo $component_path .'/helpers/css/images/icon-16-download.png'; ?>" /></a> <br/>
    <iframe id='elec-doc-pdf' style="width:100%;height:100%;" src=""></iframe>
</div>

<div id="req-detail-dlg" class="detail-dlg" style="display: none;">  
  	<div id="req-content" class="info-input">
	    <div class="detail-sec" id="req-detail-sec-1">
	    	<h3>Thông tin</h3>
      		<div>
				<p><label>ID: </label><span id='req-detail-id'></span></p>
				<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_REQUEST_REQUESTER' ); ?></label><span id='req-detail-requester'></span></p>
				<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_REQUEST_CREATED_DATE' ); ?></label><span id='req-detail-created-date'></span></p>
				<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_REQUEST_ROUTE' ); ?></label><span id='req-detail-target-school' class="editable-list" data-pk="0"></span><br></p>
				<p><label><?php echo JText::_( 'VJEEC_REQUEST_TYPE_METHOD' ); ?></label><span id='req-detail-type' class="editable-list" data-pk="0"></span></p>
	      	</div>
    	</div>
	    <div class="detail-sec" id="req-detail-sec-2">
	    	<h3>Hồ sơ</h3>
	      	<div>
				<p><label>ID: </label><span id='req-detail-dplm-id'></span></p>
				<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ); ?></label><span id='req-detail-diploma' class="editable-list" data-pk="0"></span><br></p>
				<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_TITLE' ); ?></label><span id='req-detail-holder' class="editable-txt" data-pk="0"></span><br></p>
				<p><label id="req-detail-holder-info-label-1"></label>
		  		<span id="req-detail-holder-info-1" class="editable-info-txt" infoID='' data-pk="0"></span>
		  		<label id="req-detail-holder-info-label-4"></label>
		  		<span id="req-detail-holder-info-4" class="editable-info-date" infoID='' data-pk="0"></span></p>
				<p><label id="req-detail-holder-info-label-2"></label>
		  		<span id="req-detail-holder-info-2" class="editable-info-txt" infoID='' data-pk="0"></span></p>
				<p><label id="req-detail-holder-info-label-3"></label>
		  		<span id="req-detail-holder-info-3" class="editable-info-txt" infoID='' data-pk="0"></span></p>	
	      	</div>
	    </div>
      
	    <div class="detail-sec" id="req-detail-sec-3">
	    	<h3>Quá trình xử lý</h3>
	      	<div>
				<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_REQUEST_PROCESS_TITLE' ); ?></label><span id='req-detail-process'></span></p>
					<table id='req-detail-proc-history'>
					</table>
		      	</div>
	    	</div>
  		</div>
	  	<div id="req-elec-doc" class="preview">
	  		<a title="<?php echo JText::_('VJEEC_REQUEST_DOWNLOAD_DIPLOMA');?>" style="display:none;" class="download_diploma" id="req_download_dip"><img src="<?php echo $component_path .'/helpers/css/images/icon-16-download.png'; ?>" /></a>
	      	<iframe id="elec-doc" style="width:100%;height:680px;" src=""></iframe>  
  		</div>
</div>

<!-- Create certificate dialog  -->
<div id="certificate_info_dlg" style="display:none;">
	<form id="certificate_form" target="_blank" action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&task=diplomas.generatePDF'); ?>" method="POST">
		<div>
			<label><?php echo JText::_('Loại chứng thực');?> :</label>
			<select name="certificate_type" id="select_type">
				<option value><?php echo JText::_('Chọn loại chứng thực');?></option>
				<option value="1"><?php echo JText::_('Bằng tốt nghiệp');?></option>
				<option value="0"><?php echo JText::_('Chứng nhận tốt nghiệp tạm thời');?></option>
			</select>
			<input type="checkbox" name="is_finished" id="is_finished" /> <label style="font-weight:bold;"><?php echo JText::_('Hoàn thành');?></label>
		</div>
		<br/>
		<table id="tbl_certificate">
			<tr>
				<td>
					<label><?php echo JText::_('Expected Send Date');?>:</label>
					<input class="valid_input datetime" type="text" name="certificate_date" id="certificate_date" />
				</td>
				<td>
					<label><?php echo JText::_('Reference No');?>:</label>
					<input class="valid_input" type="text" name="certificate_reference" id="certificate_reference" />
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('Name');?>:</label>
					<input class="valid_input" type="text" name="student_name" id="student_name" />
				</td>
				<td>
					<label><?php echo JText::_('Gender');?>:</label>
					<input class="valid_input" type="text" name="student_gender" id="student_gender" />
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('Date of birth');?>:</label>
					<input class="valid_input datetime" type="text" name="student_birthday" id="student_birthday" />
				</td>
				<td>
					<label><?php echo JText::_('ID No.');?>:</label>
					<input class="valid_input" type="text" name="student_idno" id="student_idno" />
				</td>
			</tr>
			<tr>
				<td>
					<label id="level_study_lbl"><?php echo JText::_('Type of degree');?>:</label>
					<input class="valid_input" type="text" name="level_study" id="level_study" />
				</td>
				<td>
					<label><?php echo JText::_('Mode of study');?>:</label>
					<input class="valid_input" type="text" name="study_mode" id="study_mode" />
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('Major');?>:</label>
					<input class="valid_input" type="text" name="student_major" id="student_major" />
				</td>
				<td>
					<label><?php echo JText::_('Ranking');?>:</label>
					<input class="valid_input" type="text" name="student_ranking" id="student_ranking" />
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('Reg. No');?>:</label>
					<input class="valid_input" type="text" name="reg_no" id="reg_no" />
				</td>
				<td>
					<label><?php echo JText::_('Dated');?>:</label>
					<input class="valid_input datetime" type="text" name="issued_date" id="issued_date" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<label id="issued_by_lbl"><?php echo JText::_('Degree granting institution');?>:</label>
					<input class="valid_input" type="text" name="issued_by" id="issued_by" />
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<button class="update_certificate" id="btn_update_certificate"><?php echo JText::_('Cập nhật');?></button>
					<input type="submit" id="btn_submit_form" value="<?php echo JText::_('In certificate');?>" />
				</td>
			</tr>
		</table>
		<input type="hidden" id="diploma_id" name="diploma_id" />
		<input type="hidden" id="certifi_finished" name="certifi_finished" />
		<input type="hidden" id="school_name" name="school_name" />
		<input type="hidden" id="sender_name" name="sender_name" />
		<input type="hidden" id="orinal_student_name" name="orinal_student_name" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>

<!-- Create diploma dialog -->
<div id="req-adding-dlg" class="detail-dlg">
  	<div id="req-content" class="info-input">
    	<form name="req-adding-form" id="req-adding-form" method="post" >
    		<div class="detail-sec" id="req-detail-sec-1">
    			<h3>Thông tin</h3>
      			<div>
					<p>
          				<label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_SCHOOL' ); ?></label>
          				<span class='req-editable' id='req-detail-target-school-create'></span></p>
						<p><label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_TYPE' ); ?></label>
          				<span class="req-editable" id="req-detail-type-create"></span>
        			</p>
      			</div>
    		</div>
    
    		<div class="detail-sec" id="req-detail-sec-2">
    			<h3>Hồ sơ</h3>
      			<div>
        			<input type="hidden" id="req-detail-dplm-id" value="0"/>
					<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ); ?></label>
          			<span class='dplm-editable' id='req-detail-diploma-create'></span></p>
					<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_TITLE' ); ?></label>
          			<span class="dplm-editable editable-txt" id='req-detail-holder-create'></span></p>
					<p>
          				<label><?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_ID_NUMBER' ); ?></label>
          				<span class="dplm-editable editable-txt" id="req-detail-holder-info-1-create"></span>
	  					<label><?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_BIRTHDAY' ); ?></label>
          				<span class='dplm-editable' id="req-detail-holder-info-4-create"></span>
        			</p>
        			<p>
						<label><?php echo JText::_( 'Giới tính' ); ?></label>
          				<span class='dplm-editable' id="req-detail-holder-gender-create"></span>
        			</p>
					<p>
						<label><?php echo JText::_( 'Số điện thoại học sinh' ); ?></label>
          				<span class='dplm-editable editable-txt' id="req-detail-holder-info-2-create"></span>
        			</p>
  					<p>
          				<label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT_ADDRESS' ).' học sinh'; ?></label>
          				<span class='dplm-editable' id="req-detail-holder-info-3-create"></span>
        			</p>
        		</div>
      		</div>
      		<input type="hidden" id="upload-path" />
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
  
  	<div id="req-elec-doc-create" class="preview">
    	<form id="upload-form" method="POST" enctype="multipart/form-data" target="upload_target" >
    		File: <input name="upload-file" type="file" />
          	<input type="submit" id="uploadFilePdf" name="submitBtn" value="<?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_ATTACHMENTS');?>" />
    	</form>
 
    	<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
    	<iframe id='elec-doc-create' style="width: 98%;height:640px;" src="javascript:false;"></iframe>  
  	</div>
</div>

<div id="progress-dlg" title="File upload">
	<div class="progress-label">Starting upload...</div>
  	<div id="progressbar"></div>
</div>


<div id="transcript_dlg" class="detail-dlg" style="display: none;">  
  	<div id="transcipt-content" class="info-input">
	    <div class="detail-sec" id="req-detail-sec-1">
	    	<h3>Info</h3>
	    	<div>
	    		<form id="frm_transcript_info">
				<table id="tbl_info">
					<tr>
						<td>
							<label>No:</label>
							<input type="text" name="no" id="no" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Full name:</label>
							<input type="text" name="full_name" id="tran_fullname" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Gender:</label>
							<input type="text" name="gender" id="tran_gender" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Date of Birth:</label>
							<input type="text" class="datetime" name="birthday" id="tran_birthday" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Place of birth:</label>
							<input type="text" name="place_birth" id="place_birth" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Ethnic Group:</label>
							<input type="text" name="ethnic_group" id="ethnic_group" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Child of fallen or war invalid soldiers: </label> <input type="checkbox" name="invalid_soldier" id="invalid_soldier" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Current Residence:</label>
							<input type="text" name="current_residence" id="current_residence" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Full name of Father:</label>
							<input type="text" name="father_name" id="father_name" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Occupation:</label>
							<input type="text" name="father_occupation" id="father_occupation" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Full name of Mother:</label>
							<input type="text" name="mother_name" id="mother_name" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Occupation:</label>
							<input type="text" name="mother_occupation" id="mother_occupation" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Dated:</label>
							<input type="text"  class="datetime" name="trans_date" id="trans_date" />
						</td>
					</tr>
					
					<tr>
						<td>
							<button class="btn" href="#" id="update_tran_info">Update</button>
						</td>
					</tr>
				</table>
				<input type="hidden" name="diploma_id" id="tran_diploma_id" />
				<input type="hidden" name="trans_id" id="trans_id" />
				</form>
	      	</div>
	    </div>
	    <div class="detail-sec" id="req-detail-sec-2">
	    	<h3>Process of study</h3>
	    	<div>
	    		<p>
			    	<label> Select school year</label> 
			    	<select id="select_school_year">
			    		<option>--- select year ---</option>
			    	</select>
			    	<a href="#" id="addSchoolYear"> Add school year</a>
		    	</p>
		    	<form name="frm_school_year" id="frm_school_year">
		    	<table>
		    		<tr>
						<td>
							<label>Academic year:</label>
							<input type="text" name="academic_year" placeholder="2013-2014" id="academic_year" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Class:</label>
							<input type="text" name="class" placeholder="10,11,12" id="class" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Band:</label>
							<input type="text" name="band" placeholder="Basic" id="band" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Advanced course:</label>
							<input type="text" name="advanced_course" id="advanced_course" />
						</td>
					</tr>
		    	</table>
		    		<input type="hidden" name="study_process_id" id="study_process_id" />
		    	</form>
	    	</div>
	    </div>
	    <div class="detail-sec" id="req-detail-sec-3">
	    	<h3>Study sumary</h3>
	      	<div>
	      		<form name="frm_study_sumary" id="frm_study_sumary" style="display:none;">
		    	<table id="tbl_sumary">
		    		<tr>
						<td>
							<label>Head teacher:</label>
							<input type="text" name="head_teacher" id="head_teacher" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Confirmation of the Principal:</label>
							<input type="text" name="school_administrator" id="school_administrator" />
						</td>
					</tr>
					<tr>
						<td>
							<label>District:</label>
							<input type="text" name="district" id="district" />
						</td>
					</tr>
					<tr>
						<td>
							<label>City:</label>
							<input type="text" name="city" id="city" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Conduct I:</label>
							<input type="text" name="conduct1" id="conduct1" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Conduct II:</label>
							<input type="text" name="conduct2" id="conduct2" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Conduct whole year:</label>
							<input type="text" name="conduct3" id="conduct3" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Learning Capacity I:</label>
							<input type="text" name="learning1" id="learning1" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Learning Capacity II:</label>
							<input type="text" name="learning2" id="learning2" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Learning Capacity whole year:</label>
							<input type="text" name="learning3" id="learning3" />
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Days of absence of whole year:</label>
							<input type="text" name="nb_days_absent" id="nb_days_absent" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Classification after retaken exam | Conduct :</label>
							<input type="text" name="retaken_conduct" id="retaken_conduct" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Classification after retaken exam | Learning Capacity:</label>
							<input type="text" name="retaken_learning" id="retaken_learning" />
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Straightly move up to higher grade:</label>
							<textarea name="straightly_move_up" id="straightly_move_up"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label>Qualified to move up to higher grade after retaken exam or practicing conduct:</label>
							<textarea name="qualified_move_up" id="qualified_move_up"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label>Unqualified to move up to higher grade:</label>
							<input type="text" name="unqualified" id="unqualified" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Holding certificate of vocational training:</label>
							<input type="text" name="holding_certificate" id="holding_certificate" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Award in contests of district-level and up:</label>
							<input type="text" name="award_contests" id="award_contests" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Other special comments and rewards:</label>
							<input type="text" name="other_special_comment" id="other_special_comment" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Ranking:</label>
							<input type="text" name="ranking" id="ranking" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Remark by the head teacher:</label>
							<textarea name="head_teacher_remark" id="head_teacher_remark"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label>Approval by the principal:</label>
							<textarea type="text" name="principal_approve" id="principal_approve"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label>Dated:</label>
							<input type="text" class="datetime" name="sumary_dated" id="sumary_dated" />
						</td>
					</tr>
		    	</table>
		    		<input type="hidden"  name="sumary_id" id="sumary_id" />
		    		<input type="hidden"  name="school_year_id" id="school_year_id" />
		    		<button id="btn_update_sumary"> Update </button>
		    	</form>
	    	</div>
  		</div>
	  	<div class="preview">
	      	<iframe id="elec-doc" style="width:100%;height:680px;" src=""></iframe>
  	</div>
</div>