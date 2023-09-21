<?php 
session_start();
include('../library/config.php');

if((isset($_SESSION['id'])!= 2418) ) {
	header('Location: ../index.php');
	exit();
}

if(isset($_POST['submit'])){
	
	$title = $_POST['title'];
	$content = $_POST['content'];
	
	if(empty($title)){
		$errors[] =  "Invalid title name!";
	}
	
	if(empty($content)){
		$errors[] =  "Content not found";
	}
	
	
	if(!isset($errors)){
		$body_fixed = stripslashes(str_replace('\r\n', '', $content));
		add_post($title, $body_fixed, $db);
		//header('Location: index.php');
		//exit();
	}
}
?>
<html>
	<head>
		<?php include('head.html'); ?>
	</head>
	
	<body>
		<?php include('header.html'); ?>
		<?php include('options.html'); ?>
		
		<div id="mainBody">
			<h1> New Post - Aura Kingdom Forest</h1>
				<div class="mainContent">
					<form action="" method="post">
	
						<dl class="controls">
							<dt><label> Title: <label><dfn>News Title</dfn></dt>
							<dd><input type="text" name="title" class="txtCtrl"/></dd>
						</dl>
		
						<dl class="controls">
							<dt><label> News Content: <label><dfn>Body of the news! </dfn></dt>
							<dd><textarea rows="15" cols="50" id="redactor" name="content" class="txtCtrl"></textarea></dd>
							
						</dl>
		
		
						<dl class="controls">
							<dt></dt>
							<dd><input type="submit" class="button primary cancel" name="submit" value=" Submit News "/></dd>
							<dd><a onclick="history.go(-1);" ><input type="submit" class="button primary cancel" value=" Cancel "/></a></dd>
						</dl>
						<dl class="controls" style="text-center;">
							<?php 
							if(isset($errors) && !empty($errors)){
								echo '<ul><li>'. implode('</li><li>', $errors). '</li></ul>';
							}?>
						</dl>
				</div>
			</div>
	</body>
</html>