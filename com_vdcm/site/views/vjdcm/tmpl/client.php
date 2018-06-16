<?php
	defined('_JEXEC') or die('Restricted access');

	JHtml::_('jquery.framework', false);
	$doc =  JFactory::getDocument();

		//$doc->addStyleSheet('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
		//$doc->addStyleSheet('https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css');
	$doc->addScript('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');

	$doc->addScript('https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js');
	$doc->addScript('https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js');

	$doc->addScript('https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js');
	
	$doc->addScript('components/com_vjeecdcm/js/vjdcm.js');
	$doc->addScript('components/com_vjeecdcm/js/client.js');
?>

<ul class="nav nav-tabs" id="vjdcm-view-main-tab">
    <li class="active"><a href="#taba" data-toggle="tab">Dashboard</a></li>
    <li><a href="#tabb" data-toggle="tab">Requests</a></li>
    <li><a href="#tabc" data-toggle="tab">Messages</a></li>
</ul>
<br>
<div class="tab-content" id="tabs">
    <div class="tab-pane active" id="taba">
        <?php
            echo $this->loadTemplate('tab-content-1');
        ?>
        
    </div>
    <div class="tab-pane" id="tabb">
        <?php
            echo "Tab a";
        ?>
    </div>
    <div class="tab-pane" id="tabc">
        Tab content 3
    </div>
</div>

<?php
echo $this->loadTemplate('req-create-dlg');
?>
