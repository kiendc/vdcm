<?php
// No direct access.
defined('_JEXEC') or die;

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
$component_path = $this->baseurl. '/components/com_vjeecdcm';
?>

<form action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&view=schools');?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('Tìm Kiếm Trường'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('Tìm kiếm trường'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Tìm kiếm'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_RESET'); ?></button>
		</div>
		<div class="filter-select">
			<span class="faux-label"><?php echo JText::_('Lọc trường theo'); ?></span> 
			<select name="filter_status" class="inputbox" onchange="this.form.submit()" style="width:250px;">
				<option value="-1">-<?php echo JText::_('VJEEC_REQUEST_STATUS');?>-</option>
				<?php echo JHtml::_('select.options', VjeecHelper::getSchoolStatus(), 'value', 'text', $this->state->get('filter.status'));?>
			</select>
			
			<select name="filter_school" class="inputbox" onchange="this.form.submit()" style="width:250px;">
				<option value="">-<?php echo JText::_('VJEEC_REQUEST_SCHOOL');?>-</option>
				<?php echo JHtml::_('select.options', VjeecHelper::getSchoolOptions(), 'value', 'text', $this->state->get('filter.school'));?>
			</select>
		</div>
	</fieldset>
	<div class="clr"></div>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="5%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="5%">
					<?php echo JHtml::_('grid.sort', JText::_( 'ID' ), 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="25%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_SV_CT_NAME' ), 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_SCHOOL_ADD_FORM_FS_USERNAME' ), 'u.username', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JText::_('VJEEC_REQUEST_STATUS'); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_SV_CT_EMAIL' ), 'u.email', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_SV_CT_PHONE' ), 'a.phone', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_SV_CT_FAX' ), 'a.fax', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_SV_CT_LAST_VISIT_DATE' ), 'u.lastvisitDate', $listDirn, $listOrder); ?>
				</th>
				<!--
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', JText::_( 'VJEECDCM_SV_CT_NB_REQUESTS' ), 'nb_requests', $listDirn, $listOrder); ?>
				</th>
				-->
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
					<?php echo JHtml::_('grid.id', $i, $item->school_id); ?>
				</td>
				<td class="center">
					<?php echo $item->school_id; ?>
				</td>
				<td class="left">
					<a href="<?php echo JRoute::_('index.php?option=com_vjeecdcm&view=school&layout=edit&id='.$item->school_id)?>"><?php echo $item->name; ?></a>
				</td>
				<td class="center">
					<?php echo $item->username; ?>
				</td>
				<td class="center">
					<?php
						$jalsaUser = VjeecHelper::getJalsaUserId();
						if ($item->represent_user_id == $jalsaUser) { 
							echo '<img src="'.$component_path.'/helpers/css/images/tick.png" />';
						} else { 
							echo '<img src="'.$component_path.'/helpers/css/images/publish_x.png" />';
						}
					?>
				</td>
				<td class="center">
					<?php echo $item->email; ?>
				</td>
				<td class="center">
					<?php echo $item->phone; ?>
				</td>
				<td class="center">
					<?php echo $item->fax; ?>
				</td>
				<td class="center">
					<?php echo date('d/m/Y: H:i:s' , strtotime($item->lastvisitDate)); ?>
				</td>
				<!--
				<td class="center">
					<?php echo $item->nb_requests; ?>
				</td>
				-->
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
