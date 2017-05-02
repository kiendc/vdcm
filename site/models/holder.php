<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');
 
class VjeecDcmModelHolder extends JModelItem
{
    public function addHolderInfo($dplmId, $infoId, $value)
    {
        $db = JFactory::getDBO();
        $holderInfo = new stdClass();
        $holderInfo->id = NULL;
        $holderInfo->diploma_id = $dplmId;
        $holderInfo->info_id = $infoId;
        $holderInfo->value = $value;
        $result = $db->insertObject('#__vjeecdcm_holder_info', $holderInfo, 'id');
    }

    public function getInfo($dplmId)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select(array('a.info_id', 'a.value', 'b.name'))
          ->from('#__vjeecdcm_holder_info AS a')
          ->join('INNER', '#__vjeecdcm_identification_info AS b on (a.info_id = b.id)')
          ->where('diploma_id = ' . $dplmId)
          ->order('a.info_id ASC');
        try 
        {
            $db->setQuery($query);
            $infos = $db->loadObjectList();
            //$jsonStr = json_encode($infos);
            //dump($jsonStr, 'holder model, get info, json string');
            return $infos;
        }
        catch (Exception $e)
        {
            return NULL;
        }
    }
}
