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
//$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js', 'text/javascript');
// bootstrap 3.3.7
//$doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css', 'text/css');
//$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js', 'text/javascript');
//select2
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/select2.min.js', 'text/javascript');
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/select2.css', 'text/css');
//$doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css', 'text/css');
//$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js', 'text/javascript');

// jqueryui
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jquery-ui.js', 'text/javascript');
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/jquery-ui.css', 'text/css');
//x-editable
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/jqueryui-editable.css', 'text/css');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jqueryui-editable.js', 'text/javascript'); 
//$doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css', 'text/css');
//$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.js', 'text/javascript'); 

//$doc->addScript($this->baseurl.'/../components/com_vjeecdcm/ext/jquery.form.js');
	
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/requests.js', 'text/javascript');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/create_request.js', 'text/javascript');
$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/gen-coverpage-pdf.js', 'text/javascript');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jspdf-autotable.js', 'text/javascript');

$doc->addStyleSheet($this->baseurl.'/../components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/custom.css');



$component_path = $this->baseurl. '/components/com_vjeecdcm';
?>

<script type="text/javascript">
 	// set site_url variable which to be used in create_request.js file 
	var site_url = "<?php echo JURI::root(); ?>";
	var formToken = '<?php echo JSession::getFormToken(); ?>';
</script>

<button id="req-view-create-btn" style="margin:10px;height:30px;color:#054993;font-weight:bold;"><?php echo JText::_('VJEECDCM_RV_BT_CREATE_REQUESTS');?></button>
<!--<button id="req-coverpage-btn" style="margin:10px;height:30px;color:#054993;font-weight:bold;">New cover page</button>-->
<span id="coverpage-date-select2">Select expected date</span>
<button id="gen-coverpage-by-date-btn" style="margin:10px;height:30px;color:#054993;font-weight:bold;"> Cover pages for normal reqs</button>
<button id="gen-schl-coverpage-by-date-btn" style="margin:10px;height:30px;color:#054993;font-weight:bold;"> Cover pages for direct reqs</button>

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

<!-- Create request detail dialog -->
<?php
echo $this->loadTemplate('req-detail-dlg'); 
?>

<!-- Create certificate dialog  -->
<?php
echo $this->loadTemplate('certificate-info-dlg'); 
?>

<!-- Create diploma dialog -->
<?php
echo $this->loadTemplate('req-adding-dlg'); 
?>

<div id="progress-dlg" title="File upload">
	<div class="progress-label">Starting upload...</div>
  	<div id="progressbar"></div>
</div>

<!-- Create transcript dialog -->
<?php
//echo $this->loadTemplate('transcript-dlg'); 
?>

<!-- Create Cover page dialog -->
<?php
echo $this->loadTemplate('coverpage-dlg'); 
?>
