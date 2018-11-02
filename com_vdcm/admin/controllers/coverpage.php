<?php

// No direct access.
defined('_JEXEC') or die;

class VjeecdcmControllerCoverpage extends JControllerAdmin
{ 
	public function __construct($config = array()) { 
		parent::__construct($config);
	}
	
    public function createCode($rq)
    {
        $date = date_create($rq->created_date);
        return $rq->degree_code . $date->format('Ymd') . $rq->request_id;
    }
	
	public function JSONCoverPage() 
	{ 
		$cid = JRequest::getVar('cid', array(), '', 'array');
		$model = $this->getModel('requests');
		$list = $model->getRequestsInfo($cid);
		echo json_encode($list);
		jexit();
	}
	
	public function JSONExpectedDates()
	{
		$model = $this->getModel('coverpage');
		$today = JRequest::getVar('today', date('Y-m-d'));
        	$dates = $model->getExpectedDates($today);
        	$id = 0;
        	$datesChoices = array();
        	foreach ($dates as $d) {
            		$datesChoices[] = (object) array('id' => $id, 'text' => $d->expected_send_date); 
            		$id++;      
        	}
		echo json_encode((object)array('results' => $datesChoices));
		jexit();
	}
	public function JSONCoverPageByDate()
	{
		$expectedDate = JRequest::getVar('expectedDate', date('Y-m-d'));
		$model = $this->getModel('coverpage');
        $reqs = $model->getRequests($expectedDate);
        $cpItems = array();
        if ($reqs)
        {
            foreach ($reqs as $rq)
            {
                $cpItems[] = array("code" =>  $this->createCode($rq),
                                   "holder_name" => $rq->holder_name,
                                   "school_name" => $rq->school_name,
                                   "school_id" => $rq->school_id);
            }
        }
		echo json_encode($cpItems);
		jexit();
	}
}

?>
