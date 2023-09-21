<?php


function add_post($title, $content, $db){
	$t = time();
	$query = $db->prepare("INSERT INTO news( title, content, date ) VALUES ('$title', '$content', '$t')");
	$query->execute();
}

function delete_post($id, $db){
	$query = $db->prepare("DELETE FROM news WHERE id='$id'");
	$query->execute();
}

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

function createNewAccount($name, $password, $email, $db){
	$query = $db->prepare("INSERT INTO tb_user( mid, password, pwd, pvalues, email, login_points, login_points_event) VALUES ('$name', '$password', MD5('$password'), '200', '$email', '0', '0')");
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
?>