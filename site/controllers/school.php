<?php
 
// No direct access.
defined('_JEXEC') or die;
 
// Include dependancy of the main controllerform class
jimport('joomla.application.component.controllerform');

class VjeecDcmControllerSchool extends JControllerLegacy
{
 
    public function confirmsRecievedAllRequests()
    {
    
        $app    = JFactory::getApplication();  
        //$reqId = $app->input->get('reqId', NULL, 'INT');
        //$procStepId = $app->input->get('procStepId', NULL, 'INT');
    
        $model  = $this->getModel('request'); 
        ////dump($model, "updateAllRequests");
        $model->updateAllRequestsAsReceived();
        //$newProcId = $model->takeActionOnRequest($reqId, $procStepId);
        
        //$this->displayView(array('view' => 'cooprequest', 'format' => 'html'));
        $this->setRedirect(JRoute::_('index.php?option=com_vjeecdcm&view=cooprequest' ));
    }
     
    /////////////////////////////////////////////////////////////////////////////
    
    public function requestViewFormHandler()
    {
        ////dump($_POST, 'Controller::schoolRequestFormHandler');
        if ($_POST['buttonClicked'] == 'confirmSel')
        {
            // Get the data from the form POST
            if(!empty($_POST['check']))
            {
                $model = $this->getModel('request');
                $model->schoolConfirmRequests($_POST['check']);
            }    
        }
        elseif ($_POST['buttonClicked'] == 'confirmAll')
        {
            $model  = $this->getModel('request'); 
            ////dump($model, "updateAllRequests");
            $model->updateAllRequestsAsReceived();           
        }
        $this->setRedirect(JRoute::_('index.php?option=com_vjeecdcm&view=cooprequest' ));
        //$this->displayView(array('view' => 'cooprequest', 'format' => 'html')); 
    }
    
    /////////////////////////////////////////////////////////////////////////////
 
    public function prepareRequestDataForSchools(&$requests, $schoolUID)
    {
        $model = $this->getModel('request');
        foreach ($requests as $rq)
        {
            $rq->requester_name = JFactory::getUser($rq->user_id)->get('name');
            $rq->processing_history = $model->getProcessingHistory($rq->request_id);
            $rq->target_school_name =  JFactory::getUser($rq->school_user_id)->get('name');
            $rq->code = $model->createCode($rq);
            $rq->route = $model->createSimpleRoute($rq);
            $rq->name = JText::_($rq->name);
            $rq->processing_status = null;
            $rq->last_update = null;
            $rq->processing_status_id = null;
            foreach ($rq->processing_history as $step)
            {
                if ($step->id != $rq->current_processing_step)
                    continue;
                $rq->processing_status = JText::_($step->name);
                $rq->last_update = $step->begin_date;
                $rq->processing_status_id = $step->name_id;
                break;
            }
	
            $rq->enable_confirm_by_user = $model->canSchoolConfirmRequest($rq, $schoolUID);    
        }
    }
  
  
    ///////////////////////////////////////////
    
    public function getRequestData()
    {
        ////dump($_POST, 'Ajax post request from datatables');
        $offset = intval($_POST['start']);
        $draw = intval($_POST['draw']);
        $limit = intval($_POST['length']);
        $stepId = intval($_POST['step_id']);
        $searchValue = $_POST['search']['value'];
        $model =& JModelLegacy::getInstance('request', 'VjeecDcmModel');
        $userId = JFactory::getUser()->get('id');
        $requests = $model->getObservingRequests($userId, $stepId, $offset, $limit, $searchValue);
        $this->prepareRequestDataForSchools($requests, $userId);
        //$requests = $model->getObservingRequests(604); 
        $nbRows = $model->getNumberObservingRequests($userId);
        $jsonData = json_encode(array('draw' => $draw,
                                      'recordsTotal' => $nbRows,
                                      'recordsFiltered' => $nbRows,
                                      'data' => $requests));
        echo $jsonData;
        jexit();
    }
  
}
