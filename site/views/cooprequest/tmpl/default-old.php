<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JText::script('VJEECDCM_RV_BT_CONFIRM_SELECTED');
JText::script('VJEECDCM_RV_BT_CONFIRM_ALL');
$doc =  JFactory::getDocument();
//$doc->addStyleSheet('components/com_vjeecdcm/css/datagrid.css');
$doc->addScript('components/com_vjeecdcm/ext/data-tables/media/js/jquery.js');
$doc->addScript('components/com_vjeecdcm/ext/data-tables/media/js/jquery.dataTables.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');
$doc->addScript('components/com_vjeecdcm/ext/data-tables/extras/TableTools/media/js/TableTools.js');
$doc->addScript('components/com_vjeecdcm/js/view-cooprequests.js');

$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/demo_page.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/jquery.dataTables.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css');
?>

<div id="confm-all-dlg" title="Confirm received">
  Did you receive the certificated file?
</div>

<div class="vjeecdcm-frame">
<div class="vjeecdcm-frame-header">
<?php echo JText::_('VJEECDCM_COOPERATOR_REQUEST_FRAME_TITLE'); ?>
</div>
<div class="vjeecdcm-frame-content">
  <!--
  <div class="toolbar">
  <a class="confirmLink buttonLink" href="index.php?option=com_vjeecdcm&task=crud.updateRequestProcessing">Confirm selected requests</a>
  <a class="confirmLink buttonLink" href="index.php?option=com_vjeecdcm&task=crud.updateRequestProcessing">Confirm all requests</a>
  </div>
  -->
  
  <form name="requestTableForm"
	action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&task=school.requestViewFormHandler'); ?>"
	method="post">
    <input type="hidden" name="buttonClicked" id="buttonClicked" value="">
  <table id="request-table">
    <?php
    $user = JFactory::getUser();
    echo '<thead>';
    echo '<tr>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_ID' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_CODE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_CREATED_DATE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_REQUESTER' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ) . '</th>';
    if ($user->id == 604)
    {
      echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_SCHOOL' ) . '</th>';
    }
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_ROUTE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_PROCESS_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_LAST_UPDATE_DATE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_UPDATE_PROCESSING' ) . '</th>';
    echo '</tr>';
    echo '</thead>'; 
    echo '<tbody>';
    if ($this->requests)
    {
      foreach ($this->requests as $rq)
      {
	echo '<tr>';
	echo '<td>' . $rq->request_id . '</td>';
	echo '<td>' . $rq->code . '</td>';
	echo '<td>' . $rq->created_date . '</td>';
	echo '<td>' . $rq->requester_name . '</td>';
	echo '<td>' . $rq->holder_name . '</td>';
	echo '<td>' . JText::_($rq->name) . '</td>';
	if ($user->id == 604)
	{
	  echo '<td>' . $rq->target_school_name . '</td>';
	}
	echo '<td>' . $rq->route . '</td>';
	echo '<td><span proc-hist="'. $rq->request_id . '" class="show-history">' . JText::_($rq->processing_status) . '</span></td>';
	echo '<td>' .  $rq->last_update . '</td>';
	if ($rq->enableConfirmByUser)
	{ // Sent by VJEEC to schools or JaLSA
	  echo '<td class="center"> <input type="checkbox" name="check[]' . '" value="' . $rq->request_id . '"> </td>';
	}
	else
	{
	  echo '<td> </td>';  
	}
	echo '</tr>';
      } // Enf of foreach
      
    }
    /*
    else 
    {
      echo 'Something wrong here <br/>';
    }
    */
    echo '</tbody>'; 
    ?>
  </table>
  </form>
  <?php
  if ($this->requests)
  {
    foreach ($this->requests as $rq)
    {
      echo '<div class="proc-hist-dlg" id="' . $rq->request_id .'" title="' . JText::_('VJEECDCM_COOPERATOR_REQUEST_PROCESSING_HISTORY') . '">';
      foreach ($rq->processing_history as $step)
      {
	echo '<p>'. JText::_($step->name) . ': ' . $step->begin_date . '</p>';
      }
      echo '</div>';
    }
  }
  ?>
</div>

<jdoc:include type="error" />
