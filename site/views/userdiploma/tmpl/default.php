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
$doc->addScript('components/com_vjeecdcm/ext/data-tables/media/js/jquery.js');
$doc->addScript('components/com_vjeecdcm/ext/data-tables/media/js/jquery.dataTables.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');
$doc->addScript('components/com_vjeecdcm/ext/data-tables/extras/TableTools/media/js/TableTools.js');
$doc->addScript('components/com_vjeecdcm/js/view-userdiplomas.js');

$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/demo_page.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/jquery.dataTables.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css'); 
?>
  
<!--<div id="del-confm-dlg" title="Xác nhận">
  Bạn có đồng ý xóa bằng khỏi hồ sơ?
</div>-->

<div class="vjeecdcm-frame">
<div class="vjeecdcm-frame-header">
<?php echo JText::_('VJEECDCM_USER_DIPOMA_FRAME_TITLE'); ?>
</div>
<div class="vjeecdcm-frame-content">

<div class="clr"></div>


<?php

    echo '<table id="diploma-table" class="diplay">';
    echo '<thead>';
    echo '<tr>';
    echo '<th> ' . JText::_( 'VJEECDCM_USER_DIPLOMA_COLUMN_TITLE' ) . '</th>';
    //echo '<th> ' . JText::_( 'VJEECDCM_USER_REQUEST_ID' ) . '</th>';
    echo '<th> ' . JText::_( 'VJEECDCM_DIPLOMA_HOLDER_TITLE' ) . '</th>';
    echo '<th> ' . JText::_( 'VJEECDCM_DIPLOMA_REGISTRATION_DATE_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_DIPLOMA_CERTIFICATION_INFO_SHORT_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_DIPLOMA_RELATING_REQUESTS' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_USER_DIPLOMA_CREATE_REQUEST' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_USER_DIPLOMA_DELETE_DIPLOMA' ) . '</th>';
    //echo '<th>' . JText::_( 'VJEECDCM_USER_DIPLOMA_ELECTRONIC_VERSION' ) . '</th>';
    echo '</tr>';
    echo '</thead>'; 
    echo '<tbody>';

    if ($this->registeredDiplomas != NULL)
    {
      foreach ($this->registeredDiplomas as $dplm)
      {
	echo '<tr>';
	if ($dplm->elecVersion != null)
	{
	  echo '<td><a href="'. $dplm->elecVersion . '">' . JText::_($dplm->name) . '</a></td>';	
	}
	else
	{
	  echo '<td>' . JText::_($dplm->name) . '</td>';
	}
	echo '<td>'. $dplm->holder_name . '</td>';
	echo '<td>' . $dplm->registration_date . '</td>';
	echo '<td>' . JText::_($dplm->certified_by_vjeec) . '</td>';
	echo '<td>' . count($dplm->relatedReqs) . '</td>';
	//echo '<td><a href="' . JURI::base( false, '/vjeecdcm') . 'abc.pdf" target="_blank">Xem</a></td>';
	echo '<td class="center"><a href="index.php?option=com_vjeecdcm&view=request&layout=add&dplmId=' . $dplm->diploma_id . '"><img src="' . JURI::base(true) . '/components/com_vjeecdcm/images/icons/add.png"></a></td>';
	if (count($dplm->relatedReqs) == 0)
	{
	  echo '<td><a class="confirmLink" href="index.php?option=com_vjeecdcm&task=client.removeDiploma&usrDplmId=' . $dplm->id . '"><img src="' . JURI::base(true) . '/components/com_vjeecdcm/images/icons/delete.png"></a></td>'; 
	}
	else
	{
	  echo '<td></td>';
	}
	  echo '</tr>';
      }
    }

    echo '</tbody>';
    echo '</table>';
  
?>
<!--
<a id="addDiploma1" class="buttonLink" href="index.php?option=com_vjeecdcm&task=crud.updateAllRequestProcessing">Bổ sung hồ sơ</a>
<br/>
<a href="index.php?option=com_vjeecdcm&view=diploma&layout=add">
<?php echo JTEXT::_('VJEECDCM_USER_DIPLOMA_BUTTON_REGISTER'); ?></a>
-->

</div>
</div>
