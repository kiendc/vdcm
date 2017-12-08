<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


$doc =  JFactory::getDocument();
$doc->addScript('components/com_vjeecdcm/ext/data-tables/media/js/jquery.js');
$doc->addScript('components/com_vjeecdcm/ext/data-tables/media/js/jquery.dataTables.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');
$doc->addScript('components/com_vjeecdcm/ext/data-tables/extras/TableTools/media/js/TableTools.js');
$doc->addScript('components/com_vjeecdcm/js/view-emplclient.js');


$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/demo_page.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/jquery.dataTables.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css');
?>


<div class="vjeecdcm-frame">
<div class="vjeecdcm-frame-header">
<?php echo JText::_('VJEECDCM_EMPLOYEE_CLIENT_FRAME_TITLE'); ?>
</div>
<div class="vjeecdcm-frame-content">

  <form name="clientTableForm"
	action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&task=employeeClientViewFormHandler'); ?>"
	method="post">
    <input type="hidden" name="buttonClicked" id="buttonClicked" value="">
  <table id="client-table">
    <?php
    $user = JFactory::getUser();
    echo '<thead>';
    echo '<tr>';
    echo '<th></th><th></th>';
    echo '<th class="center"> <input type="checkbox" name="checkAll' . '" value="0"> </th>';
    echo '<th>' . JText::_( 'VJEECDCM_CV_CT_NAME' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_CV_CT_EMAIL' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_CV_CT_LAST_VISIT_DATE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_CV_CT_NB_REQUESTS' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_CV_CT_NB_DIPLOMAS' ) . '</th>';
    echo '</tr>';
    echo '</thead>'; 
    echo '<tbody>';
    if ($this->clients)
    {
      foreach ($this->clients as $clnt)
      {
	echo '<tr>';
	echo '<td>' . $clnt->client_id . '</td>';
	echo '<td>' . $clnt->client_user_id . '</td>';
	echo '<td class="center"> <input type="checkbox" name="check[]' . '" value="' . $clnt->client_id . '"> </td>';
	echo '<td>' . $clnt->name . '</td>';
	echo '<td>' . $clnt->email . '</td>';
	echo '<td>' . $clnt->lastvisitDate . '</td>';
	if ($clnt->nb_requests)
	{
	  echo '<td>' . $clnt->nb_requests . '</td>';
	}
	else
	{
	  echo '<td>0</td>';
	}
	if ($clnt->nb_diplomas)
	{
	  echo '<td>' . $clnt->nb_diplomas . '</td>';
	}
	else
	{
	  echo '<td>0</td>';
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
</div>
