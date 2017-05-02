<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.modal');
/*
 Default layout of userdiploma view is a table of all registered diploma.
 */
$doc =  JFactory::getDocument();
/*
 Add scripts of an external library for data grid: DataTables
*/
//$doc->addScript('components/com_vjeecdcm/ext/data-tables/media/js/jquery.js');
$doc->addScript('//code.jquery.com/jquery-1.10.2.min.js');
//$doc->addScript('//ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js');
$doc->addScript('//cdn.datatables.net/1.10.0/js/jquery.dataTables.js');
$doc->addScript('//cdnjs.cloudflare.com/ajax/libs/datatables-tabletools/2.1.5/js/TableTools.js');

$doc->addScript('//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js');
$doc->addScript('components/com_vjeecdcm/ext/data-tables/extras/TableTools/media/js/TableTools.js');
$doc->addScript('components/com_vjeecdcm/js/empldiploma-default.js');

$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
//$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/demo_page.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/jquery.dataTables.css');
?>
  
<!--<div id="del-confm-dlg" title="Xác nhận">
  Bạn có đồng ý xóa bằng khỏi hồ sơ?
</div>-->
<!--
<div class="vjeecdcm-frame">
<div class="vjeecdcm-frame-header">
<?php echo JText::_('VJEECDCM_USER_DIPOMA_FRAME_TITLE'); ?>
</div>
-->
<div class="vjeecdcm-frame-content">

<table id="diploma-table" class="diplay">
<?php

    
    echo '<thead>';
    echo '<tr>';
    echo '<th>' . JText::_( 'VJEECDCM_ID' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_USER_DIPLOMA_COLUMN_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_DIPLOMA_HOLDER_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_DIPLOMA_USER' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_DIPLOMA_REGISTRATION_DATE_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_DIPLOMA_RELATING_REQUESTS' ) . '</th>';
    echo '</tr>';
    echo '</thead>';
    /*
    echo '<tbody>';

    if ($this->diplomas != NULL)
    {
      foreach ($this->diplomas as $dplm)
      {
	echo '<tr>';
	echo '<td>' . $dplm->diploma_id  . '</td>';
	if ($dplm->elecVersion != null)
	{
	  echo '<td><a href="'. $dplm->elecVersion . '">' . JText::_($dplm->name) . '</a></td>';	
	}
	else
	{
	  echo '<td>' . JText::_($dplm->name) . '</td>';
	}
	echo '<td>' . $dplm->holder_name . '</td>';
	echo '<td>' . JFactory::getUser($dplm->user_id)->name . '</td>';
	echo '<td>' . $dplm->registration_date . '</td>';
	echo '<td>';
	foreach ($dplm->relatedReqs as $req)
	{
	    echo $req->id . ' ';
	}
	echo '</td>';
	//echo '<td><a href="' . JURI::base( false, '/vjeecdcm') . 'abc.pdf" target="_blank">Xem</a></td>';
	echo '</tr>';
      }
    }

    echo '</tbody>';
    */
?>

</table>
 
</div>
