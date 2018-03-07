<?php 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JText::script('VJEECDCM_RV_BT_ARCHIVE_REQUESTS');
JText::script('VJEECDCM_RV_UCD_BT_CONFIRM');
JText::script('VJEECDCM_RV_UCD_BT_CANCEL');
JText::script('VJEECDCM_RV_BT_REMOVE_REQUESTS');
JText::script('VJEECDCM_RV_BT_UPDATE_REQUESTS');
JText::script('VJEECDCM_RV_BT_SHOW_DIRECTORY');

$doc =  JFactory::getDocument();

$doc->addScript('components/com_vjeecdcm/js/jquery-1.10.2.min.js');
$doc->addScript('components/com_vjeecdcm/js/jquery.dataTables.js');
$doc->addScript('components/com_vjeecdcm/js/TableTools.js');
$doc->addScript('components/com_vjeecdcm/js/jquery-ui.min.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery.treetable.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery.form.js');
$doc->addScript('components/com_vjeecdcm/js/select2.min.js');
$doc->addScript('components/com_vjeecdcm/js/jqueryui-editable.min.js');
$doc->addScript('components/com_vjeecdcm/js/view-emplrequests.js');

//adding css file
$doc->addStyleSheet('components/com_vjeecdcm/ext/data-tables/media/css/jquery.dataTables.css');
$doc->addStyleSheet('components/com_vjeecdcm/css/jquery.treetable.css');
$doc->addStyleSheet('components/com_vjeecdcm/css/jquery.treetable.theme.default.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/select2-3.5.0/select2.css');
$doc->addStyleSheet("components/com_vjeecdcm/css/jqueryui-editable.css");
$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');

?>

<!--
<div class="vjeecdcm-frame">
<div class="vjeecdcm-frame-header">
<?php echo JText::_('VJEECDCM_EMPLOYEE_REQUEST_FRAME_TITLE'); ?>
</div>
-->


  
  
  <!-- dialog show electronic version -->
  <div id="image-viewer-dialog" style="display: none;">
    <img id="elec-doc-image" src=""/>
  </div>
  <div id="pdf-viewer-dialog" style="display: none;">
    <iframe id='elec-doc-pdf' style="width:100%;height:100%;" src=""></iframe>>
  </div>
  <!-- confirmation dialogs -->
  <div id="update-proc-confm-dialog">
    <?php echo JText::_('VJEECDCM_RV_UCD_MSG'); ?>
  </div>
  
  <div id="req-rem-cfm-dlg" style="display:none;">
    <?php echo JText::_('VJEECDCM_RV_DCD_MSG'); ?>
    
  </div>
  
  
  <!-- context menu -->
  <ul id="req-context-menu" class="dropdown-select-menu">
    <li id="menu-item-update" ><a href="index.php?option=com_vjeecdcm&task=employee.updateProcess"><?php echo JText::_('VJEECDCM_RV_CTMN_UPDATE'); ?></a></li>
    <li id="menu-item-accept"><a href="index.php?option=com_vjeecdcm&task=employee.acceptsSubmittedRequest"><?php echo JText::_('VJEECDCM_RV_CTMN_ACCEPT'); ?></a></li>
    <li><a href="#"><?php echo JText::_('VJEECDCM_RV_CTMN_ARCHIVE'); ?></a></li>
    <li><a href="index.php?option=com_vjeecdcm&view=request"><?php echo JText::_('VJEECDCM_RV_CTMN_EDIT'); ?></a></li>
    <li id="menu-item-cert-gen" value="3"><a href="#"><?php echo JText::_('VJEECDCM_RV_CTMN_CERT_GEN'); ?></a></li>
  </ul>
  
  <!-- directory select -->
  <ul id="req-dir-select-menu" class="dropdown-select-menu">
      <li value="0"><a href="#"><?php echo JText::_('VJEECDCM_RV_SDMN_ROOT'); ?></a></li>
      <li value="1"><a href="#"><?php echo JText::_('VJEECDCM_RV_SDMN_ARCHIVED'); ?></a></li>
      <li value="2"><a href="#"><?php echo JText::_('VJEECDCM_RV_SDMN_RECEIVED'); ?></a></li>
      <li value="3"><a href="#"><?php echo JText::_('VJEECDCM_RV_SDMN_PROCESSING'); ?></a></li>
      <li value="-1"><a href="#"><?php echo JText::_('VJEECDCM_RV_SDMN_TRASHED'); ?></a></li>
  </ul>
  
<ul id="proc-step-select-menu" class="dropdown-select-menu">
    <li value="0"><a href="#"><?php echo JText::_('VJEECDCM_RV_SPSMN_AUTO'); ?></a></li>
    <li value="3"><a  href="#"><?php echo JText::_('VJEECDCM_REQUEST_PROCESS_SCREENING'); ?></a></li>
    <li value="1"><a  href="#"><?php echo JText::_('VJEECDCM_REQUEST_PROCESS_SENT'); ?></a></li>
