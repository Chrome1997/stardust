<?php 
session_start();
include('../library/config.php');

if((isset($_SESSION['id'])!=$superAdmin) || (isset($_SESSION['id'])!=$superMod) ) {
	header('Location: login.php');
	exit();
}
if(isset($_GET['id'])){
	$post = get_posts($_GET['id'], null, $db);
}else{
	header('Location: index.php');
	die();
}

if(isset($_POST['submit'])){
	$title = $db->real_escape_string($_POST['title']);
	$music = $db->real_escape_string($_POST['music']);
	$body = $db->real_escape_string($_POST['body']);
	$catid = $db->real_escape_string($_POST['category']);
	
	if(empty($title)){
		$errors[] =  "Invalid category name";
	}
	
	if(empty($music)){
		$errors[] =  "No Music";
	}
	
	if(empty($body)){
		$errors[] =  "No Content!";
	}
	
	if (strlen($title) >= 50){
		$errors[] =  "The title lenght limit is 50!";
	}	

	if(!isset($errors)){
		$body_fixed = stripslashes(str_replace('\r\n', '', $body));
		edit_post($_GET['id'], $title, $music, $body_fixed, $catid, $db);	
		$id_dicl = $_GET["id"];
		header('Location: ../music.php?id='.$id_dicl.'');
		exit();
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
			<h1> Edit: <?php echo $post[0]['title'];?></h1>
				<div class="mainContent">
					<form action="" method="post">
		
						<dl class="controls">
							<dt><label> Title: <label><dfn>Music Title</dfn></dt>
							<dd><input type="text" name="title" class="txtCtrl" value="<?php echo $post[0]['title'];?>"/></dd>
						</dl>
						
						<dl class="controls">
							<dt><label> Youtube Link: <label><dfn>e.g: https://www.youtube.com/watch?v=DaTeMurIcs </dfn></dt>
							<dd><input type="text" name="music" class="txtCtrl" value="<?php echo $post[0]['music'];?>"/></dd>
						</dl>
		
						<dl class="controls">
							<dt><label> Lyrics & Chords: <label><dfn>You must have lyrics! </dfn></dt>
							<dd><textarea rows="15" cols="50" id="redactor" name="body"  class="txtCtrl"><?php echo $post[0]['body']; ?></textarea></dd>
						</dl>
						
						<dl class="controls">
							<dt><label> Artist: <label><dfn>Be careful!</dfn></dt>
							<dd><select class="txtCtrl" name="category">
							<?php 
							foreach(get_categories(null,$db) as $category){
								$selected = ($category['name'] == $post[0]['name']) ? ' selected="selected"' : '';
							?>
								<option value="<?php echo $category['id']?>" <?php echo $selected;?>"><?php echo $category['name']?></option>
							<?php 
							}
							?>
							</select></dd>
						</dl>
						
						<dl class="controls">
							<dt></dt>
							<dd><input type="submit" class="button primary cancel" name="submit" value=" Save Edit "/></dd>
							<dd><a onclick="history.go(-1);" ><input type="submit" class="button primary cancel" value=" Cancel "/></a></dd>
						</dl>
						<dl class="controls">
							<?php 
							if(isset($errors) && ! empty($errors)){
								echo '<ul><li>'. implode('</li><li>', $errors). '</li></ul>';
							}?>
						</dl>
				</div>
			</div>
	</body>
</html>