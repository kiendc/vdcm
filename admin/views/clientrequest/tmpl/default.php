<?php 
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

$doc =  JFactory::getDocument();
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jquery.js', 'text/javascript');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jquery-ui.js', 'text/javascript');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jquery-editable1.5.js', 'text/javascript');
$doc->addScript($this->baseurl.'/../components/com_vjeecdcm/ext/jquery.form.js');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/select2.min.js', 'text/javascript');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/create_request.js', 'text/javascript');

$doc->addStyleSheet($this->baseurl.'/../components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/custom.css');
$doc->addStyleSheet($this->baseurl.'/../components/com_vjeecdcm/ext/select2-3.5.0/select2.css', 'text/css');
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/jquery-ui.css', 'text/css');
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/jqueryui-editable.css', 'text/css');
$component_path = $this->baseurl. '/components/com_vjeecdcm';
?>
<script type="text/javascript">
	var site_url = "<?php echo JURI::root(); ?>";
</script>

<form action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&view=clientrequest');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('Tìm hồ sơ '); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Tìm kiếm'); ?>" />
			<button type="submit"><?php echo JText::_('Tìm kiếm'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('Thiết lập lại'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<label for="filter_state">
				<?php echo JText::_('Lọc hồ sơ theo'); ?>
			</label>
			
			<select name="filter_school" class="inputbox" onchange="this.form.submit()" style="width:250px;">
				<option value="*"><?php echo JText::_('-Trường-');?></option>
				<?php echo JHtml::_('select.options', VjeecHelper::getSchoolOptions(), 'value', 'text', $this->state->get('filter.school'));?>
			</select>

			<select name="filter_status" class="inputbox" onchange="this.form.submit()">
				<option value="*"><?php echo JText::_('-Trạng thái-');?></option>
				<?php echo JHtml::_('select.options', VjeecHelper::getStatusOptions(), 'value', 'text', $this->state->get('filter.status'));?>
			</select>
		</div>
	</fieldset>
	
	<div class="clr"> </div>
	
	<button id="req-view-create-btn" style="margin:10px;height:30px;color:#054993;font-weight:bold;"> Tạo yêu cầu </button> <br/>
	
	<table class="adminlist">
		<thead>
			<tr>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'Mã yêu cầu', 'request_code', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'Ngày gửi', 'a.created_date', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="20%">
					<?php echo JHtml::_('grid.sort', 'Học sinh/sinh viên', 'b.holder_name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="20%">
					<?php echo JHtml::_('grid.sort', 'Hồ sơ', 'f.name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="20%">
					<?php echo JHtml::_('grid.sort', 'Gửi tới', 'd.name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'Hiện trạng', 'current_status', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'Ngày cập nhật', 'e.begin_date', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo $item->request_code; ?>
				</td>
				<td class="center">
					<?php echo date('d-m-Y', strtotime($item->created_date)); ?>
				</td>
				<td class="center">
						<?php echo $item->holder_name; ?>
				</td>
				<td class="center">
					<?php echo JText::_($item->degree_name); ?>
				</td>
				<td class="center">
					<?php echo $item->school_name; ?>
				</td>
				<td class="center">
					<?php echo JText::_($item->current_status); ?>
				</td>
				<td class="center">
					<?php echo date('d-m-Y', strtotime($item->begin_date)); ?>
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
</form>

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
						<label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT_TELEPHONE' ); ?></label>
          				<span class='dplm-editable editable-txt' id="req-detail-holder-info-2-create"></span>
        			</p>
  					<p>
          				<label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT_ADDRESS' ); ?></label>
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