<?php
 
// No direct access.
defined('_JEXEC') or die;
 
// Include dependancy of the main controllerform class
jimport('joomla.application.component.controllerform');

class VjeecDcmControllerClient extends JControllerForm
{
    public function addDiploma()
    {
         // Initialise variables.
        $app    = JFactory::getApplication();  
        // Get the data from the form POST
        $data = $app->input->getArray($_POST);
        $file = JRequest::getVar('attachment_file_name', null, 'files', 'array');
        ////dump($data, 'ControllerCRUD, addDiploma');
        ////dump($file, 'ControllerCRUD, addDiploma, upload file');
        $attModel = $this->getModel('attachment');
        $attId = $attModel->addAttachment($file);
        $newDplmId = -1;
        if ($attId != null)
        {
            $data['attachment_id'] = $attId;
    
            $model  = $this->getModel('diploma'); 
            // Now update the loaded data to the database via a function in the model
            $err = 0;
            $newDplmId  = $model->addDiploma($data);
    
            // check if ok and display appropriate message.  This can also have a redirect if desired.
            if ($newDplmId > 0) 
            {
                
                // Register the new created diploma with this user
                if (!($model->registerDiplomaToUser($newDplmId)))
                {
                    $err = 2;
                }
                else
                {
                    $holderModel = $this->getModel('holder'); 
                    $holderModel->addHolderInfo($newDplmId, 1, $data['holder_id_number']);
                    $holderModel->addHolderInfo($newDplmId, 4, $data['holder_birthday']);
                }
            }
            else 
            {
                $err = 1; 
            }
        }
        
        $this->setRedirect(JRoute::_('index.php?option=com_vjeecdcm&view=diploma&layout=finalizeadding&dplmId=' . $newDplmId ));
    }
    
    public function finalizeAddingDiploma()
    {
        switch ($_POST['choice']){
            case 'create-request':
                $this->setRedirect(JRoute::_('index.php?option=com_vjeecdcm&view=request&layout=add&dplmId=' . $_POST['newDplmId'] ));
                break;
            case 'create-diploma':
                $this->setRedirect(JRoute::_('index.php?option=com_vjeecdcm&view=diploma&layout=add')); 
                break;
            case 'view-diploma':
                //$this->displayView(array('view' => 'userdiploma', 'format' => 'html'));
                $this->setRedirect(JRoute::_('index.php?option=com_vjeecdcm&view=userdiploma')); 
                break;
        }
    }
    
    public function removeDiploma()
    {
        $app    = JFactory::getApplication();
    
        $usrDplmId = $app->input->get('usrDplmId', NULL, 'INT');
        ////dump($usrDplmId, 'crud controller, removeDiploma, usrDplmId');
        $model  = $this->getModel('diploma');
        $dplm = $model->getDiploma($usrDplmId);
        $model->removeDiploma($usrDplmId);
        if ($dplm->attachment_id != NULL)
        {
            $attModel = $this->getModel('attachment');
            $attModel->removeAttachment($dplm->attachment_id);
        }
        $this->setRedirect(JRoute::_('index.php?option=com_vjeecdcm&view=userdiploma'));            
    }
    
    public function addRequest()
    {
        $app    = JFactory::getApplication();  
        // Get the data from the form POST
        $data = $app->input->getArray($_POST);
        $model  = $this->getModel('request'); 
        $newReqId = $model->addRequest($data);
        $holderModel = $this->getModel('holder');
        $dplmId = $data['diploma_id'];
        $holderModel->addHolderInfo($dplmId, 2, $data['contact_telephone']);
        $holderModel->addHolderInfo($dplmId, 3, $data['contact_address']);
        $this->setRedirect(JRoute::_('index.php?option=com_vjeecdcm&view=userrequest')); 
    }
    
    public function uploadFile()
    {
        #//dump($_POST, 'client upload file, post');
        #//dump($_FILES, 'client upload file, files');
        jimport('joomla.filesystem.file');
        //Clean up filename to get rid of strange characters like spaces etc
        $fileInfo = $_FILES['upload-file'];
        $filename = JFile::makeSafe($fileInfo['name']);
        $src = $fileInfo['tmp_name'];
        $dest = 'tmp' . DIRECTORY_SEPARATOR . $filename;
        $upldResult = new stdClass();
        $upldResult->result = JFile::upload($src, $dest);
        $upldResult->path = $dest;
        ////dump($upldResult, 'client upload file, result');
        echo json_encode($upldResult);
        jexit();
    }
    
    public function createRequest()
    {
        //dump($_POST, 'client creates an empty request, post');
        $req = new stdClass();
        $req->diploma_id = $_POST['diploma_id'];
        $req->request_type = $_POST['req-detail-type'];
        $req->target_school_id = $_POST['req-detail-target-school'];
        $model  = $this->getModel('request'); 
        $newReqId = $model->addRequestFromObj($req);
        //dump($req, 'client created a request, req');
        echo json_encode($req);
        jexit();
    }
    
    public function createExhibit()
    {
        ////dump($_POST, 'client creates an empty exhibit, post');
        $exhb = new stdClass();
        $session = &JFactory::getSession();
        $sState = $session->getState();
        ////dump($sState, 'Client creates an exhibit, session state');
        if (!($sState == 'active')) {
            $exhb->id = -1;
            $exhb->error = 1;
            echo json_encode($exhb);
            jexit();
        }
        $attModel = $this->getModel('attachment');
        $upldFile = $_POST['fileName'];
        $exhb->attachment_id = $attModel->addAttachment($upldFile, true);
        $exhb->degree_id = $_POST['req-detail-diploma'];
        //          $exhb->issue_date = $data['issue_date'];
        $exhb->holder_name = $_POST['req-detail-holder'];
        $exhb->holder_birthday = $_POST['req-detail-holder-info-4'];
        $exhb->holder_identity_value = $_POST['req-detail-holder-info-1'];
        
        $model = $this->getModel('diploma');
        $exhb->id = $model->addDiplomaFromObj($exhb);
        $model->registerDiplomaToUser($exhb->id);
        ////dump($exhb, 'client is creating exhibit, exhb');
        
        
        $holderModel = $this->getModel('holder'); 
        $holderModel->addHolderInfo($exhb->id, 1, $_POST['req-detail-holder-info-1']);
        $holderModel->addHolderInfo($exhb->id, 4, $_POST['req-detail-holder-info-4']);
        $holderModel->addHolderInfo($exhb->id, 2, $_POST['req-detail-holder-info-2']);
        $holderModel->addHolderInfo($exhb->id, 3, $_POST['req-detail-holder-info-3']);
        
        ////dump($exhb, 'client creates an exhibit, exhb');
        echo json_encode($exhb);
        jexit();
    }
    
    public function getRequests()
    {
	JSession::checkToken() or die ('Invalid token');
	$reqModel = $this->getModel('request');
	$reqs = $reqModel->getCreatedRequests();
   	$table = array( array (1, 2, 3, 4, 5, 6), array (7, 8, 9, 10, 12, 12));
	$data = array("draw" => 1, "recordsTotal" => 2, "requests" => $reqs, "data" => $table, "postdata" => $_POST);
        echo json_encode($data);
        jexit();
    }  
}
