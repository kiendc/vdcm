<?php
/**
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
jimport( 'joomla.user.helper' );
// No direct access
defined('_JEXEC') or die;

/**
 * Contact component helper.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_contact
 * @since		1.6
 */
class VjeecHelper
{ 
	protected static $actions;
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	$vName	The name of the active view.
	 *
	 * @return	void
	 * @since	1.6
	 */
	
	static public function getJalsaUserId() { 
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query = $query->select(array('u.id'))
					->from ($db->quoteName('#__users') . ' AS u')
					->where('u.username = "JaLSA"');
		try { 
			$db->setQuery($query);
			$ret = $db->loadObject();
			$retVal = $ret->id;
		}catch (Exception $e) { 
			$retVal = null;
		}
		return $retVal;
	}
	
	static public function addSubmenu($vName) { 
		$user = JFactory::getUser();
		$isAdmin = self::isAdministrator();
		
		JSubMenuHelper::addEntry(
			JText::_('Các yêu cầu'),
			'index.php?option=com_vjeecdcm&view=requests',
			$vName == 'requests'
		);
		
		if ($isAdmin) {
			JSubMenuHelper::addEntry(
				JText::_('Jalsa view'),
				'index.php?option=com_vjeecdcm&view=jalsa',
				$vName == 'jalsa'
			);
			
			JSubMenuHelper::addEntry(
				JText::_('Quản lý trường'),
				'index.php?option=com_vjeecdcm&view=schools',
				$vName == 'schools'
			);
			JSubMenuHelper::addEntry(
				JText::_('Quản lý khách hàng'),
				'index.php?option=com_vjeecdcm&view=customers',
				$vName == 'customers'
			);
		}
		/*
		JSubMenuHelper::addEntry(
			JText::_('Các yêu cầu'),
			'index.php?option=com_vjeecdcm&view=clientrequest',
			$vName == 'clientrequest'
		);
		JSubMenuHelper::addEntry(
			JText::_('Hồ sơ'),
			'index.php?option=com_vjeecdcm&view=diplomas',
			$vName == 'diplomas'
		);
		*/
	}
	
	static function isAdministrator() {
		$loggedUser = JFactory::getUser();
		$groups = $loggedUser->groups;
		if (is_array($groups) && (in_array(7, $groups) || in_array(8, $groups))) {
			return true;
		}
		return false;
	}
	
	static function isSuperAdmin() {
		$loggedUser = JFactory::getUser();
		$groups = $loggedUser->groups;
		if (is_array($groups) && in_array(8, $groups)) {
			return true;
		}
		return false;
	}
	
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return  JObject
	 *
	 * @since   1.6
	 * @todo    Refactor to work with notes
	 */
	public static function getActions()
	{
		if (empty(self::$actions))
		{
			$user = JFactory::getUser();
			self::$actions = new JObject;

			$actions = JAccess::getActions('com_vjeecdcm');

			foreach ($actions as $action)
			{
				self::$actions->set($action->name, $user->authorise($action->name, 'com_vjeecdcm'));
			}
		}

		return self::$actions;
	}
	
	static function getStateOptions() { 
		$options = array();
		$options[] = array('text' => 'Tất cả yêu cầu', 'value' => 0);
		$options[] = array('text' => 'Lưu trữ', 'value' => 1);
		$options[] = array('text' => 'Chưa xử lý', 'value' => 2);
		$options[] = array('text' => 'Đang xử lý', 'value' => 3);
		$options[] = array('text' => 'Xóa tạm thời', 'value' => -1);
		
		return $options;
	}
	
	static function getSchoolOptions() { 
		$db = JFactory::getDbo();
		$jalsaUser = VjeecHelper::getJalsaUserId();
		$db->setQuery(
			'SELECT s.id AS value, s.name AS text' .
			' FROM #__vjeecdcm_school AS s' .
			' Where s.school_user_id != 0 and s.represent_user_id = '. $jalsaUser .
			' ORDER BY s.name ASC'
		);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		foreach ($options as &$option) {
			$option->text = $option->text;
		}

		return $options;
	}
	
