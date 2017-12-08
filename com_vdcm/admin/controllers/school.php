<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
require_once JPATH_COMPONENT.'/helpers/vjeec.php';
class VjeecdcmControllerSchool extends JControllerForm
{ 
	
	public function save($key = null, $urlVar = null) { 
		$ret = parent::save($key, $urlVar);
		$model = $this->getModel();
		if ($ret) { 
			$data  = $this->input->post->get('jform', array(), 'array');
			
			$recordId = $this->input->getInt($urlVar);
			$userData['name'] = $data['name'];
			$userData['username'] = $data['username'];
			$userData['password'] = $data['password'];
			$userData['password2'] = $data['password2'];
			$userData['email'] = $data['email'];
			$userData['block'] = $data['represent_user_id'] == VjeecHelper::getJalsaUserId() ? 0 : 1;
			
			$table = $model->getTable();
			$key = $table->getKeyName();
			if (empty($key)) {
				$key = $table->getKeyName();
			}
			// To avoid data collisions the urlVar may be different from the primary key.
			if (empty($urlVar)) {
				$urlVar = $key;
			}
			$recordId = $this->input->getInt($urlVar);
			
			if ($recordId) { 
				$item = $model->getItem($recordId);
				$schoolId = $recordId;
				$userId = $item->school_user_id;
			} else { 
				$userId = 0;
				$schoolId = JFactory::getDBO()->insertid();
			}
			
			$ret = $model->createUpdateSchoolUserLogin($schoolId, $userId, $userData);
		}
	}
}