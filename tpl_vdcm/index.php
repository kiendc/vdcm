<?php defined( '_JEXEC' ) or die( 'Restricted access' );?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" 
   xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
   
<head>
<jdoc:include type="head" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />

</head>

<body>

<div id="header">
    <div id="branding">
            <p id="home">Creditial Certificate Manager</p>
    </div>
    <div id="login">
        <jdoc:include type="modules" name="login" />               
    </div>
</div>
<div id="taskbar">
    <jdoc:include type="modules" name="nav" />    
</div>
<div id="main-panel">
    <div id="content">
        <jdoc:include type="component" />
    </div>
</div>
</body>

</html>
