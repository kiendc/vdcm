<?php
 
// No direct access.
defined('_JEXEC') or die;
 
// Include dependancy of the main controllerform class
jimport('joomla.application.component.controllerform');

class VjeecDcmControlleremployee extends JControllerForm
{
 
    public function acceptsSubmittedRequest() {
        $app    = JFactory::getApplication(); 
        $reqId = $app->input->get('reqId', NULL, 'INT');
        $stepId = $app->input->get('stepId', NULL, 'INT');
        $model  = $this->getModel('requests'); 
        $model->acceptSubmittedReq($reqId, $stepId);
        $this->setRedirect(JRoute::_('index.php?option=com_vjeecdcm&view=emplrequest' ));
    }
  
    public function updateProcess() {
        $app    = JFactory::getApplication(); 
        $reqId = $app->input->get('reqId', NULL, 'INT');
        $stepId = $app->input->get('stepId', NULL, 'INT');
        $model  = $this->getModel('requests');
        if ($stepId == 2) {
            $model->acceptSubmittedReq($reqId, $stepId);    
        } else {
             $model->updateReqProcStep($reqId, $stepId);     
        }
        $this->setRedirect(JRoute::_('index.php?option=com_vjeecdcm&view=emplrequest' ));
    }
  
    public function requestViewFormHandler() {
       
        $dirId = $_POST['directorySelected'];
        $redirectURL = 'index.php?option=com_vjeecdcm&view=emplrequest&dirId=' . $dirId; 
        if(!empty($_POST['check'])) {
            $model = $this->getModel('requests');
            switch ($_POST['buttonClicked']) {
                case 'moveSel':
                    $model->moveRequestsToDirectory($_POST['check'], $dirId);
                    break;
                case 'updateSel':
                    if ($_POST['nextUpdatingStep'] == 0) {
                        $model->updateRequestsStep($_POST['check'], $_POST['steps']);
                    } else {
                        $model->updateRequestsProcToStep($_POST['check'], $_POST['steps'], $_POST['nextUpdatingStep']);
                    }
                    break;
            }
        }
        $this->setRedirect(JRoute::_($redirectURL, false ));
    }
  
    public function requestCorrectionFormHandler() {
        
        if ($_POST['buttonClicked'] == 'deleteSel') {
            // Get the data from the form POST
            if(!empty($_POST['check'])) {
                $model = $this->getModel('requests');
                $model->removeRequests($_POST['check']);
            }    
        } elseif ($_POST['buttonClicked'] == 'updateSel') {
            if(!empty($_POST['check'])) {
                $model = $this->getModel('requests');
                $model->updateRequestsTargetSchool($_POST['check'], $_POST['target_school_id']);
            }
        }
        $this->setRedirect(JRoute::_('index.php?option=com_vjeecdcm&view=emplrequest' ));
    }
    
    public function addSchoolFormHandler() {
        $model = $this->getModel('school');
        $model->addSchool($_POST);
        $this->setRedirect('index.php?option=com_vjeecdcm&view=schools');
    }

    public function certGenFormHandler() {
        
        $fp = fopen("tmp/certInfo.txt", "w");
        fwrite($fp, json_encode($_POST));
        fclose($fp);
        $oldDir = getcwd();
        chdir('tmp');
        $wrkDir = getcwd();
        $execUser = exec('whoami');
        $output = exec('which pdflatex');
        $path = exec('echo $PATH');
        $path2 = shell_exec('echo $PATH');
        $shell =exec('echo $0');
        $fp = fopen("exhibitInfo.txt", "w");
        fwrite($fp, json_encode(array('oldir' => $oldDir,
                                      'wrkDir' => $wrkDir,
                                      'user' => $execUser,
                                      'output' => $output,
                                      'path' => $path,
                                      'path2' => $path2,
                                      'shell' => $shell)));
        fclose($fp);
        chdir($oldDir);
        echo json_encode(array('output' => $output));
        jexit();
    }
    
