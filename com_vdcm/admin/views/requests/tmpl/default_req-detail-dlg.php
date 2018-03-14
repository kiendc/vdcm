
<div id="req-detail-dlg" class="detail-dlg" style="display: none;">  
  	<div id="req-content" class="info-input">
	    <div class="detail-sec" id="req-detail-sec-1">
	    	<h3>Thông tin</h3>
      		<div>
				<p><label>ID: </label><span id='req-detail-id'></span></p>
				<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_REQUEST_REQUESTER' ); ?></label><span id='req-detail-requester'></span></p>
				<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_REQUEST_CREATED_DATE' ); ?></label><span id='req-detail-created-date'></span></p>
				<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_REQUEST_ROUTE' ); ?></label><span id='req-detail-target-school' class="editable-list" data-pk="0"></span><br></p>
				<p><label><?php echo JText::_( 'VJEEC_REQUEST_TYPE_METHOD' ); ?></label><span id='req-detail-type' class="editable-list" data-pk="0"></span></p>
	      	</div>
    	</div>
	    <div class="detail-sec" id="req-detail-sec-2">
	    	<h3>Hồ sơ</h3>
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
	    	<h3>Quá trình xử lý</h3>
	      	<div>
				<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_REQUEST_PROCESS_TITLE' ); ?></label><span id='req-detail-process'></span></p>
					<table id='req-detail-proc-history'>
					</table>
		      	</div>
	    	</div>
  		</div>
	  	<div id="req-elec-doc" class="preview">
	  		<a title="<?php echo JText::_('VJEEC_REQUEST_DOWNLOAD_DIPLOMA');?>" style="display:none;" class="download_diploma" id="req_download_dip"><img src="<?php echo $this->baseurl . '/components/com_vjeecdcm/helpers/css/images/icon-16-download.png'; ?>" /></a>
	      	<iframe id="elec-doc" style="width:100%;height:680px;" src=""></iframe>  
  		</div>
</div>