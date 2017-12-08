<?php
defined('_JEXEC') or die('Restricted access');
//JHtml::_('behavior.modal');
$doc =  JFactory::getDocument();
$doc->addStyleSheet('components/com_vjeecdcm/css/vjeecdcm.css');
?>

<div class="vjeecdcm-frame"> 
<?php
  //dump($this->dplmDetail, 'default layout of Diploma view (html format), dplmDetail');
if ($this->dplmID != NULL)
{
  $model = $this->getModel();
  //dump($model, 'ViewDisplay::diplayDiplomaDetail, model');
  $dplmDetail = $model->getDiplomaDetail($this->dplmID);
  foreach($dplmDetail as $dplm)
  {
    //dump($dplm, 'Default layout of Diploma view, $dplm');
    
    echo JText::_( $dplm->name ) . '<br/>';
    echo JText::_('VJEECDCM_DIPLOMA_HOLDER_TITLE') . ': ' . 
                 $dplm->holder_name . '<br/>';
  }
  
  //echo 'Showw here';
}
else
{
  echo 'Error';
}
?>  
  <jdoc:include type="error" />
</div>