<?php
// no direct access
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');
$user = JFactory::getUser();
$canDo = VjeecHelper::getActions();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

?>

<form action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&view=diplomas');?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('Tìm hồ sơ '); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Tìm kiếm'); ?>" />
			<button type="submit"><?php echo JText::_('Tìm kiếm'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('Thiết lập lại'); ?></button>
		</div>
	</fieldset>
	
	<div class="clr"> </div>
	
	<table class="adminlist">
		<thead>
			<tr>
				<th width="1%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="20%">
					<?php echo JHtml::_('grid.sort', 'Bộ hồ sơ', 'degree_name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="20%">
					<?php echo JHtml::_('grid.sort', 'Học sinh/sinh viên', 'b.holder_name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="20%">
					<?php echo JHtml::_('grid.sort', 'Người gửi', 'requester_name', $listDirn, $listOrder); ?>
				</th>
				
				<th class="nowrap" width="15%">
					<?php echo JHtml::_('grid.sort', 'Ngày đăng ký', 'a.registration_date', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="15%">
					<?php echo JHtml::_('grid.sort', 'Các yêu cầu liên quan', 'current_status', $listDirn, $listOrder); ?>
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
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td class="center">
					<?php echo $item->id; ?>
				</td>
				<td class="center">
					<?php
						if ($item->attachfile_url != null) { ?> 
							<a href="<?php echo $this->baseurl. '/../'.$item->attachfile_url;?>" target="_blank"><?php echo JText::_($item->degree_name); ?></a>
					<?php } ?>
				</td>
				<td class="center req_detail">
					<?php echo $item->holder_name; ?>
				</td>
				<td class="center">
					<?php echo $item->requester_name; ?>
				</td>
				<td class="center">
					<?php echo date('d/m/Y', strtotime($item->registration_date)); ?>
				</td>
				<td class="center">
					<?php
						foreach ($item->relatedReqs as $req) {
					    	echo $req->id . ' ';
						}
					?>
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