</ul>
  

<div id="req-view-toolbar">
  <button id='req-view-update-btn'><?php echo JText::_('VJEECDCM_RV_BT_UPDATE_REQUESTS');?></button>
  <button id='req-view-move-btn'><?php echo JText::_('VJEECDCM_RV_BT_ARCHIVE_REQUESTS');?></button>
  <!--<button id='req-view-show-btn'><?php echo JText::_('VJEECDCM_RV_BT_SHOW_REQUEST_BY_STATE');?></button>-->
  <label><?php echo JText::_('VJEECDCM_RV_BT_SHOW_REQUEST_BY_STATE'); ?></label><select id="shown-requests-select" >
      <option value="0"><?php echo JText::_('VJEECDCM_REQUEST_PROCESS_ALL'); ?></option>
      <option value="2"><?php echo JText::_('VJEECDCM_REQUEST_PROCESS_SUBMITTED'); ?></option>
      <option value="3"><?php echo JText::_('VJEECDCM_REQUEST_PROCESS_SCREENING'); ?></option>
      <option value="1"><?php echo JText::_('VJEECDCM_REQUEST_PROCESS_SENT'); ?></option>
      <option value="5"><?php echo JText::_('VJEECDCM_REQUEST_PROCESS_RECEIVED_BY_JALSA'); ?></option>
      <option value="6"><?php echo JText::_('VJEECDCM_REQUEST_PROCESS_RECEIVED_BY_SCHOOL'); ?></option>
  </select>
</div>


<div id="req-view-layout">
  <div id="dir-pane" style="display: inline-block;">
     <table id="dir-table">
        <tbody>
	  <tr data-tt-id="0" dirId="0">
	    <td> 
	    <!--
	    <a href="<?php echo JRoute::_('index.php?option=com_vjeecdcm&view=emplrequest');?>"> 
	      <?php echo JText::_('VJEECDCM_RV_SDMN_ROOT'); ?></a>
	    -->
	      <?php echo JText::_('VJEECDCM_RV_SDMN_ROOT'); ?>
	    </td>
	  </tr>
	  <tr data-tt-id="2" data-tt-parent-id="0" dirId="2">
            <td>
	      <!--
	      <a href="<?php echo JRoute::_('index.php?option=com_vjeecdcm&view=emplrequest&dirId=2'); ?>">
	      <?php echo JText::_('VJEECDCM_RV_SDMN_RECEIVED'); ?></a>
	      -->
	      <?php echo JText::_('VJEECDCM_RV_SDMN_RECEIVED'); ?>
	    </td>
          </tr>
        <tr data-tt-id="3" data-tt-parent-id="0" dirId="3">
          <td>
	    <!--
	    <a href="<?php echo JRoute::_('index.php?option=com_vjeecdcm&view=emplrequest&dirId=3'); ?>">
	    <?php echo JText::_('VJEECDCM_RV_SDMN_PROCESSING'); ?></a>
	    -->
	    <?php echo JText::_('VJEECDCM_RV_SDMN_PROCESSING'); ?>
	  </td>
        </tr>
	<tr data-tt-id="1" data-tt-parent-id="0" dirId="1">
            <td>
	      <!--
	      <a href="<?php echo JRoute::_('index.php?option=com_vjeecdcm&view=emplrequest&dirId=1'); ?>">
	      <?php echo JText::_('VJEECDCM_RV_SDMN_ARCHIVED'); ?></a>
	      -->
	      <?php echo JText::_('VJEECDCM_RV_SDMN_ARCHIVED'); ?>
	    </td>
	</tr>
        <tr data-tt-id="4" dirId="-1">
          <td>
	    <!--
	    <a href="<?php //echo JRoute::_('index.php?option=com_vjeecdcm&view=emplrequest&dirId=-1'); ?>">
	    <?php echo JText::_('VJEECDCM_RV_SDMN_TRASHED'); ?></a>
	    -->
	    <?php echo JText::_('VJEECDCM_RV_SDMN_TRASHED'); ?>
	  </td>
        </tr>
      </tbody></table>
  </div>
  
  <div id="table-pane" style="display: inline-block;">
    <form name="requestTableForm"
	  id="reqTabForm"
	  action="?task=employee.requestViewFormHandler"
	  method="post">
      <input type="hidden" name="buttonClicked" id="buttonClicked" value="">
      <input type="hidden" name="directorySelected" id="directorySelected" value="<?php echo $this->dirId;?>">
      <input type="hidden" name="nextUpdatingStep" id="nextUpdatingStep" value="">
      <input type="hidden" name="stepId" id="stepId" value="0">
      <table id="request-table">
      <?php
	  //$user = JFactory::getUser();
	  $this->echoRequestTable(); 
      ?>
      </table>
    </form>

  </div>
  
  
  
