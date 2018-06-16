var site_url = "<?php echo JURI::root(); ?>";

function activateTab(tab)
{
    console.log('activate ' + tab);
    $('#vjdcm-view-main-tab a[href="#' + tab + '"]').tab('show');
}
