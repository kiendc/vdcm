<?php
defined('_JEXEC') or die('Restricted access');
JHtml::_('bootstrap.framework');

$doc =  JFactory::getDocument();
$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addStyleSheet('https://cdn.datatables.net/v/bs/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/datatables.min.css');
$doc->addScript('https://cdn.datatables.net/v/bs/dt-1.10.16/af-2.2.2/b-1.5.1/b-colvis-1.5.1/datatables.min.js');
?>

<script type="text/javascript">
 	// set site_url variable which to be used in create_request.js file 
	var site_url = "<?php echo JURI::root(); ?>";
  $(document).ready(function() {
    $('#example').DataTable();
} );
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
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </tfoot>
        <tbody>
  </tbody>
</table>
