<?php
 
// No direct access.
defined('_JEXEC') or die;
 
// Include dependancy of the main controllerform class
jimport('joomla.application.component.controllerform');

class VjeecDcmControllerReqEmployee extends JControllerForm
{
    public function getRequests()
    {
        JSession::checkToken() or die ('Invalid token');
        $reqModel = $this->getModel('emplreqlist');
        $reqs = $reqModel->getRequestsOfStep($_POST["step"]);
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
