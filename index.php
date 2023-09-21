<?php 
session_start();
include("library/config.php");

function getPlayerbyID($id, $db){
	$retPlayer = array();
	$query = "SELECT idnum, mid, pvalues, login_points, login_points_event FROM tb_user WHERE idnum='$id'";	
	$query = $db->prepare($query);
	$query->execute();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$retPlayer[] = $row;
	}
	return $retPlayer;
}

function getAllNews($db){
	$retNews = array();
	$query = "SELECT id, title, content, date FROM news ORDER BY id DESC LIMIT 6";
	$query = $db->prepare($query);
	$query->execute();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$retNews[] = $row;
	}
	return $retNews;
}

$new = getAllNews($db);

$lerror = "";
if(isset($_POST['lsubmit'])){
	$username = $_POST['luser'];
	$password = $_POST['lpass'];
	
	if(empty($username) || empty($password)){
		$lerror =  '<li class="signuperror">Missing information!</li>';
	}else{
		$query = $db2->prepare("SELECT idnum, mid, password FROM tb_user WHERE mid='$username' AND password='$password'");
		$query->execute();
		if($query->rowCount() == 1){
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$_SESSION['id'] = $row['idnum'];
			}
			header('Location: index.php');
			exit();
		}else{
			$lerror =  '<li class="signuperror">Wrong username or password!</li>';
		}
	}
}

?>

<html>
<head>
	<link href="main-css.css" rel="stylesheet">
</head>
<body>
	<?php include('header.html'); ?>
	<div id="content">
		<div style="padding: 30px;padding-top: 10px;padding-bottom: 10px;">
			<!-- Banner -->
			<img style="position: relative; left: 70px;" src="images/banner.png">
			<img style="width: 100%;" src="images/divider-lg.jpg">
			
			<!-- News -->
			<div style="float: left; width: 64%;">
			<?php foreach($new as $news){ ?>
				<div class="news-content" style="max-height: 200px;text-overflow:ellipsis;">
					<label style="float: right;font-size: 12px;font-weight: bold;margin:0; "> <?php echo date('F j, Y',strtotime($news['date']));?> </label>
					<a href="news.php?id=<?php echo $news['id'];?>" style="text-decoration: none!important;"><h2> <?php echo $news['title'];?> </h2> </a>
					<div style="margin: 5;">
						<?php echo $news['content'];?>
					</div>
				</div>
				<img style="width: 100%;" src="images/divider-md.jpg">
			<?php } ?>
			</diV>
		</diV>
		
		<!-- Sidebar -->
		<?php include('sidebar.html'); ?>
	</diV>
	<div class="bottom-fix"></diV>
</body>
</html>