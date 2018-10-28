<?php

// No direct access.
defined('_JEXEC') or die;
$doc =  JFactory::getDocument();
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/coverpage.css');

?>

<table>
	

<table id="tbl_school_info">
	<tr><td> SCHOOL NAME: '.  $schoolObj->name .'</td></tr>
  	<tr><td> ADDRESS: '.  $schoolObj->address .'</td></tr>	  							
	<tr><td> ZIP CODE: '.  $schoolObj->zipcode .'</td></tr>
  	<tr><td> TEL: '.  $schoolObj->phone .'</td></tr>
  	<tr><td> ATTN: '.  $schoolObj->contact_name .'</td></tr>
</table>
<p id="shipment"> Shipment on <span style="color:red;">'.$date.'</span></p>

<table id="tbl_list_student" cellspacing="0">
	<tr>
		<td colspan="4" style="border:1px solid #000;width:435pt;vertical-align:top;padding:6pt 0 15pt 0;text-align:center;font-weight:bold;color:#002060; background:#dbe5f1;font-size:10pt;font-family:Arial;">VERIFICATION OF QUALIFICATIONS</td></tr>

	<tr id="tr_head">
		<td style="width:35pt;">No.</td>
  		<td style="width:125pt;text-align:center;font-weight:bold;padding:3pt 0 6pt 0;color:#002060;">Student Name</td>
  		<td style="width:100pt;text-align:center;font-weight:bold;padding:3pt 0 6pt 0;color:#002060;">Reference No.</td>
  		<td style="width:170pt;text-align:center;font-weight:bold;padding:3pt 0 6pt 0;color:#002060;">Qualifications</td>
	</tr>
<?php
$i = 0;
foreach ($this->list as $item) {
?> 
	<tr class="content2">
		<td align="center" style="width:35pt;"> <?php echo $i ?></td>
		<td style="width:100pt;"><?php echo $item->code ?></td>
		<td style="width:130pt;"><?php echo strtoupper(VjeecHelper::remove_vietnamese_accents($item->holder_name)) ?></td>
		<td style="width:250pt;"><?php echo $item->school_name?></td>
	</tr>
<?php	
	$i++;
}
?>	

</table>
		