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

?>

<form action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&view=customers');?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
	<fieldset id="filter-bar">
	<legend class="element-invisible"><?php echo JText::_('Tìm Khách Hàng'); ?></legend>
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('Tìm khách hàng'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Tìm kiếm'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_RESET'); ?></button>
		</div>
	</fieldset>
	<div class="clr"></div>
	
	<table class="adminlist">
		<thead>
			<tr>
				<th width="2%">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th width="3%">
					<?php echo JHtml::_('grid.sort', 'ID', 'client_id', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="3%">
					<?php echo JHtml::_('grid.sort', 'VJEECDCM_CV_CT_NAME', 'a.name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="5%">
					<?php echo JHtml::_('grid.sort', 'VJEECDCM_CV_CT_EMAIL', 'u.email', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'VJEECDCM_CV_CT_LAST_VISIT_DATE', 'u.lastvisitDate', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'VJEECDCM_CV_CT_NB_REQUESTS', 'nb_requests', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="10%">
					<?php echo JHtml::_('grid.sort', 'VJEECDCM_CV_CT_NB_DIPLOMAS', 'nb_diplomas', $listDirn, $listOrder); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) : 
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->client_id); ?>
				</td>
				<td class="center">
					<?php echo $item->client_id; ?>
				</td>
				<td class="center">
					<a href="<?php echo JRoute::_('index.php?option=com_vjeecdcm&view=customer&layout=edit&id='.$item->client_id)?>"><?php echo $item->name; ?></a>
				</td>
				<td class="center">
					<?php echo $item->email; ?>
				</td>
				<td class="center">
					<?php echo $item->lastvisitDate ? date('d-m-Y', strtotime($item->lastvisitDate)) : 'Không bao giờ'; ?>
				</td>
				<td class="center">
					<?php echo $item->nb_requests; ?>
				</td>
				<td class="center">
					<?php echo $item->nb_diplomas; ?>
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