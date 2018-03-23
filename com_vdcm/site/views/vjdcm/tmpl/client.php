<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('jquery.framework', false);
$doc =  JFactory::getDocument();

//$doc->addStyleSheet('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
//$doc->addStyleSheet('https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css');

$doc->addScript('https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js');
$doc->addScript('https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js');

$doc->addScript('components/com_vjeecdcm/js/vjdcm.js');
$doc->addScript('components/com_vjeecdcm/js/client.js');
?>

<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="#">Dashboard</a></li>
  <li role="presentation"><a href="#">Requests</a></li>
  <li role="presentation"><a href="#">Messages</a></li>
</ul>
<br>
<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
<?php
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_CODE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_CREATED_DATE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_SCHOOL' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_PROCESS_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_LAST_UPDATE_DATE' ) . '</th>';
?>
            </tr>
        </thead>
        <tbody>
  </tbody>
</table>
