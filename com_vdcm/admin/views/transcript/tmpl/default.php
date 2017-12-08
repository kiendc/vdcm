<?php 
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');
$doc =  JFactory::getDocument();
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/custom.css');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jquery.js', 'text/javascript');

$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jquery-ui.js', 'text/javascript');
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/jquery-ui.css', 'text/css');
?>
<script type="text/javascript">
	$(document).ready(function() { 
		$('#show_school_year_form').click(function(e){ 
			e.preventDefault();
			$('#tbl_add_school_year').toggle();
		})
	});
</script>

<form name="adminForm" class="transcrip_form" id="adminForm" method="POST" action="" >
	<table id="tbl_info">
			<tr>
				<td>
					<label>Full name:</label>
					<input type="text" name="full_name" id="full_name" value="<?php echo $this->transcript->holder_name ? $this->transcript->holder_name : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>No:</label>
					<input type="text" name="no" id="no" value="<?php echo $this->transcript->no ? $this->transcript->no : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Date of Birth:</label>
					<input type="text" name="birthday" id="birthday" value="<?php echo $this->transcript->holder_birthday ? $this->transcript->holder_birthday : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Place of birth:</label>
					<input type="text" name="place_birth" id="place_birth" value="<?php echo $this->transcript->place_birth ? $this->transcript->place_birth : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Ethnic Group:</label>
					<input type="text" name="ethnic_group" id="ethnic_group" value="<?php echo $this->transcript->ethnic_group ? $this->transcript->ethnic_group : ''; ?>"/>
				</td>
			</tr>
			<tr>
				<td>
					<label>Child of fallen or war invalid soldiers: </label> <input type="checkbox" name="invalid_soldier" <?php echo $this->transcript->invalid_soldier ? 'checked="true"': '';?> />
				</td>
			</tr>
			<tr>
				<td>
					<label>Current Residence:</label>
					<input type="text" name="current_residence" id="current_residence" value="<?php echo $this->transcript->current_residence ? $this->transcript->current_residence : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Full name of Father:</label>
					<input type="text" name="father_name" id="father_name" value="<?php echo $this->transcript->father_name ? $this->transcript->father_name : ''; ?>" />
					<label>Occupation:</label>
					<input type="text" name="father_occupation" id="father_occupation" value="<?php echo $this->transcript->father_occupation ? $this->transcript->father_occupation : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Full name of Mother:</label>
					<input type="text" name="mother_name" id="mother_name" value="<?php echo $this->transcript->mother_name ? $this->transcript->mother_name : ''; ?>" />
					<label>Occupation:</label>
					<input type="text" name="father_occupation" id="father_occupation" value="<?php echo $this->transcript->mother_occupation ? $this->transcript->mother_occupation : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Dated:</label>
					<input type="text" name="current_residence" id="current_residence" value="<?php echo $this->transcript->date !=null ? date('d-m-Y', strtotime($this->transcript->date)) : ''; ?>"/>
				</td>
			</tr>
		</table> <br/><br/>
		
		<a id="show_school_year_form" href="#">Add school year</a> <br/>
		
		<table id="tbl_add_school_year" style="display:none;">
			<tr>
				<td>
					<label>Academic year:</label> <input type="text" name="academic_year" id="academic_year" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Class:</label> <input type="text" name="class" id="class" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Name & address of the school:</label> <input type="text" name="school_name" id="school_name" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Band:</label> <input type="text" name="band" id="band" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Advanced course:</label> <input type="text" name="advance_course" id="advance_course" />
				</td>
			</tr>
			<tr>
				<td>
					<a href="#" id="add_school_year">Submit </a>
				</td>
			</tr>
		</table>
		
		<label>Select school year </label>
		<select>
			<option>Select school year</option>
		</select>
</form>