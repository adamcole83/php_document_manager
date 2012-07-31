<? if($HTTPS == 'on') redirect_to('http://'.$SERVER_NAME.$PHP_SELF); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml">
	<head>
		<title><? echo SITE_NAME; ?></title> 
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="ROBOTS" content="NOINDEX, NOFOLLOW" />
		
		<link rel="stylesheet" type="text/css" media="screen" href="css/screen.css" />
		
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
		<script type="text/javascript" src="/js/jquery.tools.min.js"></script>
	</head>
	<body>
		<div id="mainContainer">
			<!-- Top gold navigation -->
			<div id="searchSection">
				<? virtual('/includes/top-menu.php'); ?>
			</div><!-- #searchSection -->
			<div id="topContainer">
				<? virtual('/includes/search.php'); ?>
			</div><!-- #topContainer -->
			<!-- Top black & white navigation -->
			<? virtual('/includes/menu.php') ?>
			
			<!-- Main content -->
			<div id="middleContainer">
				<div id="contentContainer">
					<img src="img/banner.jpg" class="main-banner" width="796" height="120" />
					<!-- CONTENT BODY -->