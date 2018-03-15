<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('jquery.framework', false);
$doc =  JFactory::getDocument();

$doc->addStyleSheet('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
$doc->addStyleSheet('https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css');

$doc->addScript('https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js');
$doc->addScript('https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js');

$doc->addScript('components/com_vjeecdcm/js/vjdcm.js');
$doc->addScript('components/com_vjeecdcm/js/client.js');
?>

<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="#">Dashboard</a></li>
  <li role="presentation"><a href="#">Requests</a></li>
  <li role="presentation"><a href="#">Messages</a></li>
</ul>
<br>
<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Code</th>
                <th>File</th>
                <th>Sent</th>
                <th>Bill number</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </thead>
        <tbody>
  </tbody>
</table>
