<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('jquery.framework', false);
$doc =  JFactory::getDocument();

$doc->addStyleSheet('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
$doc->addStyleSheet('https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css');

$doc->addScript('https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js');
$doc->addScript('https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js');
?>

<script type="text/javascript">
 	// set site_url variable which to be used in create_request.js file 
	var site_url = "<?php echo JURI::root(); ?>";
	$(document).ready(function(){
		$('#example').DataTable();
	});
</script>

<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </thead>
        <tbody>
  </tbody>
</table>
