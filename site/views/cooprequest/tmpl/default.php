<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JText::script('VJEECDCM_RV_BT_CONFIRM_SELECTED');
JText::script('VJEECDCM_RV_BT_CONFIRM_ALL');
$doc =  JFactory::getDocument();
$doc->addScript('//code.jquery.com/jquery-1.10.2.min.js');
$doc->addScript('//cdn.datatables.net/1.10.0/js/jquery.dataTables.js');
$doc->addScript('//cdnjs.cloudflare.com/ajax/libs/datatables-tabletools/2.1.5/js/TableTools.js');
//$doc->addScript('components/com_vjeecdcm/ext/data-tables/extras/TableTools/media/js/TableTools.js');
$doc->addScript('//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.js');
$doc->addScript('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js');
$doc->addScript('components/com_vjeecdcm/js/view-cooprequests.js');

//$doc->addStyleSheet('//cdn.datatables.net/1.10.0/css/jquery.dataTables.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/jquery.dataTables.css');
//$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/select2-3.5.0/select2.css');
$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
?>

<div id="confm-all-dlg" title="Confirm received for all files">
  Did you receive all certificated file?
</div>

<div id="confm-sel-dlg" title="Confirm received for selected files">
  Did you receive the selected certificated file?
</div>
<!--
<div class="vjeecdcm-frame">
<div class="vjeecdcm-frame-header">
<?php echo JText::_('VJEECDCM_COOPERATOR_REQUEST_FRAME_TITLE'); ?>
</div>
<div class="vjeecdcm-frame-content">
--> 

  <!--
  <div class="toolbar">
  <a class="confirmLink buttonLink" href="index.php?option=com_vjeecdcm&task=crud.updateRequestProcessing">Confirm selected requests</a>
  <a class="confirmLink buttonLink" href="index.php?option=com_vjeecdcm&task=crud.updateRequestProcessing">Confirm all requests</a>
  </div>
  -->
  <div id="req-view-toolbar">
  <button id='confirm-selected-btn'><?php echo JText::_('VJEECDCM_RV_BT_CONFIRM_SELECTED');?></button>
  <button id='confirm-all-btn'><?php echo JText::_('VJEECDCM_RV_BT_CONFIRM_ALL');?></button>
  <select id="shown-requests-select" >
      <option value="0"><?php echo JText::_('VJEECDCM_REQUEST_PROCESS_ALL'); ?></option>
      <option value="3"><?php echo JText::_('VJEECDCM_REQUEST_PROCESS_SCREENING'); ?></option>
      <option value="1"><?php echo JText::_('VJEECDCM_REQUEST_PROCESS_SENT'); ?></option>
      <option value="5"><?php echo JText::_('VJEECDCM_REQUEST_PROCESS_RECEIVED_BY_JALSA'); ?></option>
      <option value="6"><?php echo JText::_('VJEECDCM_REQUEST_PROCESS_RECEIVED_BY_SCHOOL'); ?></option>
  </select>
</div>
  
  <form name="requestTableForm"
	action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&task=school.requestViewFormHandler'); ?>"
	method="post">
    <input type="hidden" name="buttonClicked" id="buttonClicked" value="">
    <input type="hidden" name="stepId" id="stepId" value="0">
  <table id="request-table">
    <?php
    $user = JFactory::getUser();
    echo '<thead>';
    echo '<tr>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_ID' ) . '</th>';
    echo '<th> </th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_CODE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_REQUESTER' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_SCHOOL' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_ROUTE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_PROCESS_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_LAST_UPDATE_DATE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_CREATED_DATE' ) . '</th>';
    echo '</tr>';
    echo '</thead>'; 
    ?>
    <!--
    <tfoot>
      <tr>
	<th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th>
	<th>
	  <select id="status-column-filter">
	    <option value="1">Sent</option>
	    <option value="3">Screening</option>
	  </select>
	</th>
	<th></th>
      </tr>
    </tfoot>
  -->
  </table>
  </form>
  
<!--
</div>
-->