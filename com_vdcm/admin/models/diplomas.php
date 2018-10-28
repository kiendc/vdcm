<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of contact records.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_vjeecdcm
 */
class VjeecDcmModelDiplomas extends JModelList { 
	
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'diploma_id', 'a.diploma_id',
				'registration_date', 'a.registration_date',
				'user_id', 'a.user_id',
				'attachment_id', 'b.attachment_id',
				'holder_name', 'b.holder_name',
				'name', 'c.name'
			);
		}
		parent::__construct($config);
	}
	
	public function getItems() { 
		$items	= parent::getItems();
		return $items;
	}
	
/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Adjust the context to support modal layouts.
		if ($layout = JRequest::getVar('layout', 'default'))
		{
			$this->context .= '.'.$layout;
		}
		
		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_vjeecdcm');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'desc');
	}
	
	protected function getListQuery() { 
		// Create a new query object.
		$db = $this->getDbo();
		$query	= $db->getQuery(true);
		
		$query->select($this->getState( 'list.select',
			'a.id, a.diploma_id, a.registration_date, a.user_id'
		));
		$query->from($db->quoteName('#__vjeecdcm_user_diploma'). ' AS a');
		
		//join to users
		$query->select('u.name AS requester_name');
		$query->join('INNER', $db->quoteName('#__users').' AS u ON a.user_id = u.id');
		
		//join to diploma
		$query->select('b.attachment_id, b.holder_name');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_diploma').' AS b ON a.diploma_id = b.id');
		
		//join to diploma_degree
		$query->select('c.name AS degree_name');
		$query->join('INNER', $db->quoteName('#__vjeecdcm_diploma_degree').' AS c ON b.degree_id = c.id');
		
		// Filter the items over the search string if set.
		if ($this->getState('filter.search') !== '') { 
			// Escape the search token.
			$token	= $db->Quote('%'.$db->escape($this->getState('filter.search')).'%');
			// Compile the different search clauses.
			$searches	= array();
			$searches[]	= 'a.id LIKE '.$token;
			$searches[]	= 'u.name LIKE '.$token;
			
			$searches[]	= 'b.holder_name LIKE '.$token;
			$searches[]	= 'c.name LIKE '.$token;

			// Add the clauses to the query.
			$query->where('('.implode(' OR ', $searches).')');
		}
		
		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.id')).' '.$db->escape($this->getState('list.direction', 'DESC')));
		//echo nl2br(str_replace('#__','vj_',$query));
		return $query;
	}
	
	public function addDiplomaFromObj(&$dplm) {
	    $db = JFactory::getDBO();
	    $dplm->id = NULL;
	    $dplm->holder_identity_type = 1; // So CMTND
	    $result = $db->insertObject('#__vjeecdcm_diploma', $dplm, 'id');
	    if ($result) {
	      	return $db->insertid();
	    }
	    return -1;
  	}
  	
  	public function updateDiplomaInfo($diploma) {  
  		$db = JFactory::getDBO();
  		$ret = array('status' => 'success', 'msg' => '');
  		try {
  			$result = $db->updateObject('#__vjeecdcm_diploma', $diploma, 'id');
  			if ($result) { 
  				$ret['msg'] = 'Cập nhật thành công.';
  			} else { 
  				$ret['status'] = 'error';
  				$ret['msg'] = 'Đã có lỗi xảy ra.';
  			}
  		} catch (Exception $e) { 
  			$ret['status'] = 'error';
  			$ret['msg'] = $e->getMessage();
  		}
  		return $ret; 
  	}
  	
	public function getDiplomaInfo($requestId) {  
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select(array( 
				 	'u.name as sender_name',
				 	'd.name as school_name',	                         
	                'b.holder_name',
				 	'f.path'
				))
	          ->from('#__vjeecdcm_diploma_request AS a')
	          ->join('INNER', '#__vjeecdcm_diploma AS b ON (a.diploma_id = b.id)')
	          ->join('INNER', '#__users AS u ON (a.user_id = u.id)')
		  	  ->join('INNER', '#__vjeecdcm_school AS d ON (a.target_school_id = d.id)')
		  	  ->join('INNER', '#__vjeecdcm_attachment as  e ON (b.attachment_id = e.id)')
		  	  ->join('INNER', '#__vjeecdcm_attachment_file as f ON (e.id = f.attachment_id)')
		  	  ->where('a.id = '.$requestId);
		try { 
	      	$db->setQuery($query);
	      	$result = $db->loadObject();
	      	return $result;
	    } catch (Exception $e) {
	      	return NULL;
	    }
	}
  	
	public function getDiploma($dplmID) {
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    $query->select(array('*'))
		  	->from('#__vjeecdcm_diploma')
		  	->where('id = '. $dplmID);
	    try { 
	      	$db->setQuery($query);
	      	$result = $db->loadObject();
	      	return $result;
	    } catch (Exception $e) {
	      	return NULL;
	    }
  	}
  	
  	public function getRequestId($dplmID) {
  		$db = JFactory::getDBO();
  		$query = $db->getQuery(true);
  		$query->select(array('b.id AS reqId'))
  			->from('#__vjeecdcm_diploma AS a')
  			->join('INNER', '#__vjeecdcm_diploma_request AS b ON (b.diploma_id = a.id)')
  			->where('a.id = '. $dplmID);
  		
  		try {
  			$db->setQuery($query);
  			$result = $db->loadObject();
  			return $result->reqId;
  		} catch (Exception $e) { 
  			return NULL;
  		}
  	}
  	
  	public function findMajorByName($name) { 
  		$db = JFactory::getDBO();
  		$query = $db->getQuery(true);
  		$query->select(array('id'))
  			  ->from('#__vjeecdcm_major')
  			  ->where(' LOWER(major) = "'.$db->escape(strtolower($name)) .'"');
  		try { 
  			$db->setQuery($query);
  			$ret = $db->loadObject();
  			if (isset($ret->id)) { 
  				$major_id =  $ret->id;
  			} else { 
  				$major_id = $this->insertMajor($name);
  			}
  		} catch (Exception $e) { 
  			return null;
  		}
  		return $major_id;
  	}
  	
	public function insertMajor($major) {
	    $db = JFactory::getDBO();
	    $obj = new stdClass();
	    $obj->major = $major;
	    $result = $db->insertObject('#__vjeecdcm_major', $obj, 'id');
	    if ($result) {
	      	return $db->insertid();
	    }
	    return -1;
  	}
  	
	public function getMajorList() {
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    $query->select(array('id, major'))
		  	->from('#__vjeecdcm_major');
	    try { 
	      	$db->setQuery($query);
	      	$result = $db->loadObjectList();
	      	return $result;
	    } catch (Exception $e) {
	      	return NULL;
	    }
  	}
  	
	public function getCertificateInfo($dplmID) {
	    $db = JFactory::getDBO();
	    $query = $db->getQuery(true);
	    $query->select(array('
	    			a.certifi_date,
	    			c.expected_send_date,
	    			a.reference, 
	    			a.holder_name, 
	    			a.holder_birthday as birthday, 
	    			a.gender, 
	    			a.ranking, 
	    			a.reg_no, 
	    			a.holder_identity_value as id_no, 
	    			a.issuer, 
	    			a.issue_date, 
	    			a.study_mode,
	    			a.certificate_finished,
	    			d.name_en as degree_type, 
	    			d.certificate_type,
    				b.major'))
		  	->from('#__vjeecdcm_diploma AS a')
		  	->join('INNER', '#__vjeecdcm_diploma_degree AS d ON (a.degree_id = d.id)')
		  	->join('INNER', '#__vjeecdcm_diploma_request AS c ON (a.id = c.diploma_id)')
		  	->join('LEFT', '#__vjeecdcm_major AS b ON (a.major_id = b.id)')
		  	->where('a.id = '. $dplmID);
	    try { 
	    	//echo nl2br(str_replace('#__','imz_',$query)); die();
	      	$db->setQuery($query);
	      	$result = $db->loadObject();
	      	if ($result->birthday == null || $result->birthday == '0000-00-00') { 
	      		$result->birthday = $this->getHolderBirthday($dplmID);
	      	}
	      	return $result;
	    } catch (Exception $e) {
	      	return NULL;
	    }
  	}
  	
  	public function getHolderBirthday($diplomaId) { 
  		$db = JFactory::getDBO();
  		$query = $db->getQuery(true);
  		$ret = null;
  		$query->select(array('a.value as birthday'))
  		    				->from('#__vjeecdcm_holder_info AS a')
  		    				->where('a.diploma_id = '. $diplomaId .' and info_id = 4');
  		try {
  			//echo nl2br(str_replace('#__','imz_',$query)); die();
  			$db->setQuery($query);
  			$obj = $db->loadObject();
  			$birthDay = $obj->birthday;
  			if ($birthDay != null || $birthDay != '') { 
  				$ret = date('Y-m-d', strtotime(str_replace('/', '-', $birthDay)));
  			}
  		} catch (Exception $e) {
  			$ret = null;
  		}
  		return $ret;
  	}
  	
	public function registerDiplomaToUser($dplmID) {
	    // set the variables from the passed data
	    $db = JFactory::getDBO();
	    $date = JFactory::getDate()->format('Y-m-d');
	    $usrDplm = new stdClass();
	    $usrDplm->id = NULL;
	    $usrDplm->diploma_id = $dplmID;
	    $usrDplm->user_id = JFactory::getUser()->id;
	    $usrDplm->registration_date = $date;
	    $usrDplm->certified_by_vjeec = 'VJEECDCM_USER_DIPLOMA_TBL_CERTIFIED_NO';
	    $result = $db->insertObject('#__vjeecdcm_user_diploma', $usrDplm, 'id');
	    return $result;
  	}
  	
	public function createTemplate($certification) { 
		$certificate_label = "TEMPORARY CERTIFICATE OF GRADUATION";
		$level_study_label = "Level of study:";
		$degree_label = "Issued by:";
		$color = '#F40F0F';
		if ($certification->type) { 
			$color = '#000';
			$certificate_label = "TO WHOM IT MAY CONCERN";
			$level_study_label = "Type of degree:";
			$degree_label = "Degree granting institution:";
		}
		$temp = '<table style="width:97%;margin:7pt 0 0 12pt;border:1px solid #2a345e; padding:0 0 6pt 0;background-color: #2a345e;">
					<tr>
						<td style="width:100%;">
							<table style="width:98.5%;margin:6pt 0 0 6pt;padding-bottom:6pt;background-color:#fff;">
								<tr>
									<td style="width:100%;">
										<table style="clear:both;width:98.3%;border:1pt solid #7d7b7b;padding:0 0 1pt 0;margin:6pt 0 0 6pt;background-color:#e8e2e4;">
											<tr>
												<td style="width:100%;">
													<table style="width:99.7%;border:1px solid #7d7b7b;margin:1pt 0 0 1pt;padding:0;background-color:white;">
														<tr>
								 							<td style="width:100%;font-family:Arial;">
								 								<table style="margin:15pt 12pt 0 12pt;width:98.4%;">
								 									<tr>
								 										<td style="padding: 0 0 22pt 0;font-size:8.2pt;color: #2e3062;font-family:Arial Regular;">
								 											<span style="font-family:Arial;font-weight:bold;">Declaration: </span> <br>
								 											Exclusively commissioned by the Japanese Language Schools Association (JaLSA), the Vietnam - Japan Education and Exchange Center (VJEEC), Institute of Foundation and  Higher Training, <br>
								 											cooperates with the Testing & Education Quality Control Bureau, Ministry of Education and Training of Vietnam to provide aggregated and secure delivery of degree verifications to JaLSA.
								 										</td>
								 									</tr>
								 									<tr>
									 									<td style="text-align:center;font-size:30pt;font-weight:bold;font-family: Bernard MT Condensed;color:#2E3062;">
									 										VERIFICATION OF QUALIFICATIONS
									 									</td>
									 								</tr>
									 								<tr>
									 									<td>
									 										<table style="margin:0 0 0 260pt;padding:0;">
									 											<tr>
									 												<td style="font-family:Arial Regular;font-size:15pt;">
									 													Date: <span style="font-family:Arial;font-weight:bold;margin-left:16pt;font-size:17pt;">'.$certification->date.'</span> <br/>
									 													<span style="font-size:16pt;">Reference No.:</span> <span style="font-family:Arial;font-weight:bold;margin-left:16pt;font-size:17pt;">'.$certification->reference_no.'</span>
									 												</td>
									 											</tr>
									 										</table>
									 									</td>
									 								</tr>
									 								<tr>
									 									<td style="width:100%; padding-bottom:9pt;">
									 										<table style="width:90%;font-family:Arial Narrow;font-size:14pt;margin-left:47pt;">
									 											<tr>
															    					<td style="width:60%;padding: 0 0 10pt 0;color: '.$color.'">'.$certificate_label.'</td>
															    					<td style="width:40%;"></td>
															    				</tr>
															    				<tr>
															    					<td style="padding-bottom:4pt;">Name: <span style="text-transform:uppercase;font-weight:bold;font-family:Arial;">'.$certification->name.'</span></td>
															    					<td>Gender: <span style="font-weight:bold;font-family:Arial;">'.$certification->gender.'</span></td>
															    				</tr>
															    				<tr>
															    					<td style="padding-bottom:4pt;">Date of birth: <span style="font-weight:bold;font-family:Arial;">'.$certification->birthday.'</span></td>
															    					<td>ID No: <span style="font-weight:bold;font-family:Arial;">'.$certification->id_no.'</span></td>
															    				</tr>
															    				<tr>
															    					<td style="width:55%;padding-bottom:4pt;">'.$level_study_label.' <span style="font-weight:bold;font-family:Arial;">'.$certification->level_study.'</span></td>
															    					<td>Mode of study: <span style="font-weight:bold;font-family:Arial;">'.$certification->study_mode.'</span></td>
															    				</tr>
															    				<tr>
															    					<td style="width:55%;padding-bottom:4pt;">Major: <span style="font-weight:bold;font-family:Arial;">'.$certification->major.'</span></td>
															    					<td>Ranking: <span style="font-weight:bold;font-family:Arial;">'.$certification->ranking.'</span></td>
															    				</tr>
															    				<tr>
															    					<td style="padding-bottom:4pt;">Reg. No: <span style="font-weight:bold;font-family:Arial;">'.$certification->reg_no.'</span></td>
															    					<td>Dated: <span style="font-weight:bold;font-family:Arial;">'.$certification->dated.'</span></td>
															    				</tr>
															    				<tr>
															    					<td colspan="2">'.$degree_label.' <span style="font-weight:bold;font-family:Arial;">'.$certification->issued_by.'</span></td>
															    				</tr>
									 										</table>
									 									</td>
									 								</tr>
									 								<tr>
												    					<td colspan="2" style="text-align:center;font-weight:bold;font-size:19pt; font-family:Arial;">
												    						The above facts are certified to be genuine
											    						</td>
											    					</tr>
													    			<tr>
												    					<td colspan="2" style="padding:10px 0 0 0;font-family:Arial;font-size:10pt;color:#2E3062;font-weight:bold;">
												    						<p style="text-align:center;margin:0 0 0 350pt;padding:0;width:300pt;">
												    							Institute of Foundation and Higher Training <br>
												    							Vietnam - Japan Education and Exchange Center
												    						</p>
												    					</td>
												    				</tr>
												    				<tr>
												    					<td colspan="2" style="padding:87pt 0 10pt 0;color:#2e3062;font-size:10pt;">
												    						<p style="text-align:center;margin:0 0 0 350pt;padding:0;width:300pt;">
												    							<span style="font-family:Arial;font-weight:bold;">Director</span><br>
												    							<span style="font-family:Arial Regular;">DR. TRẦN VĂN CHÍNH</span>
												    						</p>
												    					</td>
												    				</tr>
								 								</table>
							 								</td>
						 								</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>';
		return $temp;
	}
	
	public function generate2Pdf($certificate) { 
		$content = $this->createTemplate($certificate);
		//$content = utf8_encode($content);
		require_once(JPATH_COMPONENT .'/pdf/html2pdf.class.php');
		
		define('FONT_EMBEDDING_MODE', 'all');
		try { 
			$html2pdf = new HTML2PDF('L', 'A4', 'en', true, 'UTF-8');
			$html2pdf->pdf->SetDisplayMode('real');
			//add Arial Narrow font
			$html2pdf->pdf->AddFont("Arial Narrow", 'N', JPATH_COMPONENT.'/pdf/_tcpdf_5.0.002/fonts/arialn.ttf.php');
			$html2pdf->pdf->AddFont("Arial Narrow", 'B', JPATH_COMPONENT.'/pdf/_tcpdf_5.0.002/fonts/arialnb.ttf.php');
			$html2pdf->pdf->AddFont("Arial Narrow", 'BI',JPATH_COMPONENT.'/pdf/_tcpdf_5.0.002/fonts/arialnbi.ttf.php');
			$html2pdf->pdf->AddFont("Arial Narrow", 'I', JPATH_COMPONENT.'/pdf/_tcpdf_5.0.002/fonts/arialni.ttf.php');
			//add Arial Regular font
			$html2pdf->pdf->AddFont("Arial Regular", 'N', JPATH_COMPONENT.'/pdf/_tcpdf_5.0.002/fonts/arialr.php');
			//add Bernard MT Condensed font	
			$html2pdf->pdf->AddFont("Bernard MT Condensed", 'B', JPATH_COMPONENT.'/pdf/_tcpdf_5.0.002/fonts/bernhc.ttf.php');
			$html2pdf->writeHTML($content, false);
			
		} catch(HTML2PDF_exception $e) { 
			echo $e;
		}
		
		return $html2pdf;
	}
}
