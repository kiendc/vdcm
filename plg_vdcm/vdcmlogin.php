<?php

// no direct access
defined('_JEXEC') or die;

class plgUserVdcmlogin extends JPlugin
{
  public function onUserAfterLogin($options)
  {
  
  // User data
  $user_id = $options['user']->id;
  $groups  = $options['user']->groups;

  $allGroups = '';
  foreach($groups as $group)
  {
	
    $allGroups .= 'Group Id = ' . $group . ' ';
          }

          // Session data
          $session = JFactory::getSession();
          $sessionid = $session->getId();

          JFactory::getApplication()->enqueueMessage('The user ID is: ' . $user_id);
          JFactory::getApplication()->enqueueMessage('The session ID is: ' . $sessionid);

          // $allGroups is a string containing all the groups
          // Write to txt file
          // Set cookie for the same
    }
}

?>
