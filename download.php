<?php
session_start();
include('library/config.php');

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

$error = "";
$varError = "";

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
				<div class="news-content">
				<h3> Clients </h3>
					<a href="https://drive.google.com/file/d/0B5li0i8d0ZTuZ1lLcG1JY0ZDTnc/view" class="btn btn-aqred" style="text-decoration: none;width: 220px;"> Download </br> Google Drive: Client 001</a>
					<a href="https://mega.nz/#!wE0UnKRB!QlfF1879oSm7RqRVGgR-PntIs8F7iD3jU2SEybn6BgI" class="btn btn-aqred" style="text-decoration: none;width: 220px;"> Download </br> Mega.co.nz: Client 001</a>
				</br></br>
				<h3> Requirements </h3>
				<div class="download">
					<ul>
						<li><a href="https://www.microsoft.com/en-ph/download/details.aspx?id=5555"> Microsoft Visual C++ 2010 Redistributable Package (x86)</a></li>
						<li><a href="https://www.microsoft.com/en-ph/download/details.aspx?id=14632"> Microsoft Visual C++ 2010 Redistributable Package (x64)</a></li>
						<li><a href="https://www.microsoft.com/en-ph/download/details.aspx?id=24872"> Microsoft .NET Framework 4 Client Profile </a></li>
						<li><a href="https://www.microsoft.com/en-ph/download/details.aspx?id=34429"> DirectX 9.0c End-User Runtime </a></li>
					</ul>
				</div>
				</div>
				<img style="width: 100%;" src="images/divider-md.jpg">
			</diV>
		</diV>
		
		<!-- Sidebar -->
		<?php include('sidebar.html'); ?>
	</diV>
	<div class="bottom-fix"></diV>
</body>
</html>