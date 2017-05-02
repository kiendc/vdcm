<?php
defined('_JEXEC') or die('Restricted access');
//JHtml::_('behavior.framework');
//jimport( 'joomla.user.helper' );
$doc =  JFactory::getDocument();
$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
$doc->addStyleSheet('components/com_vjeecdcm/ext/jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css');

$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-1.9.1.js');
$doc->addScript('components/com_vjeecdcm/ext/jquery-ui/js/jquery-ui-1.10.3.custom.js');
$doc->addScriptDeclaration('
   jQuery.noConflict();
   jQuery(document).ready(function($) {
      $( ".acc-ms-container" ).accordion();
    });
');

?>

<!--
<div class="vjeecdcm-frame">
 <div class="vjeecdcm-frame-header">
<?php echo JText::_('VJEECDCM_USER_DASHBOARD_FRAME_TITLE'); ?>
</div>
 <div class="vjeecdcm-frame-content">
 -->
  <div id="dashboard-welcome-msg-container" style="width:100%">
   <?php 	
        echo $this->welcomeMsg;
   ?>
  </div>
  <div id="dashboard-sys-msg-container" style="float:left; width:49%; padding:5px;">
   <div class="acc-ms-container">
    <div>
     <?php echo JText::_('VJEECDCM_USER_DASHBOARD_SYSTEM_NOTICES');?>
    </div>
    <div>
    <?php
     if ($this->sysNotices != NULL)
     {
       foreach ($this->sysNotices as $notice)
       { 
         echo $notice->introtext;
       }
     }
    ?>
    </div>
   </div> 
 </div>
 <div id="dashboard-priv-msg-container" style="float:left; width:49%; padding:5px;">
  <div class="acc-ms-container">
  <div>
   <?php echo JText::_('VJEECDCM_USER_DASHBOARD_PRIVATES_MESSAGES');?>
  </div>
  <div>
   Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
    purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor
    velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In
    suscipit faucibus urna.
  </div>
 </div> 
 </div>
 <!--
 </div>
 </div>
-->

