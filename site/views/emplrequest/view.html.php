<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view'); 

class VjeecDcmViewEmplRequest extends JViewLegacy
{
  // Overwriting JView display method
  protected $requests;
  protected $dirId;
  
  function display($tpl = null) 
  {
    // Assign data to the view
    /*
    $model =& JModel::getInstance('request', 'VjeecDcmModel');
    $attModel =& JModel::getInstance('attachment', 'VjeecDcmModel');
    $holderModel =& JModel::getInstance('holder', 'VjeecDcmModel');
    //$this->requests = $model->getRequests();
    */
    $dirId = JFactory::getApplication()->input->get('dirId', null, 'INT');
    if (is_null($dirId) || $dirId == 0 )
    {
	//$this->requests = $model->getRequests();
	$this->dirId = 0;
    }
    else
    {
	//$this->requests = $model->getRequestsInDirectory($dirId);
	$this->dirId = $dirId;
    }
    $userID = JFactory::getUser()->id;
  
    // Display the view
    parent::display($tpl);
    
  }
  
  
  public function echoRequestTable()
  {
    echo '<thead>';
    echo '<tr>';
    //echo '<th></th><th></th><th></th>';
    //echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_SELECT' ) . '</th>';
    echo '<th><input type="checkbox" name="selectAll"></th>';
    echo '<th>ID</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_CODE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_REQUESTER' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_ROUTE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_PROCESS_TITLE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_CREATED_DATE' ) . '</th>';
    echo '<th>' . JText::_( 'VJEECDCM_RV_CT_REQUEST_LAST_UPDATE_DATE' ) . '</th>';
    echo '</tr>';
    echo '</thead>';
    /*
    echo '<tbody>';
    
    if ($requests)
    {
      foreach ($requests as $rq)
      {
	echo '<tr>';
	echo '<td>' . $rq->request_id . '</td>';
	echo '<td>' . $rq->proc_step_id . '</td>';
	//echo '<td>' . $rq->holder_identity_value . '</td>';
	echo '<td>' . $rq->jsonInfo . '</td>';
	echo '<td class="center"> <input type="checkbox" name="check[]' . '" value="' . $rq->request_id . '"> </td>';
	echo '<td>' . $rq->request_id . '</td>';
	echo '<td ><a href="index.php?option=com_vjeecdcm&view=request&reqId=' . $rq->request_id .'">' . $rq->code . '</a></td>';
	echo '<td>' . $rq->requester_name . '</td>';
	echo '<td class="holder">' . $rq->holder_name . '</td>';	
	if (isset($rq->elecDocInfo))
	{
	  if ($rq->elecDocInfo->type != null && $rq->elecDocInfo->type == 0)
	    echo '<td class="elec-doc-link"><a href="'. $rq->elecDocInfo->path . '">' . JText::_($rq->degree_name) . '</a></td>';
	  else
	    echo '<td class="pdf-doc-link"><a href="'. $rq->elecDocInfo->path . '">' . JText::_($rq->degree_name) . '</a></td>';
	}
	else
	{
	  echo '<td>' . JText::_($rq->degree_name) . '</td>'; 
	}
	echo '<td><span class="editable-route" data-pk="' . $rq->request_id . '">' . $rq->route . '</span></td>';
	echo '<td><input type="hidden" name="steps[' . $rq->request_id. ']" value="'. $rq->proc_step_id .'">' . JText::_($rq->name) . '</td>';
	echo '<td>' . $rq->created_date . '</td>';
	echo '<td>' .  $rq->begin_date . '</td>';
	echo '</tr>';
      } // Enf of foreach
      
    }
    */
    /*
    else 
    {
      echo 'Something wrong here <br/>';
    }
    
    
    */
  }
}

