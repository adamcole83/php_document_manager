<?
	// global initializer: needs to be relative to this file
	require_once('../../includes/initialize.php');
	// logout
	if($_GET['do']=='logout') $session->logout();
	// if user is logged in, redirect to index
	if($session->is_logged_in()) redirect_to('index.php');
?>
<? include_global_template('masthead.php') ?>
	<style type="text/css">
		@import url('/admin/css/login2.css');
	</style>
	<div id="page" class="off">
		<div id="header">
			<h2>Secure Access</h2>
		</div><!-- #header -->
		<div id="content">
			<noscript><p class="noscript">Javascript is required</p></noscript>
			
			<form id="login" class="auth" action="" method="post">
				<div id="dialog"></div>
				<label for="username">Pawprint</label>
				<div class="input-container">
					<input type="text" id="username" name="username" class="required" />
				</div>
				
				<label for="password">Password</label>
				<div class="input-container">
					<input type="password" id="password" name="password" class="required" />
				</div>
				
				<a id="retrievepwd">Forgot password?</a>
				<span id="bubbledrop"></span>
				<input type="submit" name="submit" class="submit" value="Submit" />
			</form>			
		</div><!-- #content -->
	</div><!-- #page -->
	
	<script type="text/javascript" src="/js/jquery.easing.1.3.min.js"></script>
	<script type="text/javascript" src="/admin/js/jquery.login.js"></script>
	<script type="text/javascript">
		$('#login').auth({
			dialogTop		: '+=25',
			wiggleDialog	: false
		});
	</script>
	
<? include_global_template('footer.php') ?>