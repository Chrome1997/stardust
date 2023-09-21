<?php
session_start();
include('../library/config.php');

if(!isset($_SESSION['id'])) {
	header('Location: ../index.php');
	exit();
}else{
	if($_SESSION['id'] != 2418)
	{
		header('Location: ../index.php');
		exit();
	}
}
?>

<html>
	<head>
		<link href="style/css/admin-css.css" rel="stylesheet">
	</head>
	
	<body>
		<?php include('header.html'); ?>
		<?php include('options.html'); ?>
		
		<div id="mainBody">
			<h1> Options - Aura Kingdom Forest</h1>
			
			<div class="splash">
				<ul class="iconic">
					<li class="selected"><a href="newpost.php"><span class="icon"><span class="newpost"></span></span><span class="linkText">New Post</span></a></li>
					<li class="selected"><a href="ban.php"><span class="icon"><span class="newpost"></span></span><span class="linkText">Ban System</span></a></li>
				</ul>
			</div>
			
		</div>
</body>
</html>