	static function getSchoolStatus() { 
		$options = array();
		$jalsaUser = VjeecHelper::getJalsaUserId();
		$options[] = array('text' => JText::_('VJEEC_REQUEST_STATUS_ACTIVATE'), 'value' => $jalsaUser);
		$options[] = array('text' => JText::_('VJEEC_REQUEST_STATUS_DEACTIVATE'), 'value' => 0);
		return $options;
	}
	
	static function getRequesterOptions() { 
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT DISTINCT u.id AS value, u.name AS text' .
			' FROM '. $db->quoteName('#__users') .'  AS u '.
			' INNER JOIN '.$db->quoteName('#__vjeecdcm_diploma_request').' AS a ON a.user_id = u.id ' .
			' ORDER BY u.name ASC'
		);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		foreach ($options as &$option) { 
			$option->text = $option->text;
		}
		
		return $options;
	}
	
	static function getStatusOptions() { 
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT a.id AS value, a.name AS text' .
			' FROM '. $db->quoteName('#__vjeecdcm_processing_step_name') .'  AS a '.
			' ORDER BY a.name ASC'
		);
		$options = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}

		foreach ($options as &$option) {
			$option->text = JText::_($option->text);
		}
		
		return $options;
	}
	
	static function getExpectedDateOptions() { 
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT DISTINCT a.expected_send_date' .
			' FROM '. $db->quoteName('#__vjeecdcm_diploma_request') .'  AS a '.
			' WHERE a.expected_send_date IS NOT NULL'.
			' ORDER BY expected_send_date DESC'
		);
		$ret = $db->loadObjectList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			JError::raiseNotice(500, $db->getErrorMsg());
			return null;
		}
		
		$options = array();
		if (count($ret)) { 
			foreach ($ret as $item) {
				$options[] = array('value' => $item->expected_send_date, 'text' => date('d/m/Y', strtotime($item->expected_send_date)));
			}
		}
		
		
		return $options;
	}
	
	static function remove_vietnamese_accents($str)
	{
		$accents_arr=array(
		"à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
		"ằ","ắ","ặ","ẳ","ẵ",
		"è","é","ẹ","ẻ","ẽ","ê","ề","ế","ệ","ể","ễ",
		"ì","í","ị","ỉ","ĩ",
		"ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ","ồ","ồ",
		"ờ","ớ","ợ","ở","ỡ",
		"ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ","ũ",
		"ỳ","ý","ỵ","ỷ","ỹ",
		"đ",
		"À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă",
		"Ằ","Ắ","Ặ","Ẳ","Ẵ",
		"È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
		"Ì","Í","Ị","Ỉ","Ĩ",
		"Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ",
		"Ờ","Ớ","Ợ","Ở","Ỡ",
		"Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
		"Ỳ","Ý","Ỵ","Ỷ","Ỹ",
		"Đ"
		);
		$no_accents_arr=array(
		"a","a","a","a","a","a","a","a","a","a","a",
		"a","a","a","a","a","a",
		"e","e","e","e","e","e","e","e","e","e","e",
		"i","i","i","i","i",
		"o","o","o","o","o","o","o","o","o","o","o","o","o","o",
		"o","o","o","o","o",
		"u","u","u","u","u","u","u","u","u","u","u","u",
		"y","y","y","y","y",
		"d",
		"A","A","A","A","A","A","A","A","A","A","A","A",
		"A","A","A","A","A",
		"E","E","E","E","E","E","E","E","E","E","E",
		"I","I","I","I","I",
		"O","O","O","O","O","O","O","O","O","O","O","O",
		"O","O","O","O","O",
		"U","U","U","U","U","U","U","U","U","U","U",
		"Y","Y","Y","Y","Y",
		"D"
		);
	
		return str_replace($accents_arr, $no_accents_arr, $str);
	}
	
}
