<?php 
// No direct access.
defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('behavior.modal');
$doc =  JFactory::getDocument();
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/custom.css');
?>

<form name="adminForm" class="transcrip_form" id="adminForm" method="POST" action="" >
	<table id="tbl_info">
			<tr>
				<td>
					<label>Full name:</label>
					<input class="valid_input" type="text" name="full_name" id="full_name" value="<?php echo $this->transcript->holder_name ? $this->transcript->holder_name : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>No:</label>
					<input class="valid_input" type="text" name="no" id="no" value="<?php echo $this->transcript->no ? $this->transcript->no : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Date of Birth:</label>
					<input class="valid_input" type="text" name="birthday" id="birthday" value="<?php echo $this->transcript->holder_birthday ? $this->transcript->holder_birthday : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Place of birth:</label>
					<input class="valid_input" type="text" name="place_birth" id="place_birth" value="<?php echo $this->transcript->place_birth ? $this->transcript->place_birth : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Ethnic Group:</label>
					<input class="valid_input" type="text" name="ethnic_group" id="ethnic_group" value="<?php echo $this->transcript->ethnic_group ? $this->transcript->ethnic_group : ''; ?>"/>
					<label>Child of fallen or war invalid soldiers: </label> <input type="checkbox" name="invalid_soldier" <?php echo $this->transcript->invalid_soldier ? 'checked="true"': '';?> />
				</td>
			</tr>
			<tr>
				<td>
					<label>Current Residence:</label>
					<input class="valid_input" type="text" name="current_residence" id="current_residence" value="<?php echo $this->transcript->current_residence ? $this->transcript->current_residence : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Full name of Father:</label>
					<input class="valid_input" type="text" name="father_name" id="father_name" value="<?php echo $this->transcript->father_name ? $this->transcript->father_name : ''; ?>" />
					<label>Occupation:</label>
					<input class="valid_input" type="text" name="father_occupation" id="father_occupation" value="<?php echo $this->transcript->father_occupation ? $this->transcript->father_occupation : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Full name of Mother:</label>
					<input class="valid_input" type="text" name="mother_name" id="mother_name" value="<?php echo $this->transcript->mother_name ? $this->transcript->mother_name : ''; ?>" />
					<label>Occupation:</label>
					<input class="valid_input" type="text" name="father_occupation" id="father_occupation" value="<?php echo $this->transcript->mother_occupation ? $this->transcript->mother_occupation : ''; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<label>Dated:</label>
					<input class="valid_input" type="text" name="current_residence" id="current_residence" value="<?php echo $this->transcript->date !=null ? date('d-m-Y', strtotime($this->transcript->date)) : ''; ?>"/>
				</td>
			</tr>
		</table>
		
		<h2 style="font-size:14px;"> PROCESS OF STUDY </h2>
		<table id="tbl_study_process">
			<thead>
					<tr>
						<td align="center" width="180"><b>Academic year</b></td>
						<td align="center"><b>Class</b></td>
						<td align="center"><b>School name</b></td>
						<td align="center"><b>school administration</b></td>
					</tr>
				</thead>
			<tbody>
				<?php if (count($this->process)) {
					foreach($this->process as $item) { ?>
						<tr>
							<td align="center"><input type="text" name="academic_year" value="<?php echo $item->academic_year ;?>"/> </td>
							<td align="center"><input type="text" name="class" value="<?php echo $item->class ;?>"/> </td>
							<td align="center"><input type="text" name="school_name" value="<?php echo $item->school_name ;?>"/> </td>
							<td align="center"><input type="text" name="school_administrator" value="<?php echo $item->school_administrator ;?>"/> </td>
							
						</tr>
					<?php } ?>
				<?php } ?>
			</tbody>
		</table> <br/><br/> 
		<br/>
		<?php foreach ($this->marks as $mark) { ?>
			<table class="tbl_mark">
				<thead>
					<tr>
						<td rowspan="2" align="center" width="180"><b>Subjects</b></td>
						<td colspan="3" align="center"><b>Average marks</b></td>
						<td rowspan="2" align="center"><b>Retake mark</b></td>
						<td rowspan="2" align="center"><b>Subject teachers</b></td>
					</tr>
					<tr>
				        <td align="center"><b>Term I</b></td>
				        <td align="center"><b>Term II</b></td>
				        <td align="center"><b>Whole year</b></td>
				    </tr>
				</thead>
				<tbody>
				<?php foreach ($mark as $item) { ?>
					<tr>
						<td align="left" style="padding-left:20px;"><?php echo $item->name;//echo JHtml::_('select.options', VjeecHelper::getRequesterOptions(), 'value', 'text', $this->state->get('filter.requester'));?></td>
						<td align="center"><input type="text" value="<?php echo $item->mark1; ?>" /></td>
						<td align="center"><input type="text" value="<?php echo $item->mark2; ?>" /></td>
						<td align="center"><input type="text" value="<?php echo $item->whole_year; ?>" /></td>
						<td align="center"><input type="text" value="<?php echo $item->retake_mark; ?>" /></td>
						<td align="center"><input type="text" value="<?php echo $item->teacher_sign; ?>" /></td>
					</tr>
					
				<?php } ?>
				
				</tbody>
			
			</table> <br/><br/>
			
		<?php } ?>
		
		<input type="submit" value="Cập nhật" />
</form>