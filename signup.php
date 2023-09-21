<?php
session_start();
include('library/config.php');

if(isset($_SESSION['id'])){
	header('Location: index.php');
	exit();
}

function createNewAccount($name, $password, $email, $db){
	$query = $db->prepare("INSERT INTO tb_user( mid, password, pwd, pvalues, email, login_points, login_points_event) VALUES ('$name', '$password', MD5('$password'), '200', '$email', '0', '0')");
	$query->execute();
}

function getAPFromREF($id, $db){
	$query = $db->prepare("UPDATE tb_user SET pvalues=pvalues+'250' WHERE idnum='id'");
	$query->execute();
}

function isUserEmailExist($username, $email, $db){
	$query = $db->prepare("SELECT mid, email FROM tb_user");
	$query->execute();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		if($row['mid'] == $username){
			return 1;
		}
		if($row['email'] == $email){
			return 2;
		}
	}
	return 0;
}

function getCurID($db){
	$retID = array();
	$query = $db->prepare("SELECT * FROM tb_user ORDER BY idnum DESC");
	$query->execute();
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
		$retID[] = $row;
	}
	return $retID;
}

function insertRef($id, $newID, $ip, $db){
	$query = $db->prepare("INSERT INTO user_ref( id, reg_id, ip) VALUES('$id', '$newID', '$ip')");
	$query->execute();
}

function get_ip_address() {
    $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
    foreach ($ip_keys as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                // trim for safety measures
                $ip = trim($ip);
                // attempt to validate IP
                if (validate_ip($ip)) {
                    return $ip;
                }
            }
        }
    }
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
}
function validate_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false;
    }
    return true;
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

if(isset($_POST['submit'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$rePassword = $_POST['rePassword'];
	$email = $_POST["email"];
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$emailErr = "Invalid email format"; 
	}

	if(empty($username) || empty($password) || empty($email)){
		$error = "Missing information!";
	}else{ 
		if($password != $rePassword){
			$error = "Mismatched password!";
		}else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = "Invalid email format!";
		}else if ($_POST['rules'] != "accepted") {
			$error = "Check the 'Complete checkbox!'!";
		}else if (!preg_match("/^[A-Za-z0-9_]+$/",$username)) {
			$error = "Only letters, numbers and underscore in username!";
		}else{
			if(isUserEmailExist($username, $email, $db2) == 1){
				$error = "Username already exist!";
			}else if(isUserEmailExist($username, $email, $db2) == 2){
				$error = "Email already exist!";
			}else if(isUserEmailExist($username, $email, $db2) == 0){
				createNewAccount($username, $password, $email, $db2);
				if(isset($_GET['ref'])){
					$ipA = get_ip_address();
					$GetIP = $db2->prepare("SELECT ip user_ref WHERE ip='$ipA'");
					$GetIP->execute();
					if($GetIP->rowCount() == 0){
						$newAccount = getCurID($db2)[0]['idnum'];
						insertRef($_GET['ref'], $newAccount, $ipA, $db2);
						getAPFromREF($_GET['ref'], $db2);
					}
				}
				$error = "Success";
			}
		}
	}
	if($error == "Success"){
		$varError =  '<li class="signuperror" style="border: 1px solid #276B1E!important;background: rgba(0, 255, 20, 0.28)!important;">'.$error.'</li>';
	}else{
		$varError =  '<li class="signuperror">'.$error.'</li>';
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
					<h1> SIGN UP </h1>
					<form action=""  method="post" style="width: 53%; margin-left: auto;margin-right: auto;">
					<?php if(isset($error)){echo $varError;}?>
						<input class="form-control" type="text" name="username" placeholder="Username" />
						<input class="form-control" style="background-position: 5px -56px !important;" type="password" name="password" placeholder="Password" />
						 <input class="form-control" style="background-position: 5px -56px !important;" type="password" name="rePassword" placeholder="Re-password"/>
						<input class="form-control" type="text" name="email" placeholder="Email Address"/>
						<input type="checkbox" name="rules" value="accepted">Complete?
						<input type="submit" class="btn btn-aqred" name="submit" style="float: right;" value="SIGN UP"/>
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