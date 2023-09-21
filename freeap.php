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

$pointserror = "";

if(!isset($_SESSION['id'])){
	header('Location: index.php');
	exit();
}else{
	$Player = getPlayerbyID($_SESSION['id'], $db2);


	$minutesAP = (int)$Player[0]['login_points'] / 60;
        $wonAP = (int)$Player[0]['login_points'] / 7200;
	$valuePoints = (int)$wonAP * 7200;
	
	$getWon = (int)$wonAP;
	$getID = $_SESSION['id'];


	if(isset($_POST['submit'])){
	if($_POST['answer'] == 12){
		if($minutesAP >= 120){
			$pointserror = 'You got '.$getWon.' AP Points';
			$query = $db2->prepare("UPDATE tb_user SET pvalues=pvalues+'$getWon',  login_points=login_points-'$valuePoints' WHERE idnum='$getID'");
			$query->execute();
		}else{
			$pointserror =  'Not enough minutes!';
		}
	}else{
		$pointserror =  'Invalid answer!';
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
<h2> Easy to earn epoints!  </h2>
<form action=""  method="post">
<div style="margin: 5;">
						<h2><b><span style="color: #8064a2;">Easy to earn AP!</span><br></b><b><span style="color: #b2a2c7;">You just need to make your game character online!</span></b></h2><p><b><br></b></p><hr><p><br></p><p></p><p><strong><span style="color: #e36c09;">The cumulative length of&nbsp;the&nbsp;game&nbsp;online time:&nbsp;</span></strong></p><p><strong><span style="color: #c0504d;">Character online 120 minutes = Free 1 AP point</span></strong></p><p><strong><span style="color: #4bacc6;">Exp: Character online&nbsp;<strong>180&nbsp;</strong>minutes, You can change (earn) 120 minutes to 1 point,&nbsp;<br>and remain 60 minutes available to exchange points in the next time.</span></strong></p><p><strong><br></strong></p><p><strong></strong></p><span style="color: #e36c09;"><strong>This function allow you to earn AP by being online or active on the game!</strong><br></span><p></p><p><b><span style="color: #e36c09;">Only Aura Kindom Forest has this function so enjoy the game and get</span></b></p><p><b><span style="color: #e36c09;">free AP now!</span></b></p><p><b><span style="color: #e36c09;"><br></span></b></p><p><b><font color="#4bacc6">Account Name: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <?php echo $Player[0]['mid']; ?></font></b></p><p><b><font color="#4bacc6">Account Minutes: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <?php echo $minutesAP; ?> Minute(s)</font></b></p><p><b><font color="#4bacc6">Suvey: &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input class="form-control" type="text" name="answer" style="width: 45px;display: inline-block;" />1 + 5 + 6 = ?</font></b></p>					</div>
<label style="color: red;font-weight: bold;"> <?php if(isset($pointserror)){ echo $pointserror; } ?> </label> </br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" class="btn btn-aqred" name="submit" value="Submit"/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" class="btn btn-aqred" name="back" value="Back"/>
</form>

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