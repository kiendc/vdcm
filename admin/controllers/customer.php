<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class VjeecdcmControllerCustomer extends JControllerForm { 
	
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
				$clientId = $recordId;
				$userId = $item->client_user_id;
			} else { 
				$userId = 0; // client user doesnt exists
				// get insert id
				$clientId = JFactory::getDBO()->insertid();
			}
			
			$ret = $model->createUpdateLoginedUser($clientId, $userId, $userData);
		}
		/**/
	}
}