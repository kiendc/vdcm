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
 		<div class="navbar-form navbar-right">
        		<jdoc:include type="modules" name="login" />               
    		</div>
	</div>
</nav>

<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="#">Home</a></li>
  <li role="presentation"><a href="#">Profile</a></li>
  <li role="presentation"><a href="#">Messages</a></li>
</ul>    
<div class="container-fluid">
<div class="row">
	<div class="col-sm-12">
        <jdoc:include type="component" />
	</div>
    </div>
</div>
</body>

</html>
