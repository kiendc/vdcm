<?php
 
// No direct access.
defined('_JEXEC') or die;

// Include dependancy of the main controllerform class
jimport('joomla.application.component.controllerform');
jimport('joomla.log.log');
class VjeecDcmControllerReqEmployee extends JControllerForm
{
    public function getRequests()
    {
        JSession::checkToken() or die ('Invalid token');
        JLog::addLoger(
                       array('text_file' => 'vjeecdcm.log',
                             'text_entry_format' => '{DATETIME} {CLIENTIP} {USER} {COMMAND} {MESSAGE}'),
                       JLog::ALL,
                       array('vjeecdcm')
                       );
        $reqModel = $this->getModel('emplreqlist');
        $reqs = $reqModel->getRequestsOfStep($_POST["step"]);
        $logEntry = new JLogEntry('Test logging', JLog::INFO, 'vjeecdcm');
        $logEntry->command = 'reqemployee.getRequests';
        $logEntry->user = JFactory::getUser()->username;
        JLog::add($logEntry);
        
        foreach ($reqs as $r)
        {
            $r->degree_name = JText::_($r->degree_name);
            $r->name = JText::_($r->name);
        }
        $table = array( array (1, 2, 3, 4, 5, 6), array (7, 8, 9, 10, 12, 12));
        $data = array("draw" => 1, "recordsTotal" => 2, "requests" => $reqs, "data" => $table, "postdata" => $_POST);
        echo json_encode($data);
        jexit();
    }
}
