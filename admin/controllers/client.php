<?php

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

class VjeecdcmControllerClient extends JControllerForm
{ 
	public function uploadFile() {
        jimport('joomla.filesystem.file');
        //Clean up filename to get rid of strange characters like spaces etc
        $fileInfo = $_FILES['upload-file'];
        $filename = JFile::makeSafe($fileInfo['name']);
        $src = $fileInfo['tmp_name'];
        $config         = JFactory::getConfig();
        $tmp_dir       = $config->get('tmp_path');
        $dest = $tmp_dir .'/'. $filename;
        $upldResult = new stdClass();
        $upldResult->result = JFile::upload($src, $dest);
        $upldResult->path = $filename;
        echo json_encode($upldResult);
        jexit();
    }
    
 	public function createRequest() {   
        $req = new stdClass();
        $req->diploma_id = $_POST['diploma_id'];
        $req->request_type = $_POST['req-detail-type-create'];
        $req->target_school_id = $_POST['req-detail-target-school-create'];
        $model  = $this->getModel('requests'); 
        $newReqId = $model->addRequestFromObj($req);
        echo json_encode($req);
        jexit();
    }
    
	public function createExhibit() {        
        $exhb = new stdClass();
        $session = JFactory::getSession();
        $sState = $session->getState();
        if (!($sState == 'active')) {
            $exhb->id = -1;
            $exhb->error = 1;
            echo json_encode($exhb);
            jexit();
        }
        
        $attModel = $this->getModel('attachment');
        $upldFile = $_POST['fileName'];
        $ret = $attModel->addAttachment($upldFile, true);
        if (isset($ret->error)) { 
        	$exhb->id = -1;
        	$exhb->msg = $ret->msg;
        	$exhb->error = 1;
        	echo json_encode($exhb);
            jexit();
        }
        
        $birthDay = $_POST['req-detail-holder-info-4-create'];
        $exhb->attachment_id = $ret;
        $exhb->degree_id = $_POST['req-detail-diploma-create'];
        $exhb->holder_name = $_POST['req-detail-holder-create'];
        $exhb->holder_birthday = date('Y-m-d', strtotime(str_replace('/', '-', $birthDay)));
        $exhb->gender = $_POST['req-detail-holder-gender-create'];
        $exhb->holder_identity_value = $_POST['req-detail-holder-info-1-create'];
        
        $model = $this->getModel('diplomas');
        $exhb->id = $model->addDiplomaFromObj($exhb);
        $model->registerDiplomaToUser($exhb->id);
        
        $holderModel = $this->getModel('holder'); 
        $holderModel->addHolderInfo($exhb->id, 1, $_POST['req-detail-holder-info-1-create']);
        $holderModel->addHolderInfo($exhb->id, 4, $birthDay);
        $holderModel->addHolderInfo($exhb->id, 2, $_POST['req-detail-holder-info-2-create']);
        $holderModel->addHolderInfo($exhb->id, 3, $_POST['req-detail-holder-info-3-create']);
        
        echo json_encode($exhb);
        jexit();
    }
	
}

?>