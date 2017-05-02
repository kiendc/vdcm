<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// Include dependancy of the main model form
jimport('joomla.application.component.modelform');
jimport('joomla.application.component.modelitem');
// Include dependancy of the dispatcher
jimport('joomla.event.dispatcher');
 
class VjeecDcmModelAttachment extends JModelItem
{
    public function addAttachment($file, $uploaded=false)
    {
        jimport('joomla.filesystem.file');
        if (!$uploaded)
        {
            //Clean up filename to get rid of strange characters like spaces etc
            $tmpUpld = JFile::makeSafe($file['name']);
            //$filename = $attId . '_' . '01.' . JFile::getExt($filename);
            $src = $file['tmp_name'];
            //$dest = 'vjeecdcm' . DIRECTORY_SEPARATOR . $filename;
            $tmpUpld = 'tmp' . DIRECTORY_SEPARATOR . $tmpUpld;
            $uploadResult = JFile::upload($src, $$tmpUpld);
            //dump($uploadResult, 'Attachment model, uploading file, uploadResult');
            if (!$uploadResult)
            {
                return null;
            }    
        }
        else
        {
            $tmpUpld = $file;
        }
        $userId = JFactory::getUser()->id;
        $db = JFactory::getDBO(); 
        ////dump($tmpUpld, 'Attachment model, uploaded file');
        $attachment = new stdClass();
        $attachment->owner_id = JFactory::getUser()->id;
        $result = $db->insertObject('#__vjeecdcm_attachment', $attachment, 'id');
        // Add observer here
        if (!$result)
        {   JFile::delete($tmpUpld);
            return null;
        }
        $attId = $db->insertid();
        // Upload the electronic version
        //Import filesystem libraries. Perhaps not necessary, but does not hurt
        $dest = 'vjeecdcm' . DIRECTORY_SEPARATOR . $attId . '_' . '01.' . JFile::getExt($tmpUpld);
        JFile::move($tmpUpld, $dest);
        
        $attFileObj = new stdClass();
        $attFileObj->path = $dest;
        $attFileObj->attachment_id = $attId;
        $result = $db->insertObject('#__vjeecdcm_attachment_file', $attFileObj, 'id');
        ////dump($attFileObj, 'attachment model, attFileObj');
        if ($result)
            return $attId;
        else
            return null;
    }
    
    //////////////////////////////////////////////////////////////////////////
    
    public function removeAttachment($attachmentId)
    {
        $path = $this->getFilePath($attachmentId);
        ////dump ($attachmentId, 'id, Attachment model, remove attachment');
        ////dump ($path, 'path, Attachment model, remove attachment');
        if ($path != NULL)
        {
            $trash = $path . '.trashed';
            JFile::move($path, $trash);    
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__vjeecdcm_attachemnt')
              ->where(array('id = ' . $attachmentId));
        $db->setQuery($query);
        $result = $db->query();
        
    }
    
    //////////////////////////////////////////////////////////////////////////
    
    public function getFilePath($attachmentId)
    {
        $db = JFactory::getDBO();
        ////dump($attachmentId, 'Attachment::getFilePath, result');
        $query = $db->getQuery(true);
        $query->select(array('id','attachment_id', 'path'))
            ->from('#__vjeecdcm_attachment_file')
            ->where('attachment_id = '. $attachmentId);
        try 
        {
            $db->setQuery($query);
            $result = $db->loadObjectList();
            ////dump($result, 'Attachment::getFilePath, result');
            if ($result == null)
                return null;
            return $result[0]->path;
        } 
        catch (Exception $e) 
        {
            return null;
        }
        
    }
    
    public function getFileInfo($attachmentId)
    {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select(array('path', 'type'))
            ->from('#__vjeecdcm_attachment_file')
            ->where('attachment_id = '. $attachmentId);
        try 
        {
            $db->setQuery($query);
            $result = $db->loadObjectList();
            ////dump($result, 'Attachment::getFilePath, result');
            if ($result == null)
                return null;
            return $result[0];
        } 
        catch (Exception $e) 
        {
            return null;
        }
    }
    
}
