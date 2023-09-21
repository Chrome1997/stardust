<?php 
session_start();
include('../library/config.php');

if((isset($_SESSION['id'])!= 2418) ) {
	header('Location: ../index.php');
	exit();
}

$Account = new PDO("pgsql:dbname=FFMember;host=104.251.215.107", "spiritking", "antiroot" ) or die("Failed to connect!"); 

function getBanned($db){
	$retPlayer = array();
	$query = "SELECT mid, player_id FROM user_ban";	
	$query = $db->prepare($query);
	$query->execute();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$retPlayer[] = $row;
	}
	return $retPlayer;
}

function RandomUser($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function InsertBan($user, $id, $day, $db){
	$query = "INSERT INTO user_ban(mid, player_id, ban_day) VALUES('$user', '$id', '$day')";	
	$query = $db->prepare($query);
	$query->execute();
}

function UnbanUser($id, $db){
	$query = $db->prepare("DELETE FROM user_ban WHERE player_id='$id'");
	$query->execute();
}

function UpdateUser($user, $id, $db){
	$query = "UPDATE tb_user SET mid='$user' WHERE idnum='$id'";	
	$query = $db->prepare($query);
	$query->execute();
}

$error = "";
if(isset($_POST['submit'])){
	$username = $_POST['user'];
	$days = $_POST['days'];
	if(!empty($days) && is_numeric($days)){
		$randm = RandomUser();
		$query = $Account->prepare("SELECT idnum, mid FROM tb_user WHERE mid='$username'");
		$query->execute();
		if($query->rowCount() == 1){
			while($row = $query->fetch(PDO::FETCH_ASSOC)){
				$playerID = $row['idnum'];
				InsertBan($username, $playerID, $days, $Account);
				UpdateUser($randm, $playerID, $Account);
				$error = "Account has been banned!";
			}			
		}else{
			$error = "Unable to find the user";
		}
	}else{
		$error = "Invalid days values";
	}
}

if(isset($_POST['usubmit'])){
	$playerID = $_POST['bannedCat'];
	$query = $Account->prepare("SELECT mid, player_id FROM user_ban WHERE player_id='$playerID'");
	$query->execute();
	if($query->rowCount() == 1){
		while($row = $query->fetch(PDO::FETCH_ASSOC)){
			$username = $row['mid'];
			UpdateUser($username, $playerID, $Account);
			UnbanUser($playerID, $Account);
			$error = "Account has been unbanned!";
		}			
	}else{
		$error = "Unable to find the user";
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
			<h1> Ban System - Aura Kingdom Forest</h1>
				<div class="mainContent">
					<form action="" method="post">
	
						<dl class="controls">
							<dt><label> User: <label><dfn>Account name</dfn></dt>
							<dd><input type="text" name="user" class="txtCtrl"/></dd>
						</dl>
						
						<dl class="controls">
							<dt><label> Ban Length: <label><dfn>Numbers only!</dfn></dt>
							<dd><input type="text" name="days" class="txtCtrl"/></dd>
						</dl>
		
						<dl class="controls">
							<dt></dt>
							<dd><input type="submit" class="button primary cancel" name="submit" value=" Ban User "/></dd>
							<dd><a onclick="history.go(-1);" ><input type="submit" class="button primary cancel" value=" Cancel "/></a></dd>
						</dl>
						
						<dl class="controls">
							<dt><label> Banned: <label><dfn>Banned user list!</dfn></dt>
							<dd><select class="txtCtrl" name="bannedCat">
							<?php foreach(getBanned($Account) as $banned){ ?>
								<option value="<?php echo $banned['player_id']?>"><?php echo $banned['mid']?></option>
							<?php } ?>
							</select></dd>
						</dl>
						
						<dl class="controls">
							<dt></dt>
							<dd><input type="submit" class="button primary cancel" name="usubmit" value=" Unban User "/></dd>
							<dd><a onclick="history.go(-1);" ><input type="submit" class="button primary cancel" value=" Cancel "/></a></dd>
						</dl>
						
						<dl class="controls" style="text-center;">
							<?php echo $error; ?>
						</dl>
				</div>
			</div>
	</body>
</html>