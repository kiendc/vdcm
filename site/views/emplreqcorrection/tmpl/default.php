<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JText::script('VJEECDCM_RV_BT_ARCHIVE_REQUESTS');
JText::script('VJEECDCM_RV_UCD_BT_CONFIRM');
JText::script('VJEECDCM_RV_UCD_BT_CANCEL');
JText::script('VJEECDCM_RV_BT_REMOVE_REQUESTS');


$doc =  JFactory::getDocument();
$doc->addScript('//code.jquery.com/jquery-1.10.2.min.js');
$doc->addScript('//cdn.datatables.net/1.10.0/js/jquery.dataTables.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');
//$doc->addScript('components/com_vjeecdcm/ext/data-tables/extras/TableTools/media/js/TableTools.js');
$doc->addScript('components/com_vjeecdcm/js/view-emplreqcorrection.js');


$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addStyleSheet('//cdn.datatables.net/1.10.0/css/jquery.dataTables.css');
//$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/jquery.dataTables.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css');
?>

<div class="vjeecdcm-frame">
<div class="vjeecdcm-frame-header">
<?php echo JText::_('VJEECDCM_COOPERATOR_REQUEST_FRAME_TITLE'); ?>
</div>
<div class="vjeecdcm-frame-content">
  
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
    echo '<th> </th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_CODE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_CREATED_DATE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_REQUESTER' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_SCHOOL' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_ROUTE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_PROCESS_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_LAST_UPDATE_DATE' ) . '</th>';
    echo '</tr>';
    echo '</thead>'; 
    ?>
  </table>
  </form>
</div>
</div>

