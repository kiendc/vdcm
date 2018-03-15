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
<!--
<div id="header">

    <div id="branding">
            <p id="home">Creditial Certificate Manager</p>
    </div>
-->
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header navbar-brand">
			Certificate Management
		</div>
		<ul class="nav navbar-nav navbar-right">
			<li><a href="#"><span class="glyphicon glyphicon-user" aria-hidden="true"></span><?php echo JFactory::getUser()->name; ?></a></li>
 		<li class="navbar-form">
        		<jdoc:include type="modules" name="login" />               
    		</li>
		</ul>
	</div>
</nav>
<div class="container-fluid">
<div class="row">
	<div class="col-sm-12"><jdoc:include type="component" /></div>
    </div>
</div>
</body>

</html>
