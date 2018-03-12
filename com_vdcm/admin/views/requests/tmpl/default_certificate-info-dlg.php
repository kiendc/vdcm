<div id="certificate_info_dlg" style="display:none;">
	<form id="certificate_form" target="_blank" action="<?php echo JRoute::_('index.php?option=com_vjeecdcm&task=diplomas.generatePDF'); ?>" method="POST">
		<div>
			<label><?php echo JText::_('Loại chứng thực');?> :</label>
			<select name="certificate_type" id="select_type">
				<option value><?php echo JText::_('Chọn loại chứng thực');?></option>
				<option value="1"><?php echo JText::_('Bằng tốt nghiệp');?></option>
				<option value="0"><?php echo JText::_('Chứng nhận tốt nghiệp tạm thời');?></option>
			</select>
			<input type="checkbox" name="is_finished" id="is_finished" /> <label style="font-weight:bold;"><?php echo JText::_('Hoàn thành');?></label>
		</div>
		<br/>
		<table id="tbl_certificate">
			<tr>
				<td>
					<label><?php echo JText::_('Expected Send Date');?>:</label>
					<input class="valid_input datetime" type="text" name="certificate_date" id="certificate_date" />
				</td>
				<td>
					<label><?php echo JText::_('Reference No');?>:</label>
					<input class="valid_input" type="text" name="certificate_reference" id="certificate_reference" />
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('Name');?>:</label>
					<input class="valid_input" type="text" name="student_name" id="student_name" />
				</td>
				<td>
					<label><?php echo JText::_('Gender');?>:</label>
					<input class="valid_input" type="text" name="student_gender" id="student_gender" />
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('Date of birth');?>:</label>
					<input class="valid_input datetime" type="text" name="student_birthday" id="student_birthday" />
				</td>
				<td>
					<label><?php echo JText::_('ID No.');?>:</label>
					<input class="valid_input" type="text" name="student_idno" id="student_idno" />
				</td>
			</tr>
			<tr>
				<td>
					<label id="level_study_lbl"><?php echo JText::_('Type of degree');?>:</label>
					<input class="valid_input" type="text" name="level_study" id="level_study" />
				</td>
				<td>
					<label><?php echo JText::_('Mode of study');?>:</label>
					<input class="valid_input" type="text" name="study_mode" id="study_mode" />
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('Major');?>:</label>
					<input class="valid_input" type="text" name="student_major" id="student_major" />
				</td>
				<td>
					<label><?php echo JText::_('Ranking');?>:</label>
					<input class="valid_input" type="text" name="student_ranking" id="student_ranking" />
				</td>
			</tr>
			<tr>
				<td>
					<label><?php echo JText::_('Reg. No');?>:</label>
					<input class="valid_input" type="text" name="reg_no" id="reg_no" />
				</td>
				<td>
					<label><?php echo JText::_('Dated');?>:</label>
					<input class="valid_input datetime" type="text" name="issued_date" id="issued_date" />
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<label id="issued_by_lbl"><?php echo JText::_('Degree granting institution');?>:</label>
					<input class="valid_input" type="text" name="issued_by" id="issued_by" />
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<button class="update_certificate" id="btn_update_certificate"><?php echo JText::_('Cập nhật');?></button>
					<input type="submit" id="btn_submit_form" value="<?php echo JText::_('In certificate');?>" />
				</td>
			</tr>
		</table>
		<input type="hidden" id="diploma_id" name="diploma_id" />
		<input type="hidden" id="certifi_finished" name="certifi_finished" />
		<input type="hidden" id="school_name" name="school_name" />
		<input type="hidden" id="sender_name" name="sender_name" />
		<input type="hidden" id="orinal_student_name" name="orinal_student_name" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>

