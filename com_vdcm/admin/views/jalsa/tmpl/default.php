<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');
$loggeduser = JFactory::getUser();
$canDo = VjeecHelper::getActions();

$isAdmin = $loggeduser->authorise('core.admin');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$doc =  JFactory::getDocument();
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/custom.css');

?>

<form id="adminForm" name="adminForm" action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&view=jalsa');?>" method="post" >
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('Tìm Kiếm Hồ Sơ'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('Tìm kiếm hồ sơ'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Tìm kiếm'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_RESET'); ?></button>
		</div>
		<div class="filter-select">
			<span class="faux-label"><?php echo JText::_('Lọc hồ sơ theo'); ?></span>
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
	<div class="clr"></div>
  	<table class="adminlist">
  		<thead>
  			<tr>
  				<th class="nowrap" width="3%">
					<?php echo JHtml::_('grid.sort', JText::_( 'ID' ), 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="8%">
					<?php echo JText::_( 'VJEECDCM_RV_CT_REQUEST_CODE'); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_RV_CT_REQUEST_REQUESTER' ), 'requester_name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_TITLE' ), 'b.holder_name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ), 'a.diploma_id', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="12%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_RV_CT_REQUEST_SCHOOL' ), 'd.name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="12%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_RV_CT_REQUEST_ROUTE' ), 'd.name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_RV_CT_REQUEST_PROCESS_TITLE' ), 'current_status', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="8%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_RV_CT_REQUEST_LAST_UPDATE_DATE' ), 'a.created_date', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="8%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_RV_CT_REQUEST_CREATED_DATE' ), 'e.begin_date', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="8%">
					<?php echo JHtml::_('grid.sort', JText::_( 'Ngày dự kiến gửi' ), 'a.expected_send_date', $listDirn, $listOrder); ?>
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
  			<?php foreach ($this->items as $i => $item) : ?>
  				<tr class="row<?php echo $i % 2; ?>">
  					<td class="center"><?php echo $item->request_id; ?></td>
  					<td class="center"><?php echo $item->code; ?></td>
  					<td class="center"><?php echo $item->requester_name; ?></td>
  					<td class="center"><?php echo $item->holder_name; ?></td>
  					<td class="center"><?php echo JText::_($item->degree_name); ?></td>
  					<td class="center"><?php echo $item->school_name; ?></td>
  					<td class="center">
  						<?php
  							if ($item->request_type) { 
  								echo $item->school_name;
  							} else { 
  								echo JFactory::getUser(604)->get('username');
  							} 
  						?>
  					</td>
  					<td class="center" style="font-weight:bold;font-size:11px;">
  						<span class="status_<?php echo $item->proc_step_id;?>"><?php echo JText::_($item->current_status); ?></span>
  					</td>
  					<td class="center"><?php echo $item->last_update != null ? date('d-m-Y', strtotime($item->last_update)) : ''; ?></td>
  					<td class="center"><?php echo $item->created_date != null ?date('d-m-Y', strtotime($item->created_date)) : ''; ?></td>
  					<td class="center"><span class="red"><?php echo $item->expected_send_date != null ? date('d-m-Y', strtotime($item->expected_send_date)) : ''; ?></span></td>
  				</tr>
  			<?php endforeach; ?>
  		
  		</tbody>
  	</table>
</div>
		<input type="hidden" name="task" value="1" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
</form>