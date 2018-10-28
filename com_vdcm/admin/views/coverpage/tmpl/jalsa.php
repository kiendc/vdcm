<?php

// No direct access.
defined('_JEXEC') or die;
$doc =  JFactory::getDocument();
$doc->addStyleSheet($this->baseurl.'/components/com_vjeecdcm/helpers/css/coverpage.css');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jquery.js', 'text/javascript');
$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/gen-coverpage-pdf.js', 'text/javascript');
$doc->addScript($this->baseurl.'/components/com_vjeecdcm/helpers/js/jspdf-autotable.js', 'text/javascript');

?>
<div>
<button id="gen-pdf"> Generate PDF</button>
<button onclick="location.href='/portal/administrator/index.php?option=com_vjeecdcm&view=requests';">Go back</button>
</div>
<div id="coverpage-content">

		
				
<p id="shipment"><?php echo 'VJEEC to JaLSA: Shipment on '. $this->date . 'Total requests: '. count($this->list) ?></p>

<table id="tbl_jalsa" cellspacing="0">
<thead>
<tr id="tr_head2">
	<td style="width:35pt;">Order</td>
  	<td style="width:100pt;">Code</td>
	<td style="width:130pt;">Student</td>
	<td style="width:250pt;">School</td>
</tr>
<thead>
<tbody>
<?php
$i = 1;
foreach ($this->list as $item) {
?> 
	<tr class="content2">
		<td align="center" style="width:35pt;"> <?php echo $i ?></td>
		<td style="width:100pt;"><?php echo $item->code ?></td>
		<td style="width:130pt;"><?php echo $item->holder_name ?></td>
		<td style="width:250pt;"><?php echo $item->school_name?></td>
	</tr>
<?php
	$i++;
}
?>  	
</tbody>
</table>'
</div>
		
		
		