    public function getSchoolList() {
        $model = $this->getModel('school');
        $schools = $model->getAllJALSASchools();
        $arrSchools = array();
        foreach ($schools as $schl) {
            $arrSchools[$schl->id] = $schl->name;       
        }
        echo json_encode($arrSchools);
        jexit();
    }
  
    public function getSelect2SchoolList() {
        $model = $this->getModel('school');
        $schools = $model->getAllJALSASchools($_GET['query']);
        $arrSchools = array();
        foreach ($schools as $schl) {
            $arrSchools[] = (object) array('id' => $schl->id, 'text' => $schl->name);       
        }
        echo json_encode($arrSchools);
        //echo json_encode(array("results" => $arrSchools));
        jexit();
    }
    
    public function getSelect2ExhibitList() {
        $model = $this->getModel('diploma');
        $exhibits = $model->getDiplomaDegrees();
        $arrExhibits = array();
        foreach ($exhibits as $exhb) {
            $arrExhibits[] = (object) array('id' => $exhb->id, 'text' => JText::_($exhb->name));
        }
        echo json_encode($arrExhibits);
        jexit();
    }
    
    public function modifyTargetSchool() { 
    	
        $model = $this->getModel('requests');
        $reqId = intval($_POST['pk']);
        $schoolId = intval($_POST['value']);
        
        $result = $model->modifyTargetSchool($reqId, $schoolId);
        if ($result != NULL) { 
            echo json_encode(array('success' => true, 'result' => $result));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Error in updating database'));    
        }
        jexit();
    }
    
    public function modifyAssignee() { 
    	$reqId = intval($_POST['pk']);
    	$type = $_POST['type'];
    	$userId = intval($_POST['value']);
    	$model = $this->getModel('requests');
    	$ret = $model->updateAssignee($reqId, $type, $userId);
    	if ($ret) { 
    		echo json_encode(array('success' => true, 'result' => $ret ));
    	} else { 
    		echo json_encode(array('success' => false, 'message' => 'Error in updating database')); 
    	}
    	jexit();
    }
    
    public function getSelectEmployees() {
    	$model = $this->getModel('requests');
        $ret = $model->getListEmployee();
        $list = array();
        if (count($ret)) {
	        foreach ($ret as $item) {
	            $list[] = (object) array('id' => $item->id, 'text' => $item->name);       
	        }
        }
        echo json_encode($list);
        jexit();
    }
    
    public function editRequestDetail() { 
        $model = $this->getModel('requests');
        $reqId = intval($_POST['pk']);
        $result = new stdClass();
        switch ($_POST['name']) {
            case "req-detail-type":
                $reqType = intval($_POST['value']);
                $result->retrn_object = $model->modifyRequestType($reqId, $reqType);
                break;
            case 'req-detail-target-school':
                $schlId = intval($_POST['value']);
                $result->retrn_object = $model->modifyTargetSchool($reqId, $schlId);
                break;
           }
        $result->success = true;
        $result->msg = 'Nothing';
        echo json_encode($result);
        jexit();
    }
    
    public function editDiplomaDetail() {
        $model = $this->getModel('diploma');
        $dplmId = intval($_POST['pk']);
        $result = new stdClass();
        switch ($_POST['name']) {
            case 'req-detail-diploma' :
                $degreeId = intval($_POST['value']);
                $model->modifyDegree($dplmId, $degreeId);
                break;
            case 'req-detail-holder' :
                $holderName = $_POST['value'];
                $model->modifyHolderName($dplmId, $holderName);
                break;
            
        }
        $result->success = true;
        $result->msg = 'Nothing';
        echo json_encode($result);
        jexit();
    }
    