</div>




<div id="req-view-cert-dlg" class="detail-dlg">
  <form name="certGenForm" id="cert-gen-form" 
  action="index.php?option=com_vjeecdcm&task=employee.certGenFormHandler">
  <div id="certGenForm-content">
    <div id="info-input" class="info-input">
    <label>
      <?php echo JText::_('Date: '); ?> 
    </label>
    <input type="date" name="certDate" id="certDate">
    <label>
      <?php echo JText::_('Reference No: '); ?> 
    </label>
    <span id="certRef" class="editable"><input name="certRef" value=""></span>
    
      
    <table id="cert-info-table" style="margin-top: 20px;">
      <thead>
      <tr>
	<th colspan=2>Left column</th>
	<th colspan=2>Right column</th>
      </tr>
      <tr>
	<th>Label</th>
	<th>Information</th>
	<th>Label</th>
	<th>Information</th>
      </tr>	      
      </thead>
      <tbody>
	<tr>
	  <td>Name: </td><td id="holderName"><input type="text" name="holderName" value="Nguyen Van A"></td>
	  <td>Gender: </td><td id="holderGender">
	    <select name="holderGender">
	      <option value="0">Male</option>
	      <option value="1">Female</option>
	    </select>
	  </td>
	</tr>
	<tr>
	  <td>Date of birth: </td><td id="holderBirthday"><input type="date" name="holderBirthday" value="February 30, 0000"></td>
	  <td>ID No: </td><td id="holderId"><input type="text" name="holderId" value=""></td>
	</tr>
	<?php
	for ($i = 0; $i < 4; $i++)
	{
	  $id1 = 2 * $i;
	  $id2 = 2 * $i + 1;
	  echo '<tr><td>&nbsp;</td><td id="info' . $id1 . '"> </td><td> </td><td id="info' . $id2 . '"> </td> </tr>';
	}
	?>
      </tbody>
    </table>
     <div id="preview-output"></div>
    </div>
    <div id="preview" class="preview">
      <iframe id='cert-pdf' style="width:100%;height:500px;" src="media/media/certificates/mau-2.pdf"></iframe>>  
    </div>
  </div>
  <input type="submit" value="Preview" >
  <input type="submit" value="Generate" >  
  </form>
</div>




<div id="req-detail-dlg" class="detail-dlg">  
  <div id="req-content" class="info-input">
    <div class="detail-sec" id="req-detail-sec-1">
    <h3>Thong tin </h3>
      <div>
	<p><label>ID: </label><span id='req-detail-id'></span></p>
	<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_REQUEST_REQUESTER' ); ?></label><span id='req-detail-requester'></span></p>
	<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_REQUEST_CREATED_DATE' ); ?></label><span id='req-detail-created-date'></span></p>
	<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_REQUEST_ROUTE' ); ?></label><span id='req-detail-target-school' class="editable-list" data-pk="0"></span><br></p>
	<p><label><?php echo JText::_( 'Phuong thuc gui' ); ?></label><span id='req-detail-type' class="editable-list" data-pk="0"></span></p>
      </div>
    </div>
    
    <div class="detail-sec" id="req-detail-sec-2">
    <h3>Ho so </h3>
      <div>
	<p><label>ID: </label><span id='req-detail-dplm-id'></span></p>
	<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ); ?></label><span id='req-detail-diploma' class="editable-list" data-pk="0"></span><br></p>
	<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_TITLE' ); ?></label><span id='req-detail-holder' class="editable-txt" data-pk="0"></span><br></p>
	<p><label id="req-detail-holder-info-label-1"></label>
	  <span id="req-detail-holder-info-1" class="editable-info-txt" infoID='' data-pk="0"></span>
	  <label id="req-detail-holder-info-label-4"></label>
	  <span id="req-detail-holder-info-4" class="editable-info-date" infoID='' data-pk="0"></span></p>
	<p><label id="req-detail-holder-info-label-2"></label>
	  <span id="req-detail-holder-info-2" class="editable-info-txt" infoID='' data-pk="0"></span></p>
	<p><label id="req-detail-holder-info-label-3"></label>
	  <span id="req-detail-holder-info-3" class="editable-info-txt" infoID='' data-pk="0"></span></p>	
      </div>
    </div>
      
    <div class="detail-sec" id="req-detail-sec-3">
    <h3>Qua trinh xu ly</h3>
      <div>
	<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_REQUEST_PROCESS_TITLE' ); ?></label><span id='req-detail-process'></span></p>
	<table id='req-detail-proc-history'>
	  
	</table>
      </div>
    </div>
  </div>
  <div id="req-elec-doc" class="preview">
      <iframe id='elec-doc' style="width:100%;height:680px;" src=""></iframe>>  
  </div>
  
</div>


<!--
</div>

</div>
-->
