<div id="req-adding-dlg" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
        <h4 class="modal-title"><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FRAME_TITLE' ); ?></h4>
      </div>
      <div class="modal-body">
        <div class="container col-md-12">
        <div class="row">
            <div class="col">
                <form>
                    <h3><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION' ); ?></h3>
                    <div class="form-group row">
                        <label for="req-detail-diploma" class="col-sm-2 col-form-label">
                            <?php echo JText::_( 'VJEECDCM_RV_CT_DIPLOMA_TITLE' ); ?>
                        </label>
                        <div class="col-sm-8">
                            <select class="form-control input-sm" id="req-detail-diploma" data-live-search="true"></select>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="req-detail-target-school" class="col-sm-2 col-form-label" data-live-search="true">
                            <?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_SCHOOL' ); ?>
                        </label>
                        <div class="col-sm-8">
                            <select class="form-control input-sm" id="req-detail-target-school"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="req-detail-holder" class="col-sm-2 col-form-label">
                            <?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_INFORMATION_TYPE' ); ?>
                        </label>
                        <div class="form-check-inline col-sm-3">
                            <input class="form-check-input" type="radio" name="request-type" id="req-detail-type-1" value="option1"/>
                            <label class="form-check-label" for="req-detail-type-1">
                                <?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_INFORMATION_SEND_TO_JALSA' ); ?>
                            </label>
                        </div>
                        <div class="form-check-inline col-sm-3">
                            <input class="form-check-input" type="radio" name="request-type" id="req-detail-type-2" value="option2"/>
                            <label class="form-check-label" for="req-detail-type-2">
                                <?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_INFORMATION_SEND_TO_SCHOOL' ); ?>
                            </label>
                        </div>
                    </div>
                    <h3><?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT' ); ?></h3>
                    <div class="form-group row">
                        <label for="req-detail-holder" class="col-sm-2 col-form-label">
                            <?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_NAME' ); ?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="req-detail-holder" placeholder="Ho va ten"/>
                        </div>
                        <label for="req-detail-gender" class="col-sm-2 col-form-label">
                            <?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_GENDER' ); ?>
                        </label>
                        <div class="col-sm-2">
                            <select class="form-control" id="req-detail-gender" style="width:100%">
                                <option selected>Chon</option>
                                <option value="1">
                                    <?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_GENDER_MALE' ); ?>
                                </option>
                                <option value="2">
                                    <?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_GENDER_FEMALE' ); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="req-detail-holder-info-4" class="col-sm-2 col-form-label">
                            <?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_BIRTHDAY' ); ?>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="req-detail-holder-info-4" placeholder="Ngay sinh"/>
                        </div>
                        <label for="req-detail-holder-info-1" class="col-sm-2 col-form-label">
                            <?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_HOLDER_ID_NUMBER' ); ?>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="req-detail-holder-info-1" placeholder="So CMT"/>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="req-detail-holder-info-2" class="col-sm-2 col-form-label">
                            <?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT_TELEPHONE' ); ?>
                        </label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="req-detail-holder-info-2" placeholder="So dien thoai"/>
                        </div>
                        <label for="req-detail-holder-info-3" class="col-sm-2 col-form-label">
                            <?php echo JText::_( 'VJEECDCM_REQUEST_ADD_FORM_FS_CONTACT_ADDRESS' ); ?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="req-detail-holder-info-3" placeholder="Dia chi"/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="input-group" name="AttachedFile">
                            <label class="form-control" id="upload-file-info">
                                <?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_FILE_NAME' ); ?>
                            </label>
                            <span class="input-group-btn">
                                <label class="btn btn-default" for="my-file-selector">
                                    <input id="my-file-selector" type="file" style="display:none;"
                                    onchange="onUploadFileChange(this.files)"/>
                                    <?php echo JText::_( 'VJEECDCM_DIPLOMA_ADD_FORM_FS_FILE_NAME' ); ?>
                                </label></span>
                        </div>
                    </div>
                    <?php echo JHtml::_('form.token'); ?>
                </form>
            </div>
            <div id="file-preview" class="col">
                <embed src="../../vdcm/test.pdf" frameborder="0" width="100%" height="400px"/>
            </div>
        </div>
        </div>
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
  