    public function editDiplomaHolderInfo() {
        $model = $this->getModel('diploma');
        $infoId = intval($_POST['pk']);
        $dplmId = intval($_POST['dplmId']);
        $infoVal = $_POST['value'];
        $result = new stdClass();
        $model->modifyHolderInfo($dplmId, $infoId, $infoVal);
        $result->success = true;
        $result->msg = 'Nothing';
        echo json_encode($result);
        jexit();
    }
    
    
    public function prepareRequestDataForEmployees(&$requests) {
        $attModel = $this->getModel('attachment');
        $holderModel = $this->getModel('holder');
        $model = $this->getModel('requests');
        foreach ($requests as $rq) {
            $rq->requester_name = JFactory::getUser($rq->user_id)->get('name');
            $rq->target_school_name =  JFactory::getUser($rq->school_user_id)->get('name');
            $rq->code = $model->createCode($rq);
            $rq->route = new stdClass();
            
            $rq->route->type = $rq->request_type;
            $rq->route->school_id = $rq->target_school_id;
            
            
            if ($rq->request_type == 0) {
                $rq->route->full_name =  JFactory::getUser(604)->get('username') . ' / ' . $rq->target_school_name; // JaLSA
            } else if ($rq->request_type == 1) {
                $rq->route->full_name = $rq->target_school_name;
            }
            
            $rq->elec_doc = $attModel->getFileInfo($rq->attachment_id);
            $rq->elec_doc->path = 'http://vjeec.vn/portal/' . $rq->elec_doc->path;
            $rq->elec_doc->name = JText::_($rq->degree_name);
            
            
            $rq->holder = new stdClass();
            $rq->holder->name = $rq->holder_name;
            $rq->holder->info = $holderModel->getInfo($rq->diploma_id);
            foreach ($rq->holder->info as $item) { die('test');
                $item->name = JText::_($item->name);
            }
            
            $rq->state = new stdClass();
            $rq->state->step_id = $rq->proc_step_id;
            $rq->state->name = JText::_($rq->name);

            $rq->context_info = new stdClass();
            $rq->context_info->req_id = $rq->request_id;
            $rq->context_info->step_id = $rq->proc_step_id;
        }
    }
    
    public function getJSONRequests() { 
        $offset = intval($_POST['start']);
        $draw = intval($_POST['draw']);
        $limit = intval($_POST['length']);
        $searchValue = $_POST['search']['value'];
        $dirId = intval($_POST['dir_id']);
        $stepId = intval($_POST['step_id']);
        $model = $this->getModel('requests');
        $requests = $model->getRequestsInDirectory($dirId, $stepId, $offset, $limit, $searchValue);
        $this->prepareRequestDataForEmployees($requests);
        $jsonData = json_encode(array('draw' => $draw,
                                      'recordsTotal' => 1000,
                                      'recordsFiltered' => 1000,
                                      'data' => $requests));
        echo $jsonData;
        jexit();
    }

    public function getRequestDetail() { 
        $reqId = intval(JRequest::getVar('req_id', 0));
        
        $model = $this->getModel('requests');
        $dplmModel = $this->getModel('diploma');
        $attModel = $this->getModel('attachment');
        $hldrModel = $this->getModel('holder');
        $schlModel = $this->getModel('school');
        $rq = $model->getRequestDetail($reqId);
       
        if ($rq != NULL) { 
            $rq->dplm = $dplmModel->getDiplomaDetail($rq->diploma_id);
            $rq->holderInfo = $hldrModel->getInfo($rq->diploma_id);
            foreach ($rq->holderInfo as $item) {
                $item->name = JText::_($item->name);
            }
            
            $baseUrl = JURI::base();
            $rq->elec_doc = $attModel->getFileInfo($rq->dplm->attachment_id);
            $rq->elec_doc->path = $baseUrl . '../' . $rq->elec_doc->path;
            $rq->elec_doc->name = JText::_($rq->degree_name);
            
            $rq->targetSchool = $schlModel->getSchoolDetail($rq->target_school_id);
            $rq->procHistory = $model->getProcessingHistory($rq->id);
        }
        
        echo json_encode($rq);
        jexit();
    }
}