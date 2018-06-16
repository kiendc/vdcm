<div id="req-adding-dlg" class="modal fade" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        <h4 class="modal-title" id="gridSystemModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
        <form class="form-inline">
          <div class="detail-sec" id="req-detail-sec-1">
            <h3><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION' ); ?></h3>
                <input type="hidden" id="req-detail-dplm-id" value="0"/>
                <p><label><?php echo JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ); ?></label>
                <a href="#" id='req-detail-diploma'></a></p>
                <p><label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_SCHOOL' ); ?></label>
                <a href="#" id="req-detail-target-school" data-type="text" data-pk="1" data-placement="right" data-placeholder="Required" data-title="Enter your firstname"></a>
                </p>
            <p>
            <label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_TYPE' ); ?></label>
            <a href="#" id="req-detail-type"></a>
            </p>
          </div>
          <div class="detail-sec" id="req-detail-sec-2">
            <h3><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT' ); ?></h3>
            <div>
              <p><label><?php echo JText::_( 'VJEECDCM_RV_CT_DIPLOMA_HOLDER_TITLE' ); ?></label>
              <a href="#" class="dplm-editable editable-txt" id='req-detail-holder'></a></p>
              <p><label><?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_ID_NUMBER' ); ?></label>
              <a href="#" class="dplm-editable editable-txt" id="req-detail-holder-info-1"></a>
              <label><?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_BIRTHDAY' ); ?></label>
              <a href="#" id="req-detail-holder-info-4"></a></p>
              <p><label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT_TELEPHONE' ); ?></label>
              <a href="#" class='dplm-editable editable-txt' id="req-detail-holder-info-2"></a></p>
              <p><label><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT_ADDRESS' ); ?></label>
              <a href="#" class='dplm-editable' id="req-detail-holder-info-3"></a></p>
            </div>
          </div>
          <input type="hidden" id="upload-path" value=""/>
				<?php echo JHtml::_('form.token'); ?>
        </form>
      </div>
      <div class="modal-footer">
        <button type="reset" class="btn btn-default" style="float:right;">
          <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_BUTTON_RESET'); ?>
        </button>
        <button type="submit" class="btn btn-default" id='create-req-btn' value="confirm" style="float:right;">
          <?php echo JText::_('VJEECDCM_REQUEST_ADD_FORM_BUTTON_CREATE'); ?>
        </button>
      </div>
			
    </div>
  </div>
</div>
  

