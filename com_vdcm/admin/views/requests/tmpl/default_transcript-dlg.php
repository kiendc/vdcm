<div id="transcript_dlg" class="detail-dlg" style="display: none;">  
  	<div id="transcipt-content" class="info-input">
	    <div class="detail-sec" id="req-detail-sec-1">
	    	<h3>Info</h3>
	    	<div>
	    		<form id="frm_transcript_info">
				<table id="tbl_info">
					<tr>
						<td>
							<label>No:</label>
							<input type="text" name="no" id="no" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Full name:</label>
							<input type="text" name="full_name" id="tran_fullname" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Gender:</label>
							<input type="text" name="gender" id="tran_gender" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Date of Birth:</label>
							<input type="text" class="datetime" name="birthday" id="tran_birthday" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Place of birth:</label>
							<input type="text" name="place_birth" id="place_birth" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Ethnic Group:</label>
							<input type="text" name="ethnic_group" id="ethnic_group" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Child of fallen or war invalid soldiers: </label> <input type="checkbox" name="invalid_soldier" id="invalid_soldier" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Current Residence:</label>
							<input type="text" name="current_residence" id="current_residence" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Full name of Father:</label>
							<input type="text" name="father_name" id="father_name" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Occupation:</label>
							<input type="text" name="father_occupation" id="father_occupation" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Full name of Mother:</label>
							<input type="text" name="mother_name" id="mother_name" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Occupation:</label>
							<input type="text" name="mother_occupation" id="mother_occupation" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Dated:</label>
							<input type="text"  class="datetime" name="trans_date" id="trans_date" />
						</td>
					</tr>
					
					<tr>
						<td>
							<button class="btn" href="#" id="update_tran_info">Update</button>
						</td>
					</tr>
				</table>
				<input type="hidden" name="diploma_id" id="tran_diploma_id" />
				<input type="hidden" name="trans_id" id="trans_id" />
				</form>
	      	</div>
	    </div>
	    <div class="detail-sec" id="req-detail-sec-2">
	    	<h3>Process of study</h3>
	    	<div>
	    		<p>
			    	<label> Select school year</label> 
			    	<select id="select_school_year">
			    		<option>--- select year ---</option>
			    	</select>
			    	<a href="#" id="addSchoolYear"> Add school year</a>
		    	</p>
		    	<form name="frm_school_year" id="frm_school_year">
		    	<table>
		    		<tr>
						<td>
							<label>Academic year:</label>
							<input type="text" name="academic_year" placeholder="2013-2014" id="academic_year" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Class:</label>
							<input type="text" name="class" placeholder="10,11,12" id="class" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Band:</label>
							<input type="text" name="band" placeholder="Basic" id="band" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Advanced course:</label>
							<input type="text" name="advanced_course" id="advanced_course" />
						</td>
					</tr>
		    	</table>
		    		<input type="hidden" name="study_process_id" id="study_process_id" />
		    	</form>
	    	</div>
	    </div>
	    <div class="detail-sec" id="req-detail-sec-3">
	    	<h3>Study sumary</h3>
	      	<div>
	      		<form name="frm_study_sumary" id="frm_study_sumary" style="display:none;">
		    	<table id="tbl_sumary">
		    		<tr>
						<td>
							<label>Head teacher:</label>
							<input type="text" name="head_teacher" id="head_teacher" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Confirmation of the Principal:</label>
							<input type="text" name="school_administrator" id="school_administrator" />
						</td>
					</tr>
					<tr>
						<td>
							<label>District:</label>
							<input type="text" name="district" id="district" />
						</td>
					</tr>
					<tr>
						<td>
							<label>City:</label>
							<input type="text" name="city" id="city" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Conduct I:</label>
							<input type="text" name="conduct1" id="conduct1" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Conduct II:</label>
							<input type="text" name="conduct2" id="conduct2" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Conduct whole year:</label>
							<input type="text" name="conduct3" id="conduct3" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Learning Capacity I:</label>
							<input type="text" name="learning1" id="learning1" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Learning Capacity II:</label>
							<input type="text" name="learning2" id="learning2" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Learning Capacity whole year:</label>
							<input type="text" name="learning3" id="learning3" />
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Days of absence of whole year:</label>
							<input type="text" name="nb_days_absent" id="nb_days_absent" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Classification after retaken exam | Conduct :</label>
							<input type="text" name="retaken_conduct" id="retaken_conduct" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Classification after retaken exam | Learning Capacity:</label>
							<input type="text" name="retaken_learning" id="retaken_learning" />
						</td>
					</tr>
					
					<tr>
						<td>
							<label>Straightly move up to higher grade:</label>
							<textarea name="straightly_move_up" id="straightly_move_up"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label>Qualified to move up to higher grade after retaken exam or practicing conduct:</label>
							<textarea name="qualified_move_up" id="qualified_move_up"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label>Unqualified to move up to higher grade:</label>
							<input type="text" name="unqualified" id="unqualified" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Holding certificate of vocational training:</label>
							<input type="text" name="holding_certificate" id="holding_certificate" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Award in contests of district-level and up:</label>
							<input type="text" name="award_contests" id="award_contests" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Other special comments and rewards:</label>
							<input type="text" name="other_special_comment" id="other_special_comment" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Ranking:</label>
							<input type="text" name="ranking" id="ranking" />
						</td>
					</tr>
					<tr>
						<td>
							<label>Remark by the head teacher:</label>
							<textarea name="head_teacher_remark" id="head_teacher_remark"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label>Approval by the principal:</label>
							<textarea type="text" name="principal_approve" id="principal_approve"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<label>Dated:</label>
							<input type="text" class="datetime" name="sumary_dated" id="sumary_dated" />
						</td>
					</tr>
		    	</table>
		    		<input type="hidden"  name="sumary_id" id="sumary_id" />
		    		<input type="hidden"  name="school_year_id" id="school_year_id" />
		    		<button id="btn_update_sumary"> Update </button>
		    	</form>
	    	</div>
  		</div>
	  	<div class="preview">
	      	<iframe id="elec-doc" style="width:100%;height:680px;" src=""></iframe>
  	</div>
</div>
