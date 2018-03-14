<div id="req-adding-dlg" class="detail-dlg">
  	<div id="req-content" class="info-input">
    	<form name="req-adding-form" id="req-adding-form" method="post" >
    		<div class="detail-sec" id="req-detail-sec-1">
    			<h3>Thông tin</h3>
      			<div>
					<p>
          				<label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_SCHOOL' ); ?></label>
          				<span class='req-editable' id='req-detail-target-school-create'></span></p>
						<p><label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_TYPE' ); ?></label>
          				<span class="req-editable" id="req-detail-type-create"></span>
        			</p>
      			</div>
    		</div>
    
    		<div class="detail-sec" id="req-detail-sec-2">
    			<h3>Hồ sơ</h3>
      			<div>
        			<input type="hidden" id="req-detail-dplm-id" value="0"/>
					<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ); ?></label>
          			<span class='dplm-editable' id='req-detail-diploma-create'></span></p>
					<p><label><?php echo JText::_( 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_TITLE' ); ?></label>
          			<span class="dplm-editable editable-txt" id='req-detail-holder-create'></span></p>
					<p>
          				<label><?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_ID_NUMBER' ); ?></label>
          				<span class="dplm-editable editable-txt" id="req-detail-holder-info-1-create"></span>
	  					<label><?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_BIRTHDAY' ); ?></label>
          				<span class='dplm-editable' id="req-detail-holder-info-4-create"></span>
        			</p>
        			<p>
						<label><?php echo JText::_( 'Giới tính' ); ?></label>
          				<span class='dplm-editable' id="req-detail-holder-gender-create"></span>
        			</p>
					<p>
						<label><?php echo JText::_( 'Số điện thoại học sinh' ); ?></label>
          				<span class='dplm-editable editable-txt' id="req-detail-holder-info-2-create"></span>
        			</p>
  					<p>
          				<label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT_ADDRESS' ).' học sinh'; ?></label>
          				<span class='dplm-editable' id="req-detail-holder-info-3-create"></span>
        			</p>
        		</div>
      		</div>
      		<input type="hidden" id="upload-path" />
      		<div>
        		<button type="reset" class="button" style="float:right;">
          			<?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_BUTTON_RESET'); ?>
        		</button>
        		<button type="submit" class="button" name="action" id='create-req-btn' value="confirm" style="float:right;">
          			<?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_BUTTON_CREATE'); ?>
        		</button>
      		</div>
      		<?php echo JHtml::_('form.token'); ?>
    	</form>
  	</div>
  
  	<div id="req-elec-doc-create" class="preview">
    	<form id="upload-form" method="POST" enctype="multipart/form-data" target="upload_target" >
    		File: <input name="upload-file" type="file" />
          	<input type="submit" id="uploadFilePdf" name="submitBtn" value="<?php echo JText::_('VJEECDCM_DIPLOMA_ADD_FORM_FS_ATTACHMENTS');?>" />
    	</form>
 
    	<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
    	<iframe id='elec-doc-create' style="width: 98%;height:640px;" src="javascript:false;"></iframe>  
  	</div>
</div>
